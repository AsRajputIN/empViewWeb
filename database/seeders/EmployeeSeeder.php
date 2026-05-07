<?php
// database/seeders/EmployeeSeeder.php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Senior Developer',
            'joining_date' => '2018-01-15',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'position' => 'Project Manager',
            'joining_date' => '2015-06-20',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'position' => 'QA Engineer',
            'joining_date' => '2022-03-10',
            'is_active' => true,
        ]);

        Employee::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'position' => 'UI/UX Designer',
            'joining_date' => '2020-11-05',
            'is_active' => false,
        ]);

        Employee::create([
            'name' => 'Charlie Wilson',
            'email' => 'charlie@example.com',
            'position' => 'DevOps Engineer',
            'joining_date' => '2017-08-30',
            'is_active' => true,
        ]);
    }
}