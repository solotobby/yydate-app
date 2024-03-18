<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Users extends Component
{

    public function message($userId){
        
       $authenticatedUser = Auth::user();
        //check existing conversation
       $existingConversation = Conversation::where(function ($query) use($authenticatedUser, $userId){
            $query->where('sender_id', $authenticatedUser->id)
                  ->where('receiver_id', $userId);
       })->orwhere(function ($query) use($authenticatedUser, $userId){
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $authenticatedUser);
       })->first();

       if($existingConversation){
            return redirect()->route('chat', ['query' => $existingConversation->id]);
       }

       //create conversation
       $createdConversation = Conversation::create([
                'sender_id' => $authenticatedUser->id,
                'receiver_id' => $userId
       ]);
       return redirect()->route('chat', ['query' => $createdConversation->id]);


    }

    public function render()
    {

        return view('livewire.users', [
            'users' => User::where('id', '!=', auth()->id())->get()
        ]);
       
    }
}
