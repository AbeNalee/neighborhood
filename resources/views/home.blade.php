@extends('layouts.app')

@section('content')
<div class="container">
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
    <div class="row my-2 text-center">
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-success bg-opacity-25 btn">
                <h4>{{ $sales = \App\Models\Cart::totalSales() }}</h4>
                <small>sold</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-warning bg-opacity-25 btn">
                <h4>{{ $spend = abs(\App\Models\Cart::totalSpent()) }}</h4>
                <small>spent</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-info bg-opacity-25 btn">
                <h4>{{ $sales - $spend }}</h4>
                <small>profit</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-primary bg-opacity-25 btn" href="{{ route('stock.index') }}">
                <h4>{{ \App\Models\Product::all()->sum('value') }}</h4>
                <small>in stock</small>
            </a>
        </div>
    </div>
    @endif
    <div class="row my-2 text-center">
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-success bg-opacity-25 btn h-100" href="{{ route('sales.index', ['period' => 'day']) }}">
                <h4>{{ $salesToday = \App\Models\Cart::totalSalesToday() }}</h4>
                <small>Sales Today</small>
                <small class="float-left small link">View Details</small>
            </a>
        </div>
        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-success bg-opacity-25 btn h-100">
                <h4>{{ $salesToday = \App\Models\Cart::totalSalesMonth() }}</h4>
                <small>Sales This Month</small>
            </a>
        </div>
        @endif
        <div class="col-6 col-lg-3 my-1">
            <a class="card card-body bg-success bg-opacity-25 btn h-100">
                <h4>{{ $salesToday = \App\Models\Cart::totalSalesWeek() }}</h4>
                <small>Sales This Week</small>
            </a>
        </div>
    </div>
        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('staff'))
        <div class="row my-2 text-center">
            <div class="col-6 col-lg-3 my-1">
                <a class="btn btn-primary bg-opacity-50" href="{{ route('stock.index') }}">
                    <h4>View Inventory</h4>
                </a>
            </div>
        </div>
        @endif
    <hr>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6 my-1">
            <div class="row px-2">
                <make-sale purpose="purchase"></make-sale>
            </div>
            <div class="row mt-3 px-2">
                <div class="card px-0">
                    <div class="card-header">
                        <span class="">Debtors</span>
                        <a class="btn btn-secondary float-end py-0" href="#" type="button">Show All</a>
                    </div>

                    <div class="card-body">
                        <ul class="list px-2">
                            @foreach(\App\Models\Creditor::all() as $creditor)
                                <li class="list-group-item">
                                    <h3 class="form-control">
                                        {{ $creditor->name }}
                                        <span class="fw-bold float-end px-2 rounded bg-light">
                                            Ksh. {{ $creditor->amount_owed }}
                                        </span>
                                    </h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
            <div class="col-12 col-lg-6 my-1">
                <div class="card">
                    <div class="card-header">
                        <span class="">Low Stock (lt 5)</span>
                        <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                                data-bs-target="#updateModal" type="button">Update</button>
                    </div>

                    <div class="card-body">
                        <ul class="list px-2">
                            @foreach(\App\Models\Product::lowStock() as $product)
                                <li class="list-group-item">
                                    <h3 class="form-control">
                                        {{ $product->name }}
                                        <span class="fw-bold float-end px-2 rounded {{
                                          $product->stock_count > 10 ? "bg-success": ($product->stock_count > 2 ?
                                          "bg-warning" : "bg-danger") }}">
                                        {{ $product->stock_count }}
                                    </span>
                                    </h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <add-item></add-item>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
