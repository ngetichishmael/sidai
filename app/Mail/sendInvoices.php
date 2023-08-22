<?php

namespace App\Mail;

use App\Models\crm\emails;
use App\Models\wingu\business;
use App\Models\wingu\file_manager as documents;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wingu;

class sendInvoices extends Mailable
{
   use Queueable, SerializesModels;
   public $content;
   public $subject;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($content,$subject,$from,$mailID,$docID,$attachment)
   {
      $this->content     = $content;
      $this->subject     = $subject;
      $this->$from       = $from;
      $this->mailID      = $mailID;
      $this->docID       = $docID; //id of eithe the estimate,invoice
      $this->attachment  = $attachment;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {

      $subject    = $this->subject;
      $content    = $this->content;
      $mailID     = $this->mailID;
      $docID      = $this->docID;
      $attachment      = $this->attachment;

      $from = Wingu::business(Auth::user()->businessID)->primary_email;
      $name = Wingu::business(Auth::user()->businessID)->name;

      $path = base_path().'/public/businesses/'.Wingu::business(Auth::user()->businessID)->businessID.'/finance/invoices/';

      //get email info
      $email = emails::find($mailID);

      //get attachments
      $checkfiles = documents::where('fileID',$docID)
                     ->where('section','invoice')
                     ->where('businessID',Auth::user()->businessID)
                     ->count();

        $business = business::join('invoice_settings','invoice_settings.businessID','=','business.id')
                        ->where('business.id',Auth::user()->businessID)
                        ->select('*','business.businessID as businessID')
                        ->first();


      $message = $this->view('email.template01', compact('content','subject','business'))
                  ->from($from, $name)
                  ->subject($subject);

                  //email cc's
                  if ($email->cc != ""){
                     $data = json_decode($email->cc, TRUE);
                     for($i=0; $i < count($data); $i++ ) {
                        $message->cc($data[$i]);
               		}
                  }

                  //attachments
                  if($attachment != 'No'){
                     $message->attach($attachment);
                  }

                  if($checkfiles > 0){
                     $files = documents::where('fileID',$docID)
                              ->where('section','invoice')
                              ->where('businessID',Auth::user()->businessID)
                              ->get();
                     foreach ($files as $attach) {
                        $message->attach($path.$attach->file_name);// attach each file
                     }
                  }
      return $message;
   }
}
