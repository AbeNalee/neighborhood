@extends('layouts.app')

@section('content')
    <div class="content">

        <div class="container">
            <h2 class="mb-5">
                <span class="">Inventory</span>
                <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                        data-bs-target="#addModal" type="button">Add Products</button>
            </h2>

            <div class="table-responsive">

                <table class="table table-striped custom-table">
                    <thead>
                    <tr>
                        <th scope="col">
                            <label class="control control--checkbox">
                                <input type="checkbox"  class="js-check-all"/>
                                <div class="control__indicator"></div>
                            </label>
                        </th>
                        <th scope="col">Name</th>
                        <th scope="col">Highest Buy Price</th>
                        <th scope="col">Selling Price</th>
                        <th scope="col">Profit</th>
                        <th scope="col">Stock Count</th>
                        <th scope="col">Value</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\Product::all() as $product)
                        <tr scope="row">
                            <td>
                                <label class="control control--checkbox">
                                    <input type="checkbox" />
                                    <div class="control__indicator"></div>
                                </label>
                            </td>
                            <td>
                                {{ $product->name }}
                            </td>
                            <td class="pl-0">
                                <div class="d-flex align-items-center">
                                    {{ $buy = $product->stocks()->orderBy('buy_price', 'DESC')->first()->buy_price }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{ $product->sell_price }}
                                </div>
                            </td>
                            <td class="px-2 {{ $buy < $product->sell_price ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->sell_price - $buy }}
                            </td>
                            <td class="px-2 {{$product->stock_count > 10 ? "bg-success": ($product->stock_count > 2 ?
                                                "bg-warning" : "bg-danger") }}">
                                {{ $product->stock_count }}
                            </td>
                            <td>{{ $product->value }}</td>
                            <td>
                                <button class="btn btn-secondary py-0 more" data-bs-toggle="modal"
                                        data-bs-target="{{ '#reduce' . $product->id }}" type="button">Reduce Stock</button>
                                <div class="modal fade" id="{{ 'reduce' . $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <reduce-stock :product="{{ $product }}"></reduce-stock>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
@endsection
