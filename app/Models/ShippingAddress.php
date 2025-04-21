<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'province',
        'district',
        'ward',
        'address',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Lấy dữ liệu danh sách tỉnh, huyện, xã và lưu vào cache.
     */
    public static function loadLocations()
    {
        return Cache::remember('locations_data', 60 * 60, function () {
            try {
                $provinces = Http::get('https://provinces.open-api.vn/api/?depth=3')->json();
                
                $provinceList = [];
                $districtList = [];
                $wardList = [];

                foreach ($provinces as $province) {
                    $provinceList[$province['code']] = $province['name'];
                    foreach ($province['districts'] as $district) {
                        $districtList[$district['code']] = $district['name'];
                        foreach ($district['wards'] as $ward) {
                            $wardList[$ward['code']] = $ward['name'];
                        }
                    }
                }

                return [
                    'provinces' => $provinceList,
                    'districts' => $districtList,
                    'wards' => $wardList,
                ];
            } catch (\Exception $e) {
                Log::error('Error fetching locations: ' . $e->getMessage());
                return [
                    'provinces' => [],
                    'districts' => [],
                    'wards' => [],
                ];
            }
        });
    }

    /**
     * Lấy tên tỉnh theo ID
     */
    public function getProvinceNameAttribute()
    {
        $locations = self::loadLocations();
        return $locations['provinces'][$this->province] ?? 'Unknown Province';
    }

    /**
     * Lấy tên huyện theo ID
     */
    public function getDistrictNameAttribute()
    {
        $locations = self::loadLocations();
        return $locations['districts'][$this->district] ?? 'Unknown District';
    }

    /**
     * Lấy tên xã theo ID
     */
    public function getWardNameAttribute()
    {
        $locations = self::loadLocations();
        return $locations['wards'][$this->ward] ?? 'Unknown Ward';
    }
}