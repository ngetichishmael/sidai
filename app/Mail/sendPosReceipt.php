<?php
namespace App\Mail;
use App\Models\finance\customer\customers;
use App\Models\finance\invoice\invoice_products;
use App\Models\finance\invoice\invoices;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendPosReceipt extends Mailable
{
   use Queueable, SerializesModels;
   public $subject;
   public $saleID;

   /**
   * Create a new message instance.
   *
   * @return void
   */
   public function __construct($subject,$saleID)
   {
      $this->subject = $subject;
      $this->saleID = $saleID;
   }

   /**
       * Build the message.
      *
      * @return $this
      */
   public function build()
   {
      $subject = $this->subject;
      $invoice = invoices::join('business','business.id','=','invoices.businessID')
								->join('currency','currency.id','=','business.base_currency')
								->join('status','status.id','=','invoices.statusID')
								->join('invoice_settings','invoice_settings.businessID','=','invoices.businessID')
								->where('invoices.id',$this->saleID)
								->where('invoices.businessID',Auth::user()->businessID)
								->select('*','invoices.id as invoiceID','business.name as businessName','invoices.statusID as invoiceStatusID','business.businessID as business_code','business.businessID as businessID')
								->first();

		$products = invoice_products::where('invoiceID',$invoice->invoiceID)->get();

		$client = customers::join('customer_address','customer_address.customerID','=','customers.id')
					->where('customers.id',$invoice->customerID)
					->where('businessID',Auth::user()->businessID)
					->select('*','customers.id as clientID','bill_country as countryID')
					->first();

      $from = $invoice->primary_email;
      $name = $invoice->businessName;

      return $this->view('email.pos_receipt', compact('invoice','products','client'))->from($from, $name)->subject($subject);
   }
}
