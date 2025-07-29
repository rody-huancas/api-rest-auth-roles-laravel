<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|min:10|max:100',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Products::create([
            "name"  => $request->get("name"),
            "price" => $request->get("price"),
        ]);

        return response()->json([
            "message" => "Product created successfully"
        ], 201);
    }

    public function getProducts()
    {
        $products = Products::all();

        if ($products->isEmpty()) {
            return response()->json([
                "message" => "No products found"
            ], 404);
        }

        return response()->json([
            "products" => $products
        ], 200);
    }

    public function getProductById($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }

        return response()->json([
            "product" => $product
        ], 200);
    }

    public function updateProductById(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|string|min:10|max:100',
            'price' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has("name")) {
            $product->name = $request->get("name");
        }

        if ($request->has("price")) {
            $product->price = $request->get("price");
        }

        $product->update();

        return response()->json([
            "message" => "Product updated successfully"
        ], 200);
    }

    public function deleteProductById($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }

        $product->delete();

        return response()->json([
            "message" => "Product deleted successfully"
        ], 200);
    }
}
