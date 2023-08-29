<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *       version="1.0.0",
 *       title="Laravel web24.com.pl Recruitment Task",
 *       description="Swagger for recruitment task",
 *       @OA\Contact(
 *           email="emctrakers@gmail.com"
 *       ),
 *       @OA\License(
 *           name="Apache 2.0",
 *           url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *       )
 *  )
 * @OA\Server(
 *       url=L5_SWAGGER_CONST_HOST,
 *       description="Laravel web24.com.pl Recruitment Task dynamic host server"
 *   )
 * @OA\PathItem(path="/api")
 * @OA\Tag(
 *      name="Authenticate",
 *      description="Authenticate method for reset of API"
 *  )
 * @OA\Tag(
 *      name="Companies",
 *      description="API for companies managment"
 *  )
 * @OA\Tag(
 *      name="Employees",
 *      description="API for employees managment"
 *  )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 *
 *   @OA\Schema(
 *     schema="CompanyWithEmployees",
 *     type="object",
 *
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       example="12"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       type="string",
 *       example="TrakNet"
 *     ),
 *     @OA\Property(
 *       property="nip",
 *       type="string",
 *       example="9372456534"
 *     ),
 *     @OA\Property(
 *       property="address",
 *       type="string",
 *       example="Kraszewskiego 11/4"
 *     ),
 *     @OA\Property(
 *       property="city",
 *       type="string",
 *       example="Sopot"
 *     ),
 *     @OA\Property(
 *       property="post_code",
 *       type="initger",
 *       example="81815"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       example="2023-08-28T06:42:12.000000Z"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       example="2023-08-28T06:42:12.000000Z"
 *     ),
 *     @OA\Property(property="employees", type="array", @OA\Items(ref="#/components/schemas/Employee"))
 *   )
 *   @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       example="12"
 *     ),
 *     @OA\Property(
 *       property="company_id",
 *       type="integer",
 *       example="22"
 *     ),
 *     @OA\Property(
 *       property="firstname",
 *       type="string",
 *       example="Krszytof"
 *     ),
 *     @OA\Property(
 *       property="lastname",
 *       type="string",
 *       example="Nikiel"
 *     ),
 *     @OA\Property(
 *       property="email",
 *       type="string",
 *       example="emctrakers@gmail.com"
 *     ),
 *     @OA\Property(
 *       property="phone_number",
 *       type="string",
 *       nullable="true",
 *       example="+48 781 955 019"
 *     ),
 *     @OA\Property(
 *        property="created_at",
 *        type="string",
 *        example="2023-08-28T06:42:12.000000Z"
 *      ),
 *     @OA\Property(
 *        property="updated_at",
 *        type="string",
 *        example="2023-08-28T06:42:12.000000Z"
 *      ),
 *   )
 *   @OA\Schema(
 *     schema="Company",
 *     type="object",
 *
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       example="12"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       type="string",
 *       example="TrakNet"
 *     ),
 *     @OA\Property(
 *       property="nip",
 *       type="string",
 *       example="9372456534"
 *     ),
 *     @OA\Property(
 *       property="address",
 *       type="string",
 *       example="Kraszewskiego 11/4"
 *     ),
 *     @OA\Property(
 *       property="city",
 *       type="string",
 *       example="Sopot"
 *     ),
 *     @OA\Property(
 *       property="post_code",
 *       type="initger",
 *       example="81815"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       example="2023-08-28T06:42:12.000000Z"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       example="2023-08-28T06:42:12.000000Z"
 *     ),
 *   )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
