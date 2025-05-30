<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'attach_resume',
        'career_job_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function careerJob()
    {
        return $this->belongsTo(CareerJob::class, 'career_job_id', 'id');
    }

    // Scopes for filtering
    public function scopeByCareerJob(Builder $query, $careerJobId)
    {
        return $query->where('career_job_id', $careerJobId);
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

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orWhere('contact_number', 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeWithActiveJobs(Builder $query)
    {
        return $query->whereHas('careerJob', function ($q) {
            $q->where('status', 'active');
        });
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getResumeUrlAttribute()
    {
        if ($this->attach_resume) {
            return asset('storage/' . $this->attach_resume);
        }
        return null;
    }

    public function getHasResumeAttribute()
    {
        return !empty($this->attach_resume);
    }
}
