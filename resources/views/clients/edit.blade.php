@extends('layouts.main_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.client_form_manage_clients') }} | {{ trans('translation.client_action_add') }}
@stop
@section('css')
    @include('layouts.includes.form_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.client_form_manage_clients'),
        'subtitle' => trans('translation.client_action_add'),
        'route' => route('clients.index'),
        'text' => trans('translation.client_form_clients_list'),
        'permission' => 'client-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection
@section('content')
        <form action="{{route('clients.update',$object->id)}}" method="post" id="userForm">
            @csrf
             @method('PUT')
             <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header  bg-primary text-white between-center">
                            <h6 class="card-title mb-0 text-white">Informations du client</h6>
                            <h6 class="card-title mb-0 text-white">Total des garanties :{{getTotalGaranties(collect($object->garanties))}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @include('form.input', [
                                    'cols' => 'col-md-6 ',
                                    'column' => 'code_client',
                                    'model' => 'client',
                                    'optional' => 'text-danger',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'code_client',
                                    'column_value' => $object->code_client,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'plafond',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'number',
                                    'class_name' => '',
                                    'column_id' => 'plafond',
                                    'column_value' => $object->plafond,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'name_fr',
                                    'model' => 'client',
                                    'optional' => 'text-danger',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'name_fr',
                                    'column_value' => $object->name_fr,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6 rtl',
                                    'column' => 'name_ar',
                                    'model' => 'client',
                                    'optional' => 'text-danger',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'name_ar',
                                    'column_value' => $object->name_ar,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'ice',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'ice',
                                    'column_value' => $object->ice,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'phone',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'number',
                                    'class_name' => '',
                                    'column_id' => 'phone',
                                    'column_value' => $object->phone,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'fax',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'number',
                                    'class_name' => '',
                                    'column_id' => 'fax',
                                    'column_value' => $object->fax,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'email',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'email',
                                    'class_name' => '',
                                    'column_id' => 'email',
                                    'column_value' => $object->email,
                                    'readonly' => 'false',
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'region_id',
                                    'isReload' => false,
                                    'label' => 'client_form_region_id',
                                    'optional' => 'text-secondary',
                                    'divID' => 'region_id',
                                    'options' => $regions,
                                    'object' => $object,
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'ville_id',
                                    'isReload' => false,
                                    'label' => 'client_form_ville_id',
                                    'optional' => 'text-secondary',
                                    'divID' => 'ville_id',
                                    'options' => $villes,
                                    'object' => $object,
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'secteur_id',
                                    'isReload' => false,
                                    'label' => 'client_form_secteur_id',
                                    'optional' => 'text-secondary',
                                    'divID' => 'secteur_id',
                                    'options' => $secteurs,
                                    'object' => $object,
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-3',
                                    'column' => 'cd_postale',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'number',
                                    'class_name' => 'onlyNumber',
                                    'column_id' => 'cd_postale',
                                    'column_value' => $object->cd_postale,
                                    'readonly' => 'false',
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'type_client',
                                    'isReload' => false,
                                    'label' => 'client_form_type_client',
                                    'optional' => 'text-danger',
                                    'divID' => 'type_client',
                                    'options' => $client_types,
                                    'object' => $object,
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'fonction',
                                    'isReload' => false,
                                    'label' => 'client_form_fonction',
                                    'optional' => 'text-secondary',
                                    'divID' => 'fonction',
                                    'options' => $fonctions,
                                    'object' => $object,
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'parent_type',
                                    'isReload' => false,
                                    'label' => 'client_form_parent_type',
                                    'optional' => 'text-secondary',
                                    'divID' => 'parent_type',
                                    'options' => $parent_types,
                                    'object' => $object,
                                ])
                                @include('form.singleSelect', [
                                    'cols' => 'col-md-3 ',
                                    'column' => 'parent_id',
                                    'isReload' => false,
                                    'label' => 'client_form_parent_id',
                                    'optional' => 'text-secondary',
                                    'divID' => 'parent_id',
                                    'options' => [],
                                    'object' => $object,
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'address',
                                    'model' => 'client',
                                    'optional' => 'text-danger',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'address',
                                    'column_value' => $object->address,
                                    'readonly' => 'false',
                                ])
                                @include('form.input', [
                                    'cols' => 'col-md-6',
                                    'column' => 'obs',
                                    'model' => 'client',
                                    'optional' => 'text-secondary',
                                    'input_type' => 'text',
                                    'class_name' => '',
                                    'column_id' => 'obs',
                                    'column_value' => $object->obs,
                                    'readonly' => 'false',
                                ])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="card-title mb-0 text-white">Details des garanties</h6>
                        </div>
                        <div class="card-body">
                            @include('clients.create_table')


                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">{{ trans('translation.general_general_save') }}</button>
                    </div>
                </div>
        </form>

        </div>
@endsection

@section('js')
    @include('layouts.includes.form_js')
    <script src="{{ asset('assets/custom_js/validate_number.js') }}"></script>
    <script src="{{ asset('assets/custom_js/region_ville.js') }}"></script>
    <script src="{{ asset('assets/custom_js/ville_secteur.js') }}"></script>
    <script src="{{ asset('assets/custom_js/clients/saveClient.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\StoreClientRequest') !!}

@endsection
