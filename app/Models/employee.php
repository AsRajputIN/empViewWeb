<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'position', 
        'joining_date', 
        'is_active'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['years_of_service'];

    public function getYearsOfServiceAttribute()
    {
        return $this->joining_date->diffInYears(now());
    }
}