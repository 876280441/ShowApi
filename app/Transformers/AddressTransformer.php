<?php


namespace App\Transformers;


use App\Models\Address;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{
    public function transform(Address $address)
    {
        return [
            'id' => $address->id,
            'name' => $address->name,
            'city_id' => $address->city_id,
            'city_str' => city_name($address->city_id),
            'phone' => $address->phone,
            'address' => $address->address,
            'is_default' => $address->is_default,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}
