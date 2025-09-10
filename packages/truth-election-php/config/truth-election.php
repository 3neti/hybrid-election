<?php

return [
    'finalize_election_return' => [
        'pipes' => [
            // any custom pipe
        ],
    ],
    'store' => \TruthElection\Support\InMemoryElectionStore::class,
];
