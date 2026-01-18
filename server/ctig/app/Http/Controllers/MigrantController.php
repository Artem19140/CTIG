<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\MigrantPostRequest;
use App\Models\Migrant;

class MigrantController extends Controller 
{
    public function show(): JsonResponse{
        return response()->json(["result" => "ok"]);
    }

    public function store(MigrantPostRequest $request ): JsonResponse{
        $migrant = Migrant::where("passport_number", $request->get('passportNumber'))
                            ->where("passport_series", $request->get('passportSeries'))
                            ->first();
        if($migrant){
            return response()->json(["result" => "exist"]);
        }
        Migrant::create($request->all());
        return response()->json(["result" => "ok"]);
    }
}
