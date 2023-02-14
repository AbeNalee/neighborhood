<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\StockControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockControlController extends Controller
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
     * @param  \App\Models\StockControl  $stockControl
     * @return \Illuminate\Http\Response
     */
    public function show(StockControl $stockControl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockControl  $stockControl
     * @return \Illuminate\Http\Response
     */
    public function edit(StockControl $stockControl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockControl  $stockControl
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockControl $stockControl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockControl  $stockControl
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockControl $stockControl)
    {
        //
    }

    public function clearSold()
    {
        if (Auth::user()->email == 'abrahamaguvasu@gmail.com') {
            $carts = Cart::where('value', '>', 0)->get();

            foreach ($carts as $cart) {
                $cart->cartItems()->each(function($cartItem) {
                    $cartItem->delete();
                });

                $cart->purchase()->delete();
                $cart->delete();
            }

            return "Done";
        }

        return "Sorry. you can't do this";
    }
}
