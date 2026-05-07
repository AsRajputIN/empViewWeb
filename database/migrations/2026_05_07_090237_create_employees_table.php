<?php
// database/migrations/2024_01_01_000000_create_employees_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('position');
            $table->date('joining_date');
            $table->boolean('is_active')->default(true);
            $table->integer('years_of_service')->virtualAs('TIMESTAMPDIFF(YEAR, joining_date, CURDATE())');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}