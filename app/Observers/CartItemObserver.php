<?php

namespace App\Observers;

use App\Models\CartItem;

class CartItemObserver
{
    /**
     * Handle the CartItem "created" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function created(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "updated" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function updated(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "deleted" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function deleted(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "restored" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function restored(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "force deleted" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function forceDeleted(CartItem $cartItem)
    {
        //
    }
}
