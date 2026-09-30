<?php

namespace App\Services;

use App\Models\Manager;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Support\ReservationConfirmationToken;
use Illuminate\Support\Facades\DB;

class RestaurantTransactionalMailService
{
    public function __construct(
        private MailService $mail,
        private CustomizationService $customization,
    ) {}

    public function sendOrderCreated(int $orderId, int $restaurantId): void
    {
        $order = Order::with('restaurant')->where('id', $orderId)->where('restaurant_id', $restaurantId)->first();
        if (! $order) {
            return;
        }

        $items = DB::table('order_items')->where('order_id', $orderId)->get();
        $restaurant = $order->restaurant ?? Restaurant::find($restaurantId);
        if (! $restaurant) {
            return;
        }

        $customerEmail = trim((string) $order->customer_email);
        if ($customerEmail && filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $html = $this->wrap($restaurant, 'Order Confirmation', $this->orderConfirmationBody($order, $items, $restaurant));
            $this->mail->send($customerEmail, (string) $order->customer_name, 'Order Confirmation - '.$restaurant->name, $html, [
                'from_name' => $restaurant->name,
            ]);
        }

        $managerEmail = $this->managerEmail($restaurantId);
        if ($managerEmail) {
            $html = $this->wrap($restaurant, 'New Order', $this->managerNewOrderBody($order, $items, $restaurant));
            $this->mail->send($managerEmail, '', 'New Order #'.$this->orderNumber($order).' - '.$restaurant->name, $html, [
                'from_name' => $restaurant->name,
            ]);
        }
    }

