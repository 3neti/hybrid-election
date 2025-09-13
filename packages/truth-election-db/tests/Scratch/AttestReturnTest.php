<?php

use TruthElection\Data\{CandidateData, PositionData, VoteData, SignPayloadData};
use TruthElectionDb\Actions\{AttestReturn, SetupElection, CastBallot};
use TruthElectionDb\Models\{Precinct, ElectionReturn};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\File;
use TruthElection\Enums\Level;

uses(RefreshDatabase::class);

beforeEach(function () {
    File::ensureDirectoryExists(base_path('config'));
    File::copy(realpath(__DIR__ . '/../../../config/election.json'), base_path('config/election.json'));
    File::copy(realpath(__DIR__ . '/../../../config/precinct.yaml'), base_path('config/precinct.yaml'));

    Precinct::truncate();
    SetupElection::run();

    $this->precinctCode = 'CURRIMAO-001';
    $this->votes = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(
                    code: 'LD_001',
                    name: 'Leonardo DiCaprio',
                    alias: 'LD',
                    position: new PositionData(
                        code: 'PRESIDENT',
                        name: 'President',
                        level: Level::NATIONAL,
                        count: 1
                    )
                ),
            ])
        )
    ]);

    CastBallot::run(
        ballotCode: 'BALLOT-777',
        precinctCode: $this->precinctCode,
        votes: $this->votes,
    );

    $this->return = ElectionReturn::generateFromPrecinct($this->precinctCode);
});

test('successfully signs election return using AttestReturn', function () {
    $payload = SignPayloadData::fromQrString('BEI:A1:signature123');

    $response = AttestReturn::run($payload, $this->return->code);

    expect($response)
        ->message->toBe('Signature saved successfully.')
        ->id->toBe('A1')
        ->name->toBe('Alice')
        ->role->toBe('chairperson')
        ->signed_at->toBeString();

    $updated = ElectionReturn::whereCode($this->return->code)->first();

    expect($updated->signatures)->toHaveCount(1);
    expect($updated->signatures[0]['id'])->toBe('A1');
    expect($updated->signatures[0]['signature'])->toBe('signature123');
});

//test('appends second inspector signature using AttestReturn', function () {
//    AttestReturn::run(SignPayloadData::fromQrString('BEI:A1:sig1'), $this->return->code);
//    AttestReturn::run(SignPayloadData::fromQrString('BEI:B2:sig2'), $this->return->code);
//
//    $updated = ElectionReturn::whereCode($this->return->code)->first();
//
//    expect($updated->signatures)->toHaveCount(2);
//
//    $ids = collect($updated->signatures)->pluck('id');
//    expect($ids)->toContain('A1')->toContain('B2');
//
//    $b2 = collect($updated->signatures)->firstWhere('id', 'B2');
//    expect($b2['signature'])->toBe('sig2');
//});
//
//test('returns 404 for unknown inspector', function () {
//    $payload = SignPayloadData::fromQrString('BEI:Z9:wrong');
//
//    AttestReturn::run($payload, $this->return->code);
//})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class, 'Inspector with ID [Z9] not found.');
//
//test('returns 404 for missing election return', function () {
//    $payload = SignPayloadData::fromQrString('BEI:A1:legit');
//
//    AttestReturn::run($payload, 'NON-EXISTENT-ER');
//})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class, 'Election return [NON-EXISTENT-ER] not found.');
