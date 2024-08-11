@extends('layouts.base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.delivery_form_manage_deliveries') }} | {{ trans('translation.delivery_action_edit') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')

@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.delivery_form_manage_deleted_deliveries'),
        'subtitle' => trans('translation.delivery_form_deleted_deliveries_list'),
        'route' => route('deliveries.index'),
        'text' => trans('translation.delivery_form_deliveries_list'),
        'permission' => 'delivery-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection

@section('card_body')
    @include('deliveries.trashedTable',[
        'model'=>'delivery',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
@endsection
