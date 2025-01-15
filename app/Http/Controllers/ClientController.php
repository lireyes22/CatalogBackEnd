<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google_Client;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json(['data' => $clients], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'Client_Status' => 'required|string|max:255',
            'Client_RegistrationDate' => 'required|date',
            'Client_Address' => 'required|array',
            'Client_Address.Address_Street' => 'required|string|max:255',
            'Client_Address.Address_CrossStreets' => 'required|string|max:255',
            'Client_Address.Address_Neighborhood' => 'required|string|max:255',
            'Client_Address.Address_IntNumber' => 'required|string|max:255',
            'Client_Address.Address_ExtNumber' => 'required|string|max:255',
            'Client_Address.Address_References' => 'required|string|max:255',
            'Client_Address.Address_PostalCode' => 'required|string|max:255',
        ]);
        $data = json_decode($request->getContent(), true);
        $client_registration_date = Carbon::createFromFormat('d-m-Y', $data['Client_RegistrationDate'])->format('Y-m-d\TH:i:s.v\Z');
        $client = new Client();
        $client->Client_Status = $data['Client_Status'];
        $client->Client_RegistrationDate = $client_registration_date;
        $client->Client_Address = $data['Client_Address'];
        try {
            $client->save();
            return response()->json([
                'message' => 'Client created successfully',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Client creation failed',
                'error' => $e
            ], 409);
        }
    }
    public function show(Client $client)
    {
        return $client;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        /* $request->validate([
            'Client_Status' => 'sometimes|string|max:255',
            'Client_RegistrationDate' => 'sometimes|date',
            'Client_Address' => 'sometimes|array',
            'Client_Address.Address_Street' => 'sometimes|string|max:255',
            'Client_Address.Address_CrossStreets' => 'sometimes|string|max:255',
            'Client_Address.Address_Neighborhood' => 'sometimes|string|max:255',
            'Client_Address.Address_IntNumber' => 'sometimes|string|max:255',
            'Client_Address.Address_ExtNumber' => 'sometimes|string|max:255',
            'Client_Address.Address_References' => 'sometimes|string|max:255',
            'Client_Address.Address_PostalCode' => 'sometimes|string|max:255',
        ]);

        // Actualizar el cliente con los datos de la solicitud
        $client->update($request->only([
            'Client_Status',
            'Client_RegistrationDate',
            'Client_Address'
        ]));
        return response()->json(['message' => 'Client updated successfully', 'data' => $client], 200); */
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return response()->json(['message' => 'Client deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Client deletion failed', 'error' => $e], 409);
        }
    }

    public function sss() {}
}
