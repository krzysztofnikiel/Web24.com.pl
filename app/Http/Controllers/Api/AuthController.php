<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User authenticate",
     *     tags={"Authenticate"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@gmail.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="token",
     *                  type="string"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *           response=401,
     *           description="Unauthorized"
     *     ),
     *     @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *             @OA\Property(property="fieldName", type="array", @OA\Items(anyOf={@OA\Schema(type="string")}))
     *          )
     *     ),
     *     @OA\Response(
     *           response=500,
     *           description="Exception"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $data = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        $validateUser = Validator::make($data,
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([$validateUser->errors()], 400);
        }

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel10SanctumAuth');
            return response()->json(['token' => $token->plainTextToken]);
        }

        return response()->json([], 401);
    }
}
