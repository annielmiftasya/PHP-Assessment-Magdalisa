<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $products = $this->productRepository->getAllPaginated($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
            'message' => 'Products retrieved successfully.'
        ], 200);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'stock' => 'required|integer',
                'price' => 'required|numeric',
            ]);

            $product = $this->productRepository->create($validatedData);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product created successfully.'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    public function show($id): JsonResponse
    {
        $product = $this->productRepository->getById($id);

        if ($product) {
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product retrieved successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found.'
        ], 404);
    }


    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer',
                'price' => 'sometimes|required|numeric',
            ]);

            $updated = $this->productRepository->update($id, $validatedData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found or no changes made.'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    public function destroy($id): JsonResponse
    {
        $deleted = $this->productRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found.'
        ], 404);
    }
}
