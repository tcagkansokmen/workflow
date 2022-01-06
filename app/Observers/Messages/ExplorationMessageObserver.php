<?php

namespace App\Observers\Messages;

use App\Models\ExplorationMessage;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class ExplorationMessageObserver
{
    /**
     * Handle the exploration_message "created" event.
     *
     * @param  \App\Models\ExplorationMessage  $exploration_message
     * @return void
     */
    public function created(ExplorationMessage $exploration_message)
    {
        $exploration_id = $exploration_message->exploration_id;
        $user_id = $exploration_message->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = ExplorationMessage::where('exploration_id', $exploration_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('exploration-detail', ['id' => $exploration_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $exploration_message->message;
                $not->save();
            }
        }
    }

    /**
     * Handle the exploration_message "updated" event.
     *
     * @param  \App\Models\ExplorationMessage  $exploration_message
     * @return void
     */
    public function updated(ExplorationMessage $exploration_message)
    {

    }

    /**
     * Handle the exploration_message "deleted" event.
     *
     * @param  \App\Models\ExplorationMessage  $exploration_message
     * @return void
     */
    public function deleted(ExplorationMessage $exploration_message)
    {
        //
    }

    /**
     * Handle the exploration_message "restored" event.
     *
     * @param  \App\Models\ExplorationMessage  $exploration_message
     * @return void
     */
    public function restored(ExplorationMessage $exploration_message)
    {
        //
    }

    /**
     * Handle the exploration_message "force deleted" event.
     *
     * @param  \App\Models\ExplorationMessage  $exploration_message
     * @return void
     */
    public function forceDeleted(ExplorationMessage $exploration_message)
    {
        //
    }
}
