<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'address',
        'type',
        'area',
        'value',
        'status',
        'is_active',
        'start_date',
        'end_date',
    ];

    /*
     * Auto-set is_active based on status
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($project) {
            // If stopped → inactive
            if ($project->status === 'stopped') {
                $project->is_active = false;
            } else {
                $project->is_active = true;
            }
        });
    }

    /**
     * Relationship: Project belongs to a company
     */

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function siteTransactions()
    {
        return $this->hasMany(SiteTransaction::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
