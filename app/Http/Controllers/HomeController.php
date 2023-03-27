<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['deploy']);
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
        $data = Product::join('stock_controls', function ($join) use($request) {
            $join->on('products.id', '=', 'stock_controls.product_id');
            if ($request->has('sale') &&    $request->query('sale')) {
                $join->where('stock_controls.stock_count', '>', 0);
            }
        })
            ->select("name", "sell_price", "products.id", "stock_count", "buy_price")
            ->where("name","LIKE","%{$request->query('query')}%")
            ->get();

        return response()->json($data);
    }

    public function deploy(Request  $request)
    {
        Log::info('Webhook initiated.');
        $githubPayload = $request->getContent();
        $githubHash = $request->header('X-Hub-Signature');
        $localToken = env('DEPLOY_SECRET');
        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

        if (hash_equals($githubHash, $localHash)) {
            $root_path = base_path();
            $process = new Process(['./deploy.sh'], '/home/liquorco/neighborhood');
            $process->run(function ($type, $buffer) {
                Log::info($buffer);
                echo $buffer;
            });
        }
    }
}
