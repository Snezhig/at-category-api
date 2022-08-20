<?php

namespace App\Services;

use App\Exceptions\Models\ModelExistException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryService
{
    /**
     * @param array $data
     * @return int
     * @throws ModelExistException
     */
    public function save(array $data): int
    {
        $exists = Category::query()->where('slug', $data['slug'])->exists();
        if ($exists) {
            throw new ModelExistException();
        }
        return Category::create($data)->id;
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
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
