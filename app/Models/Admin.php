<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ['username', 'email', 'password_hash'];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'is_super_admin' => 'boolean',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function isSuperAdmin(): bool
    {
        if (! \Illuminate\Support\Facades\Schema::hasColumn('admins', 'is_super_admin')) {
            return false;
        }

        return (bool) $this->is_super_admin;
    }
}
