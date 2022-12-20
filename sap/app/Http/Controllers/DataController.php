<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Incoming\ActionEnum;
use App\Incoming\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class DataController extends Controller
{
    public function __construct(private Factory $factory)
    {
        $this->factory  = $factory;
    }

    /**
     * @OA\Post(
     *      path="/data",
     *      operationId="postData",
     *      summary="Post data",
     *      description="Endpoint responsible for processing data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PostDataRequest"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Request successful",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     * )
     */
    public function post(Request $request)
    {
        $validated = $request->validate([
            'method' => ['required', Rule::in(array_map(fn($a) => $a->value, ActionEnum::cases()))],
            'payload' => ['required']
        ]);

        $action = $this->factory->create(ActionEnum::from($validated['method']));

        return $action->execute($request->toArray());
    }
}
