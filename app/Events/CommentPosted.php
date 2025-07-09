<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use App\Models\User;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Siarkan di channel privat yang spesifik untuk post ini
        // DAN di channel aktivitas admin
        return [
            new PrivateChannel('post.' . $this->comment->post_id),
            new PrivateChannel('admin-activity'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        // Muat relasi user untuk dikirim ke frontend
        $this->comment->load('user');

        return [
            'comment' => [
                'id' => $this->comment->id,
                'content' => $this->comment->content,
                'created_at_human' => $this->comment->created_at->diffForHumans(),
                'user' => [
                    'name' => $this->comment->user->name,
                    'avatar' => $this->comment->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($this->comment->user->name).'&background=random',
                ]
            ]
        ];
    }
}
