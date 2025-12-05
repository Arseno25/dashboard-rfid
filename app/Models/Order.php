<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use LaracraftTech\LaravelDateScopes\DateScopes;

class Order extends Model
{
    use HasFactory, DateScopes;
    protected $fillable = [
        'order_number',
        'customer_id',
        'product_id',
        'user_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_note',
        'rfid_uid',
        'quantity',
        'status',
        'payment_method',
        'payment_status',
        'gateway_reference',
        'gateway_payload',
        'invoice_path',
        'invoice_sent_at',
        'price',
        'price_before_discount',
        'discount_amount',
        'total',
        'response',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'invoice_sent_at' => 'datetime',
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function discount() : BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function httplog() : HasMany
    {
        return $this->hasMany(HttpLog::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(string $prefix = 'ORD'): string
    {
        return strtoupper($prefix) . '-' . Str::orderedUuid()->toString();
    }
}
