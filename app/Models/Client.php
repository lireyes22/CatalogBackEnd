<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MongoApiModel;
use MongoDB\BSON\ObjectId;

class Client extends MongoApiModel
{
    use HasFactory;
    protected $collection = 'Clients';

    protected $fillable = [
        'Client_Status',
        'Client_RegistrationDate',
        'Client_Address',
        'User',
    ];

    public function getShippingFormat(){
        $user = $this->getUser();
        return [
            'Client_ID' => new ObjectId($this['_id']),
            'Client_Name' => $user['User_Name'],
        ];
    }

    public function getUser(){
        return User::where('_id', new ObjectId($this['User']))->first();
    }
}
