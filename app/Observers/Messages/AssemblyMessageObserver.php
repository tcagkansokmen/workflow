<?php

namespace App\Observers\Messages;

use App\Models\AssemblyMessage;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class AssemblyMessageObserver
{
    /**
     * Handle the assembly_message "created" event.
     *
     * @param  \App\Models\AssemblyMessage  $assembly_message
     * @return void
     */
    public function created(AssemblyMessage $assembly_message)
    {
        $assembly_id = $assembly_message->assembly_id;
        $user_id = $assembly_message->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = AssemblyMessage::where('assembly_id', $assembly_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('assembly-detail', ['id' => $assembly_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $assembly_message->message;
                $not->save();
            }
        }
    }

    /**
     * Handle the assembly_message "updated" event.
     *
     * @param  \App\Models\AssemblyMessage  $assembly_message
     * @return void
     */
    public function updated(AssemblyMessage $assembly_message)
    {

    }

    /**
     * Handle the assembly_message "deleted" event.
     *
     * @param  \App\Models\AssemblyMessage  $assembly_message
     * @return void
     */
    public function deleted(AssemblyMessage $assembly_message)
    {
        //
    }

    /**
     * Handle the assembly_message "restored" event.
     *
     * @param  \App\Models\AssemblyMessage  $assembly_message
     * @return void
     */
    public function restored(AssemblyMessage $assembly_message)
    {
        //
    }

    /**
     * Handle the assembly_message "force deleted" event.
     *
     * @param  \App\Models\AssemblyMessage  $assembly_message
     * @return void
     */
    public function forceDeleted(AssemblyMessage $assembly_message)
    {
        //
    }
}
