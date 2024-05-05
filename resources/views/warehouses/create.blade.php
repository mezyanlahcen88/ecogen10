@extends('layouts.main_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.warehouse_form_manage_warehouses') }} |
    {{ trans('translation.warehouse_action_add') }}
@stop
@section('css')
    @include('layouts.includes.form_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.warehouse_form_manage_warehouses'),
        'subtitle' => trans('translation.warehouse_action_add'),
        'route' => route('warehouses.index'),
        'text' => trans('translation.warehouse_form_warehouses_list'),
        'permission' => 'warehouse-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection
@section('content')
    <form action="{{ route('warehouses.store') }}" method="post" id="userForm" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-12">
                <div class="card card-body">

                    <div class="row">
                        @include('form.input', [
                            'cols' => 'col-md-6',
                            'column' => 'name',
                            'model' => 'warehouse',
                            'optional' => 'text-danger',
                            'input_type' => 'text',
                            'class_name' => '',
                            'column_id' => 'name',
                            'column_value' => old('name'),
                            'readonly' => 'false',
                        ])
                        <div class="col-md-6 col-xl-6 col-xs-6 col-sm-6 my-1">
                            <div class="form-group" autocomplete="false">
                                <label for="type"> {{ trans('translation.warehouse_form_type') }} &nbsp; <span
                                    class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="type">
                                    <option value="" selected>{{ trans('translation.general_general_select') }} </option>
                                    @foreach (warehouseType() as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ $key == 'Principal' ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')
                                <p class="text-danger"><small>{{ $message }}</small></p>
                            @enderror
                            <span id="type-error" class="help-block error-help-block"></span>
                        </div>
                        <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="content">{{ trans('translation.numerotation_form_comments') }} &nbsp;
                                    <span class="text-secondary">*</span></label>
                                <textarea class="form-control ckeditor" name="address" id="comments" style="height: 213px">{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

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
    <script src="{{ asset('assets/custom_js/ckeditor.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\StoreWarehouseRequest') !!}
@endsection
