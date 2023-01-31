<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockControl;
use Illuminate\Http\Request;
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
            'name' => $request->name . " " . $size,
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
            if((int) $size > 999) {
                return $size/1000 . "L";
            }
            return $size . "ML";
        }
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
}
