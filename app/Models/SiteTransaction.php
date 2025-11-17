<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTransaction extends Model
{
    protected $fillable = [
        'project_id',
        'company_id',
        'type',
        'transaction_date',
        'details',
        'description',
        'category',
        'transaction_type',
        'cement_rate',
        'cement_quantity',
        'cement_total_price',
        'expense',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
