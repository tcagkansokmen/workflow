<?php

namespace App\Observers\Messages;

use App\Models\ProductionMessage;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class ProductionMessageObserver
{
    /**
     * Handle the production_message "created" event.
     *
     * @param  \App\Models\ProductionMessage  $production_message
     * @return void
     */
    public function created(ProductionMessage $production_message)
    {
        $production_id = $production_message->production_id;
        $user_id = $production_message->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = ProductionMessage::where('production_id', $production_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('production-detail', ['id' => $production_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $production_message->message;
                $not->save();
            }
        }
    }

    /**
     * Handle the production_message "updated" event.
     *
     * @param  \App\Models\ProductionMessage  $production_message
     * @return void
     */
    public function updated(ProductionMessage $production_message)
    {

    }

    /**
     * Handle the production_message "deleted" event.
     *
     * @param  \App\Models\ProductionMessage  $production_message
     * @return void
     */
    public function deleted(ProductionMessage $production_message)
    {
        //
    }

    /**
     * Handle the production_message "restored" event.
     *
     * @param  \App\Models\ProductionMessage  $production_message
     * @return void
     */
    public function restored(ProductionMessage $production_message)
    {
        //
    }

    /**
     * Handle the production_message "force deleted" event.
     *
     * @param  \App\Models\ProductionMessage  $production_message
     * @return void
     */
    public function forceDeleted(ProductionMessage $production_message)
    {
        //
    }
}
