<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('products.index');
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $purchase = Purchase::create([
            'purpose' => 'restocking'
        ]);
        $cart = Cart::create([
            'value' => -1 * abs((int)$request->quantity * (int)$request->buy_price),
            'purchase_id' => $purchase->id
        ]);
        $size = $this->formatSize(strtoupper($request->size));
        $product = Product::firstOrCreate([
            'name' => strtoupper($request->name) . $size,
            'alcoholic' => $request->alcoholic,
        ],[
            'sell_price' => $request->sell_price
        ]);

        $product->carts()
            ->attach($cart->id, [
                'quantity' => (int)$request->quantity
            ]);

        $stock = $product->stocks()->where('buy_price', $request->buy_price)->first();

        if (isset($stock)) {
            $stock->stock_count += $request->quantity;
            $stock->save();
        } else {
            $product->stocks()->create([
                'product_id' => $product->id,
                'buy_price' => $request->buy_price,
                'stock_count' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function formatSize($size)
    {
        if (str_contains($size, 'ML')) {
            return $this->formatSize(substr_replace($size, '', -2));
        } else {
            if((int)$size == 0 || $size == null) {
                return null;
            }
            if((int) $size > 999) {
                return " " . $size/1000 . "L";
            }
            return " " . $size . "ML";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $stock)
    {
        if ($request->purpose == 'add') {
            $stockControl = $stock->stocks()->latest()->first();
            $purchase = Purchase::create([
                'purpose' => 'restocking'
            ]);
            $cart = Cart::create([
                'value' => -1 * abs((int)$request->quantity * (int)$stockControl->buy_price),
                'purchase_id' => $purchase->id
            ]);
            $stock->carts()
                ->attach($cart->id, [
                    'quantity' => (int)$request->quantity
                ]);
            $stockControl->stock_count += $request->quantity;
            $stockControl->save();
        } else {
            $cartItems = $stock->cartItems;
            $this->clearCarts($cartItems, $request->quantity);
            $stocks = $stock->stocks()->where('stock_count', '>', 0)->oldest()->get();
            $this->reduceStock($stocks, $request->quantity);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $stock)
    {
        //
    }

    public function transact(Request $request)
    {
        $purchase = Purchase::create([
            'purpose' => $request->purpose
        ]);
        $cart = Cart::create([
            'value' => $request->purpose === 'purchase' ? (int)$request->amount : -1 * abs((int)$request->amount),
            'purchase_id' => $purchase->id
        ]);
        foreach ($request->items as $item){
            //create cart relationship
            $quantity = (int) $item['quantity'];

            $product = Product::find($item['id']);
            $product->carts()
                ->attach($cart->id, [
                    'quantity' => -1 * $quantity
                ]);

            $stocks = $product->stocks()->where('stock_count', '>', 0)->oldest()->get();
            $this->reduceStock($stocks, $quantity);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    function reduceStock($stocks, $quantity)
    {
        for ($i = 0; $i < count($stocks); $i++) {
            if ($quantity == 0) {
                break;
            }
            $count = $stocks[$i]->stock_count;
            if ($quantity >= $count) {
                $quantity -= $count;
                $stocks[$i]->stock_count = 0;
                $stocks[$i]->save();
            } else {
                $stocks[$i]->stock_count -= $quantity;
                $stocks[$i]->save();
                $quantity = 0;
            }
        }
    }

    function clearCarts($cartItems, $quantity)
    {
        for ($i = 0; $i < count($cartItems); $i++) {
            if ($quantity == 0) {
                break;
            }
            $count = $cartItems[$i]->quantity;
            if ($quantity >= $count) {
                $quantity -= $count;
                $reducedBy = $cartItems[$i]->quantity;
                $cartItems[$i]->update([
                    'quantity' => 0,
                ]);
            } else {
                $cartItems[$i]->quantity -= $quantity;
                $cartItems[$i]->save();
                $reducedBy = $quantity;
                $quantity = 0;
            }

            Log::info($reducedBy);
            $cart = $cartItems[$i]->cart;
            //get cartItem value (quantity * sell/buy price)
            if ($cart->purchase->purpose == 'restocking') {
                $stock = $cartItems[$i]->product->stocks()->where('stock_count', '>', 0)->first();
                $val = $reducedBy * $stock->buy_price;
                Log::warning("clearing stock. Cart value is currently $cart->value.\n It will be reduced by $val");
                $cart->value += $val;
                $cart->save();
                Log::warning("cleared stock. Cart value updated to $cart->value");
            }
        }
    }
}
