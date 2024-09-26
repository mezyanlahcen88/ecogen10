@extends('layouts.new_base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.purchase_form_manage_purchases') }} |
    {{ trans('translation.purchase_form_purchases_list') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb_with_trashed', [
        'title' => trans('translation.purchase_form_manage_purchases'),
        'subtitle' => trans('translation.purchase_form_purchases_list'),
        'route' => route('purchases.create'),
        'text' => trans('translation.purchase_action_add'),
        'permission' => 'purchase-create',
        'icon' => 'las la-plus',
        'icon'=>'las la-plus',
        'routeTrashed' => route('purchases.trashed'),
        'textTrashed' => trans('translation.purchase_form_deleted_purchases_list'),
        'permission'=>'purchase-trashed',
        'iconTrashed'=>'las la-trash-alt',
    ])
@endsection


@section('card-body')
    @include('purchases.table',[
        'model'=>'purchase',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection

