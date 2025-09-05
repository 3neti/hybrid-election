<?php

namespace TruthElection\Tests;

use TruthElection\Support\InMemoryElectionStore;

trait ResetsInMemoryElectionStore
{
    protected function resetElectionStore(): void
    {
        InMemoryElectionStore::instance()->reset();
    }
}
