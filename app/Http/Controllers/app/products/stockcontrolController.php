<?php

namespace App\Http\Controllers\app\products;

use App\Http\Controllers\Controller;
use App\Mail\sendLpo;
use App\Models\crm\emails;
use App\Models\currency;
use App\Models\file_manager as docs;
use App\Models\lpo;
use App\Models\lpo\lpo_products;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\settings;
use App\Models\status;
use App\Models\suppliers\contact_persons;
use App\Models\suppliers\suppliers;
use App\Models\tax;
use Finance;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mail;
use PDF;
use Wingu;

class stockcontrolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * stock control
     *
     *
     */
    public function index()
    {
        //check if user is linked to a business and allow access
        $lpos = lpo::join('suppliers', 'suppliers.id', '=', 'lpo.supplierID')
            ->join('lpo_settings', 'lpo_settings.businessID', '=', 'lpo.businessID')
            ->join('business', 'business.id', '=', 'lpo.businessID')
            ->join('currency', 'currency.id', '=', 'business.base_currency')
            ->join('status', 'status.id', '=', 'lpo.statusID')
            ->where('type', 'Stock control')
            ->where('lpo.businessID', Auth::user()->businessID)
            ->orderby('lpo.id', 'desc')
            ->select('*', 'lpo.id as lpoID', 'status.name as statusName')
            ->get();
        return view('app.finance.products.stock.index', compact('lpos'));
    }

    /**
     * order stock
     */
    public function order()
    {

        $suppliers = suppliers::where('businessID', Auth::user()->businessID)->OrderBy('id', 'DESC')->get();
        $products = product_information::where('businessID', Auth::user()->businessID)->OrderBy('id', 'DESC')->get();
        $status = status::all();

        return view('app.finance.products.stock.order', compact('suppliers', 'products', 'status'));
    }

    /**
     * product price
     *
     */
    public function productPrice()
    {
        return json_encode(product_price::where('productID', Input::get('product'))->first());
    }

    /**
     * store order
     *
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'supplier' => 'required',
            'lpo_number' => 'required',
            'lpo_date' => 'required',
            'lpo_due' => 'required',
        ));

        //store invoice
        $store = new lpo;
        $store->supplierID = $request->supplier;
        $store->lpo_number = $request->lpo_number;
        $store->reference_number = $request->reference_number;
        $store->discount = $request->discount;
        $store->discount_type = $request->discount_type;
        $store->total = $store->total(
            $request->qty,
            $request->price,
            $request->discount,
            $request->discount_type,
            $request->tax
        );
        $store->sub_total = $store->amount($request->qty, $request->price);
        $store->tax = $request->tax;
        $store->title = $request->title;
        $store->lpo_date = $request->lpo_date;
        $store->lpo_due = $request->lpo_due;
        $store->customer_note = $request->customer_note;
        $store->terms = $request->terms;
        $store->statusID = 10;
        $store->businessID = Auth::user()->businessID;
        $store->created_by = Auth::user()->id;
        $store->save();

        //products
        $products = $request->productID;
        foreach ($products as $k => $v) {
            $product = new lpo_products;
            $product->lpoID = $store->id;
            $product->productID = $request->productID[$k];
            $product->quantity = $request->qty[$k];
            $product->price = $request->price[$k];
            $product->save();
        }

        //update lpo number
        $setting = settings::where('businessID', Auth::user()->businessID)->first();
        $setting->number = $setting->number + 1;
        $setting->save();

        Session::flash('success', 'order has been successfully created');

        return redirect()->route('finance.product.stock.control');

    }

    /**
     * show lpo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $count = 1;
        $filec = 1;
        $lpo = lpo::join('business', 'business.id', '=', 'lpo.businessID')
            ->join('currency', 'currency.id', '=', 'business.base_currency')
            ->join('lpo_settings', 'lpo_settings.businessID', '=', 'business.id')
            ->join('status', 'status.id', '=', 'lpo.statusID')
            ->where('lpo.id', $id)
            ->where('lpo.businessID', Auth::user()->businessID)
            ->select('*', 'lpo.id as lpoID', 'business.name as businessName')
            ->first();

        $products = lpo_products::where('lpoID', $lpo->lpoID)->get();

        $supplier = suppliers::join('supplier_address', 'supplier_address.supplierID', '=', 'suppliers.id')
            ->where('suppliers.businessID', Auth::user()->businessID)
            ->where('suppliers.id', $lpo->supplierID)
            ->select('*', 'suppliers.id as supplierID')
            ->first();

        $files = docs::where('fileID', $id)->where('folder', 'lpo')->where('businessID', Auth::user()->businessID)->get();

        $persons = contact_persons::where('supplierID', $supplier->supplierID)->get();

        // $template = Wingu::template(Wingu::business(Auth::user()->businessID)->templateID)->template_name;

        return view('app.finance.products.stock.show', compact('supplier', 'lpo', 'products', 'count', 'filec', 'files', 'persons', 'template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $lpo = lpo::where('id', $id)->where('businessID', Auth::user()->businessID)->first();
        $suppliers = suppliers::where('businessID', Auth::user()->businessID)->OrderBy('id', 'DESC')->get();
        $supplier = suppliers::where('id', $lpo->supplierID)->where('businessID', Auth::user()->businessID)->first();

        $taxs = tax::where('businessID', Auth::user()->businessID)->OrderBy('id', 'DESC')->get();
        $currency = currency::find($lpo->currency_id);
        $lpoproducts = lpo_products::where('lpoID', $id)->get();

        $products = product_information::where('businessID', Auth::user()->businessID)->OrderBy('id', 'DESC')->get();

        $count = 1;

        if ($lpo->tax == 0) {
            $taxed = 0;
        } else {
            $taxed = $lpo->sub_total * ($lpo->tax / 100);
        }

        return view('app.finance.products.stock.edit', compact('suppliers', 'supplier', 'taxed', 'count', 'lpo', 'products', 'lpoproducts', 'taxs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'supplier' => 'required',
            'lpo_number' => 'required',
            'lpo_date' => 'required',
            'lpo_due' => 'required',
        ));

        $update = lpo::find($id);
        $update->supplierID = $request->supplier;
        $update->reference_number = $request->reference_number;
        $update->discount = $request->discount;
        $update->discount_type = $request->discount_type;
        $update->total = $update->total(
            $request->qty,
            $request->price,
            $request->discount,
            $request->discount_type,
            $request->tax
        );
        $update->sub_total = $update->amount($request->qty, $request->price);
        $update->tax = $request->tax;
        $update->lpo_date = $request->lpo_date;
        $update->lpo_due = $request->lpo_due;
        $update->title = $request->title;
        $update->customer_note = $request->customer_note;
        $update->terms = $request->terms;
        $update->businessID = Auth::user()->businessID;
        $update->updated_by = Auth::user()->id;
        $update->save();

        //delete product
        $delete = lpo_products::where('lpoID', $id);
        $delete->delete();

        //new products
        $products = $request->productID;
        foreach ($products as $k => $v) {
            $product = new lpo_products;
            $product->lpoID = $update->id;
            $product->productID = $request->productID[$k];
            $product->quantity = $request->qty[$k];
            $product->price = $request->price[$k];
            $product->save();
        }

        Session::flash('success', 'Order has been successfully updated');

        return redirect()->back();
    }

    /**
     *     show mailing section
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mail($id)
    {
        $count = 1;
        $lpo = lpo::join('business', 'business.id', '=', 'lpo.businessID')
            ->join('currency', 'currency.id', '=', 'business.base_currency')
            ->join('lpo_settings', 'lpo_settings.businessID', '=', 'business.id')
            ->join('status', 'status.id', '=', 'lpo.statusID')
            ->where('lpo.id', $id)
            ->where('lpo.businessID', Auth::user()->businessID)
            ->select('*', 'lpo.id as lpoID', 'business.name as businessName', 'status.name as statusName')
            ->first();

        $supplier = suppliers::join('supplier_address', 'supplier_address.supplierID', '=', 'suppliers.id')
            ->where('suppliers.businessID', Auth::user()->businessID)
            ->where('suppliers.id', $lpo->supplierID)
            ->select('*', 'suppliers.id as supplierID')
            ->first();

        $files = docs::where('fileID', $id)->where('businessID', Auth::user()->businessID)->where('folder', 'lpo')->get();

        $contacts = contact_persons::where('supplierID', $lpo->vendorID)->where('businessID', Auth::user()->businessID)->get();

        $products = lpo_products::where('lpoID', $lpo->lpoID)->get();

        $directory = base_path() . '/storage/files/business/' . $lpo->primary_email . '/finance/lpo/';

        //create directory if it doesn't exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $pdf = PDF::loadView('templates/' . Wingu::template(Wingu::business(Auth::user()->businessID)->templateID)->template_name . '/lpo/lpo', compact('products', 'lpo', 'supplier', 'count'));

        $pdf->save($directory . $lpo->prefix . $lpo->lpo_number . '.pdf');

        return view('app.finance.products.stock.mail', compact('lpo', 'files', 'contacts', 'supplier'));
    }

    /**
     *     send lpo via email
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'email_from' => 'required|email',
            'send_to' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        //lpo information
        $lpo = lpo::where('id', $request->lpoID)->where('businessID', Auth::user()->businessID)->first();

        //client info
        $supplier = suppliers::where('suppliers.id', $lpo->supplierID)
            ->where('businessID', Auth::user()->businessID)
            ->first();

        $checkatt = count(collect($request->attach_files));
        if ($checkatt > 0) {
            //change file status to null
            $filechange = docs::where('folder', 'lpo')->where('fileID', $lpo->id)->where('businessID', Auth::user()->businessID)->get();
            foreach ($filechange as $fc) {
                $null = docs::find($fc->id);
                $null->attach = "No";
                $null->save();
            }

            for ($i = 0; $i < count($request->attach_files); $i++) {
                $sendfile = docs::find($request->attach_files[$i]);
                $sendfile->attach = "Yes";
                $sendfile->save();
            }
        } else {
            $chage = docs::where('folder', 'lpo')->where('fileID', $lpo->id)->where('businessID', Auth::user()->businessID)->get();
            foreach ($chage as $cs) {
                $null = docs::find($cs->id);
                $null->attach = "No";
                $null->save();
            }
        }

        //check for email CC
        $checkcc = count(collect($request->email_cc));

        //save email
        $emails = new emails;
        $emails->message = $request->message;
        $emails->clientID = $supplier->id;
        $emails->subject = $request->subject;
        $emails->mail_from = $request->email_from;
        if ($checkatt > 0) {
            $emails->attachment = json_encode($request->get('files'));
        }
        $emails->category = 'lpo Document';
        $emails->status = 'Sent';
        $emails->ip = Helper::get_client_ip();
        $emails->type = 'Outgoing';
        $emails->section = 'LPO';
        $emails->mail_to = $request->send_to;
        if ($checkcc > 0) {
            $emails->cc = json_encode($request->get('email_cc'));
        }
        $emails->save();

        //update lpo
        $lpo->statusID = 6;
        $lpo->save();

        //send email
        $subject = $request->subject;
        $content = $request->message;
        $from = $request->email_from;
        $to = $request->send_to;
        $mailID = $emails->id;
        $doctype = 'LPO';
        $docID = $lpo->id; //lpo ID

        if ($request->attaches == 'Yes') {
            $attachment = base_path() . '/storage/files/business/' . Wingu::business(Auth::user()->businessID)->primary_email . '/finance/lpo/' . Finance::lpo()->prefix . $lpo->lpo_number . '.pdf';
        } else {
            $attachment = 'No';
        }

        Mail::to($to)->send(new sendLpo($content, $subject, $from, $mailID, $docID, $doctype, $attachment));

        //recorord activity
        $activities = 'lpo #' . Finance::lpo()->prefix . $lpo->lpo_number . ' has been sent to the supplier by ' . Auth::user()->name;
        $section = 'LPO';
        $type = 'Sent';
        $adminID = Auth::user()->id;
        $activityID = $request->lpoID;
        $businessID = Auth::user()->businessID;

        Wingu::activity($activities, $section, $type, $adminID, $activityID, $businessID);

        Session::flash('success', 'lpo Sent to supplier successfully');

        return redirect()->back();

    }

    /**
     * attachment lpo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attachment_files(Request $request)
    {

        $lpo = lpo::where('id', $request->lpoID)->where('businessID', Auth::user()->businessID)->first();

        //directory
        $directory = base_path() . '/storage/files/business/' . Wingu::business(Auth::user()->businessID)->primary_email . '/finance/lpo/';

        //create directory if it doesn't exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        //get file name
        $file = $request->file('file');
        $size = $file->getSize();

        //change file name
        $filename = Helper::generateRandomString() . $file->getClientOriginalName();

        //move file
        $upload_success = $file->move($directory, $filename);

        //save the upload details into the database
        $upload = new docs;

        $upload->fileID = $request->lpoID;
        $upload->folder = 'lpo';
        $upload->name = Finance::lpo()->prefix . $lpo->lpo_number;
        $upload->file_name = $filename;
        $upload->file_size = $size;
        $upload->attach = 'No';
        $upload->file_mime = $file->getClientMimeType();
        $upload->created_by = Auth::user()->id;
        $upload->businessID = Auth::user()->businessID;
        $upload->save();
    }

    /**
     * generate lpo pdf
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        $count = 1;
        $lpo = lpo::join('business', 'business.id', '=', 'lpo.businessID')
            ->join('currency', 'currency.id', '=', 'business.base_currency')
            ->join('lpo_settings', 'lpo_settings.businessID', '=', 'business.id')
            ->join('status', 'status.id', '=', 'lpo.statusID')
            ->where('lpo.id', $id)
            ->where('lpo.businessID', Auth::user()->businessID)
            ->select('*', 'lpo.id as lpoID', 'business.name as businessName')
            ->first();

        $supplier = suppliers::join('supplier_address', 'supplier_address.supplierID', '=', 'suppliers.id')
            ->where('suppliers.businessID', Auth::user()->businessID)
            ->where('suppliers.id', $lpo->supplierID)
            ->select('*', 'suppliers.id as supplierID')
            ->first();

        $products = lpo_products::where('lpoID', $lpo->lpoID)->get();

        $pdf = PDF::loadView('templates/' . Wingu::template(Wingu::business(Auth::user()->businessID)->templateID)->template_name . '/lpo/lpo', compact('products', 'lpo', 'supplier', 'count'));

        return $pdf->download(Finance::lpo()->prefix . $lpo->lpo_number . '.pdf');
    }

    /**
     * print lpo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id) {
        $count = 1;
        $lpo = lpo::join('business', 'business.id', '=', 'lpo.businessID')
            ->join('currency', 'currency.id', '=', 'business.base_currency')
            ->join('lpo_settings', 'lpo_settings.businessID', '=', 'business.id')
            ->join('status', 'status.id', '=', 'lpo.statusID')
            ->where('lpo.id', $id)
            ->where('lpo.businessID', Auth::user()->businessID)
            ->select('*', 'lpo.id as lpoID', 'business.name as businessName')
            ->first();

        $supplier = suppliers::join('supplier_address', 'supplier_address.supplierID', '=', 'suppliers.id')
            ->where('suppliers.businessID', Auth::user()->businessID)
            ->where('suppliers.id', $lpo->supplierID)
            ->select('*', 'suppliers.id as supplierID')
            ->first();

        $products = lpo_products::where('lpoID', $lpo->lpoID)->get();

        $pdf = PDF::loadView('templates/' . Wingu::template(Wingu::business(Auth::user()->businessID)->templateID)->template_name . '/lpo/lpo', compact('products', 'lpo', 'supplier', 'count'));

        return $pdf->stream(Finance::lpo()->prefix . $lpo->lpo_number . '.pdf');
    }

    /**
     * deliverd stock
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delivered($id)
    {
        $lpo = lpo::where('id', $id)->where('businessID', Auth::user()->businessID)->first();
        $products = lpo_products::where('lpoID', $id)->get();

        if ($lpo->delivered_status == "") {
            foreach ($products as $product) {
                $inventory = product_inventory::where('productID', $product->productID)->where('businessID', Auth::user()->businessID)->first();
                $inventory->current_stock = $inventory->current_stock + $product->quantity;
                $inventory->save();
            }

            $lpo->delivered_status = 14;
            $lpo->statusID = 14;
            $lpo->save();
        }

        Session::flash('success', 'Stock updated add marked as delivered');

        return redirect()->back();
    }
}
