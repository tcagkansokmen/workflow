<?php

namespace App\Observers\Messages;

use App\Models\BriefComment;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class BriefCommentObserver
{
    /**
     * Handle the brief_comment "created" event.
     *
     * @param  \App\Models\BriefComment  $brief_comment
     * @return void
     */
    public function created(BriefComment $brief_comment)
    {
        $brief_id = $brief_comment->brief_id;
        $user_id = $brief_comment->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = BriefComment::where('brief_id', $brief_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('brief-detail', ['id' => $brief_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $brief_comment->comment;
                $not->save();
            }
        }
    }

    /**
     * Handle the brief_comment "updated" event.
     *
     * @param  \App\Models\BriefComment  $brief_comment
     * @return void
     */
    public function updated(BriefComment $brief_comment)
    {

    }

    /**
     * Handle the brief_comment "deleted" event.
     *
     * @param  \App\Models\BriefComment  $brief_comment
     * @return void
     */
    public function deleted(BriefComment $brief_comment)
    {
        //
    }

    /**
     * Handle the brief_comment "restored" event.
     *
     * @param  \App\Models\BriefComment  $brief_comment
     * @return void
     */
    public function restored(BriefComment $brief_comment)
    {
        //
    }

    /**
     * Handle the brief_comment "force deleted" event.
     *
     * @param  \App\Models\BriefComment  $brief_comment
     * @return void
     */
    public function forceDeleted(BriefComment $brief_comment)
    {
        //
    }
}
