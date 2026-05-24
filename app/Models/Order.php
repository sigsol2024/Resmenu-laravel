<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'restaurant_id', 'order_number', 'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'payment_method', 'status', 'subtotal', 'delivery_fee', 'tax', 'total',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /** @var list<string> */
    public const STATUSES = ['pending', 'confirmed', 'on_hold', 'cancelled', 'completed'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function displayNumber(): string
    {
        return $this->order_number ?: '#'.$this->id;
    }
}
