<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\DocumentDetails;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $document = Document::all();
            return response()->json(['data' => $document], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los documentos'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Documento creado'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
   /* public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required|string|unique:documents,uuid',
            'codCustomer' => 'required|string|max:255',
            'date' => 'required|date',
            'totalSale' => 'required|string|max:20',
            'status' => 'required|string|max:20',
            'webUser' => 'required|string|max:255',
        ]);

        try {
            $document = Document::create($validated);
            return response()->json(['message' => 'Documento creado con exito', 'data' => $document], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear Documento'], 500);
        }
    }*/

    public function store(Request $request)
    {
    // Validar estructura JSON recibida
    $validator = Validator::make($request->all(), [
        // Validación para el cliente
        'customer.codCustomer' => 'required|string',
        'customer.address' => 'required|string',
        'customer.dui' => 'required|string',
        'customer.nit' => 'required|string',
        'customer.name' => 'required|string',
        'customer.email' => 'required|email',
        'customer.celphone' => 'required|string',
        'customer.phone' => 'required|string',
        // Validación para el documento
        'document.uuid' => 'required|string',
        'document.webUser' => 'required|string',
        'document.date' => 'required|date',
        'document.totalSale' => 'required|numeric',
        'document.status' => 'required|integer|in:0,1',
        'document.documentDetail' => 'required|array',
        // Validación para los detalles del documento
        'document.documentDetail.*.quantity' => 'required|integer|min:1',
        'document.documentDetail.*.product' => 'required|string',
        'document.documentDetail.*.unitPrice' => 'required|numeric|min:0',
    ]);

    // Si la validación falla, devolver errores
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Iniciar transacción
    DB::beginTransaction();

    try {
        // Guardar los datos del cliente
        $customerData = $request->input('customer');
        $customer = Customer::create($customerData);

        // Guardar los datos del documento
        $documentData = $request->input('document');
        $documentData['codCustomer'] = $customer->codCustomer; // Relación con el cliente
        $document = Document::create($documentData);

        // Guardar los detalles del documento
        $details = $documentData['documentDetail'];
        foreach ($details as $detail) {
            $detail['uuid'] = $document->uuid; // Relación con el documento
            DocumentDetails::create($detail);
        }

        // Confirmar transacción
        DB::commit();

        return response()->json([
            'message' => 'Document and details saved successfully',
            'document_id' => $document->id,
        ], 201);
    } catch (\Exception $e) {
        // Revertir cambios si hay un error
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to save document',
            'error' => $e->getMessage(),
        ], 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return response()->json(['data' => $document], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return response()->json(['data' => $document], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'uuid' => 'required|string|unique:documents,uuid',
            'codCustomer' => 'required|string|max:255',
            'date' => 'required|date',
            'totalSale' => 'required|string|max:20',
            'status' => 'required|string|max:20',
            'webUser' => 'required|string|max:255',
        ]);

        try {
            $document->update($validated);
            return response()->json(['message' => 'Cliente actualizado con éxito', 'data' => $document], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar cliente'], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        try {
            $document->delete();
            return response()->json(['message' => 'Documento eliminado con exito'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar Documento'], 500);
        }
    }

    public function getByStatus($status) {
        if (!is_numeric($status)) {
            return response()->json(['error', 'El Estado debe de ser un numero valido'], 400);
        }

        try {
            $document = Document::where('status', $status)->get();

            if ($document->isEmpty()) {
                return response()->json(['error', 'No se encontraron documentos con ese Estado'], 400);
            }

            return response()->json(['message', 'Documento(s) encontrados.', 'data' => $document], 200);

        } catch (\Exception $e) {
            return response()->json(['error', 'Error al obtener los documentos', 'details' => $e->getMessage()], 500);
        }
    }

    public function updateStatus($id) {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['error', 'Documento no encontrado'], 400);
        }

        $document->status = 1;
        $document->save();

        return response()->json(['message', 'Estado actualizado', 'data' => $document], 200);
    }

    public function getByWebUser($webUser)
    {
        try {
            // Buscar documentos por webUser
            $documents = Document::where('webUser', $webUser)->get();
    
            // Verificar si se encontraron documentos
            if ($documents->isEmpty()) {
                return response()->json(['error' => 'No se encontraron documentos para este usuario'], 404);
            }
    
            return response()->json(['message' => 'Documentos encontrados', 'data' => $documents], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los documentos', 'details' => $e->getMessage()], 500);
        }
    }
}
