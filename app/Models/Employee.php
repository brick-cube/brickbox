<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'aadhar_number',
        'name',
        'email',
        'phone',
        'position',
        'full_day_rate',
        'half_day_rate',
        'details',
        'is_active',
        'joined_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'date',
        'full_day_rate' => 'decimal:2',
        'half_day_rate' => 'decimal:2',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
