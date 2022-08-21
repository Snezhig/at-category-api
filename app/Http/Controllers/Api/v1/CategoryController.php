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
            'slug' => 'required|string|not_regex:/[0-9]/',
            'name' => 'required|string',
            'description' => 'string|nullable',
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
        return response()->json($this->service->get($key));
    }


    public function update(Request $request, $key): JsonResponse
    {
        $rules = collect([
            'slug' => 'string|not_regex:/[0-9]/',
            'name' => 'string',
            'description' => 'string',
            'active' => 'boolean'
        ]);
        $input = $request->only($rules->keys()->toArray());
        $validator = Validator::make($input, $rules->toArray());
        $code = Response::HTTP_OK;
        try {
            if ($validator->fails()) {
                $data = ['errors' => $validator->errors()->toArray()];
                $code = Response::HTTP_BAD_REQUEST;
            } else {
                $data = ['status' => $this->service->update($key, $input)];
            }
        } catch (ModelExistException $e) {
            $data = ['errors' => [$e->getMessage()]];
            $code = Response::HTTP_CONFLICT;
        }
        return response()->json($data, $code);
    }


    public function destroy(string $key): JsonResponse
    {
        return response()->json(['status' => $this->service->delete($key)]);
    }
}
