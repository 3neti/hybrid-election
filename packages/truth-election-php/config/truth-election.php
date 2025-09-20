<?php

use TruthElection\Pipes\PersistElectionReturnJson;
use TruthElection\Pipes\EncodeElectionReturnLines;

return [
    'finalize_election_return' => [
        'pipes' => [
            PersistElectionReturnJson::class,
            EncodeElectionReturnLines::class
        ],
    ],
    'storage' => [
        'disk' => env('TRUTH_ELECTION_DISK', 'local'),
    ],
//    'store' => \TruthElection\Support\InMemoryElectionStore::class,
];
