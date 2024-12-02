<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $companies = Company::factory(10)->create();
       Employee::factory(100)->create([
            'company_id' => $companies->random()->id,
       ]);
    }
}
