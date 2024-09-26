@extends('layouts.main_layout')
@section('title')
    {{ env('APP_NAME') }} | {{ trans('translation.commands_form_manage_commands') }} |
    {{ trans('translation.commands_action_edit') }}
@stop
@section('css')
    @include('layouts.includes.form_css')
@endsection
@section('page-header')
    @include('components.new_breadcrumb', [
        'title' => trans('translation.commands_form_manage_commands'),
        'subtitle' => trans('translation.commands_action_edit'),
        'route' => route('commands.index'),
        'text' => trans('translation.commands_form_commands_list'),
        'permission' => 'commands-list',
        'icon' => 'lab la-stack-exchange',
    ])
@endsection
@section('content')
    <form action="{{ route('commands.update', 'commandeid') }}" method="post" id="commands_form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center gy-3">
                            <div class="col-sm">
                                <h5 class="card-title mb-0">Encaisement ticket</h5>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    class="bg-info text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                    <label for="" id="commands">Reste à payer</label>
                                    <label for="" id="total_restanter" class="fs-3">{!! $object->total_restant !!}</label>
                                </div>
                                <div
                                    class="bg-primary text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                    <label for="">Total TTC :</label>
                                    <label for="" id="total_ttc" class="fs-3">{{ $object->TTTC }}</label>
                                </div>

                                <div
                                    class="bg-warning text-light h-25 w-100 d-flex  justify-content-between align-items-center px-4 mb-1">
                                    <label for="">Dont TVA :</label>
                                    <label for="" id="total_ttva" class="fs-3">{{ $object->TVA }}</label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="bg-soft-success text-white w-100 d-flex  justify-content-center align-items-center "
                                    style="height: 184px">
                                    <input type="number"
                                        class="form-control w-50  bg-soft-success text-white border-0 fs-1 text-center"
                                        name="" id="montantPayer" aria-describedby="helpId" value="0"
                                         @if ($object->total_restant <= 0) readonly @endif />


                                </div>
                            </div>

                        </div>
                        <div class="row mt-3">
                            @include('form.singleSelect', [
                                'cols' => 'col-md-5 ',
                                'column' => 'reglement',
                                'isReload' => false,
                                'label' => 'commands_form_reglement',
                                'optional' => 'text-danger',
                                'divID' => 'reglement',
                                'options' => $reglements,
                                'object' => false,
                            ])
                            @include('form.input', [
                                'cols' => 'col-md-5',
                                'column' => 'check_ref',
                                'model' => 'commands',
                                'optional' => 'text-secondary',
                                'input_type' => 'number',
                                'class_name' => '',
                                'column_id' => 'check_ref',
                                'column_value' => old('check_ref'),
                                'readonly' => 'false',
                            ])
                            <div class="col-md-2 mt-4">
                                <a href="#" id="btnValider" class="btn btn-primary text-light  w-100"><i
                                        class="las la-check"></i></a>
                            </div>
                            <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="content">{{ trans('translation.commands_form_comment') }} &nbsp;
                                        <span class="text-secondary">*</span></label>
                                    <textarea class="form-control" name="comment" id="commentReg" rows="5">{{ old('comment') }}</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="index" id="index">
                        </div>
                        <div class="row mt-3">
                            <table id="scroll-horizontal" class="table nowrap align-middle table-hover table-bordered"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Mode de reglement</th>
                                        <th>Montant</th>
                                        <th>Echéance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all" id=RegTableBody>
                                    {{-- <tr class="text-center">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="text-start">
                    <button type="submit"
                        class="btn btn-primary storeCommand">{{ trans('translation.commands_action_edit') }}</button>
                </div>
            </div>
        </div>
    </form>

@endsection
@section('js')
    @include('layouts.includes.form_js')
    <script src="{{ asset('assets/custom_js/manage-reglements.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\StoreCommandRequest') !!}
@endsection
