<?php

namespace App\Classes;

use Log;
use App\Models\{AssemblyOrder, AssemblyRanking, User, Customer};
use App\Classes\AMQLib;
use App\Classes\GeneratePass;
use Web64\Colors\Facades\Colors;

class ApiTests {

    private const ASSEMBLY_ORDER_UPDATED = 'assembly-order-updated';
    private const TEST = 'test';

    public function __construct()
    {
        $this->amq = new AMQLib;
        $this->generate = new GeneratePass;
    }

    public function add_order($userId, $idStore)
    {
        $JSON = '{"payload": {"idMeasurementOrder": '.$this->generate->randUserId().',"idStore": "'.$idStore.'","creationDateTime": "2022-05-27 14:24:27","installationCity": "Zabki","installationAddress": "jp 65","installationName": "paweł J","estimatedCostNet": "5.56","idUserRanking" : [6,'.$userId.'],"taken": 0}}';
        $data = json_decode($JSON);
        $data = (array) $data;
        $data = $data['payload'];
        $createAssemblyOrder = AssemblyOrder::create((array) $data);
        
        $user = User::where(['referenceNumber' => $data->idUserRanking[1]])->first();

        $customerId = Customer::where('innerCustomerId', $data->idStore)->first();

        if($user === null || $customerId === NULL) {
            if($user === null)
                Colors::error('User does not exist');
            if($customerId === null)
                Colors::error('Id store does not exist');
        } else {
            $createRanking = AssemblyRanking::create([
                'position' => $data->idUserRanking[0],
                'orderId' => $createAssemblyOrder->id,
                'userId' => $user->id
            ]);

            $contentId = [
                'content' => [
                    'orderId' => $createAssemblyOrder->id
                ]
            ];

            $content = serialize($contentId['content']);

            $this->amq->publish( json_encode($content), self::ASSEMBLY_ORDER_UPDATED, self::TEST);
        }
            

        
    }
    
}
