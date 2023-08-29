<?php

namespace Tests\Unit\Model;

use App\Models\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /**
     * @return void
     * @test
     * @dataProvider companiesPatchProvider
     */
    public function patchCompany($request, $expected)
    {
        $company = new Company();
        $company->name = 'Test';
        $company->nip = 4175718909;
        $company->city = 'Zakopane';
        $company->post_code = 81989;
        $company->address = '3 maja 14/2';
        $company->patch($request);

        $this->checkDefaultAssertion($company, $expected);
        $this->assertNotEmpty($company->updated_at);
    }

    public static function companiesPatchProvider(): array
    {
        return [
            [
                'input' => [],
                'expected' => [
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ]
            ],
            [
                'input' => [
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'unknown' => false
                ],
                'expected' => [
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ]
            ],
            [
                'input' => [
                    'nip' => 7971663669,
                ],
                'expected' => [
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 7971663669,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ]

            ],
        ];
    }

    /**
     * @test
     * @return void
     * @dataProvider companiesPutProvider
     */
    public function putCompany($request, $expected, $exist)
    {
        $company = new Company();
        $company->name = $request['name'];
        $company->nip = $request['nip'];
        $company->city = $request['city'];
        $company->post_code = $request['post_code'];
        $company->address = $request['address'];
        if ($exist) {
            $company->id = $request['id'];
        }
        $company->put($request, $request['id']);

        $this->checkDefaultAssertion($company, $expected);
        if ($exist) {
            $this->assertNull($company->created_at);
            $this->assertNotEmpty($company->updated_at);
            return;
        }
        $this->assertNotEmpty($company->created_at);
        $this->assertNull($company->updated_at);
    }

    public static function companiesPutProvider(): array
    {
        return [
            [
                'input' => [
                    'id' => 11,
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                'expected' => [
                    'id' => 11,
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                false
            ],
            [
                'input' => [
                    'id' => 22,
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 7971663669,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                'expected' => [
                    'id' => 22,
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 7971663669,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                true
            ],
        ];
    }

    /**
     * @test
     * @return void
     * @dataProvider companiesCreateProvider
     */
    public function createCompany($request, $expected)
    {
        $company = new Company();
        $company->name = $request['name'];
        $company->nip = $request['nip'];
        $company->city = $request['city'];
        $company->post_code = $request['post_code'];
        $company->address = $request['address'];
        $company->add($request);

        $this->checkDefaultAssertion($company, $expected);
        $this->assertNotEmpty($company->created_at);
        $this->assertNull($company->updated_at);
    }

    public static function companiesCreateProvider(): array
    {
        return [
            [
                'input' => [
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                'expected' => [
                    'name' => 'Apple',
                    'city' => 'Wawa',
                    'nip' => 4175718909,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ]

            ],
            [
                'input' => [
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 7971663669,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ],
                'expected' => [
                    'name' => 'Test',
                    'city' => 'Zakopane',
                    'nip' => 7971663669,
                    'post_code' => 81989,
                    'address' => '3 maja 14/2'
                ]

            ],
        ];
    }

    /**
     * @param Company $company
     * @param array $expected
     * @return void
     */
    private function checkDefaultAssertion(Company $company, array $expected): void
    {
        $this->assertEquals($company->name, $expected['name']);
        $this->assertEquals($company->nip, $expected['nip']);
        $this->assertEquals($company->city, $expected['city']);
        $this->assertEquals($company->post_code, $expected['post_code']);
        $this->assertEquals($company->address, $expected['address']);
        $this->assertNull($company->deleted_at);
    }
}
