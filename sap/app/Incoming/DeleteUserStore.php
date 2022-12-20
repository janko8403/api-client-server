<?php

namespace App\Incoming;

use App\Models\{UserStore, Customer, User};
use Illuminate\Http\Request;
use Validator;
use App\Incoming\BaseResource;

class DeleteUserStore extends BaseResource implements ActionInterface
{
    /**
         * @OA\Post(
         * path="delete_user_store",
         * summary="Delete user store",
         * tags={"method: delete_user_store"},
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

        $deleteUserStore = UserStore::where([
            'customerId' => $customerId->id,
            'userId' => $userId->id
        ])->delete();

        return $this->sendResponse($deleteUserStore, 'User store deleted successfully.');
    }

}