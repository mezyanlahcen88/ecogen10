<div class="col-12 mt-3">
    <div class="card">
        <div class="card-header bg-primary">
            <h6 class="card-title mb-0 text-white">Détails de la livraison</h6>
        </div>
        <div class="card-body">
            <table id="scroll-horizontal" class="table nowrap align-middle table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>{{ trans('translation.delivery_table_client_id') }}</th>
                        <th class="d-none">id command details</th>
                        <th class="d-none">Produit</th>
                        <th class="d-none">Client</th>
                        <th>{{ trans('translation.delivery_table_product_id') }}</th>
                        <th>{{ trans('translation.commands_table_quantity') }}</th>
                        <th>{{ trans('translation.delivery_table_qty_livred') }}</th>
                        <th>{{ trans('translation.delivery_table_qty_remine') }}</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all" id="productTableBody">
                    @foreach ($object->products as $product)
                        <tr class="text-center {{$product->pivot->qty_reste === 0 ? 'bg-success text-white' : ''}}">
                            <td>{{ $object->client->name_fr }}</td>
                            <td class="d-none">{{ $product->pivot->id }}</td>
                            <td class="d-none productId">{{ $product->pivot->product_id }}</td>
                            <td class="d-none clientId">{{ $object->client_id }}</td>
                            <td>{{ $product->pivot->designation }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td class="qty_livred">{{ $product->pivot->qty_livred }}</td>
                            @if ($product->pivot->qty_reste === 0)
                              <td class="delivery-quantity text-white">Complete</td>
                            @else

                            @endif
                            <td class="qty_reste">{{ $product->pivot->qty_reste }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
