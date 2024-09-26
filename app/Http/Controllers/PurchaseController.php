<?php

namespace App\Http\Controllers;

use App\Dto\PurchaseDto;
use App\Models\Purchase;
use App\Forms\PurchaseForm;
use App\Enums\StaticOptions;
use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Supplier;
use RealRashid\SweetAlert\Facades\Alert;


class PurchaseController extends Controller
{

    public $staticOptions;
    public $crudService;
    public function __construct(CrudService $crudService, StaticOptions $staticOptions)
    {
        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-show|purchase-delete', ['only' => ['index']]);
        $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-show', ['only' => ['show']]);
        $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
        $this->middleware('permission:purchase-restore', ['only' => ['restore']]);
        $this->middleware('permission:purchase-trashed', ['only' => ['trashed']]);
        $this->middleware('permission:purchase-forse-delete', ['only' => ['forseDelete']]);
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

        $tableRows =(new Purchase())->getRowsTable();
        $objects = Purchase::get();
        $suppliers = Supplier::get();
        return view('purchases.index',compact('tableRows','objects','suppliers'));
    }
    /**
     * Display a list of soft-deleted records.
     *
     * @return \Illuminate\View\View The view with the list of soft-deleted records.
     */
    public function trashed(Request $request)
    {
        $objects = Purchase::onlyTrashed()->get();
        $tableRows =(new Purchase())->getRowsTableTrashed();
        return view('purchases.trashedIndex', compact('tableRows','objects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $object = new Purchase();
        $variable = '';
        return view('purchases.create',compact('variable'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        $validated = $request->validated();
        $this->crudService->storeRecord(new Purchase(),$request->except('_token','proengsoft_jsvalidation'));

        return redirect()->route('purchases.index');
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = purchase::findOrfail($id);
        return view('purchases.edit',compact('object'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(StorePurchaseRequest $request,string $id)
    {
        $validated = $request->validated();
        $this->crudService->updateRecord(new Purchase(),$validated,$id);
        return redirect()->route('purchases.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $object = Purchase::findOrFail($request->id)->delete();

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

        $object = Purchase::withTrashed()->findOrFail($id)->restore();
        // storeSidebar();
        return redirect()->route('purchases.index');
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

        $object = Purchase::withTrashed()->findOrFail($id);
        // deletePicture($object,'purchases','picture');
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
        $object = Purchase::findOrFail($id);
        $object->isactive = !$object->isactive;
        $object->save();
        $message = $object->isactive ? trans('translation.purchase_message_activated') : trans('translation.purchase_message_inactivated');
        return response()->json(['code' => 200, 'active' => $object->isactive, 'message' => $message]);
    }
}
