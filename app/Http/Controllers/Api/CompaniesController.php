<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    private function getCompanyById($id): Company|null
    {
        return Company::query()->whereNull('deleted_at')->where('id', '=', $id)->first();
    }

    /**
     * @OA\Get(
     *      path="/companies",
     *      operationId="getComapniesList",
     *      tags={"Companies"},
     *      summary="Get list of companies",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CompanyWithEmployees"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      )
     *)
     */
    public function index(): JsonResponse
    {
        $companies = Company::query()->with(['employees'])->whereNull('deleted_at')->get();

        return response()->json([
            "success" => true,
            "data" => $companies
        ]);
    }

    /**
     * @OA\Get(
     *      path="/companies/{id}",
     *      operationId="getComapny",
     *      tags={"Companies"},
     *      @OA\Parameter(
     *          description="Company Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      summary="Get company",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/CompanyWithEmployees")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      ),
     *      @OA\Response(
     *            response=404,
     *            description="Not Found"
     *      )
     *)
     */
    public function read($id): JsonResponse
    {
        $company = Company::query()->whereNull('deleted_at')->with(['employees'])->where('id', '=', $id)->first();
        if ($company === null) {
            return response()->json(["success" => false], 404);
        }
        return response()->json([
            "success" => true,
            "data" => $company
        ]);
    }

    /**
     * @OA\Delete (
     *      path="/companies/delete/{id}",
     *      operationId="deleteComapny",
     *      tags={"Companies"},
     *      @OA\Parameter(
     *          description="Company Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      summary="Delete company",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      ),
     *      @OA\Response(
     *            response=404,
     *            description="Not Found"
     *      )
     *)
     */
    public function delete($id): JsonResponse
    {
        $company = $this->getCompanyById($id);
        if ($company === null) {
            return response()->json(["success" => false], 404);
        }
        \DB::beginTransaction();
        try {

            $company->remove();
            $company->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error($e);
            return response()->json(["success" => false], 500);
        }

        return response()->json(["success" => true]);
    }

    /**
     * @OA\Patch (
     *      path="/companies/patch/{id}",
     *      operationId="patchComapny",
     *      tags={"Companies"},
     *      @OA\Parameter(
     *          description="Company Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nip",
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="post_code",
     *                      type="int"
     *                  ),
     *                  example={"name": "New Company Name", "nip": "9528837725", "address": "3 maja 14/2", "city": "Sopot", "post_code": "86123"}
     *              )
     *          )
     *      ),
     *      summary="Update specific field/fields in company",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Company")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      ),
     *      @OA\Response(
     *            response=404,
     *            description="Not Found"
     *      ),
     *      @OA\Response(
     *            response=400,
     *            description="Bad Request",
     *            @OA\JsonContent(
     *               @OA\Property(property="success", type="bool", example="false"),
     *               @OA\Property(property="message", type="array", @OA\Items(ref="#/components/schemas/Validation"))
     *           )
     *      ),
     *)
     */
    public function patch($id, Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'max:255',
                'nip' => 'NIP|unique:companies,nip,' . $id . ',id',
                'city' => 'min:3|max:255',
                'address' => 'min:3|max:255',
                'post_code' => 'digits:5',
            ]
        );

        if ($validate->fails()) {
            return response()->json(["success" => false, 'message' => $validate->errors()], 400);
        }

        $company = $this->getCompanyById($id);
        if ($company === null) {
            return response()->json(["success" => false], 404);
        }
        try {
            $company->patch($request->all());
            $company->save();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(["success" => false], 500);
        }

        return response()->json(["success" => true, 'data' => $company]);
    }

    /**
     * @OA\Put (
     *      path="/companies/put/{id}",
     *      operationId="putComapny",
     *      tags={"Companies"},
     *      @OA\Parameter(
     *          description="Company Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nip",
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="post_code",
     *                      type="int"
     *                  ),
     *                  example={"name": "New Company Name", "nip": "9528837725", "address": "3 maja 14/2", "city": "Sopot", "post_code": "86123"}
     *              )
     *          )
     *      ),
     *      summary="Update if company exist or insert new company",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Company")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      ),
     *      @OA\Response(
     *            response=400,
     *            description="Bad Request",
     *            @OA\JsonContent(
     *               @OA\Property(property="success", type="bool", example="false"),
     *               @OA\Property(property="message", type="array", @OA\Items(ref="#/components/schemas/Validation"))
     *           )
     *      ),
     *)
     */
    public function put($id, Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'nip' => 'NIP|unique:companies,nip,' . $id . ',id',
                'city' => 'required|min:3|max:255',
                'address' => 'required|min:3|max:255',
                'post_code' => 'required|digits:5',
            ]
        );

        if ($validate->fails()) {
            return response()->json(["success" => false, 'message' => $validate->errors()], 400);
        }

        $company = $this->getCompanyById($id);
        if ($company === null) {
            $company = new Company();
        }
        try {
            $company->put($request->all(), $id);
            $company->save();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(["success" => false], 500);
        }

        return response()->json(["success" => true, 'data' => $company]);
    }

    /**
     * @OA\Post (
     *      path="/companies/create",
     *      operationId="createComapny",
     *      tags={"Companies"},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nip",
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="post_code",
     *                      type="int"
     *                  ),
     *                  example={"name": "New Company Name", "nip": "9528837725", "address": "3 maja 14/2", "city": "Sopot", "post_code": "86123"}
     *              )
     *          )
     *      ),
     *      summary="Create new Company",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Company")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Exception"
     *      ),
     *      @OA\Response(
     *            response=400,
     *            description="Bad Request",
     *            @OA\JsonContent(
     *               @OA\Property(property="success", type="bool", example="false"),
     *               @OA\Property(property="message", type="array", @OA\Items(ref="#/components/schemas/Validation"))
     *           )
     *      ),
     *)
     */
    public function create(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|max:255',
                'nip' => 'required|NIP|unique:companies,nip',
                'city' => 'required|min:3|max:255',
                'address' => 'required|min:3|max:255',
                'post_code' => 'required|digits:5',
            ]
        );

        if ($validate->fails()) {
            return response()->json(["success" => false, 'message' => $validate->errors()], 400);
        }

        try {
            $company = new Company();
            $company->add($request->all());
            $company->save();
            $company->refresh();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(["success" => false], 500);
        }

        return response()->json(["success" => true, 'data' => $company]);
    }
}
