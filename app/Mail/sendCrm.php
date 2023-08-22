<?php

namespace App\Mail;

use App\Models\crm\emails;
use App\Models\wingu\business;
use App\Models\wingu\file_manager as documents;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendCrm extends Mailable
{
   use Queueable, SerializesModels;
   public $content;
   public $subject;

   /**
   * Create a new message instance.
   *
   * @return void
   */
   public function __construct($content,$subject,$from,$mailID,$parentID,$folder){
      $this->content     = $content;
      $this->subject     = $subject;
      $this->$from       = $from;
      $this->mailID      = $mailID;
      $this->parentID    = $parentID; //id of eithe the estimate,invoice
      $this->folder     = $folder;
   }

   /**
   * Build the message.
   *
   * @return $this
   */
   public function build()
   {
      $business = business::find(Auth::user()->businessID);

      $subject    = $this->subject;
      $from       = $this->from;
      $content    = $this->content;
      $folder     = $this->folder;
      $mailID     = $this->mailID;
      $parentID   = $this->parentID;

      $name = $business->name;

      $path = base_path().'/public/businesses/'.$business->businessID.'/'.$folder.'/';

      //get email info
      $email = emails::where('id',$mailID)->where('businessID',Auth::user()->businessID)->first();

      //get attachments
      $checkfiles = documents::where('fileID',$parentID)
                     ->where('folder',$folder)
                     ->where('section','customer')
                     ->where('attach','Yes')
                     ->where('businessID',Auth::user()->businessID)
                     ->count();

      $message = $this->view('email.template01', compact('content','subject','business'))
                    ->from($from, $name)
                    ->subject($subject);

      //email cc's
      if ($email->cc != ""){
         $message->cc($email->cc);
      }

      //check if their is any attachment
      if($checkfiles > 0){
         $files = documents::where('fileID',$parentID)
                  ->where('folder',$folder)
                  ->where('section','customer')
                  ->where('attach','Yes')
                  ->where('businessID',Auth::user()->businessID)
                  ->get();
         foreach ($files as $attach) {
         $message->attach($path.$attach->file_name);// attach each file
         }
      }

      return $message;

      return $this->view('email.template01', compact('content','business'))->from($from, $name)->subject($subject);
   }
}
