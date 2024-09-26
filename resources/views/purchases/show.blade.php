@extends('layouts.main_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.purchase_form_manage_purchases') }} |
    {{ trans('translation.purchase_action_show') }}
@stop
@section('css')
    @include('layouts.includes.form_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.purchase_form_manage_purchases'),
        'subtitle' => trans('translation.purchase_action_show'),
        'route' => route('purchases.index'),
        'text' => trans('translation.purchase_form_purchases_list'),
        'permission' => 'purchases-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">{{ trans('translation.purchase_action_show') }}</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{route('purchases.ViewpurchaseInvoice',$object->id)}}" class="btn btn-primary" target="_blank">Voir  purchase</a>

                                <a href="#" id="purchase_edit" class="getpurchase" data-purchase-id="{{ $object->id }}" title="Edit"><span
                                    class="btn btn-info">{{ trans('translation.purchase_action_edit') }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 for="">Cette purchasee est <span class="text-warning">
                                            {{ $object->status }}</span></h3>
                                </div>
                                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content">Les informations du supplier &nbsp;
                                            <span class="text-secondary">*</span></label>
                                        <table>
                                            <tr>
                                                <th>Designation </th>
                                                <td> : {{ $object->supplier->name_fr }} | {{ $object->supplier->name_ar }}</td>
                                            </tr>
                                            <tr>
                                                <th>ICE </th>
                                                <td> : {{ $object->supplier->ice }}</td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL </th>
                                                <td> : {{ $object->supplier->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>TELEPHONE </th>
                                                <td> : {{ $object->supplier->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>FAX </th>
                                                <td> : {{ $object->supplier->fax }}</td>
                                            </tr>
                                            <tr>
                                                <th>ADRESSE </th>
                                                <td> : {{ $object->supplier->address }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <label class="form-label my-1">Date de crétation</label>
                                        <input type="text" class="form-control" value="{{ $object->status_date }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content">{{ trans('translation.purchase_form_comment') }} &nbsp;
                                            <span class="text-secondary">*</span></label>
                                        <textarea class="form-control" name="comment" id="comment" rows="5" readonly>{{ $object->comment }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div
                                class="bg-info text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                <label for="" id="purchases">purchasee N° :</label>
                                <label for="" id="num_purchases" >{{ $object->ref }}</label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="card-title mb-0 text-white">Détails de la purchasee</h6>
                </div>
                <div class="card-body">
                    @include('purchases.show_table')
                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
    @include('layouts.includes.form_js')
@endsection
