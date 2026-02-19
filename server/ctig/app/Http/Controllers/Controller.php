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
    // protected function created($data = []){
    //     return response()->json([
    //         'data'=> $data
    //     ],201);
    // }
    protected function created($data = [])
{
    if ($data instanceof \Illuminate\Http\Resources\Json\JsonResource ||
        $data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
        
        return $data->response()->setStatusCode(201);
    }

    return response()->json([
        'data' => $data
    ], 201);
}


    protected function noContent(){
        return response()->noContent();
    }
}
