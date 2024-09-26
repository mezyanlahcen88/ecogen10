<?php

namespace App\Http\Controllers;

use \Mpdf\Mpdf as PDF;
use App\Dto\PurchaseDto;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Forms\PurchaseForm;
use Illuminate\Support\Str;
use App\Enums\StaticOptions;
use Illuminate\Http\Request;
use App\Services\CrudService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePurchaseRequest;



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
    // public function store(StorePurchaseRequest $request)
    // {
    //     $validated = $request->validated();
    //     $this->crudService->storeRecord(new Purchase(),$request->except('_token','proengsoft_jsvalidation'));

    //     return redirect()->route('purchases.index');
    //     }
    public function store(Request $request)
    {
                $data = $request->all();
                $validator = Validator::make($request->all(), [
                     'supplier' => ['bail', 'required'],
                     'status' => ['bail', 'required', 'min:3'],
                     'status_date' => ['bail', 'required', 'date'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()]);
                }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $purchase = new Purchase();
            $purchase->id = Str::uuid();
            $purchase->ref = $data['num_purchase'];
            $purchase->HT = null;
            $purchase->TVA = null;
            $purchase->TTTC = null;
            $purchase->status = $data['status'];
            $purchase->status_date = $data['status_date'];
            $purchase->supplier_id = $data['supplier'];
            $purchase->created_by = Auth::id();
            $purchase->comment = $data['comment'];
            $purchase->save();

            foreach ($data['products'] as $item) {
                DB::table('product_purchase')->insert([
                    'id' => Str::uuid(),
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'designation' => $item['designation'],
                    'quantity' => $item['quantite'],
                    'unite' => $item['unite'],
                ]);
            }
            incPurchaseNumerotation();
            DB::commit();
            return response()->json([
                'success' => true,
                'id' => $purchase->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $object = Purchase::with('products')->findOrfail($id);
        return view('purchases.show', compact('object'));
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


    public function generatePdf($id)
    {
        $object = Purchase::with('products')->findOrfail($id);
        $data = ['command'=>$object];
        // $pdf = PDF::loadView('command.command_pdf', $object);
        // $filename = $object['command_code'] . '_' . now()->format('YmdHis') . '.pdf';
        // return $pdf->download($filename);
               // Setup a filename
               $documentFileName = $object['command_code'] . '_' . now()->format('YmdHis') . '.pdf';

               // Create the mPDF document
               $document = new PDF( [
                   'mode' => 'utf-8',
                   'format' => 'A4',
                   'margin_header' => '3',
                   'margin_top' => '20',
                   'margin_bottom' => '20',
                   'margin_footer' => '2',
               ]);

               // Set some header informations for output
               $header = [
                   'Content-Type' => 'application/pdf',
                   'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
               ];

               // Write some simple Content
               $document->WriteHTML(view('command.command_pdf_old', $data));
               // $document->WriteHTML('<p>Write something, just for fun!</p>');

               // Save PDF on your public storage
               Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));

               // Get file back from storage with the give header informations
               return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }

    public function viewPurchaseInvoice($id)
    {
        $command = Purchase::with('products')
            ->with('client')
            ->findOrfail($id);
        // return $command;
        return view('commands.show_command_pdf', compact('command'));
    }

    public function printPurchaseInvoice($id)
{
    $command = Purchase::with('products')
    ->with('client')
    ->findOrfail($id);
    $data = ['command'=>$command];


    $documentFileName =  $command['command_code'] . '_' . now()->format('YmdHis') . '.pdf';

    $document = new PDF( [
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_header' => '3',
        'margin_top' => '20',
        'margin_bottom' => '20',
        'margin_footer' => '2',
    ]);

    $header = [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
    ];

    // Write some simple Content
    $document->WriteHTML(view('commands.command_pdf', $data));

    // Save PDF on your public storage
    Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));

    // Get file back from storage with the give header informations
    return Storage::disk('public')->download($documentFileName, 'Request', $header);
}
}
