<?php

namespace App\Http\Requests\Sort;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorySort
{
    public function __construct(
        private Request $request
    )
    {
    }

    private function getSort(): array
    {
        $sort = $this->request->get('sort', '-id');
        $order = 'ASC';
        //Can be retrieved from service or something like that
        $fields = ['description', 'name', 'id', 'slug', 'active'];
        if (Str::startsWith($sort, '-')) {
            $sort = Str::substr($sort, 1);
            $order = 'DESC';
        }
        $sort = in_array($sort, $fields, true) ? $sort : 'id';

        return [$sort => $order];
    }

    public function toArray(): array
    {
        return $this->getSort();
    }
}
