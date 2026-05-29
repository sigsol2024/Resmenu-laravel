<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Models\Order;

use App\Models\Restaurant;

use App\Services\MenuService;

use App\Services\OrderService;

use App\Services\OrderSubmissionService;

use App\Services\RestaurantPaymentService;

use App\Support\ApiJsonResponse;

use App\Support\OrderCancelToken;

use App\Support\OrderConfirmationToken;

use Illuminate\Http\Request;



class OrderApiController extends Controller

{

    public function __construct(

        private OrderSubmissionService $orders,

        private MenuService $menu,

        private OrderService $orderService,

        private RestaurantPaymentService $payments,

    ) {}



    public function store(Request $request)

    {

        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) $request->input('slug', '')));

        $restaurant = $this->menu->findActiveRestaurantBySlug($slug);

        if (! $restaurant) {

            return ApiJsonResponse::error('Restaurant not found', null, 404);

        }



        $cartJson = $request->input('cart_json', '[]');

        if (is_string($cartJson) && strlen($cartJson) > 262144) {

            return ApiJsonResponse::error('Cart data too large', null, 400);

        }

        $cart = is_array($cartJson) ? $cartJson : (json_decode((string) $cartJson, true) ?: []);



        $method = strtolower((string) $request->input('payment_method', 'cash'));

        $customer = [

            'customer_name' => $request->input('customer_name'),

            'customer_phone' => $request->input('customer_phone'),

            'customer_email' => $request->input('customer_email'),

            'delivery_address' => $request->input('delivery_address'),

            'payment_method' => $method,

        ];



        if (in_array($method, ['paystack', 'flutterwave'], true)) {

            $payment = $this->payments->initiateOrderPayment($restaurant, $cart, $customer, $method);

            if (! ($payment['success'] ?? false)) {

                return ApiJsonResponse::error(

                    implode(' ', $payment['errors'] ?? ['Unable to start payment']),

                    ['errors' => $payment['errors'] ?? []],

                    400,

                );

            }



            return ApiJsonResponse::success('Payment required', [

                'payment_required' => true,

                'redirect_url' => $payment['redirect_url'],

            ]);

        }



        if (in_array($method, ['card', 'online', 'bank_transfer'], true)) {

            return ApiJsonResponse::error('Online payment must use paystack or flutterwave', null, 400);

        }



        $result = $this->orders->createFromCart((int) $restaurant->id, $cart, $customer);

        if (! $result['success']) {

            return ApiJsonResponse::error(implode(' ', $result['errors']), ['errors' => $result['errors']], 400);

        }



        $orderId = (int) $result['order_id'];

        $confirmationUrl = OrderConfirmationToken::confirmationUrl($orderId, $slug);

        $payload = [

            'order_id' => $orderId,

            'redirect' => $confirmationUrl !== '' ? $confirmationUrl : null,

        ];

        $token = OrderCancelToken::build($orderId, $slug, 900);

        if ($token) {

            $payload['cancel_order'] = $token;

        }



        return ApiJsonResponse::success('Order created', $payload);

    }



    public function show(Order $order)

    {

        $this->authorizeRestaurant($order);



        return ApiJsonResponse::success('Order details', $order->load('restaurant'));

    }



    public function updateStatus(Request $request, Order $order)

    {

        $this->authorizeRestaurant($order);

        $status = $request->validate(['status' => 'required|in:'.implode(',', Order::STATUSES)])['status'];

        $order->update(['status' => $status]);

        try {
            app(\App\Services\RestaurantTransactionalMailService::class)
                ->sendOrderStatusChange($order->id, (int) $order->restaurant_id, $status);
        } catch (\Throwable $e) {
            report($e);
        }

        return ApiJsonResponse::success('Status updated', ['id' => $order->id, 'status' => $status]);

    }



    public function cancel(Request $request, Order $order)

    {

        if (! OrderCancelToken::verify($request->all(), (int) $order->id)) {

            return ApiJsonResponse::error('Invalid cancel token', null, 403);

        }

        if ($order->status !== 'pending') {

            return ApiJsonResponse::error('Order cannot be cancelled', null, 400);

        }

        $order->update(['status' => 'cancelled']);



        return ApiJsonResponse::success('Order cancelled', ['id' => $order->id]);

    }



    public function analytics(Request $request)

    {

        $restaurantId = (int) $request->attributes->get('restaurant_id');



        return ApiJsonResponse::success('Orders analytics', [

            'by_status' => $this->orderService->countByStatus($restaurantId),

            'revenue' => $this->orderService->revenueTotal($restaurantId),

            'recent' => $this->orderService->recent($restaurantId, 10),

        ]);

    }



    private function authorizeRestaurant(Order $order): void

    {

        $restaurantId = (int) request()->attributes->get('restaurant_id');

        if ((int) $order->restaurant_id !== $restaurantId) {

            abort(403);

        }

    }

}


