@extends('layouts.app')

@section('content')
<div class="container">
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
            <a class="card card-body bg-primary bg-opacity-25 btn">
                <h4>{{ \App\Models\Product::all()->sum('value') }}</h4>
                <small>in stock</small>
            </a>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6 my-1">
            <make-sale purpose="purchase"></make-sale>
        </div>
        <div class="col-12 col-lg-6 my-1">
            <div class="card">
                <div class="card-header">
                    <span class="">Low Stock (lt 5)</span>
                    @if(in_array(\Illuminate\Support\Facades\Auth::user()->email, ['abrahamaguvasu@gmail.com', 'muteshiteddy@gmail.com']))
                        <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                            data-bs-target="#updateModal" type="button">Update</button>
                    @endif
                </div>

                <div class="card-body">
                    <ul class="list px-2">
                        @foreach(\App\Models\Product::lowStock()->get() as $product)
                            <li class="list-group-item">
                                <h3 class="form-control">
                                    {{ $product->name }}
                                    <span class="fw-bold float-end px-2 rounded {{
                                          $product->stock > 2 ? "bg-warning":
                                          "bg-danger" }}">
                                        {{ $product->stock }}
                                    </span>
                                </h3>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @if(in_array(\Illuminate\Support\Facades\Auth::user()->email, ['abrahamaguvasu@gmail.com', 'muteshiteddy@gmail.com']))
            <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <make-sale purpose="restocking"></make-sale>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
