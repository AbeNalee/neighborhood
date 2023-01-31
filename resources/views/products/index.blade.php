@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 my-1">
                <div class="card">
                    <div class="card-header">
                        <span class="">Inventory</span>
                        <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                                data-bs-target="#addModal" type="button">Add Products</button>
                    </div>

                    <div class="card-body">
                        <ul class="list px-2">
                            @foreach(\App\Models\Product::all() as $product)
                                <li class="list-group-item row">
                                    <div class="col-7">
                                        <h3 class="form-control">
                                            {{ $product->name }}
                                            <span class="fw-bold float-end px-2 rounded {{
                                          $product->stock_count > 10 ? "bg-success": ($product->stock_count > 2 ?
                                          "bg-warning" : "bg-danger") }}">
                                        {{ $product->stock_count }}
                                    </span>
                                        </h3>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <add-item></add-item>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
