<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('items')->get(), 200);
    }

    public function store(OrderCreateRequest $request)
    {
        try{
            $validated = $request->validated();

            $customer = Customer::find($validated['customerId']);
            if(empty($customer->id)){
                return response()->json(['error' => 'Müşteri bulunamadı! ' . $customer->id], 400);
            }

            $orderItems = [];

            foreach ($request->toArray()['items'] as $item) {
                $product = Product::find($item['productId']);
                if(empty($product->id)){
                    return response()->json(['error' => 'Ürün bulunamadı! ' . $product->id], 400);
                }
                if ($product->stock < $item['quantity']) {
                    return response()->json(['error' => ' ID si ' . $product->id . ' olan ürün için yeterli stok yok.'], 400);
                }
                $orderItems[] = [
                    "product_id" => $product->id,
                    "quantity" => $item["quantity"],
                    "unit_price" => $item["unitPrice"],
                    "total" => $item["total"]
                ];
            }

            $order = Order::create([
                "customer_id" => $customer->id,
                "total" => $validated["total"],
            ]);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
            }

            OrderItems::insert($orderItems);

            foreach ($request->toArray()['items'] as $item) {
                $product = Product::find($item['productId']);
                $product->decrement('stock', $item['quantity']);
            }

            return response()->json([
                'message' => 'Sipariş başarıyla oluşturuldu.',
                'order' => $order,
                'ordersItems' => $orderItems
            ], 201);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Sistemsel bir hata oluştu!' ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['error' => 'Sipariş bulunamadı.'], 404);
            }

            $order->items()->delete();

            $order->delete();

            return response()->json([
                'message' => 'Sipariş ve ilişkili öğeler başarıyla silindi.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Silme işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
