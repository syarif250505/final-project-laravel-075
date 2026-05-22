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
        $data = $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email"], 
            "phone" => ["nullable", "string"], 
            "address" => ["nullable", "string"],
            "status" => ["nullable", "boolean"],
        ]);

        $data["status"] = $data["status"] ?? true;

        $customer = Customer::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Customer created successfully",
            "data" => $customer,
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
                "errors" => [],
            ], 404);
        }

        $data = $request->validate([
            "name" => ["sometimes", "string"],
            "email" => ["sometimes", "email"],
            "phone" => ["nullable", "string"],
            "address" => ["nullable", "string"],
            "status" => ["nullable", "boolean"],
        ]);

        $customerData->update($data);

        return response()->json([
            "success" => true,
            "message" => "Customer updated successfully",
            "data" => $customerData,
        ]);
    }

    public function destroy(int $customer): JsonResponse
    {
        $customerData = Customer::query()->find($customer);

        if (!$customerData) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
                "errors" => [],
            ], 404);
        }

        // Jika tidak ada relasi subscriptions di Customer, hapus saja pengecekan exists() 
        // yang sebelumnya ada di ServiceController agar tidak error.
        
        $customerData->delete();

        return response()->json([
            "success" => true,
            "message" => "Customer deleted successfully",
            "data" => null,
        ]);
    }
}