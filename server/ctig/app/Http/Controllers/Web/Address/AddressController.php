<?php

namespace App\Http\Controllers\Web\Address;

use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController
{

    public function index()
    {
        return AddressResource::collection(Address::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string']
        ]);

        Address::create([
            'address' => $request->input('address'),
            'creator_id' => $request->user()->id
        ]);
    }

    public function show(Address $address)
    {
        $address->load('creator');
        return new AddressResource($address);
    }

    public function update(Request $request, Address $address)
    {
        //
    }

    public function destroy(Address $address)
    {
        $address->is_active = false;
        $address->save();
        return ;
    }
}
