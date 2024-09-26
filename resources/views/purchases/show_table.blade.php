<table id="scroll-horizontal" class="table nowrap align-middle table-hover table-bordered" style="width:100%">
    <thead class="table-light">
        <tr class="text-center">
            <th>REF</th>
            <th>Désignation & المنتوج</th>
            <th>{{ trans('translation.commands_table_unite') }}</th>
            <th>{{ trans('translation.commands_table_quantity') }}</th>

        </tr>
    </thead>
    <tbody class="list form-check-all" id="productTableBody">
@foreach ($object->products as $product)
<tr class="text-center">
    <td>{{$product->product_code}}</td>
    <td>{{$product->pivot->designation}}</td>
    <td>{{$product->unite}}</td>
  <td>{{$product->pivot->quantity}}</td>
  
</tr>
@endforeach
    </tbody>
</table>

