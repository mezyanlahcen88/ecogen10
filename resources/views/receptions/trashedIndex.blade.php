@extends('layouts.base_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.reception_form_manage_receptions') }} | {{ trans('translation.reception_action_edit') }}
@stop
@section('css')
    @include('layouts.includes.datatable_css')

@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.reception_form_manage_deleted_receptions'),
        'subtitle' => trans('translation.reception_form_deleted_receptions_list'),
        'route' => route('receptions.index'),
        'text' => trans('translation.reception_form_receptions_list'),
        'permission' => 'reception-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection

@section('card_body')
    @include('receptions.trashedTable',[
        'model'=>'reception',
    ])
@endsection

@section('js')
    @include('layouts.includes.datatable_js')
@endsection
