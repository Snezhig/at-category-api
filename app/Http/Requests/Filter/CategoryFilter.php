<?php

namespace App\Http\Requests\Filter;

use Illuminate\Http\Request;

class CategoryFilter
{
    public function __construct(
        private Request $request
    )
    {
    }

    private function getFilter(): array
    {
        $filter = [];
        $fields = ['search', 'name', 'description', 'active'];
        foreach ($fields as $field) {
            if (!$this->request->filled($field)) {
                continue;
            }
            $value = $this->request->get($field);
            switch ($field) {
                case 'search':
                {
                    unset($filter['name'], $filter['description']);
                    $filter[$field] = $value;
                    break;
                }
                case 'name':
                case 'description':
                    if (!array_key_exists('search', $filter)) {
                        $filter[$field] = $value;
                    }
                    break;
                case 'active':
                    $filter[$field] = in_array($value, ['true', '1'], true);
            }
        }
        return $filter;
    }

    public function toArray(): array
    {
        return $this->getFilter();
    }
}
