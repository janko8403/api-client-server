<?php

namespace App\Incoming;

use App\Models\{User, Dictionary};
use App\Classes\GeneratePass;
use Illuminate\Http\Request;
use Validator;
use App\Incoming\BaseResource;

class CreateUser extends BaseResource implements ActionInterface
{

    /**
         * @OA\Post(
         * path="create_user",
         * summary="Create new user",
         * tags={"method: create_user"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Pass user data",
         *    @OA\JsonContent(
         *       required={"name","idUser", "type", "email", "phoneNumber", "isActive"},
         *       @OA\Property(property="name", type="string", example="Komfort"),
         *       @OA\Property(property="idUser", type="int", example="2"),
         *       @OA\Property(property="type", type="string", example="Monter"),
         *       @OA\Property(property="firstName", type="string", example="Jan"),
         *       @OA\Property(property="lastName", type="string", example="Kowalski"),
         *       @OA\Property(property="email", type="string", example="jankowalski@test.pl"),
         *       @OA\Property(property="npsValue", type="int", example="1"),
         *       @OA\Property(property="dailyProductivity", type="int", example="123"),
         *       @OA\Property(property="listOfCategory", type="string", example="ABC123"),
         *       @OA\Property(property="phoneNumber", type="string", example="48454433222"),
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
            'payload.idUser' => ['required', 'unique:user,referenceNumber', 'integer'],
            'payload.type' => ['required', 'exists:dictionarydetails,name'],
            'payload.name' => 'required',
            'payload.email' => ['required', 'unique:user,email'],
            'payload.phoneNumber' => 'required',
            'payload.isActive' => 'required',
        ];
    }

    public function mesagges()
    {
        return [
            'exists' => 'Type must be a "Monter"',
        ];
    }

    public function validate(array $data): bool 
    {
       Validator::make($data, $this->rules(), $this->mesagges())->validate();
       return true;
    }

    public function execute(array $data)
    {
        $this->validate($data);

        $type = Dictionary::where(['name' => $data['payload']['type']])->first();
        if($type === null || $data['payload']['type'] !== 'Monter') 
            return $this->sendError($type, 'Type must be a "Monter"');

        $gp = new GeneratePass;
        $pass = $gp->randomPassword();
    
        $createUser = User::create([
            'referenceNumber' => $data['payload']['idUser'],
            'positionDicId' => $type->id,
            'company_name' => $data['payload']['name'],
            'name' => $data['payload']['firstName'],
            'surname' => $data['payload']['lastName'],
            'email' => $data['payload']['email'],
            'npsValue' => $data['payload']['npsValue'],
            'dailyProductivity' => $data['payload']['dailyProductivity'],
            'listOfCategory' => $data['payload']['listOfCategory'],
            'phoneNumber' => $data['payload']['phoneNumber'],
            'isActive' => $data['payload']['isActive'],
            'password' => $pass,
            'login' => $data['payload']['email']
        ]);

        return $this->sendResponse($createUser, 'User created successfully.');
    }
}
