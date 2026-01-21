<?php

namespace App\Http\Controllers\Migrant;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Migrant\MigrantPostRequest;
use App\Models\Migrant;
use App\Http\Resources\Migrant\MigrantResource;

class MigrantController extends Controller 
{

    public function index(){
        return MigrantResource::collection(Migrant::all());
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

    public function show(Migrant $migrant){
        return new MigrantResource($migrant);
    }

    
    

    public function update(Migrant $migrant)
    {
        //
    }

    public function destroy(Migrant $migrant)
    {
        //
    }
}
