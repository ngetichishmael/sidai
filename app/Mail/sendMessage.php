<?php

namespace App\Mail;

use App\Models\wingu\business;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wingu;

class sendMessage extends Mailable
{
   use Queueable, SerializesModels;
   public $content;
   public $subject;

   /**
   * Create a new message instance.
   *
   * @return void
   */
   public function __construct($content,$subject)
   {
      $this->content = $content;
      $this->subject = $subject;
   }

   /**
       * Build the message.
      *
      * @return $this
      */
   public function build()
   {
      $subject = $this->subject;
      $content = $this->content;
      $business = Wingu::business();
      $from = $business->primary_email;
      $name = $business->name;

      return $this->view('email.template01', compact('content','business'))->from($from, $name)->subject($subject);
   }
}
