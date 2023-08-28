<?php

namespace Tests\Unit\Model;

use App\Models\Company;
use Carbon\Carbon;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

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
                'input' => [
                    'name' => 'Apple',
                    'city' => 'Wawa'
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
     * @dataProvider companiesPutOrCreateProvider
     */
    public function putCompany($request, $expected)
    {
        $company = new Company();
        $company->name = $request['name'];
        $company->nip = $request['nip'];
        $company->city = $request['city'];
        $company->post_code = $request['post_code'];
        $company->address = $request['address'];
        $company->put($request);

        $this->checkDefaultAssertion($company, $expected);
        $this->assertNull($company->created_at);
        $this->assertNotEmpty($company->updated_at);
    }


    /**
     * @test
     * @return void
     * @dataProvider companiesPutOrCreateProvider
     */
    public function createCompany($request, $expected)
    {
        $company = new Company();
        $company->name = $request['name'];
        $company->nip = $request['nip'];
        $company->city = $request['city'];
        $company->post_code = $request['post_code'];
        $company->address = $request['address'];
        $company->create($request);

        $this->checkDefaultAssertion($company, $expected);
        $this->assertNotEmpty($company->created_at);
        $this->assertNull($company->updated_at);
    }

    public static function companiesPutOrCreateProvider(): array
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
