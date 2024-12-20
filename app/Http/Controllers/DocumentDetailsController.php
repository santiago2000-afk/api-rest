<?php

namespace App\Http\Controllers;

use App\Models\DocumentDetails;
use Illuminate\Http\Request;

class DocumentDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $documentDetails = DocumentDetails::all();
            return response()->json(['data' => $documentDetails], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los detalles de el documentos'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Documento con detalles creado'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required',
            'quantity' => 'required',
            'product' => 'required',
            'unitPrice' => 'required',
        ]);

        try {
            $documentDetails = DocumentDetails::create($validated);
            return response()->json(['message' => 'Documento con detalles creado con exito', 'data' => $documentDetails], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear Documento con detalles'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentDetails $documentDetails)
    {
        return response()->json(['data' => $documentDetails], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentDetails $documentDetails)
    {
        return response()->json(['data' => $documentDetails], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentDetails $documentDetails)
    {
        $validated = $request->validate([
            'uuid' => 'required|string|unique:documents,uuid',
            'quantity' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'unitPrice' => 'required|string|max:20',
        ]);

        try {
            $documentDetails->update($validated);
            return response()->json(['message' => 'Documento con detalles actualizado con exito', 'data' => $documentDetails], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar Documento con detalles'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentDetails $documentDetails, $id)
    {
        try {
            $id = DocumentDetails::find($id);
            $id->delete();
            return response()->json(['message' => 'Documento con dettale eliminado con exito'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar Documento con dettale'], 500);
        }
    }
}
