@extends('layouts.new_base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.reception_form_manage_receptions') }} |
    {{ trans('translation.reception_form_receptions_list') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb_with_trashed', [
        'title' => trans('translation.reception_form_manage_receptions'),
        'subtitle' => trans('translation.reception_form_receptions_list'),
        'route' => route('receptions.create'),
        'text' => trans('translation.reception_action_add'),
        'permission' => 'reception-create',
        'icon' => 'las la-plus',
        'icon'=>'las la-plus',
    'routeTrashed' => route('receptions.trashed'),
    'textTrashed' => trans('translation.reception_form_deleted_receptions_list'),
    'permission'=>'reception-trashed',
    'iconTrashed'=>'las la-trash-alt',

    ])
@endsection


@section('card-body')
    @include('receptions.table',[
        'model'=>'reception',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection

