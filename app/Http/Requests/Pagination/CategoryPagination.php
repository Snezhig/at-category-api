<?php

namespace App\Http\Requests\Pagination;

use Illuminate\Http\Request;

class CategoryPagination
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_PER_PAGE = 2;

    public function __construct(
        private Request $request
    )
    {
    }

    public function getPage(): int
    {
        $page = (int)$this->request->get('page');
        return $page > 0 ? $page : self::DEFAULT_PAGE;
    }

    public function getPerPage(): int
    {
        $perPage = (int)$this->request->get('pageSize');
        return $perPage > 0 ? $perPage : self::DEFAULT_PER_PAGE;
    }
}
