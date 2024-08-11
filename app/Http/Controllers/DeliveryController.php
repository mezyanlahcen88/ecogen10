<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Dto\DeliveryDto;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Forms\DeliveryForm;
use App\Enums\StaticOptions;
use Illuminate\Http\Request;
use App\Services\CrudService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreDeliveryRequest;
use App\Models\ProductCommand;

class DeliveryController extends Controller
{
    public $staticOptions;
    public $crudService;
    public function __construct(CrudService $crudService, StaticOptions $staticOptions)
    {
        $this->middleware('permission:delivery-list|delivery-create|delivery-edit|delivery-show|delivery-delete', ['only' => ['index']]);
        $this->middleware('permission:delivery-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:delivery-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delivery-show', ['only' => ['show']]);
        $this->middleware('permission:delivery-delete', ['only' => ['destroy']]);
        $this->middleware('permission:delivery-restore', ['only' => ['restore']]);
        $this->middleware('permission:delivery-trashed', ['only' => ['trashed']]);
        $this->middleware('permission:delivery-forse-delete', ['only' => ['forseDelete']]);
        $this->staticOptions = $staticOptions;
        $this->crudService = $crudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tableRows = (new Delivery())->getRowsTable();
        $objects = Delivery::with('client')->with('command')->with('car')->with('driver')->get();
        return view('deliveries.index', compact('tableRows', 'objects'));
    }
    /**
     * Display a list of soft-deleted records.
     *
     * @return \Illuminate\View\View The view with the list of soft-deleted records.
     */
    public function trashed(Request $request)
    {
        $objects = Delivery::onlyTrashed()->get();
        $tableRows = (new Delivery())->getRowsTableTrashed();
        return view('deliveries.trashedIndex', compact('tableRows', 'objects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($commandId)
    {
        $object = Command::findOrfail($commandId);
        return view('deliveries.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_method' => ['bail', 'required'],
            'deliverer' => ['bail', 'required', 'min:3'],
            'delivery_date' => ['bail', 'required', 'date'],
            'comment' => ['bail', 'nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        //    first update the values inside the command details
        foreach ($request['tableData'] as $detail) {
            $productCommand = ProductCommand::where('id', $detail['commandDetailId'])->first();
            $productCommand->qty_livred = $productCommand->qty_livred + $detail['delivery_quantity'];
            $productCommand->qty_reste = $productCommand->qty_reste - $detail['delivery_quantity'];
            $productCommand->save();
        }

        //    second  store the delivery

        $delivery = new Delivery();
        $delivery->command_id = $request['command_id'];
        $delivery->delivery_method = $request['delivery_method'];
        $delivery->deliverer = $request['deliverer'];
        $delivery->delivery_date = $request['delivery_date'];
        $delivery->comment = $request['comment'];
        $delivery->client_id = $request['client_id'];
        $delivery->save();

        //    third   store the delivery details
        foreach ($request['tableData'] as $detail) {
            $deliveryDetail = new DeliveryDetail();
            $deliveryDetail->qty_livred = $detail['qty_livred'];
            $deliveryDetail->product_id = $detail['product_id'];
            $deliveryDetail->command_id = $detail['command_id'];
            $deliveryDetail->client_id = $detail['client_id'];
            $deliveryDetail->delivery_id = $delivery->id;
            $deliveryDetail->comment = $request['comment'];
            $deliveryDetail->save();
        }
        return response()->json([
            'success' => true,
        ]);

        // return redirect()->route('deliveries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = delivery::findOrfail($id);
        return view('deliveries.edit', compact('object'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDeliveryRequest $request, string $id)
    {
        $validated = $request->validated();
        $this->crudService->updateRecord(new Delivery(), $validated, $id);
        return redirect()->route('deliveries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $object = Delivery::findOrFail($request->id)->delete();
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @param int $id The ID of the user to restore.
     * @return \Illuminate\Http\RedirectResponse The redirect response.
     */
    public function restore(Request $request, $id)
    {
        $object = Delivery::withTrashed()->findOrFail($id)->restore();
        // storeSidebar();
        return redirect()->route('deliveries.index');
    }

    /**
     * Force delete a record permanently.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @param int $id The ID of the record to force delete.
     * @return void
     */
    public function forceDelete(Request $request, $id)
    {
        $object = Delivery::withTrashed()->findOrFail($id);
        // deletePicture($object,'deliveries','picture');
        $object->forceDelete();
        // storeSidebar();
    }

    /**
     * Change the status (active/inactive) of a user.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $object = Delivery::findOrFail($id);
        $object->isactive = !$object->isactive;
        $object->save();
        $message = $object->isactive ? trans('translation.delivery_message_activated') : trans('translation.delivery_message_inactivated');
        return response()->json(['code' => 200, 'active' => $object->isactive, 'message' => $message]);
    }
}
