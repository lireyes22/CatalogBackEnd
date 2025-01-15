<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Client;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;

class OAuthController extends Controller
{
    public function client(Request $request)
    {
        $tokenPayload = $request->input('userToken');
        $email = $tokenPayload['email'];
        $uid = $tokenPayload['uid'];
        $name = $tokenPayload['name'];
        $dataReq = $request->all();
        $Phone_Number = isset($dataReq['Phone_Number']) ? $dataReq['Phone_Number'] : null;
        $user = User::where('User_Email', $email)->first();
        if ($user) {
            //Regresar información del usuario
            $data['user'] =  UserResource::make($user);
            //Buscar si es cliente o/y proveedor
            $client = Client::where('User', new ObjectId($user->_id))->first();
            //Si es cliente regresar la información
            if ($client) {
                $data['client'] = ClientResource::make($client);
            }
            //Regresar información
            return response()->json([
                'message' => 'User already exists',
                'data' => $data
            ], 200);
        } else {
            //Crear usuario
            $date = Carbon::now()->format('Y-m-d\TH:i:s.v\Z');
            $user = new User();
            $user->User_Uid = $uid;
            $user->User_Name = $name;
            $user->User_Email = $email;
            $user->User_Password = '';
            $user->User_PhoneNumber = $Phone_Number;
            $user->User_Status = 'active';
            $user->User_RegistrationDate = $date;
            $user->User_Roles = ['client'];
            //Crear Cliente
            $client = new Client();
            $client->Client_Status = 'active';
            $client->Client_RegistrationDate = $date;
            $client->Client_Address = null;
            try {
                //Guardar usuario
                $user->save();
                //Guardar cliente
                $client->User = new ObjectId($user->_id);
                $client->save();
                //Regresar información
                $data['user'] =  UserResource::make($user);
                $data['client'] = ClientResource::make($client);
                //Regresar información
                return response()->json([
                    'message' => 'User created successfully',
                    'data' => $data
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'User creation failed',
                    'error' => $e
                ], 409);
            }
        }
    }
    public function supplier(Request $request)
    {
        $tokenPayload = $request->input('userToken');
        $email = $tokenPayload['email'];
        $user = User::where('User_Email', $email)->first();
        if ($user) {
            //Buscar información del usuario
            $data['user'] =  UserResource::make($user);
            //Buscar si es supplier proveedor
            $supplier = Supplier::where('User', new ObjectId($user->_id))->first();
            //Si es proveedor regresar la información
            if ($supplier) {
                $data['supplier'] = SupplierResource::make($supplier);
                //Regresar información
                return response()->json([
                    'message' => 'User already exists',
                    'data' => $data
                ], 200);
            //Si no es proveedor no existe
            } else {
                //Regresar información
                return response()->json([
                    'message' => 'User-Supplier not exists',
                    'status' => 'error',
                ], 404);
            }
        } else {
            //Regresar información
            return response()->json([
                'message' => 'User not exists',
                'status' => 'error',
            ], 404);
        }
    }
}
