<?php

namespace App\Mail;

use App\Models\crm\emails;
use App\Models\wingu\business;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wingu;

class sendStatement extends Mailable
{
   use Queueable, SerializesModels;
   public $content;
   public $subject;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($content,$subject,$from,$mailID,$attachment)
   {
      $this->content     = $content;
      $this->subject     = $subject;
      $this->$from       = $from;
      $this->mailID      = $mailID;
      $this->attachment  = $attachment;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {

      $subject     = $this->subject;
      $content     = $this->content;
      $mailID      = $this->mailID;
      $attachment  = $this->attachment;

      $from = Wingu::business()->primary_email;
      $name = Wingu::business()->name;

      //get email info
      $email = emails::where('id',$mailID)->where('businessID',Auth::user()->businessID)->first();

      $business = business::where('id',Auth::user()->businessID)->first();

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

      return $message;
   }
}
