<?php

return [
    'finalize_election_return' => [
        'pipes' => [
            // any custom pipe
        ],
    ],
    'store' => \TruthElectionDb\Support\DatabaseElectionStore::class,
];
