<?php

namespace App\Incoming;

use App\Models\{UserStore, Customer, User};
use Illuminate\Http\Request;
use Validator;
use App\Incoming\BaseResource;

class CreateUserStore extends BaseResource implements ActionInterface
{
    /**
         * @OA\Post(
         * path="create_user_store",
         * summary="Create new user store",
         * tags={"method: create_user_store"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Pass user store data",
         *    @OA\JsonContent(
         *       required={"idStore","idUser"},
         *       @OA\Property(property="idStore", type="int", example="1"),
         *       @OA\Property(property="idUser", type="int", example="2"),
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
            'payload.idStore' => ['required', 'exists:customer,innerCustomerId'],
            'payload.idUser' => ['required', 'exists:user,referenceNumber'],
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

        $customerId = Customer::where('innerCustomerId', $data['payload']['idStore'])->first();
        $userId = User::where('referenceNumber', $data['payload']['idUser'])->first();
    
        $createUserStore = UserStore::create([
            'customerId' => $customerId->id,
            'userId' => $userId->id
        ]);

        return $this->sendResponse($createUserStore, 'User store created successfully.');
    }
}