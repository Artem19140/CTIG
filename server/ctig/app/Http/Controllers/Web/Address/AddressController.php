<?php

namespace App\Http\Controllers\Web\Address;

use App\Exceptions\BusinessException;
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
        $request->validate(['address' => ['required', 'string']]);
        if($address->exams()->exists()){
            throw new BusinessException('Нельзя редактировать адрес после привязки экзаменов');
        }
        $address->address = $request->input('address');
        $address->save();
        return response()->json(new AddressResource($address));
    }
    
    public function toggleActive(Address $address){
        //Только лишь org_admin
        $address->is_active = false;
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
