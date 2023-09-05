<?php

namespace App\Http\Livewire\Users;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public $role;
   protected $users;
   public $selectedData = [];
   public $title;
   public $body;
   protected $listeners = ['closeModal' => 'closeModal'];

   public function closeModal()
   {
      dd("here");
      $this->showModal = false;
   }
   public function mount($role)
   {
      $this->role = $role;
   }

   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $users = User::where('account_type', $this->role)
         ->whereLike(['Region.name', 'name', 'email', 'phone_number'], $searchTerm)
         ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate($this->perPage);

      $this->bulkDisabled = count($this->selectedData) < 1;
      return view('livewire.users.index', ['users' => $users]);
   }
   public function deactivate($id)
   {
      User::whereId($id)->update(
         ['status' => "Suspended"]
      );
      return redirect()->back();
   }
   public function activate($id)
   {
      User::whereId($id)->update(
         ['status' => "Active"]
      );

//      return redirect()->to('/users');
      return redirect()->back();
   }

   public function SelectedNotify()
   {
      return redirect()->to(url()->previous());
   }
   public function MassiveNotify()
   {
      $fcms=[];
      $bussiness_code=Auth::User()->business_code;
      if (empty($this->selectedData )){
         session()->flash('error', "No user selected");
         return redirect()->to(url()->previous());
      }
      foreach ($this->selectedData as $value) {
         Notification::create([
            "user_code" => $value,
            "name" => User::where('user_code', $value)->pluck("name")->implode(''),
            "title" => $this->title,
            "body" => $this->body,
            "image" => "null",
            "date" => now(),
            'bussiness_code' => $bussiness_code,
            "status" => 1
         ]);
         $fcm = User::where('user_code', $value)->pluck("fcm_token")->implode('');
         if (!empty($fcm)) {
            $fcms[] = $fcm;
         }
      }
      if (!empty($fcms)){
         $this->sendFirebaseNotification($fcms);
         session()->flash('success', "Notification Sent");
         return redirect()->to(url()->previous());
      }
      if (empty($fcms)) {
         session()->flash('error', "No valid FCM tokens found for the user");
         return redirect()->to(url()->previous());
      }
   }
   public function sendFirebaseNotification($fcm_token)
   {
      $token_default = [
         "dNCXRn5ISZCH3LxStsbv6N:APA91bF9PQYSUYcBxFl3MhYRieB-8XnnojhU0t3QL89rLFydStIQPeMlNorWoGulScjpmZuhzes7ovE5w0pL7jhVq4MF5Km0rVIQGDi6eLtrk_gCFhxe2j_5MibRXER-eN7HkVMDSz03",
      ];
      $tokenzined = [  $fcm_token ];
      info($tokenzined);
      $token = $fcm_token == null ? $token_default : $tokenzined;
      $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

      $fcmNotification = [
         'registration_ids'  => $token, //device token (smartphones unique identifier)
         'notification' => [
            'title' => $this->title, //notification title
            'body' => $this->body, //notification body
         ],
         'data' => [
            "route" => '/notification'
         ]
      ];

      $headers = [
         'Authorization: key=AAAAF82SEcA:APA91bG8wzqRzTiPtl-IAVH6BvjFpAIjR23PWks_BAcclupXSZXE-f_YFISD-nfKCWpwym7G60EmH1oa1hScvreTtVAHrkH_BFiCpP66zvzTslZyXSCDgpiXaJVtv4gc2zKm-YC3wXvx', //firebase server key
         'Content-Type: application/json'
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $fcmUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
      $result = curl_exec($ch);
      curl_close($ch);
//      return response()->json([
//         $result
//      ]);
//      dump($result);
   }
}
