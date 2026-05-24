<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Manager extends Authenticatable
{
    protected $table = 'managers';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ['username', 'email', 'password_hash', 'restaurant_id'];

    protected $hidden = ['password_hash'];

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
