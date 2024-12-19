<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json(['data' => $customers], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los clientes'], 500);
        }
    }

    public function create()
    {
        return response()->json(['message' => 'Usuario creado'], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codCustomer' => 'required|string|unique:customers,codCustomer',
            'address' => 'required|string|max:255',
            'passport' => 'required|string|max:50',
            'dui' => 'required|string|max:20',
            'nit' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'celphone' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        try {
            $customer = Customer::create($validated);
            return response()->json(['message' => 'Cliente creado con exito', 'data' => $customer], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear cliente'], 500);
        }
    }

    public function show(Customer $customer)
    {
        return response()->json(['data' => $customer], 200);
    }

    public function edit(Customer $customer)
    {
        return response()->json(['data' => $customer], 200);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'codCustomer' => 'nullable|string|unique:customers,codCustomer,' . $customer->id,
            'address' => 'nullable|string|max:255',
            'passport' => 'nullable|string|max:50',
            'dui' => 'nullable|string|max:20',
            'nit' => 'nullable|string|max:20',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'celphone' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            $customer->update($validated);
            return response()->json(['message' => 'Cliente actualizado con éxito', 'data' => $customer], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar cliente'], 500);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response()->json(['message' => 'Cliente eliminado con exito'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar cliente'], 500);
        }
    }
}
