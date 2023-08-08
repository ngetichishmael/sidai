<?php

namespace App\Notifications;

use App\Models\customers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
//    private $details;
   private $order;
   private $orderitems;
   private $distributor;
   /**
    * @var mixed
    */


   /**
     * Create a new notification instance.
     *
     * @return void
     */

   public function __construct($order, $distributor)
   {
      $this->order = $order;
      $this->distributor = $distributor;
      $this->orderitems = Order_items::where('order_code', $this->order->order_code)->get();
      Log::debug($this->order->order_code);
      Log::debug($this->orderitems);
   }

//    public function __construct()
//    {
//        $this->details=$details;
//       $this->user = $user;
//       $this->orderId = $orderId;
//    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
       public function via($notifiable)
    {
       return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       Log::debug('----------------------------------'. $this->orderitems);
      if ($this->order){
         $customer=customers::find($this->order->customerID);
         if (!empty($customer)) {
           $orderitems=$this->orderitems;
//            foreach ($orderitems as $item) {
//               $orderDetails .= $item->product_name . "\t\t" . $item->quantity . "\n";
//            }
         }
        $mapLink = 'https://www.google.com/maps?q=' . $customer->latitude . ',' . $customer->longitude;
//         $message="A new order has been placed for ".$customer->customer_name;
//         $location= "Customer Location Pin: " . $mapLink;
         $customer=$customer->customer_name;
         Log::debug('----------------------------------'. $this->orderitems);
         Log::debug('*****************************'. $orderitems);
         return (new MailMessage)
            ->subject('New Order Notification')
            ->view('email.order_notification', [
               'customer' => $customer,
               'location' => $mapLink,
               'ordercode'=>$this->order->order_code,
               'name'=>$this->distributor,
               'orderitems'=>$this->orderitems,
         ]);
      }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
Log::debug("in toarray db notifiable");
       $orderDetails = '';
       if ($this->order){
          $customer=customers::find($this->order->customerID);
          foreach ($this->order->items as $item) {
             $orderDetails .= $item->product_name . "\t\t" . $item->quantity . "\n";
          }

          $mapLink = 'https://www.google.com/maps?q=' . $customer->latitude . ',' . $customer->longitude;

          return [
           'name' => $this->user->name,
           'user_code' => $this->user->user_code,
               'title' => 'A new order has been placed from the Test Shop.',
              'body'=>[
//                 'Location: ' =>$mapLink,
              'Order details: ',
             $orderDetails,
]
        ];
    }
    }
}
