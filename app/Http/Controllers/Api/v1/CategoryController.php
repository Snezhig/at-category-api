<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Filter\CategoryFilter;
use App\Http\Requests\Pagination\CategoryPagination;
use App\Http\Requests\Sort\CategorySort;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CategoryFilter $filter, CategorySort $sort, CategoryPagination $pagination): JsonResponse
    {
        return response()->json(
            $this->service->getCategories(
                $filter->toArray(),
                $sort->toArray(),
                $pagination->getPage(),
                $pagination->getPerPage()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(string $key): JsonResponse
    {
        $column = is_numeric($key) ? 'id' : 'slug';
        return response()->json(Category::query()->where($column, $key)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     */
    public function destroy(Category $category): JsonResponse
    {
        return response()->json(['status' => $category->delete()]);
    }
}
