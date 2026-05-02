<?php

namespace App\Http\Controllers\Web\Address;

use App\Http\Requests\Address\AddressPostRequest;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AddressController
{

    public function index(Request $request)
    {
        $addresses = Address::where('center_id', $request->user()->center_id)
            ->withExists('exams as examsExists')
            ->orderByDesc('id')
            ->orderBy('is_active')
            ->get();
        return Inertia::render('Center/Center', [
            'addresses' => AddressResource::collection($addresses),
            'tab' => 'addresses'
        ]);
    }

    public function store(AddressPostRequest $request)
    {
        $user = $request->user();

        Address::create([
            'address' => $request->validated('address'),
            //'creator_id' => $user->id,
            'max_capacity' => $request->validated('capacity'),
            'center_id' => $user->center_id
        ]);
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'address' => ['required', 'string'],
            'maxCapacity' => ['required', 'integer', 'min:1']
        ]);

        if(!$address->exams()->exists()){
            $address->address = $request->input('address');
        }

        $address->max_capacity = $request->input('maxCapacity');
        $address->save();
        return response()->json(new AddressResource($address));
    }
    
    public function toggleActive(Request $request, Address $address){
        $request->validate(['active' => 'required', 'boolean']);
        $address->is_active = $request->input('active');
        $address->save();
        return response()->noContent();
    }

    public function destroy(Address $address)
    {
        $address->is_active = false;
        $address->save();
        return ;
    }
}
