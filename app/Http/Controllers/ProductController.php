<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function store(ProductCreateRequest $request)
    {
        $validated = $request->validated();

        $customerExist = Product::where("name",$validated["name"])->first();
        if(!empty($customerExist->id)){
            return response()->json(['message' => 'Böyle bir ürün zaten kayıtlı.'], 404);
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Ürün başarıyla oluşturuldu.',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı.'], 404);
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı.'], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|integer',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Ürün başarıyla güncellendi.',
            'product' => $product
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı.'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Ürün başarıyla silindi.'], 200);
    }
}
