<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Client;
use MongoDB\BSON\ObjectId;

class RolePolicy
{
    // Verificar si el usuario tiene rol de proveedor
    public function isSupplier(User $user)
    {
        echo "isSupplier";
        $supplier = Supplier::where('User', new ObjectId($user->_id))->first();
        return $supplier !== null;
    }

    // Verificar si el usuario tiene rol de cliente
    public function isClient(User $user)
    {
        return Client::where('User', new ObjectId($user->_id))->exists() === false;
    }
}