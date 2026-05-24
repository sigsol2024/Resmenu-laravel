<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name', 'slug', 'description', 'monthly_price', 'annual_price', 'yearly_discount_percent',
        'max_categories', 'max_menu_items', 'max_qr_styles', 'max_templates',
        'features', 'is_active', 'display_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
