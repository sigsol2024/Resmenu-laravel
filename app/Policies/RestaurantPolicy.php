<?php

namespace App\Policies;

use App\Models\Manager;
use App\Models\Restaurant;

class RestaurantPolicy
{
    public function view(Manager $manager, Restaurant $restaurant): bool
    {
        return (int) $manager->restaurant_id === (int) $restaurant->id;
    }

    public function update(Manager $manager, Restaurant $restaurant): bool
    {
        return $this->view($manager, $restaurant);
    }
}
