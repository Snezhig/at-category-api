<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\Models\ModelExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Filter\CategoryFilter;
use App\Http\Requests\Pagination\CategoryPagination;
use App\Http\Requests\Sort\CategorySort;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service
    )
    {
    }
    
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


    public function store(Request $request): JsonResponse
    {
        $rules = collect([
            'slug' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'active' => 'required|boolean'
        ]);

        $input = $request->only($rules->keys()->toArray());
        $validator = Validator::make($input, $rules->toArray());
        $code = Response::HTTP_OK;

        try {
            if ($validator->fails()) {
                $data = $validator->errors()->toArray();
                $code = Response::HTTP_BAD_REQUEST;
            } else {
                $data = ['id' => $this->service->save($input)];
            }
        } catch (ModelExistException $e) {
            $data = ['error' => $e->getMessage()];
            $code = Response::HTTP_CONFLICT;
        }

        return response()->json($data, $code);
    }

    public function show(string $key): JsonResponse
    {
        $column = is_numeric($key) ? 'id' : 'slug';
        return response()->json(Category::query()->where($column, $key)->first());
    }


    public function update(Request $request, Category $category): JsonResponse
    {
        $rules = collect([
            'slug' => 'string',
            'name' => 'string',
            'description' => 'string',
            'active' => 'boolean'
        ]);
        $data = $request->only($rules->keys()->toArray());
        $validator = Validator::make($data, $rules->toArray());
        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['status' => $this->service->update($category, $data)]);
    }


    public function destroy(Category $category): JsonResponse
    {
        return response()->json(['status' => $category->delete()]);
    }
}
