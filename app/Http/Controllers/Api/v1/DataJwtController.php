<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataJwtController extends Controller
{
    public function open()
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);

    }

    public function closed()
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'),200);
    }
}
