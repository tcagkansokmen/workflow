<?php

namespace App\Observers\Messages;

use App\Models\OfferComment;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class OfferCommentObserver
{
    /**
     * Handle the offer_comment "created" event.
     *
     * @param  \App\Models\OfferComment  $offer_comment
     * @return void
     */
    public function created(OfferComment $offer_comment)
    {
        $offer_id = $offer_comment->offer_id;
        $user_id = $offer_comment->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = OfferComment::where('offer_id', $offer_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('offer-detail', ['id' => $offer_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $offer_comment->comment;
                $not->save();
            }
        }
    }

    /**
     * Handle the offer_comment "updated" event.
     *
     * @param  \App\Models\OfferComment  $offer_comment
     * @return void
     */
    public function updated(OfferComment $offer_comment)
    {

    }

    /**
     * Handle the offer_comment "deleted" event.
     *
     * @param  \App\Models\OfferComment  $offer_comment
     * @return void
     */
    public function deleted(OfferComment $offer_comment)
    {
        //
    }

    /**
     * Handle the offer_comment "restored" event.
     *
     * @param  \App\Models\OfferComment  $offer_comment
     * @return void
     */
    public function restored(OfferComment $offer_comment)
    {
        //
    }

    /**
     * Handle the offer_comment "force deleted" event.
     *
     * @param  \App\Models\OfferComment  $offer_comment
     * @return void
     */
    public function forceDeleted(OfferComment $offer_comment)
    {
        //
    }
}
