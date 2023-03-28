@extends('layouts.app')

@section('content')
    <div class="content">
        <?php
        $hasRole = \Illuminate\Support\Facades\Auth::user()->hasRole('admin');
        $total = 0;
        $profit = 0;
        ?>
        <div class="container">
            <h2 class="mb-5">
                <span class="">Sales</span>
                <button class="btn btn-secondary float-end py-0" data-bs-toggle="modal"
                        data-bs-target="#makeSaleModal" type="button">Make Sale</button>
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
                        <th scope="col">Product</th>
                        @if($hasRole)<th scope="col">Profit Margin</th>@endif
                        <th scope="col">Sell Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Amount</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($sales as $sale)
                        @if($sale->cart !== null)
                            @foreach($sale->cart->cartItems as $item)
                                <tr scope="row">
                                    <td>
                                        <label class="control control--checkbox">
                                            <input type="checkbox" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </td>
                                    <td>
                                        {{ $item->product->name }}
                                    </td>
                                    @if($hasRole)
                                        <td>
                                            {{ $margin = $item->product->profit_margin }}
                                        </td>
                                    @endif
                                    <td>
                                        {{ $item->product->sell_price }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $quantity = abs($item->quantity) }}
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        {{ $soldAmount = $item->product->sell_price * $quantity }}
                                        <?php $total += $soldAmount; $profit += ($item->product->profit_margin * $quantity);?>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    @if($hasRole)
                        <tr>
                            <td></td>
                            <td>
                                <h1>
                                    TOTALS
                                </h1>
                            </td>
                            <td></td>
                            <td></td>
                            <td>Profit: {{ $profit }}</td>
                            <td>{{ $total }}</td>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="modal fade" id="makeSaleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <make-sale purpose="purchase"></make-sale>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

