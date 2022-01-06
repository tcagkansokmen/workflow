<?php

namespace App\Observers\Messages;

use App\Models\ExplorationDesignComment;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class ExplorationDesignCommentObserver
{
    /**
     * Handle the exploration_design_comment "created" event.
     *
     * @param  \App\Models\ExplorationDesignComment  $exploration_design_comment
     * @return void
     */
    public function created(ExplorationDesignComment $exploration_design_comment)
    {
        $exploration_id = $exploration_design_comment->exploration_id;
        $user_id = $exploration_design_comment->user_id;

        $users = User::where('group_id', 2)->orWhere('group_id', 1)->pluck('id')->toArray();
        $assembly_users = ExplorationDesignComment::where('exploration_id', $exploration_id)->pluck('user_id')->toArray();

        $all = array_merge($users, $assembly_users);
        foreach($all as $a){
            if($user_id!=$a){
                $not = new Notification();
                $not->user_id = $a;
                $not->redirect = route('exploration-detail', ['id' => $exploration_id], false);
                $not->title = 'Yeni Mesaj';
                $not->message = $exploration_design_comment->comment;
                $not->save();
            }
        }
    }

    /**
     * Handle the exploration_design_comment "updated" event.
     *
     * @param  \App\Models\ExplorationDesignComment  $exploration_design_comment
     * @return void
     */
    public function updated(ExplorationDesignComment $exploration_design_comment)
    {

    }

    /**
     * Handle the exploration_design_comment "deleted" event.
     *
     * @param  \App\Models\ExplorationDesignComment  $exploration_design_comment
     * @return void
     */
    public function deleted(ExplorationDesignComment $exploration_design_comment)
    {
        //
    }

    /**
     * Handle the exploration_design_comment "restored" event.
     *
     * @param  \App\Models\ExplorationDesignComment  $exploration_design_comment
     * @return void
     */
    public function restored(ExplorationDesignComment $exploration_design_comment)
    {
        //
    }

    /**
     * Handle the exploration_design_comment "force deleted" event.
     *
     * @param  \App\Models\ExplorationDesignComment  $exploration_design_comment
     * @return void
     */
    public function forceDeleted(ExplorationDesignComment $exploration_design_comment)
    {
        //
    }
}
