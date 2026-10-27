<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class ApiCrudController extends Controller
{
    public function __construct(protected CloudinaryImageService $images) {}

    /** @return class-string<Model> */
    abstract protected function modelClass(): string;

    abstract protected function rules(bool $updating): array;

    protected function relations(): array
    {
        return [];
    }

    protected function imageFolder(): ?string
    {
        return null;
    }

    protected function syncRelations(): array
    {
        return [];
    }

    protected function hasDependencies(Model $model): bool
    {
        return false;
    }

    public function index()
    {
        $model = $this->modelClass();

        return response()->json($model::query()->with($this->relations())->get());
    }

    public function store(Request $request)
    {
        $record = $this->save($request, $request->validate($this->rulesWithImage(false)));

        return response()->json([
            'message' => 'Registro creado correctamente.',
            'data' => $record,
        ], 201);
    }

    public function show(string $id)
    {
        $model = $this->modelClass();

        return response()->json($model::query()->with($this->relations())->findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $model = $this->modelClass();
        $record = $model::query()->findOrFail($id);
        $record = $this->save($request, $request->validate($this->rulesWithImage(true)), $record);

        return response()->json([
            'message' => 'Registro actualizado correctamente.',
            'data' => $record,
        ]);
    }

    public function destroy(string $id)
    {
        $model = $this->modelClass();
        $record = $model::query()->findOrFail($id);

        if ($this->hasDependencies($record)) {
            throw new HttpException(409, 'No se puede eliminar el registro porque tiene datos relacionados.');
        }

        if ($this->imageFolder() !== null && $record->imagen_public_id) {
            $this->images->delete($record->imagen_public_id);
        }

        $record->delete();

        return response()->json(['message' => 'Registro eliminado correctamente.']);
    }

    private function rulesWithImage(bool $updating): array
    {
        $rules = $this->rules($updating);

        if ($this->imageFolder() !== null) {
            $rules['imagen'] = $updating
                ? 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
                : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        }

        return $rules;
    }

    private function save(Request $request, array $data, ?Model $record = null): Model
    {
        $syncData = [];
        foreach ($this->syncRelations() as $relation => $input) {
            if (array_key_exists($input, $data)) {
                $syncData[$relation] = $data[$input];
                unset($data[$input]);
            }
        }

        $file = $data['imagen'] ?? null;
        unset($data['imagen']);

        $previousPublicId = $record?->imagen_public_id;
        $uploadedImage = $file && $this->imageFolder() !== null
            ? $this->images->upload($file, $this->imageFolder())
            : null;

        if ($uploadedImage !== null) {
            $data = array_merge($data, $uploadedImage);
        }

        $modelClass = $this->modelClass();

        try {
            $record = DB::transaction(function () use ($modelClass, $record, $data, $syncData): Model {
                if ($record === null) {
                    $record = $modelClass::query()->create($data);
                } else {
                    $record->update($data);
                }

                foreach ($syncData as $relation => $ids) {
                    $record->{$relation}()->sync($ids);
                }

                return $record->fresh($this->relations()) ?? $record;
            });
        } catch (Throwable $exception) {
            if ($uploadedImage !== null) {
                $this->images->delete($uploadedImage['imagen_public_id']);
            }

            throw $exception;
        }

        if ($uploadedImage !== null && $previousPublicId !== null) {
            $this->images->delete($previousPublicId);
        }

        return $record;
    }
}
