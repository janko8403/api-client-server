<?php

namespace App\Virtual;

/**
 * @OA\Schema(
 *      title="Post data request",
 *      description="Post data request body data",
 *      type="object",
 *      required={"method", "payload"},
 * )
 */
class PostDataRequest
{
    /**
     * @OA\Property(
     *      title="method",
     *      description="API action to be executed",
     *      example="create_user|update_user|create_user_store|delete_user_store|create_store|update_store|create_assembly_order|update_assembly_order",
     * )
     *
     * @var string
     */
    public $method;

    /**
     * @OA\Property(
     *      title="payload",
     *      description="Action payload (object)",
     * )
     *
     * @var Payload
     */
    public $payload;
}
