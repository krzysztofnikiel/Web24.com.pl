<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pl_PL');
        for ($i = 1; $i <= 50; $i++) {
            $company = Company::create([
                'name' => $faker->company,
                'nip' => $faker->taxpayerIdentificationNumber,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'post_code' => str_replace('-', '', $faker->postcode),
                'created_at' => Carbon::now()
            ]);
            $company->refresh();
            $EmployeesNumber = rand(0, 6);
            for ($e = 1; $e <= $EmployeesNumber; $e++) {
                Employee::create([
                    'company_id' => $company->id,
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                    'email' => $i . $e . $faker->email,
                    'phone_number' => rand(1, 10) > 3 ? $faker->phoneNumber : null,
                ]);
            }
        }
    }
}
