<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class DiscountController extends Controller
{
    public function calculateDiscount($orderId)
    {
        try {
            $order = Order::with('items')->findOrFail($orderId);
            $discounts = [];
            $totalDiscount = 0;

            if ($order->total >= 1000) {
                $discountAmount = $order->total * 0.10;
                $discounts[] = [
                    'discountReason' => '10_PERCENT_OVER_1000',
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($order->total - $discountAmount, 2),
                ];
                $totalDiscount += $discountAmount;
            }

            foreach ($order->items as $item) {
                if ($item->product->category == 2 && $item->quantity >= 6) {
                    $freeItems = intdiv($item->quantity, 6);
                    $discountAmount = $freeItems * $item->unit_price;
                    $discounts[] = [
                        'discountReason' => 'BUY_5_GET_1',
                        'discountAmount' => number_format($discountAmount, 2),
                        'subtotal' => number_format($order->total - $totalDiscount - $discountAmount, 2),
                    ];
                    $totalDiscount += $discountAmount;
                }
            }

            $category1Items = $order->items->filter(function ($item) {
                return $item->product->category == 1 && $item->quantity >= 1;
            });

            if ($category1Items->count() >= 2) {
                $cheapestItem = $category1Items->sortBy('price')->first();
                $discountAmount = $cheapestItem->unit_price * 0.20;
                $discounts[] = [
                    'discountReason' => '20_PERCENT_CHEAPEST_CATEGORY_1',
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($order->total - $totalDiscount - $discountAmount, 2),
                ];
                $totalDiscount += $discountAmount;
            }

            return response()->json([
                'orderId' => $orderId,
                'discounts' => $discounts,
                'totalDiscount' => number_format($totalDiscount, 2),
                'discountedTotal' => number_format($order->total - $totalDiscount, 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Sistemsel bir hata oluştu'], 500);
        }
    }
}
