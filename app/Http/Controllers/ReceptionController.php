<?php

namespace App\Http\Controllers;

use App\Dto\ReceptionDto;
use App\Models\Reception;
use App\Forms\ReceptionForm;
use App\Enums\StaticOptions;
use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Http\Requests\StoreReceptionRequest;
use RealRashid\SweetAlert\Facades\Alert;


class ReceptionController extends Controller
{

    public $staticOptions;
    public $crudService;
    public function __construct(CrudService $crudService, StaticOptions $staticOptions)
    {
        $this->middleware('permission:reception-list|reception-create|reception-edit|reception-show|reception-delete', ['only' => ['index']]);
        $this->middleware('permission:reception-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:reception-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:reception-show', ['only' => ['show']]);
        $this->middleware('permission:reception-delete', ['only' => ['destroy']]);
        $this->middleware('permission:reception-restore', ['only' => ['restore']]);
        $this->middleware('permission:reception-trashed', ['only' => ['trashed']]);
        $this->middleware('permission:reception-forse-delete', ['only' => ['forseDelete']]);
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

           $tableRows =(new Reception())->getRowsTable();
        $objects = Reception::get();
        return view('receptions.index',compact('tableRows','objects'));
    }
    /**
     * Display a list of soft-deleted records.
     *
     * @return \Illuminate\View\View The view with the list of soft-deleted records.
     */
    public function trashed(Request $request)
    {
        $objects = Reception::onlyTrashed()->get();
        $tableRows =(new Reception())->getRowsTableTrashed();
        return view('receptions.trashedIndex', compact('tableRows','objects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $object = new Reception();
        $variable = '';
        return view('receptions.create',compact('variable'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceptionRequest $request)
    {
        $validated = $request->validated();
        $this->crudService->storeRecord(new Reception(),$request->except('_token','proengsoft_jsvalidation'));

        return redirect()->route('receptions.index');
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reception  $reception
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reception  $reception
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = reception::findOrfail($id);
        return view('receptions.edit',compact('object'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reception  $reception
     * @return \Illuminate\Http\Response
     */
    public function update(StoreReceptionRequest $request,string $id)
    {
        $validated = $request->validated();
        $this->crudService->updateRecord(new Reception(),$validated,$id);
        return redirect()->route('receptions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reception  $reception
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $object = Reception::findOrFail($request->id)->delete();

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

        $object = Reception::withTrashed()->findOrFail($id)->restore();
        // storeSidebar();
        return redirect()->route('receptions.index');
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

        $object = Reception::withTrashed()->findOrFail($id);
        // deletePicture($object,'receptions','picture');
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
        $object = Reception::findOrFail($id);
        $object->isactive = !$object->isactive;
        $object->save();
        $message = $object->isactive ? trans('translation.reception_message_activated') : trans('translation.reception_message_inactivated');
        return response()->json(['code' => 200, 'active' => $object->isactive, 'message' => $message]);
    }
}
