<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | La ruta al archivo JSON que contiene las credenciales para la cuenta de
    | servicio de Firebase. Debes generar este archivo en la consola de Firebase.
    |
     */

    'credentials' => env('FIREBASE_CREDENTIALS', base_path('/storage/app/firebase_credentials.json')),
];
