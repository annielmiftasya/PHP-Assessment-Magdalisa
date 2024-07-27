<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
   public function getAll()
   {
      return Product::all();
   }

   public function getAllPaginated($perPage)
   {
      return Product::paginate($perPage);
   }

   public function getById($id)
   {
      return Product::findOrFail($id);
   }

   public function create(array $attributes)
   {
      return Product::create($attributes);
   }

   public function update($id, array $attributes)
   {
      $product = $this->getById($id);
      $product->update($attributes);
      return $product;
   }

   public function delete($id)
   {
      $product = $this->getById($id);
      $product->delete();
      return response()->noContent();
   }
}
