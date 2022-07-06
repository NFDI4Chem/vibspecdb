<?php
 
namespace App\Events;
 
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
 
class ServerCreated implements ShouldBroadcast
{
    use SerializesModels;
 
    /**
     * The user that created the server.
     *
     * @var \App\Models\User
     */
    public $user;
    // public $connection = 'redis';
    // public $queue = 'default';
 
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user="adsf")
    {
        $this->user = $user;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.1');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'server.created';
    }
    

}