    public function sendOrderStatusChange(int $orderId, int $restaurantId, string $newStatus): void
    {
        $order = Order::where('id', $orderId)->where('restaurant_id', $restaurantId)->first();
        if (! $order) {
            return;
        }

        $restaurant = Restaurant::find($restaurantId);
        $customerEmail = trim((string) $order->customer_email);
        if (! $restaurant || ! $customerEmail || ! filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $items = DB::table('order_items')->where('order_id', $orderId)->get();
        $body = '<h2 style="margin:0 0 16px;font-size:22px;color:#111827;">Order status update</h2>'
            .'<p>Your order <strong>#'.e($this->orderNumber($order)).'</strong> is now <strong>'.e(ucfirst($newStatus)).'</strong>.</p>'
            .$this->orderItemsTable($items);

        $html = $this->wrap($restaurant, 'Order Update', $body);
        $this->mail->send($customerEmail, (string) $order->customer_name, 'Order Update - '.$restaurant->name, $html, [
            'from_name' => $restaurant->name,
        ]);
    }

    /**
     * Guest "reservation received" email plus manager notification. Callers must only invoke this
     * once the booking is final: no deposit due, gateway payment verified, or bank transfer approved.
     */
    public function sendReservationCreated(int $reservationId, int $restaurantId, bool $notifyManager = true): void
    {
        $reservation = TableReservation::where('id', $reservationId)->where('restaurant_id', $restaurantId)->first();
        $restaurant = Restaurant::find($restaurantId);
        if (! $reservation || ! $restaurant) {
            return;
        }

        $depositOutstanding = (float) ($reservation->deposit_amount ?? 0) > 0 && ! $reservation->deposit_paid;

        $guestEmail = trim((string) $reservation->guest_email);
        if (! $depositOutstanding && $guestEmail && filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            $html = $this->wrap($restaurant, 'Reservation Received', $this->reservationGuestBody($reservation, $restaurant));
            $this->mail->send($guestEmail, (string) $reservation->guest_name, 'Reservation Received - '.$restaurant->name, $html, [
                'from_name' => $restaurant->name,
            ]);
        }

        $managerEmail = $notifyManager ? $this->managerEmail($restaurantId) : null;
        if ($managerEmail) {
            $html = $this->wrap($restaurant, 'New Reservation', $this->reservationManagerBody($reservation, $restaurant));
            $this->mail->send($managerEmail, '', 'New Reservation #'.$this->reservationNumber($reservation).' - '.$restaurant->name, $html, [
                'from_name' => $restaurant->name,
            ]);
        }
    }

    /** Guest notice that the restaurant could not match their bank transfer; the reservation stays pending. */
    public function sendReservationDepositRejected(int $reservationId, int $restaurantId): void
    {
        $reservation = TableReservation::where('id', $reservationId)->where('restaurant_id', $restaurantId)->first();
        $restaurant = Restaurant::find($restaurantId);
        $guestEmail = trim((string) ($reservation->guest_email ?? ''));
        if (! $reservation || ! $restaurant || ! $guestEmail || ! filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if ($reservation->deposit_paid || in_array((string) $reservation->status, ['rejected', 'cancelled', 'completed'], true)) {
            return;
        }

        $custom = $this->customization->forRestaurant($restaurant);
        $primary = $custom['primary_color'] ?? '#111827';
        $payUrl = ReservationConfirmationToken::confirmationUrl((int) $reservation->id, (string) $restaurant->slug)
            ?: route('public.checkout', ['slug' => $restaurant->slug, 'reservation_id' => $reservation->id]);

        $body = '<h2 style="margin:0 0 16px;font-size:22px;color:#111827;">We couldn\'t confirm your deposit</h2>'
            .'<p>Hello '.e($reservation->guest_name).', '.e($restaurant->name).' was unable to confirm the bank transfer for your reservation <strong>#'.e($this->reservationNumber($reservation)).'</strong>.</p>'
            .$this->reservationDetailsList($reservation)
            .'<p>Your reservation has not been cancelled, but your table is only secured once the ₦'.number_format((float) $reservation->deposit_amount, 2).' deposit is received.</p>'
            .'<p style="margin:24px 0;"><a href="'.e($payUrl).'" style="display:inline-block;background:'.e($primary).';color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">Pay deposit</a></p>'
            .'<p>If you believe you have already paid, please reply to this email or contact the restaurant with your transfer receipt.</p>';

        $html = $this->wrap($restaurant, 'Deposit not confirmed', $body);
        $this->mail->send($guestEmail, (string) $reservation->guest_name, 'Action needed: reservation deposit - '.$restaurant->name, $html, [
            'from_name' => $restaurant->name,
        ]);
    }

    public function sendReservationStatusChange(int $reservationId, int $restaurantId, string $newStatus): void
    {
        $reservation = TableReservation::where('id', $reservationId)->where('restaurant_id', $restaurantId)->first();
        $restaurant = Restaurant::find($restaurantId);
        $guestEmail = trim((string) ($reservation->guest_email ?? ''));
        if (! $reservation || ! $restaurant || ! $guestEmail || ! filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $custom = $this->customization->forRestaurant($restaurant);
        $body = '<h2 style="margin:0 0 16px;font-size:22px;color:#111827;">Reservation update</h2>'
            .'<p>Your reservation <strong>#'.e($this->reservationNumber($reservation)).'</strong> is now <strong>'.e(ucfirst($newStatus)).'</strong>.</p>'
            .$this->reservationDetailsList($reservation);

        if ($newStatus === 'confirmed') {
            $confirmUrl = ReservationConfirmationToken::confirmationUrl((int) $reservation->id, (string) $restaurant->slug);
            if ($confirmUrl !== '') {
                $body .= '<p style="margin-top:16px;"><a href="'.e($confirmUrl).'" style="color:'.e($custom['primary_color'] ?? '#111827').';">View reservation confirmation</a></p>';
            }
        }

        $html = $this->wrap($restaurant, 'Reservation Update', $body);
        $this->mail->send($guestEmail, (string) $reservation->guest_name, 'Reservation Update - '.$restaurant->name, $html, [
            'from_name' => $restaurant->name,
        ]);
    }

    public function sendAccountSuspended(Restaurant $restaurant, string $reason): void
    {
        $email = $this->managerEmail((int) $restaurant->id)
            ?: (is_string($restaurant->email) ? $restaurant->email : null)
            ?: (is_string($restaurant->manager_email) ? $restaurant->manager_email : null);
        if (! $email) {
            return;
        }

        $purgeDays = (int) config('restaurant_lifecycle.purge_days_after_suspend', 7);
        if ($reason === 'inactivity') {
            $title = 'Account suspended due to inactivity';
            $body = '<h2 style="margin:0 0 8px;font-size:22px;color:#111827;">Account suspended</h2>'
                .'<p>Your restaurant <strong>'.e($restaurant->name).'</strong> has been suspended because there has been no account activity for 30 days.</p>'
                .'<p>Unless the account is restored, it will be permanently deleted in approximately '.$purgeDays.' days.</p>'
                .'<p>Contact support if you need this account restored.</p>';
        } else {
            $title = 'Account suspended by administrator';
            $body = '<h2 style="margin:0 0 8px;font-size:22px;color:#111827;">Account suspended</h2>'
                .'<p>Your restaurant <strong>'.e($restaurant->name).'</strong> has been suspended by an administrator.</p>'
                .'<p>Unless restored, the account may be permanently deleted after '.$purgeDays.' days.</p>'
                .'<p>Contact support for assistance.</p>';
        }

        $html = $this->wrap($restaurant, $title, $body);
        $this->mail->send($email, '', $title.' - '.$restaurant->name, $html, [
            'from_name' => config('app.name', 'Resmenu'),
        ]);
    }

    private function managerEmail(int $restaurantId): ?string
    {
        // managers table has no is_active column
        $manager = Manager::where('restaurant_id', $restaurantId)->first();

        return $manager?->email;
    }

    private function orderNumber(Order $order): string
    {
        if (! empty($order->order_number)) {
            return (string) $order->order_number;
        }

        return strtoupper(str_pad(base_convert((string) $order->id, 10, 36), 8, '0', STR_PAD_LEFT));
    }

    private function reservationNumber(TableReservation $reservation): string
    {
        if (! empty($reservation->reservation_number)) {
            return (string) $reservation->reservation_number;
        }

        return strtoupper(str_pad(base_convert((string) $reservation->id, 10, 36), 8, '0', STR_PAD_LEFT));
    }

    private function wrap(Restaurant $restaurant, string $title, string $body): string
    {
        $custom = $this->customization->forRestaurant($restaurant);
        $primary = $custom['primary_color'] ?? '#111111';
        $menuUrl = url('/restaurant/'.$restaurant->slug);
        $logoUrl = $restaurant->logo ? rtrim((string) config('resmenu.upload_url', url('/uploads')), '/').'/logos/'.$restaurant->logo : '';

        $header = $logoUrl
            ? '<img src="'.e($logoUrl).'" alt="'.e($restaurant->name).'" style="max-height:52px;display:block;margin:0 auto;">'
            : '<h1 style="color:#fff;font-size:24px;margin:0;">'.e($restaurant->name).'</h1>';

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>'.e($title).'</title></head>'
            .'<body style="margin:0;padding:24px;background:#f8f5f5;font-family:Inter,sans-serif;">'
            .'<div style="max-width:640px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">'
            .'<header style="background:#1f2937;padding:28px;text-align:center;border-bottom:4px solid '.e($primary).'">'.$header.'</header>'
            .'<section style="padding:32px 28px;color:#374151;line-height:1.6;">'.$body.'</section>'
            .'<footer style="background:#1f2937;padding:20px;text-align:center;color:#9ca3af;font-size:12px;">'
            .'<a href="'.e($menuUrl).'" style="color:'.e($primary).'">View Menu</a> &copy; '.date('Y').' '.e($restaurant->name)
            .'</footer></div></body></html>';
    }

    /** @param \Illuminate\Support\Collection<int, object> $items */
    private function orderConfirmationBody(Order $order, $items, Restaurant $restaurant): string
    {
        return '<h2 style="margin:0 0 8px;font-size:26px;color:#111827;">Thank you for your order!</h2>'
            .'<p>Hello '.e($order->customer_name).', your order has been received.</p>'
            .'<p><strong>Order #'.e($this->orderNumber($order)).'</strong></p>'
            .$this->orderItemsTable($items)
            .'<p style="margin-top:16px;font-size:18px;font-weight:700;">Total: ₦'.number_format((float) $order->total, 2).'</p>';
    }

    /** @param \Illuminate\Support\Collection<int, object> $items */
    private function managerNewOrderBody(Order $order, $items, Restaurant $restaurant): string
    {
        return '<h2 style="margin:0 0 8px;font-size:22px;">New order received</h2>'
            .'<p>Order #'.e($this->orderNumber($order)).' from '.e($order->customer_name).'</p>'
            .$this->orderItemsTable($items);
    }

    /** @param \Illuminate\Support\Collection<int, object> $items */
    private function orderItemsTable($items): string
    {
        $rows = '';
        foreach ($items as $item) {
            $qty = (int) ($item->quantity ?? 1);
            $price = (float) ($item->price ?? 0);
            $rows .= '<tr><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;">'.e($item->name ?? '').' x'.$qty.'</td>'
                .'<td style="padding:8px 0;text-align:right;">₦'.number_format($qty * $price, 2).'</td></tr>';
        }

        return '<table style="width:100%;border-collapse:collapse;margin-top:16px;"><tbody>'.$rows.'</tbody></table>';
    }

    private function reservationGuestBody(TableReservation $reservation, Restaurant $restaurant): string
    {
        $deposit = (float) ($reservation->deposit_amount ?? 0);

        $body = '<h2 style="margin:0 0 8px;font-size:26px;color:#111827;">Reservation received</h2>'
            .'<p>Hello '.e($reservation->guest_name).', we\'ve received your reservation and our team will get back to you shortly.</p>'
            .'<p><strong>Reservation #'.e($this->reservationNumber($reservation)).'</strong></p>'
            .$this->reservationDetailsList($reservation);

        if ($deposit > 0 && $reservation->deposit_paid) {
            $body .= '<p>Your deposit of ₦'.number_format($deposit, 2).' has been received.</p>';
        }

        $confirmUrl = ReservationConfirmationToken::confirmationUrl((int) $reservation->id, (string) $restaurant->slug);
        if ($confirmUrl !== '') {
            $body .= '<p style="margin-top:16px;"><a href="'.e($confirmUrl).'">View your reservation</a></p>';
        }

        return $body;
    }

    private function reservationManagerBody(TableReservation $reservation, Restaurant $restaurant): string
    {
        return '<h2 style="margin:0 0 8px;font-size:22px;">New reservation</h2>'
            .'<p>Reference #'.e($this->reservationNumber($reservation)).'</p>'
            .'<p>Guest: '.e($reservation->guest_name).' &middot; '.e($reservation->guest_phone).' &middot; '.e($reservation->guest_email).'</p>'
            .$this->reservationDetailsList($reservation)
            .((float) ($reservation->deposit_amount ?? 0) > 0 && $reservation->deposit_paid
                ? '<p>Deposit of ₦'.number_format((float) $reservation->deposit_amount, 2).' has been paid.</p>'
                : '');
    }

    private function reservationDetailsList(TableReservation $reservation): string
    {
        $time = $reservation->reservation_time ? substr((string) $reservation->reservation_time, 0, 5) : '';

        return '<ul style="padding-left:20px;">'
            .'<li>Date: '.e($reservation->reservation_date?->format('M j, Y') ?? (string) $reservation->reservation_date).'</li>'
            .'<li>Time: '.e($time).'</li>'
            .'<li>Party size: '.(int) $reservation->party_size.'</li>'
            .($reservation->special_occasion ? '<li>Occasion: '.e($reservation->special_occasion).'</li>' : '')
            .($reservation->notes ? '<li>Notes: '.e($reservation->notes).'</li>' : '')
            .'</ul>';
    }
}
