<?php

namespace App\Http\Controllers\Web\Address;

use App\Http\Requests\Address\AddressPostRequest;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use App\Models\Center;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AddressController
{

    public function index(Request $request, Center $center)
    {
        abort_if($request->user()->center_id !== $center->id, 403);
        $addresses = Address::where('center_id', $request->user()->center_id)
            ->withExists('exams as examsExists')
            ->orderByDesc('id')
            ->where('is_active', true)
            ->get();
        return Inertia::render('Center/Center', [
            'addresses' => AddressResource::collection($addresses),
            'tab' => 'addresses'
        ]);
    }

    public function store(AddressPostRequest $request, Center $center)
    {
        $user = $request->user();
        abort_if($user->center_id !== $center->id, 403);
        Address::create([
            'address' => $request->validated('address'),
            'max_capacity' => $request->validated('capacity'),
            'center_id' => $user->center_id
        ]);
    }

    public function update(Request $request, Center $center, Address $address)
    {
        $this->authorize($center, $address);
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
    

    public function destroy(Center $center, Address $address)
    {
        $this->authorize($center, $address);
        $address->is_active = false;
        $address->save();
        return response()->noContent();
    }

    protected function authorize(Center $center, Address $address){
        abort_if($address->center_id !== $center->id, 403);
    }
}
