<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use App\Models\Ballot;

class BallotSubmitted // implements ShouldBroadcast // ← Uncomment this if you want to broadcast
{
    use Dispatchable, SerializesModels;

    public Ballot $ballot;

    /**
     * Create a new event instance.
     */
    public function __construct(Ballot $ballot)
    {
        $this->ballot = $ballot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * Only needed if you implement ShouldBroadcast.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ballots'),
        ];
    }

    /**
     * Optionally format the data to be broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'ballot' => $this->ballot->load('precinct')->toArray(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'BallotSubmitted';
    }
}
