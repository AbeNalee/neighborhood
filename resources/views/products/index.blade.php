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
                        <th scope="col">Highest Buy Price(Ksh)</th>
                        <th scope="col">Selling Price(Ksh)</th>
                        <th scope="col">Profit(Ksh)</th>
                        <th scope="col">Stock Count</th>
                        <th scope="col">Value(Ksh)</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sumValue = 0;
                        $spent = 0;
                        $profit = 0;
                    ?>
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
                                {{ $prof = $product->sell_price - $buy }}
                            </td>
                            <td class="px-2 {{$product->stock_count > 10 ? "bg-success": ($product->stock_count > 2 ?
                                                "bg-warning" : "bg-danger") }}">
                                {{ $product->stock_count }}
                            </td>
                            <td>{{ $product->value }}</td>
                                <?php $sumValue += $product->value; $spent += ($buy * $product->stock_count); $profit += ($prof * $product->stock_count);?>
                            <td>
                                <button class="btn btn-secondary mx-1 py-0 more" data-bs-toggle="modal"
                                        data-bs-target="{{ '#reduce' . $product->id }}" type="button">Reduce</button>
                                <button class="btn btn-secondary mx-1 py-0 more" data-bs-toggle="modal"
                                        data-bs-target="{{ '#add' . $product->id }}" type="button">
                                    Add
                                </button>
                                <div class="modal fade" id="{{ 'reduce' . $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <reduce-stock purpose="reduce" :product="{{ $product }}"></reduce-stock>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="{{ 'add' . $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <reduce-stock purpose="add" :product="{{ $product }}"></reduce-stock>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>
                            <h1>
                                TOTALS
                            </h1>
                        </td>
                        <td>
                            Spent: {{ $spent }}
                        </td>
                        <td></td>
                        <td>Expected Margin: {{ $profit }}</td>
                        <td></td>
                        <td>{{ $sumValue }}</td>
                        <td></td>
                    </tr>
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
