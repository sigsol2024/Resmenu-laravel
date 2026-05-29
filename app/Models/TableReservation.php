<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableReservation extends Model
{
    protected $table = 'table_reservations';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'restaurant_id', 'reservation_number', 'status', 'guest_name', 'guest_email', 'guest_phone',
        'reservation_date', 'reservation_time', 'party_size', 'special_occasion',
        'deposit_amount', 'deposit_paid', 'notes', 'is_walkin',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'deposit_amount' => 'decimal:2',
        'deposit_paid' => 'boolean',
        'is_walkin' => 'boolean',
    ];

    /** @var list<string> */
    public const STATUSES = ['pending', 'confirmed', 'rejected', 'cancelled', 'completed'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
