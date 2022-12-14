<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $data = Product::select("name", "sell_price", "id", "stock", "buy_price")
            ->where("name","LIKE","%{$request->query('query')}%")
            ->isInStock()
            ->get();

        return response()->json($data);
    }
}
