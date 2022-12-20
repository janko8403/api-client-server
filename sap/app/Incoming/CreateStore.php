<?php

namespace App\Incoming;

use App\Models\{Customer, CustomerData, Dictionary};
use Illuminate\Http\Request;
use Validator;
use App\Incoming\BaseResource;

class CreateStore extends BaseResource implements ActionInterface
{

    /**
         * @OA\Post(
         * path="create_store",
         * summary="Create new store",
         * tags={"method: create_store"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Pass store data",
         *    @OA\JsonContent(
         *       required={"idStore","name","city","address","postalCode","phoneNumber","email", "isActive"},
         *       @OA\Property(property="idStore", type="int", example="2"),
         *       @OA\Property(property="name", type="string", example="Komfort"),
         *       @OA\Property(property="city", type="string", example="Warszawa"),
         *       @OA\Property(property="address", type="string", example="Puławska 5"),
         *       @OA\Property(property="postalCode", type="string", example="00-000"),
         *       @OA\Property(property="phoneNumber", type="string", example="455444444"),
         *       @OA\Property(property="email", type="emial", example="jankowalski@test.pl"),
         *       @OA\Property(property="isActive", type="int", example="0"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="The payload %payload% store field is required.")
         *        )
         *     )
         * )
    */
    public function rules()
    {
        return [
            'payload.idStore' => ['required', 'unique:customer,innerCustomerId'],
            'payload.name' => 'required',
            'payload.city' => 'required',
            'payload.address' => 'required',
            'payload.postalCode' => 'required',
            'payload.phoneNumber' => 'required',
            'payload.email' => 'required',
            'payload.isActive' => 'required',
        ];
    }

    public function validate(array $data): bool 
    {
       Validator::make($data, $this->rules())->validate();
       return true;
    }

    public function execute(array $data)
    {
        $this->validate($data);

        $city = Dictionary::firstOrCreate(
            ['name' => $data['payload']['city']],
            ['dictionaryId' => 5]
        );

        $store = Customer::create([
            'innerCustomerId' => $data['payload']['idStore'],
            'phoneNumber' => $data['payload']['phoneNumber'],
            'email' => $data['payload']['email'],
        ]);

        $createStoreData = CustomerData::create([
            'customerId' => $store->id,
            'name' => $data['payload']['name'],
            'cityDicId' => $city->id,
            'streetName' => $data['payload']['address'],
            'zipCode' => $data['payload']['postalCode'],
            'isActive' => $data['payload']['isActive'],
        ]);

        return $this->sendResponse([$store, $createStoreData], 'Store created successfully.');
    }

}