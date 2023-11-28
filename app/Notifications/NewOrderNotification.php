<?php

namespace App\Notifications;

use App\Models\customers;
use App\Models\Order_items;
use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Translation\t;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use SerializesModels, Queueable;
//    private $details;
   private $order;
   private $orderitems;
   private $distributor;
   private $sales;
   private  $sales_number;
   /**
    * @var mixed
    */


   /**
     * Create a new notification instance.
     *
     * @return void
     */

   public function __construct($order, $distributor, $sales, $sales_number)
   {
      $this->order = $order;
      $this->sales = $sales;
      $this->sales_number = $sales_number;
      $this->distributor = $distributor;
      $this->orderitems = Order_items::where('order_code', $this->order)->get();
//      Log::debug($this->order);
//      info(".........", [$this->orderitems]);

   }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
       public function via($notifiable)
    {
      info('Reached here!');
       return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       info("her in tomail function");
       try {
       info('----------------------------------' . $this->orderitems);
       if ($this->order) {
          $o = Orders::find($this->order);
          $customer = customers::find($o->customerID);
          if (!empty($customer)) {
             $orderitems = $this->orderitems;
          }
          Log::debug('Customer---------------- ' . $customer);
          $mapLink = 'https://www.google.com/maps?q=' . $customer->latitude ?? '0' . ',' . $customer->longitude ?? '';
          $customer = $customer->customer_name;
//         dd(gettype($orderitems));
          return (new MailMessage)
             ->subject('New Order Notification')
             ->view('email.order_notification', [
                'customer' => $customer,
                'location' => $mapLink,
                'ordercode' => $this->order,
                'name' => $this->distributor,
                'orderitems' => $this->orderitems,
                'sales' => $this->sales,
                'sales_number' => $this->sales_number,
             ]);
       }
       } catch (\Exception $exception) {
          Log::error('Error in toMail method: ' . $exception->getMessage());
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
        if ($this->order && $this->order->items) { // Check if $this->order and $this->order->items are not null
            $customer = customers::find($this->order->customerID);
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
