<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Franchise extends Model
{
    use HasFactory;

    protected $table = 'franchise';

    protected $fillable = [
        'name',
        'phone_1',
        'phone_2',
        'email_id',
        'shop_location_plan',
        'message',
        'address',
        'pincode',
        'taluk',
        'district',
        'state'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes for filtering
    public function scopeByState(Builder $query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopeByDistrict(Builder $query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeByTaluk(Builder $query, $taluk)
    {
        return $query->where('taluk', $taluk);
    }

    public function scopeByPincode(Builder $query, $pincode)
    {
        return $query->where('pincode', $pincode);
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
                ->orWhere('email_id', 'LIKE', '%' . $search . '%')
                ->orWhere('phone_1', 'LIKE', '%' . $search . '%')
                ->orWhere('phone_2', 'LIKE', '%' . $search . '%')
                ->orWhere('district', 'LIKE', '%' . $search . '%')
                ->orWhere('state', 'LIKE', '%' . $search . '%');
        });
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getPhonesAttribute()
    {
        $phones = [$this->phone_1];
        if ($this->phone_2) {
            $phones[] = $this->phone_2;
        }
        return $phones;
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->taluk . ', ' . $this->district . ', ' . $this->state . ' - ' . $this->pincode;
    }
}
