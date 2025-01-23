<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class DiscountController extends Controller
{
    public function calculateDiscount($orderId)
    {
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

        return response()->json([
            'orderId' => $orderId,
            'discounts' => $discounts,
            'totalDiscount' => number_format($totalDiscount, 2),
            'discountedTotal' => number_format($order->total - $totalDiscount, 2),
        ]);
    }
}
