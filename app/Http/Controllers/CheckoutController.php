<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        return Inertia::render('Checkout/Index', [
            'cart' => array_values($cart),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $order = Order::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'total' => $total,
            'payment_status' => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('shop.index')->with('success', 'Tellimus salvestatud!');
    }

    public function stripeCheckout(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Ostukorv on tühi.');
    }

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $order = Order::create([
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'total' => $total,
        'payment_provider' => 'stripe',
        'payment_status' => 'pending',
    ]);

    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'product_name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
        ]);
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $lineItems = [];

    foreach ($cart as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => (int) round($item['price'] * 100),
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $session = Session::create([
        'mode' => 'payment',
        'line_items' => $lineItems,
        'success_url' => url('/checkout/success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => url('/checkout/cancel'),
        'client_reference_id' => (string) $order->id,
        'metadata' => [
            'order_id' => $order->id,
        ],
    ]);

    $order->update([
        'stripe_checkout_session_id' => $session->id,
    ]);

    return Inertia::location($session->url);
}

    public function success()
    {
        return Inertia::render('Checkout/Success');
    }

    public function cancel()
    {
        return Inertia::render('Checkout/Cancel');
    }
}