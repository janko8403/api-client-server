<?php

namespace App\Incoming;

use App\Models\{AssemblyOrder, AssemblyRanking, User};
use Illuminate\Http\Request;
use Validator;
use App\Incoming\BaseResource;
use App\Classes\AMQLib;

class CreateAssemblyOrder extends BaseResource implements ActionInterface
{
    private const ASSEMBLY_ORDER_UPDATED = 'assembly-order-updated';

    /**
         * @OA\Post(
         * path="create_assembly_order",
         * summary="Create new order",
         * tags={"method: create_assembly_order"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Pass order data",
         *    @OA\JsonContent(
         *       required={"idMeasurementOrder","idStore","creationDateTime","installationCity","installationAddress","installationName","estimatedCostNet","idUserRanking"},
         *       @OA\Property(property="idMeasurementOrder", type="int", example="10"),
         *       @OA\Property(property="idStore", type="int", example="2"),
         *       @OA\Property(property="creationDateTime", type="timestamps", example="2022-05-27 14:24:27"),
         *       @OA\Property(property="installationCity", type="string", example="Warszawa"),
         *       @OA\Property(property="installationAddress", type="string", example="Puławska 5"),
         *       @OA\Property(property="installationName", type="string", example="Jan Kowalski"),
         *       @OA\Property(property="estimatedCostNet", type="int", example="5.55"),
         *       @OA\Property(property="idUserRanking", type="string", example={0,1}),
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
            'payload.idMeasurementOrder' => ['required', 'integer'],
            'payload.idStore' => ['required', 'exists:customer,innerCustomerId'],
            'payload.creationDateTime' => ['required', 'date_format:Y-m-d H:i:s'],
            'payload.installationCity' => 'required',
            'payload.installationAddress' => 'required',
            'payload.installationName' => 'required',
            'payload.estimatedCostNet' => 'required',
            'payload.idUserRanking' => 'required'
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

        $data = $data['payload'];
        $data = array_merge($data, ['taken' => 0]);

        $user = User::where(['referenceNumber' => $data['idUserRanking'][1]])->first();
        if($user === null) {
            return $this->sendError($user, 'User does not exist');
        } else {
            $createAssemblyOrder = AssemblyOrder::create($data);
            $createRanking = AssemblyRanking::create([
                'position' => $data['idUserRanking'][0],
                'orderId' => $createAssemblyOrder->id,
                'userId' => $user->id
            ]);

            $contentId = [
                'content' => [
                    'orderId' => $createAssemblyOrder->id
                ]
            ];

            $content = serialize($contentId['content']);

            $amq = new AMQLib;
            $amq->publish( json_encode($content), self::ASSEMBLY_ORDER_UPDATED);
    
            return $this->sendResponse($createAssemblyOrder, 'Assembly order created successfully.');

        }
        
    }

}