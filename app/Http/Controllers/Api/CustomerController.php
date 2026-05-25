<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query("status");

        $query = Customer::query();

        if ($status !== null) {
            if (!in_array($status, ["active", "inactive"], true)) {
                return response()->json([
                    "success" => false,
                    "message" => "Validation failed",
                    "errors" => [
                        "status" => ["The selected status is invalid."],
                    ],
                ], 422);
            }

            $query->where("status", $status === "active");
        }

        $customers = $query->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Customers retrieved successfully",
            "data" => $customers,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Pastikan semua kolom yang dikirim dari form ADA di dalam validasi ini!
        $data = $request->validate([
            'customer_id' => ['required', 'string', 'unique:customers,customer_id'],
            'name'        => ['required', 'string'],
            'email'       => ['required', 'email', 'unique:customers,email'],
            'address'     => ['nullable', 'string'],
            'phone'       => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        // Set default status jika kosong
        $data['status'] = $data['status'] ?? true;

        // Simpan ke database
        $customer = Customer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data'    => $customer,
        ], 201);
    }

    public function show(int $customer): JsonResponse
    {
        $customerData = Customer::query()->find($customer);

        if (!$customerData) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
                "errors" => [],
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Customer retrieved successfully",
            "data" => $customerData,
        ]);
    }

    public function update(Request $request, int $customer): JsonResponse
    {
        $customerData = Customer::query()->find($customer);

        if (!$customerData) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
            ], 404);
        }

        $data = $request->validate([
            "name"    => ["sometimes", "string"],
            // Perhatikan bagian ini, kita tambahkan ID agar email dirinya sendiri diabaikan
            "email"   => ["sometimes", "email", "unique:customers,email," . $customer],
            "phone"   => ["nullable", "string"],
            "address" => ["nullable", "string"],
            "status"  => ["nullable"],
        ]);

        $customerData->update($data);

        return response()->json([
            "success" => true,
            "message" => "Customer updated successfully",
            "data"    => $customerData,
        ]);
    }

    public function activate($id)
    {
        $customer = Customer::findOrFail($id);
        // Jika status sudah true, tidak perlu update, langsung kembalikan sukses
        if ($customer->status == true) {
            return response()->json(['success' => true, 'message' => 'Status already active']);
        }
        $customer->update(['status' => true]);
        return response()->json(['success' => true, 'message' => 'Activated']);
    }

    public function deactivate($id)
    {
        $customer = Customer::findOrFail($id);
        if ($customer->status == false) {
            return response()->json(['success' => true, 'message' => 'Status already inactive']);
        }
        $customer->update(['status' => false]);
        return response()->json(['success' => true, 'message' => 'Deactivated']);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        // Tambahkan respons sukses agar JavaScript tidak error
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}
