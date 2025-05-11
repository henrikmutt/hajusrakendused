<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CartController extends Controller
{
    public function add(Request $request, Product $product) 
    {

        // $request->session()->forget('cart');

        $cart = session()->get('cart', []);

        if(data_get($cart, $product->id)) {
            $cart[$product->id]['quantity'] += 1;
        }else {
            $cart[$product->id] = 
            [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'description' => $product->description,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function remove() 
    {
        
    }

    public function clear() 
    {
        session()->forget('cart');

        return redirect()->to(route('products.index'));
    }

    public function view() 
    {
        return Inertia::render('Cart');
    }

    public function update(Request $request) 
    {
        $cart = session()->get('cart');

        if(data_get($cart, $request->id)){
            $cart[$request->id]['quantity'] = $request->quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $lineItems = [];

        foreach ($cart as $id => $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                        'description' => $item['description'],
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('cart.checkout'),
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function success()
    {
        session()->forget('cart');

        return redirect()->route('products.index')->with('success', 'Payment successful.');
    }


}
