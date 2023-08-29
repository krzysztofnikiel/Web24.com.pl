<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/employees",
     *      operationId="getEmployeesList",
     *      tags={"Employees"},
     *      summary="Get list of employees",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Employee"))
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
        $employees = Employee::query()->whereNull('deleted_at')->get();

        return response()->json(['success' => true, 'data' => $employees]);
    }

    /**
     * @OA\Get(
     *      path="/employees/{id}",
     *      operationId="getEmployees",
     *      tags={"Employees"},
     *      @OA\Parameter(
     *          description="Employee Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      summary="Get employee",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Employee")
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
        $employee = $this->getEmployeeById($id);
        if ($employee === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json(['success' => true, 'data' => $employee]);
    }

    /**
     * @OA\Delete (
     *      path="/employees/delete/{id}",
     *      operationId="deleteEmployee",
     *      tags={"Employees"},
     *      @OA\Parameter(
     *          description="Employee Id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *      ),
     *      summary="Delete Employee",
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
        $employee = $this->getEmployeeById($id);
        if ($employee === null) {
            return response()->json(['success' => false], 404);
        }
        try {
            $employee->remove();
            $employee->save();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @OA\Patch (
     *      path="/employees/patch/{id}",
     *      operationId="employeesComapny",
     *      tags={"Employees"},
     *      @OA\Parameter(
     *          description="Employee Id",
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
     *                      property="firstname",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="lastname",
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone_number",
     *                      type="string"
     *                  ),
     *                  example={"firstname": "Jan", "lastname": "Kowalski", "email": "jan.kowalski@traknet.pl"}
     *              )
     *          )
     *      ),
     *      summary="Update specific field/fields in employee",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Employee")
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
                'firstname' => 'min:3|max:255',
                'lastname' => 'min:3|max:255',
                'email' => 'min:3|max:255|unique:employees,email,' . $id . ',id',
                'phone_number' => 'nullable|min:6|max:14',
            ]
        );

        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 400);
        }

        $employee = $this->getEmployeeById($id);
        if ($employee === null) {
            return response()->json(['success' => false], 404);
        }
        try {
            $employee->patch($request->all());
            $employee->save();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false], 500);
        }

        return response()->json(['success' => true, 'data' => $employee]);
    }

    /**
     * @OA\Put (
     *      path="/employees/put/{id}",
     *      operationId="putEmployee",
     *      tags={"Employees"},
     *      @OA\Parameter(
     *          description="Employee Id",
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
     *                      property="firstname",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="lastname",
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone_number",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="company_id",
     *                      type="int"
     *                  ),
     *                   example={"firstname": "Jan", "lastname": "Kowalski", "email": "jan.kowalski@traknet.pl", "phone_number": "781 955 019", "company_id": 1}
     *              )
     *          )
     *      ),
     *      summary="Update if employee exist or insert new employee",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Employee")
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
     *              @OA\Property(property="success", type="bool", example="false"),
     *              @OA\Property(property="message", type="array", @OA\Items(ref="#/components/schemas/Validation"))
     *           )
     *      ),
     *)
     */
    public function put($id, Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(),
            [
                'firstname' => 'required|min:3|max:255',
                'lastname' => 'required|min:3|max:255',
                'email' => 'required|min:3|max:255|unique:employees,email,' . $id . ',id',
                'phone_number' => 'nullable|min:6|max:14',
                'company_id' => 'required|digits',
            ]
        );

        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 400);
        }

        $employee = $this->getEmployeeById($id);
        if ($employee === null) {
            $employee = new Employee();
        }
        try {
            $employee->put($request->all(), $id);
            $employee->save();
            $employee->refresh();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false], 500);
        }

        return response()->json(['success' => true, 'data' => $employee]);
    }

    /**
     * @OA\Post (
     *      path="/employees/create",
     *      operationId="createEmployee",
     *      tags={"Employees"},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                    @OA\Property(
     *                       property="firstname",
     *                       type="string"
     *                   ),
     *                   @OA\Property(
     *                       property="lastname",
     *                       type="int"
     *                   ),
     *                   @OA\Property(
     *                       property="email",
     *                       type="string"
     *                   ),
     *                   @OA\Property(
     *                       property="phone_number",
     *                       type="string"
     *                   ),
     *                   @OA\Property(
     *                       property="company_id",
     *                       type="int"
     *                   ),
     *                    example={"firstname": "Jan", "lastname": "Kowalski", "email": "jan.kowalski@traknet.pl", "phone_number": "781 955 019", "company_id": 1}
     *              )
     *          )
     *      ),
     *      summary="Create new Employee",
     *      security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="bool"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Employee")
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
     *              @OA\Property(property="success", type="bool", example="false"),
     *              @OA\Property(property="message", type="array", @OA\Items(ref="#/components/schemas/Validation"))
     *           )
     *      ),
     *)
     */
    public function create(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(),
            [
                'firstname' => 'required|min:3|max:255',
                'lastname' => 'required|min:3|max:255',
                'email' => 'required|min:3|max:255|unique:employees,email',
                'phone_number' => 'nullable|min:6|max:14',
                'company_id' => 'required|numeric|min:1',
            ]
        );

        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 400);
        }

        try {
            $company = new Employee();
            $company->add($request->all());
            $company->save();
            $company->refresh();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false], 500);
        }

        return response()->json(['success' => true, 'data' => $company]);
    }

    /**
     * @param int $id
     * @return Employee|null
     */
    private function getEmployeeById(int $id): Employee|null
    {
        return Employee::query()->whereNull('deleted_at')->where('id', '=', $id)->first();
    }
}
