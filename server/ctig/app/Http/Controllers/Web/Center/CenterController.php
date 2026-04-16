<?php

namespace App\Http\Controllers\Web\Center;

use App\Http\Resources\CenterResource;
use App\Models\Center;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CenterController
{
    public function index(){
        //Все организации
    }
    public function store(){
        //Создать, а потом еще orgadmin
    }
    
    public function show(Request $request, Center $center){
        if($center->id != $request->user()->center_id){
            abort(404);
        }
        return Inertia::render('Center/Center', [
            'center' => new CenterResource($center)
        ]);
    }

    public function destroy(Center $center){
        $center->is_active = false;
        return response()->noContent();
    }
}
