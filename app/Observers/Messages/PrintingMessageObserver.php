<?php

namespace App\Observers\Messages;

use App\Models\PrintingMessage;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class PrintingMessageObserver
{
    /**
     * Handle the printing_message "created" event.
     *
     * @param  \App\Models\PrintingMessage  $printing_message
     * @return void
     */
    public function created(PrintingMessage $printing_message)
    {
        $printing_id = $printing_message->printing_id;
        $user_id = $printing_message->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = PrintingMessage::where('printing_id', $printing_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('printing-detail', ['id' => $printing_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $printing_message->message;
                $not->save();
            }
        }
    }

    /**
     * Handle the printing_message "updated" event.
     *
     * @param  \App\Models\PrintingMessage  $printing_message
     * @return void
     */
    public function updated(PrintingMessage $printing_message)
    {

    }

    /**
     * Handle the printing_message "deleted" event.
     *
     * @param  \App\Models\PrintingMessage  $printing_message
     * @return void
     */
    public function deleted(PrintingMessage $printing_message)
    {
        //
    }

    /**
     * Handle the printing_message "restored" event.
     *
     * @param  \App\Models\PrintingMessage  $printing_message
     * @return void
     */
    public function restored(PrintingMessage $printing_message)
    {
        //
    }

    /**
     * Handle the printing_message "force deleted" event.
     *
     * @param  \App\Models\PrintingMessage  $printing_message
     * @return void
     */
    public function forceDeleted(PrintingMessage $printing_message)
    {
        //
    }
}
