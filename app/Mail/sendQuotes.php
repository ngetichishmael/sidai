<?php

namespace App\Mail;

use App\Models\crm\emails;
use App\Models\wingu\business;
use App\Models\wingu\file_manager as documents;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendQuotes extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    public $subject;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct($content,$subject,$from,$mailID,$docID,$doctype,$attachment)
    {
        $this->content     = $content;
        $this->subject     = $subject;
        $this->$from       = $from;
        $this->mailID      = $mailID;
        $this->docID       = $docID; //id of eithe the estimate,invoice
        $this->doctype     = $doctype;
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
        $from       = $this->from;
        $content    = $this->content;
        $doctype    = $this->doctype;
        $mailID     = $this->mailID;
        $docID      = $this->docID;
        $attachment      = $this->attachment;

        $business = business::join('quote_settings','quote_settings.businessID','=','business.id')
                        ->where('business.id',Auth::user()->businessID)
                        ->select('*','business.businessID as business_code')
                        ->first();

        $name = $business->name;

        $path = base_path().'/public/businesses/'.$business->business_code.'/finance/'.$doctype.'/';

        //get email info
        $email = emails::find($mailID);


        //get attachments
        $checkfiles = documents::where('fileID',$docID)
                        ->where('section',$doctype)
                        ->where('attach','Yes')
                        ->where('businessID',Auth::user()->businessID)
                        ->count();


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

                    //email bcc
                    if($business->bcc != ""){
                        $message->bcc($business->bcc,'Finance');
                    }


                    //attachments
                    if($attachment != 'No'){
                        $message->attach($attachment);
                    }

                    if($checkfiles > 0){
                        $files = documents::where('fileID',$docID)
                                ->where('section',$doctype)
                                ->where('attach','Yes')
                                ->where('businessID',Auth::user()->businessID)
                                ->get();
                        foreach ($files as $attach) {
                        $message->attach($path.$attach->file_name);// attach each file
                        }
                    }
        return $message;
    }
}
