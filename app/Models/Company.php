<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subscription_status'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
