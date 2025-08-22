<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use App\Models\Ballot;

class BallotSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public bool $afterCommit = true;

    public Ballot $ballot;

    public function __construct(Ballot $ballot)
    {
        $this->ballot = $ballot;
    }

    public function broadcastOn(): array
    {
        return [ new Channel("precinct.{$this->ballot->precinct->code}") ];
    }

    public function broadcastWith(): array
    {
        return [
            'ballot' => $this->ballot->load('precinct')->getData()->toArray(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ballot.submitted';
    }
}
