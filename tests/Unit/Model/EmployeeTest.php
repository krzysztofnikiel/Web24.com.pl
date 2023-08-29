<?php

namespace Model;

use App\Models\Company;
use App\Models\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * @return void
     * @test
     * @dataProvider employeesPatchProvider
     */
    public function patchEmployee($request, $expected)
    {
        $employee = new Employee();
        $employee->firstname = 'Jan';
        $employee->lastname = 'Kowalski';
        $employee->email = 'j.kowalski_test@gmail.com';
        $employee->phone_number = '781 955 019';
        $employee->patch($request);

        $this->checkDefaultAssertion($employee, $expected);
        $this->assertNotEmpty($employee->updated_at);
    }

    public static function employeesPatchProvider(): array
    {
        return [
            [
                'input' => [],
                'expected' => [
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'email' => 'j.kowalski_test@gmail.com',
                    'phone_number' => '781 955 019',
                ]
            ],
            [
                'input' => [
                    'firstname' => 'Ania',
                    'lastname' => 'Lewandowska',
                    'phone_number' => null,
                    'unknown' => false
                ],
                'expected' => [
                    'firstname' => 'Ania',
                    'lastname' => 'Lewandowska',
                    'email' => 'j.kowalski_test@gmail.com',
                    'phone_number' => null
                ]
            ],
            [
                'input' => [
                    'email' => 'atest@test.pl',
                    'phone_number' => '777 777 777',
                ],
                'expected' => [
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'email' => 'atest@test.pl',
                    'phone_number' => '777 777 777',
                ]

            ],
        ];
    }

    /**
     * @test
     * @return void
     * @dataProvider employeesPutProvider
     */
    public function putEmployee($request, $expected, $exist)
    {
        $employee = new Employee();
        $employee->firstname = $request['firstname'];
        $employee->lastname = $request['lastname'];
        $employee->email = $request['email'];
        if (isset($request['phone_number'])) {
            $employee->phone_number = $request['phone_number'];
        }
        if ($exist) {
            $employee->id = $request['id'];
        }
        $employee->put($request, $request['id']);

        $this->checkDefaultAssertion($employee, $expected);
        if ($exist) {
            $this->assertNull($employee->created_at);
            $this->assertNotEmpty($employee->updated_at);
            return;
        }
        $this->assertNotEmpty($employee->created_at);
        $this->assertNull($employee->updated_at);
    }

    public static function employeesPutProvider(): array
    {
        return [
            [
                'input' => [
                    'id' => 11,
                    'firstname' => 'Krzysztof',
                    'lastname' => 'Ni',
                    'email' => 'emctrakers@gmail.com',
                ],
                'expected' => [
                    'id' => 11,
                    'firstname' => 'Krzysztof',
                    'lastname' => 'Ni',
                    'email' => 'emctrakers@gmail.com',
                ],
                false
            ],
            [
                'input' => [
                    'id' => 22,
                    'firstname' => 'Test',
                    'lastname' => 'Zakopane',
                    'email' => 'test@test.pl'
                ],
                'expected' => [
                    'id' => 22,
                    'firstname' => 'Test',
                    'lastname' => 'Zakopane',
                    'email' => 'test@test.pl'
                ],
                true
            ],
        ];
    }

    /**
     * @test
     * @return void
     * @dataProvider employeesCreateProvider
     */
    public function createEmployee($request, $expected)
    {
        $employee = new Employee();
        $employee->firstname = $request['firstname'];
        $employee->lastname = $request['lastname'];
        $employee->email = $request['email'];
        if (isset($request['phone_number'])) {
            $employee->phone_number = $request['phone_number'];
        }
        $employee->add($request);

        $this->checkDefaultAssertion($employee, $expected);
        $this->assertNotEmpty($employee->created_at);
        $this->assertNull($employee->updated_at);
    }

    public static function employeesCreateProvider(): array
    {
        return [
            [
                'input' => [
                    'id' => 11,
                    'firstname' => 'Krzysztof',
                    'lastname' => 'Ni',
                    'email' => 'emctrakers@gmail.com',
                ],
                'expected' => [
                    'id' => 11,
                    'firstname' => 'Krzysztof',
                    'lastname' => 'Ni',
                    'email' => 'emctrakers@gmail.com',
                ],

            ],
        ];
    }

    /**
     * @param Employee $employee
     * @param array $expected
     * @return void
     */
    private function checkDefaultAssertion(Employee $employee, array $expected): void
    {
        $this->assertEquals($employee->firstname, $expected['firstname']);
        $this->assertEquals($employee->lastname, $expected['lastname']);
        $this->assertEquals($employee->email, $expected['email']);
        if (isset($expected['phone_number'])) {
            $this->assertEquals($employee->phone_number, $expected['phone_number']);
        }
        $this->assertNull($employee->deleted_at);
    }
}
