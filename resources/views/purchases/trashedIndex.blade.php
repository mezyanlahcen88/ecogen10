@extends('layouts.base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.purchase_form_manage_purchases') }} | {{ trans('translation.purchase_action_edit') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')

@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.purchase_form_manage_deleted_purchases'),
        'subtitle' => trans('translation.purchase_form_deleted_purchases_list'),
        'route' => route('purchases.index'),
        'text' => trans('translation.purchase_form_purchases_list'),
        'permission' => 'purchase-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection

@section('card_body')
    @include('purchases.trashedTable',[
        'model'=>'purchase',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
@endsection
