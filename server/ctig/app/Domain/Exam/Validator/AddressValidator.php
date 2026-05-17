<?php 

namespace App\Domain\Exam\Validator;

use App\Domain\Center\CenterContext;
use App\Models\Address;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AddressValidator{
    public function validate(int $addressId):void{
        $address = $this->findOrFailAddress($addressId);
        $this->ensureAddressIsActive($address);
    }

    protected function findOrFailAddress(int $addressId):Address{
        $address =  Address::query()
            ->forCenter(app(CenterContext::class)->id())
            ->find($addressId);
        if(!$address){
            Log::warning('address not found, no same center', ['address_id' => $addressId]);
            abort(404);
        }
        return $address;
    }
    

    protected function ensureAddressIsActive(Address $address){
        if(!$address->is_active){
            throw ValidationException::withMessages([
                'addressId' => 'Адрес проведения экзамена не актуален'
            ]);
        }
    }
}