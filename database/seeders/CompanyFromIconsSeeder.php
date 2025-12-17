<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanyFromIconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creates up to 30 companies using the CompanyFactory, which
        // derives name, website, email, and logo from PNG icon files.
        Company::factory()->count(30)->create();
    }
}

