@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-2 text-center">
        <div class="col-4">
            <div class="card card-body bg-success bg-opacity-25">
                <h4>{{ $sales = \App\Models\Cart::totalSales() }}</h4>
                <small>sold</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body bg-warning bg-opacity-25">
                <h4>{{ $spend = abs(\App\Models\Cart::totalSpent()) }}</h4>
                <small>spent</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body bg-info bg-opacity-25">
                <h4>{{ $sales - $spend }}</h4>
                <small>profit</small>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <span class="">Low Stock (lt 5)</span>
                    <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                            data-bs-target="#updateModal" type="button">Update</button>
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
        <div class="col-6">
            <make-sale purpose="purchase"></make-sale>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <make-sale purpose="restocking"></make-sale>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
