<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'category_id',
        'brand_id',
        'price',
        'description',
    ];
    protected $appends = ['discounted_price', "time_left", "average_rating", "count_rating"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function colors()
    {
        return $this->hasMany(Color::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
    public function discount()
    {
        return $this->hasOne(Discount::class);
    }  
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getDiscountedPriceAttribute()
    {
        $originalPrice = $this->price;
    
        // Nếu không có discount hoặc discount đã hết hạn, trả về giá gốc
        if (
            !$this->discount ||
            now()->lt($this->discount->start_date) || // chưa tới ngày bắt đầu
            now()->gt($this->discount->end_date)      // đã quá ngày kết thúc
        ) {
            return $originalPrice;
        }
    
        // Áp dụng giảm giá theo loại
        if ($this->discount->discount_type === 'percent') {
            return max(0, $originalPrice - ($originalPrice * $this->discount->discount_value / 100));
        }
    
        if ($this->discount->discount_type === 'fixed') {
            return max(0, $originalPrice - $this->discount->discount_value);
        }
    
        return $originalPrice;
    }
      
    
    public function getTimeLeftAttribute()
    {
        if ($this->discount && $this->discount->end_date)
        {
            $end = \Carbon\Carbon::parse($this->discount->end_date);
            $now = \Carbon\Carbon::now();
            if ($end->isPast())
            {
                return "Đã hết hạn";
            }
            // Tính toán thời gian còn lại
            $diff = $end->diff($now);
            $days = $diff->d > 0 ? $diff->d . ' ngày ' : '';
            $hours = $diff->h > 0 ? $diff->h . ' giờ ' : '';
            $minutes = $diff->i > 0 ? $diff->i . ' phút' : '';
    
            return $days . $hours . $minutes;
        }
    }

    public function getAverageRatingAttribute()
    {
        if ($this->rating && $this->rating->count() > 0) 
        {
            return round($this->rating->avg('rating'), 1);
        }
    
        return null; // hoặc có thể return 0 nếu bạn muốn mặc định là 0 sao
    }    
    public function getCountRatingAttribute()
    {
        if ($this->rating && $this->rating->count())
        {
            return $this->rating->count();
        }
    }
}
