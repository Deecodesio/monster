<?php

namespace App\Model;

use App\Model\CareerJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerJobCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function careerJobs()
    {
        return $this->belongsToMany(CareerJob::class, 'career_job_categories', 'category_id');
    }
}
