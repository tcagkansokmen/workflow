<?php
// app/Events/MessageSent.php
namespace App\Events;

use App\Models\User;
use App\Models\BriefComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
/**
 * Just implement the ShouldBroadcast interface and Laravel will automatically 
 * send it to Pusher once we fire it 
**/
class MakeDesignComment extends BriefComment implements ShouldBroadcast 
{
    use SerializesModels;

    /**
     * Only (!) Public members will be serialized to JSON and sent to Pusher
    **/
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('design.'.$this->message->brief_id);
    }
}