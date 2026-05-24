<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $table = 'restaurants';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'description', 'logo', 'hero_image',
        'template_id', 'is_active', 'enable_food_ordering', 'enable_table_reservations', 'header_menu_items',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'enable_food_ordering' => 'boolean',
        'enable_table_reservations' => 'boolean',
        'header_menu_items' => 'array',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('display_order');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
