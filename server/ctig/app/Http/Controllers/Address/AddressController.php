<?php

namespace App\Http\Controllers\Address;

use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
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
        return $this->noContent();
    }
}
