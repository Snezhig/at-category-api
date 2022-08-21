<?php

namespace App\Services;

use App\Exceptions\Models\ModelExistException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService
{
    /**
     * @param array $data
     * @return int
     * @throws ModelExistException
     */
    public function save(array $data): int
    {
        if ($this->isModelExist($data['slug'])) {
            throw new ModelExistException();
        }
        return Category::create($data)->id;
    }

    public function update(string $idOrSlug, array $data): bool
    {
        if (array_key_exists('slug', $data) && $this->isModelExist($data['slug'])) {
            throw new ModelExistException(sprintf('Model with slug %s is alredy exist', $data['slug']));
        }
        return Category::query()
            ->where($this->resolveColumn($idOrSlug), $idOrSlug)
            ->firstOrFail()
            ->update($data);
    }

    public function delete(string $idOrSlug): bool
    {
        if (!$this->isModelExist($idOrSlug)) {
            throw new ModelNotFoundException();
        }
        return Category::query()
            ->where($this->resolveColumn($idOrSlug), $idOrSlug)
            ->select(['id'])
            ->delete();
    }

    public function get(string $idOrSlug): Category
    {
        return Category::query()
            ->where($this->resolveColumn($idOrSlug), $idOrSlug)
            ->firstOrFail();
    }

    private function isModelExist(string $idOrSlug): bool
    {
        return Category::query()->where($this->resolveColumn($idOrSlug), $idOrSlug)->exists();
    }

    private function resolveColumn(string $idOrSlug): string
    {
        return is_numeric($idOrSlug) ? 'id' : 'slug';
    }

    public function getCategories(array $filter, array $sort, int $page = 1, int $perPage = 15): array
    {
        $query = Category::query();
        foreach ($filter as $field => $value) {
            $method = "process" . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($query, $value);
            } else {
                $query->where($field, $value);
            }
        }
        foreach ($sort as $field => $order) {
            $query->orderBy($field, $order);
        }
        return $query->paginate(perPage: $perPage, page: $page)->items();
    }

    private function processSearch(Builder $builder, $value): void
    {
        $builder->where(static function (Builder $builder) use ($value) {
            return $builder
                ->where('name', 'like', "%$value%")
                ->orWhere('description', 'like', "%$value%");
        });
    }

    private function processName(Builder $builder, $value): void
    {
        $builder->where('name', 'like', "%$value%");
    }

    private function processDescription(Builder $builder, $value): void
    {
        $builder->where('description', 'like', "%$value%");
    }
}
