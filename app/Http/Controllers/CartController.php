<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        
    }

    public function view() 
    {
        
    }

    public function update() 
    {
        
    }
}
