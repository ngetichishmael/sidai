<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    public function index1()
    {
        $customer = Auth::user()->user_code;
        $tickets = SupportTicket::where('customer_code', $customer)->get();
        return response()->json($tickets);
    }
    public function index()
    {
        $tickets = SupportTicket::paginate(10);
        $unreadCount = Message::where('read', 0)->count();
        return view('app.support.index', compact('tickets', 'unreadCount'));
    }

    public function index2()
    {
        $user = Auth::user()->user_code;
        $tickets = SupportTicket::with('messages')->where('user_code', $user)->get();
        return response()->json(['data' => $tickets]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
            'subject' => 'required|string|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $ticket = new SupportTicket();
        $ticket->subject = $request->subject ?? '';
        $ticket->description = $request->description ?? '';
        $ticket->status = 'open';
        $ticket->user_code = $request->user()->user_code;
        $ticket->save();

        $message = new Message();
        $message->ticket_id = $ticket->id;
        $message->sender_code = $request->user()->user_code;
        $message->message = $request->input('message');
        $message->read = 1;
        $message->save();

        return response()->json(['data' => $ticket]);
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('messages')->findOrFail($id);
        return response()->json(['data' => $ticket]);
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = $request->input('status');
        $ticket->save();

        return response()->json(['data' => $ticket]);
    }

    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully']);
    }
    public function getMessages(Request $request)
    {
        $messages = Message::where('ticket_id', $request->ticket_id)->get();
        return response()->json(['messages' => $messages], 200);
    }
    public function addMessage(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $message = new Message();
        $message->ticket_id = $ticket->id;
        $message->sender_code = $request->user()->user_code;
        $message->message = $request->input('message');
        $message->read = 0;
        $message->save();

        return response()->json(['data' => $message]);
    }
    public function replyToMessage(Request $request, $ticketId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ticket = SupportTicket::findOrFail($ticketId);
//      $message = Message::findOrFail($messageId);

        $newMessage = new Message();
        $newMessage->ticket_id = $ticket->id;
        $newMessage->sender_code = $request->user()->user_code;
        $newMessage->message = $request->input('message');
//      $newMessage->parent_id = $message->id;
        // Mark the original message as read
        $newMessage->read = 1;
        $newMessage->save();
        return response()->json(['data' => $newMessage]);
    }

}
