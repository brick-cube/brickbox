<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subscription_status'
    ];

    // public function projects()
    // {
    //     return $this->hasMany(Project::class);
    // }

    // public function employees()
    // {
    //     return $this->hasMany(Employee::class);
    // }
}
