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
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ürün eklenirken hata oluştu'], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Ürün bulunamadı.'], 404);
            }

            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Sistemsel bir hata oluştu'], 500);
        }
    }

    public function update(ProductCreateRequest $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Ürün bulunamadı.'], 404);
            }

            $validated = $request->validated();

            $product->update($validated);

            return response()->json([
                'message' => 'Ürün başarıyla güncellendi.',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ürün güncellenirken hata oluştu'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Ürün bulunamadı.'], 404);
            }

            $product->delete();

            return response()->json(['message' => 'Ürün başarıyla silindi.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Silinirken hata oluştu'], 500);
        }
    }
}
