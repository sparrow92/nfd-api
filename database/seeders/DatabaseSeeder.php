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
       $companies = Company::factory(50)->create();
       Employee::factory(1000)->create([
          'company_id' => $companies->random()->id,
       ]);
    }
}
