<?php

namespace App\View\Composers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ManagerLayoutComposer
{
    public function compose(View $view): void
    {
        $manager = Auth::guard('manager')->user();
        $restaurantId = (int) request()->attributes->get('restaurant_id', $manager?->restaurant_id ?? 0);
        $restaurant = $restaurantId ? Restaurant::find($restaurantId) : null;

        $uploadUrl = rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/');
        $logoUrl = ($restaurant && $restaurant->logo)
            ? $uploadUrl.'/logos/'.rawurlencode($restaurant->logo)
            : null;

        $username = $manager?->username ?? 'Manager';
        $initials = strtoupper(substr($username, 0, 2));

        $showOrders = $restaurant && (int) ($restaurant->enable_food_ordering ?? 1) === 1;
        $showReservations = $restaurant && (int) ($restaurant->enable_table_reservations ?? 1) === 1;

        $view->with([
            'layoutRestaurant' => $restaurant,
            'layoutLogoUrl' => $logoUrl,
            'layoutUsername' => $username,
            'layoutUserInitials' => $initials,
            'layoutNavItems' => $this->buildNavItems($showOrders, $showReservations),
            'layoutPageTitle' => $view->getData()['pageTitle'] ?? $view->getData()['title'] ?? 'Manager',
        ]);
    }

    /** @return list<array{id: string, name: string, href: string, icon: string}> */
    private function buildNavItems(bool $showOrders, bool $showReservations): array
    {
        $items = [
            ['id' => 'dashboard', 'name' => 'Dashboard', 'href' => route('manager.dashboard'), 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
        ];

        if ($showOrders) {
            $items[] = ['id' => 'orders', 'name' => 'Orders', 'href' => route('manager.orders.index'), 'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'];
        }
        if ($showReservations) {
            $items[] = ['id' => 'reservations', 'name' => 'Reservations', 'href' => route('manager.reservations.index'), 'icon' => 'M3.75 9h16.5m-16.5 6.75h16.5'];
            $items[] = ['id' => 'table-inventory', 'name' => 'Table inventory', 'href' => route('manager.table-inventory.index'), 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 9.75h18M4.5 21h15a1.5 1.5 0 001.5-1.5V9.75M3 9.75l.75-5.25A1.5 1.5 0 015.25 3h13.5a1.5 1.5 0 011.5 1.5l.75 5.25'];
        }

        return array_merge($items, [
            ['id' => 'menu-items', 'name' => 'Menu Items', 'href' => route('manager.menu-items.index'), 'icon' => 'M12 6v12m-3-3h6m-3-3h6'],
            ['id' => 'categories', 'name' => 'Categories', 'href' => route('manager.categories.index'), 'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z'],
            ['id' => 'qr-code', 'name' => 'QR Code', 'href' => route('manager.qr.code'), 'icon' => 'M3.75 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V6.31l-3.72 3.72a.75.75 0 01-1.06-1.06l3.72-3.72H4.5a.75.75 0 01-.75-.75zm9.75 0a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V6.31l-3.72 3.72a.75.75 0 11-1.06-1.06l3.72-3.72H14.25a.75.75 0 01-.75-.75zM3.75 15a.75.75 0 01.75.75H5.69l3.72-3.72a.75.75 0 111.06 1.06l-3.72 3.72v1.19a.75.75 0 01-1.5 0v-4.5zm9.75 0a.75.75 0 01.75.75h1.19l-3.72-3.72a.75.75 0 111.06-1.06l3.72 3.72V10.5a.75.75 0 011.5 0v4.5a.75.75 0 01-.75.75z'],
            ['id' => 'customization', 'name' => 'Templates', 'href' => route('manager.customization'), 'icon' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42'],
            ['id' => 'billing', 'name' => 'Billing', 'href' => route('manager.billing.index'), 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
            ['id' => 'payment-settings', 'name' => 'Payment Settings', 'href' => route('manager.billing.payment-settings'), 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
            ['id' => 'settings', 'name' => 'Settings', 'href' => route('manager.settings.edit'), 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ]);
    }
}
