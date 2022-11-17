<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function transact(Request $request)
    {
        $purchase = Purchase::create();
        $cart = Cart::create([
            'value' => $request->purpose === 'purchase' ? (int)$request->amount : -1 * abs((int)$request->amount),
            'purchase_id' => $purchase->id
        ]);
        foreach ($request->items as $item){
            //create cart relationship
            if ($request->purpose == 'purchase') {
                $quantity = - (int) $item['quantity'];
            } else {
                $quantity = (int) $item['quantity'];
            }
            $product = Product::find($item['id']);
            $product->carts()
                ->attach($cart->id, [
                    'quantity' => $quantity
                ]);

            $product->stock += $quantity;
            $product->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
