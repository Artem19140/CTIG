<?php

namespace App\Http\Controllers\Web\Center;

use App\Http\Requests\Center\CenterStoreRequest;
use App\Http\Resources\Center\CenterIndexResource;
use App\Http\Resources\Center\CenterResource;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CenterSuperAdminController
{
    public function show(Center $center){
        $center->load([
            'employees.roles'
        ]);
        return Inertia::render('SuperAdmin/CenterShow', [
            'center' => new CenterResource($center)
        ]);
    }

    public function store(CenterStoreRequest $request){
        $wrongPassword = !Hash::check($request->validated('password'), $request->user()->password);
        if($wrongPassword){
            Log::warning('wrong sa password', [

            ]);
            abort(404);
        }
        Center::create([
            'short_name' => $request->validated('shortName'),
            'time_zone' => $request->validated('timeZone')
        ]);
        return response()->noContent();
    }

    public function index(){
        $centers = Center::query()
            ->withCount('employees')
            ->get();
        return Inertia::render('SuperAdmin/Centers', [
            'centers' => CenterIndexResource::collection($centers)
        ]);
    }

    public function destroy(Center $center){
        $center->is_active = false;
        return response()->noContent();
    }
}
