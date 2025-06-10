<?php

namespace App\Model;

use App\Model\Addcity;
use App\Model\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CareerJob extends Model
{
    use HasFactory;

    protected $table = 'career_jobs';

    protected $fillable = [
        'job_name',
        'job_details',
        'location_id',
        'state_id',
        'created_by',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function city()
    {
        return $this->belongsTo(Addcity::class, 'location_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Roles::class, 'created_by', 'id');
    }

    // Scopes for filtering
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive(Builder $query)
    {
        return $query->where('status', 2);
    }

    public function scopeByLocation(Builder $query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    public function scopeByState(Builder $query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    public function scopeByCreator(Builder $query, $createdBy)
    {
        return $query->where('created_by', $createdBy);
    }

    public function scopeDateRange(Builder $query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }
}
