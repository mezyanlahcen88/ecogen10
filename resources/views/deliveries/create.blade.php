@extends('layouts.main_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.delivery_form_manage_deliveries') }} |
    {{ trans('translation.delivery_action_add') }}
@stop
@section('css')
    @include('layouts.includes.form_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.delivery_form_manage_deliveries'),
        'subtitle' => trans('translation.delivery_action_add'),
        'route' => route('deliveries.index'),
        'text' => trans('translation.delivery_form_deliveries_list'),
        'permission' => 'delivery-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection
@section('content')
<form action="{{ route('deliveries.store') }}" method="post" id="deliveries_form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">Bon de livraison pour le client <span
                                    class="fs-2">{{ $object->client->name_fr }} | {{ $object->client->name_ar }}</span>
                            </h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('commands.show', $object->id) }}" class="btn btn-primary"
                                    target="_blank">Voir command</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <input type="hidden" name="client_id" value="{{$object->client_id}}">
                                <input type="hidden" name="command_id" value="{{$object->id}}">
                                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                                    @include('form.singleSelect', [
                                        'cols' => 'col-md-12 ',
                                        'column' => 'delivery_method',
                                        'isReload' => false,
                                        'label' => 'delivery_form_delivery_method',
                                        'optional' => 'text-danger',
                                        'divID' => 'delivery_method',
                                        'options' => cars(),
                                        'object' => false,
                                    ])
                                    @include('form.singleSelect', [
                                        'cols' => 'col-md-12 ',
                                        'column' => 'deliverer',
                                        'isReload' => false,
                                        'label' => 'delivery_form_deliverer',
                                        'optional' => 'text-danger',
                                        'divID' => 'deliverer',
                                        'options' => drivers(),
                                        'object' => false,
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <label class="form-label my-1">Date de livraison</label>
                                        <input type="text" class="form-control" data-provider="flatpickr"
                                            data-enable-time data-date-format="Y-m-d"
                                            placeholder="{{ Carbon\Carbon::now()->format('d-m-Y H:i') }}"
                                            name="delivery_date">
                                    </div>
                                    <span id="delivery_date-error" class="help-block error-help-block"></span>

                                </div>
                                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content">{{ trans('translation.commands_form_comment') }} &nbsp;
                                            <span class="text-secondary">*</span></label>
                                        <textarea class="form-control" name="comment" id="comment" rows="5">{{ old('comment') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div
                                class="bg-info text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                <label for="" id="commands">Commande N° :</label>
                                <label for="" id="num_commands">{{ $object->command_code }}</label>
                            </div>
                            <div
                                class="bg-primary text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                <label for="">Total TTC :</label>
                                <label for="" id="total_ttc">{{ $object->TTTC }}</label>
                            </div>
                            <div
                                class="bg-success text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                <label for="">Total HT :</label>
                                <label for="" id="total_ht">{{ $object->HT }}</label>
                            </div>
                            <div
                                class="bg-warning text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                <label for="">Total TVA :</label>
                                <label for="" id="total_ttva">{{ $object->TVA }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="card-title mb-0 text-white">Détails de la livraison</h6>
                </div>
                <div class="card-body">
                    @include('deliveries.create_table')
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="text-start">
            <button type="submit" class="btn btn-primary storeDelivery">{{ trans('translation.general_general_save') }}</button>
        </div>
    </div>
</form>
@endsection
@section('js')
<script src="{{ asset('assets/custom_js/delivery.js') }}"></script>

    @include('layouts.includes.form_js')
@endsection
