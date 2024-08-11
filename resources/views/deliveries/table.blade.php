<div class="card-body">
    <table id="scroll-horizontal" class="table nowrap align-middle table-hover table-bordered" style="width:100%">
        <thead class="table-light">
            <tr class="text-center">
                @foreach ($tableRows as $key => $value)
                    <th>{{ trans('translation.' . $model . '_table_' . $value) }} </th>
                @endforeach
                <th class="sort" data-sort="action">{{ trans('translation.' . $model . '_table_active') }}</th>
                <th class="sort" data-sort="action">{{ trans('translation.general_general_action') }}</th>
            </tr>
        </thead>
        <tbody class="list form-check-all">
            @foreach ($objects as $object)
                <tr class="text-center">
                 <td>{{$object->client->getdesignation()}}</td>
                 <td>{{$object->command->command_code}}</td>
                 <td>{{$object->driver->first_name}}</td>
                 <td>{{$object->car->marque}}</td>
                 <td>{{$object->delivery_date}}</td>

                    <td class="isactive ">
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input changeStatus" type="checkbox" role="switch" id="check"
                                data-id="{{ $object->id }}" {{ $object->active ? 'checked' : '' }}
                                data-route-name="{{ route('deliveries.changestatus') }}">
                        </div>
                    </td>
                    <td>
                        @include('deliveries.actions')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
