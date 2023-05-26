<?php
 
namespace App\Events;
 
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
 
class SendUserMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
 
    /**
     * The user that created the server.
     *
     * @var \App\Models\User
     */
    private $message;
    private $user;
    // public $connection = 'redis';
    public $queue = 'notification';
 
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user, array $message = null)
    {
        $this->message = $message;
        $this->user = $user;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.'. $this->user->id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'UserJobs.submitted';
    }
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return [
            'message'=> $this->message
        ];
    }
}