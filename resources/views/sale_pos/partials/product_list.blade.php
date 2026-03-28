@forelse($products as $product)

    <div class="modal fade" id="opening_stock_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"> </div>

    <div class="col-md-3 col-xs-4 product_list no-print">

        @if($product->qty_available > 0)
{{--            <button type="button"--}}
{{--                    class="edit-btn"--}}
{{--                    data-href="{{ action([\App\Http\Controllers\OpeningStockController::class, 'add'], ['product_id' => $product->variation_id]) }}"--}}
{{--                    style="position:absolute; top:5px; right:5px; z-index:10; background:#fff; border:none; padding:4px; border-radius:4px; cursor:pointer;"--}}
{{--                    onclick="event.stopPropagation();">--}}
{{--                ✏️--}}
{{--            </button>--}}
            <div class="product_box hover:tw-shadow-lg hover:tw-animate-pulse"
                 data-variation_id="{{$product->variation_id}}"
                 title="{{$product->name}} @if($product->type == 'variable')- {{$product->variation}} @endif {{ '(' . $product->sub_sku . ')'}} @if(!empty($show_prices)) @lang('lang_v1.default') - @format_currency($product->selling_price) @foreach($product->group_prices as $group_price) @if(array_key_exists($group_price->price_group_id, $allowed_group_prices)) {{$allowed_group_prices[$group_price->price_group_id]}} - @format_currency($group_price->price_inc_tax) @endif @endforeach @endif">


                @else
                    <div class="product_box hover:tw-shadow-lg hover:tw-animate-pulse"
                         data-variation_id="{{$product->product_id}}"
                         title="{{$product->name}} @if($product->type == 'variable')- {{$product->variation}} @endif {{ '(' . $product->sub_sku . ')'}} @if(!empty($show_prices)) @lang('lang_v1.default') - @format_currency($product->selling_price) @foreach($product->group_prices as $group_price) @if(array_key_exists($group_price->price_group_id, $allowed_group_prices)) {{$allowed_group_prices[$group_price->price_group_id]}} - @format_currency($group_price->price_inc_tax) @endif @endforeach @endif"
                         style="background: #e9cbe9">

                        @endif



                        <div class="text_div">
                            <small class="text text-muted" style="color: darkblue;font-size: 14px">{{$product->name}}
                                @if($product->type == 'variable')
                                    - {{$product->variation}}
                                @endif
                            </small>

                            <small class="text-muted" style="color: orangered;font-weight: bold;font-size: 16px">
                                ({{$product->sub_sku}})
                            </small><br>
                            <small class="text-muted" style="font-size: 100%;color:darkred;font-weight: bold">
                                @if($product->enable_stock)
                                    {{ @num_format($product->qty_available) }} {{$product->unit}} @lang('lang_v1.in_stock')
                                @else
                                    --
                                @endif
                            </small>
{{--                            <a href="#" data-href= {{action([\App\Http\Controllers\OpeningStockController::class, 'add'], ['product_id' => $product->product_id])}} class="add-opening-stock">Edit stock</a>--}}

                        </div>

                    </div>

            </div>
            @empty
                <input type="hidden" id="no_products_found">
                <div class="col-md-12">
                    <h4 class="text-center">
                        @lang('lang_v1.no_products_to_display')
                    </h4>

                </div>
@endforelse

