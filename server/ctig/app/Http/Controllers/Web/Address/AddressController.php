<?php

namespace App\Http\Controllers\Web\Address;

use App\Domain\Center\CenterContext;
use App\Http\Requests\Address\AddressPostRequest;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AddressController
{
    public function index(Request $request, Center $center)
    {
        abort_if($request->user()->center_id !== $center->id, 403);
;
        $addresses = Address::forCenter(app(CenterContext::class)->id())
            ->withExists('exams as examsExists')
            ->orderByDesc('id')
            ->where('is_active', true)
            ->get();
        Log::info('address_view_index', []);
        return Inertia::render('Center/Center', [
            'addresses' => AddressResource::collection($addresses),
            'tab' => 'addresses'
        ]);
    }

    public function store(AddressPostRequest $request, Center $center)
    {
        $employee = $request->user();
        abort_if($employee->center_id !== $center->id, 403);
        $address = Address::create([
            'address' => $request->validated('address'),
            'max_capacity' => $request->validated('capacity'),
            'center_id' => $employee->center_id,
            'creator_id' => $employee->id
        ]);
        return response()->json([
            'address' => new AddressResource($address)
        ], 
        201);
    }

    public function update(Request $request, Center $center, Address $address)
    {
        abort_if($request->user()->center_id !== $center->id, 403);
        abort_if($address->center_id !== $center->id, 403);
        $request->validate([
            'address' => ['required', 'string'],
            'maxCapacity' => ['required', 'integer', 'min:1']
        ]);
        $before = new AddressResource($address)->resolve();
        if(!$address->exams()->exists()){
            $address->address = $request->input('address');
        }

        $address->max_capacity = $request->input('maxCapacity');
        $address->save();
        Log::info('address_updated', [
            'address_id' => $address->id,
            'changes' => [
                'before' => $before,
                'after' =>  new AddressResource($address)->resolve()
            ]
        ]);
        return response()->json(new AddressResource($address));
    }
    

    public function destroy(
        Request $request,
        Center $center, 
        Address $address
    ){
        abort_if($request->user()->center_id !== $center->id, 403);
        abort_if($address->center_id !== $center->id, 403);
        $address->is_active = false;
        $address->save();
        Log::info('address_destroy', [
            'address_id' => $address->id
        ]);
        return response()->noContent();
    }
}
