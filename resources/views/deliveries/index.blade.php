@extends('layouts.new_base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.delivery_form_manage_deliveries') }} |
    {{ trans('translation.delivery_form_deliveries_list') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb_with_trashed', [
        'title' => trans('translation.delivery_form_manage_deliveries'),
        'subtitle' => trans('translation.delivery_form_deliveries_list'),
        'route' => route('deliveries.create'),
        'text' => trans('translation.delivery_action_add'),
        'permission' => 'delivery-create',
        'icon' => 'las la-plus',
        'icon'=>'las la-plus',
    'routeTrashed' => route('deliveries.trashed'),
    'textTrashed' => trans('translation.delivery_form_deleted_deliveries_list'),
    'permission'=>'delivery-trashed',
    'iconTrashed'=>'las la-trash-alt',

    ])
@endsection


@section('card-body')
    @include('deliveries.table',[
        'model'=>'delivery',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection

