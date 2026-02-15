<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ok($data = [])
    {
        return response()->json([
            'data' => $data,
        ]);
    }
    protected function created($data = []){
        return response()->json([
            'data'=> $data
        ],201);
    }

    protected function noContent(){
        return response()->noContent();
    }
}
