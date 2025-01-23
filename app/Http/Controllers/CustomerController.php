<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCreateRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers, 200);
    }

    public function store(CustomerCreateRequest $request)
    {
        $validated = $request->validated();

        $customerExist = Customer::where("name",$validated["name"])->first();
        if(!empty($customerExist->id)){
            return response()->json(['message' => 'Böyle bir müşteri zaten kayıtlı.'], 404);
        }

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Müşteri başarıyla oluşturuldu.',
            'customer' => $customer
        ], 201);
    }



    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı.'], 404);
        }

        return response()->json($customer, 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı.'], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'since' => 'nullable',
            'revenue' => 'nullable|numeric|min:0',
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Müşteri başarıyla güncellendi.',
            'customer' => $customer
        ], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı.'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Müşteri başarıyla silindi.'], 200);
    }
}
