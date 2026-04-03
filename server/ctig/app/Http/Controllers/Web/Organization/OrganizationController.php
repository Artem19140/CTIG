<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationController
{
    public function index(){
        
    }
    public function store(){
        
    }
    
    public function show(Request $request, Organization $organization){
        if($organization->id != $request->user()->organization_id){
            abort(404);
        }
        return Inertia::render('Organization/Organization', [
            'organization' => new OrganizationResource($organization)
        ]);
    }

    public function destroy(){

    }
}
