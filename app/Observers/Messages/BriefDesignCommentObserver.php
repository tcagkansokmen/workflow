<?php

namespace App\Observers\Messages;

use App\Models\BriefDesignComment;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class BriefDesignCommentObserver
{
    /**
     * Handle the brief_design_comment "created" event.
     *
     * @param  \App\Models\BriefDesignComment  $brief_design_comment
     * @return void
     */
    public function created(BriefDesignComment $brief_design_comment)
    {
        $brief_id = $brief_design_comment->brief_id;
        $user_id = $brief_design_comment->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = BriefDesignComment::where('brief_id', $brief_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('brief-detail', ['id' => $brief_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $brief_design_comment->comment;
                $not->save();
            }
        }
    }

    /**
     * Handle the brief_design_comment "updated" event.
     *
     * @param  \App\Models\BriefDesignComment  $brief_design_comment
     * @return void
     */
    public function updated(BriefDesignComment $brief_design_comment)
    {

    }

    /**
     * Handle the brief_design_comment "deleted" event.
     *
     * @param  \App\Models\BriefDesignComment  $brief_design_comment
     * @return void
     */
    public function deleted(BriefDesignComment $brief_design_comment)
    {
        //
    }

    /**
     * Handle the brief_design_comment "restored" event.
     *
     * @param  \App\Models\BriefDesignComment  $brief_design_comment
     * @return void
     */
    public function restored(BriefDesignComment $brief_design_comment)
    {
        //
    }

    /**
     * Handle the brief_design_comment "force deleted" event.
     *
     * @param  \App\Models\BriefDesignComment  $brief_design_comment
     * @return void
     */
    public function forceDeleted(BriefDesignComment $brief_design_comment)
    {
        //
    }
}
