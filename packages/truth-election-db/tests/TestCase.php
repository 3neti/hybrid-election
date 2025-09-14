<?php

namespace TruthElectionDb\Tests;

use Lorisleiva\Actions\ActionServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use TruthElection\TruthElectionServiceProvider;
use TruthElectionDb\Actions\SetupElection;
use TruthElectionDb\TruthElectionDbServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Facades\Actions;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadConfig();

//        if ($this->app->runningInConsole()) {
//            Actions::registerCommands([
//                __DIR__ . '/../src/Actions', // or wherever SetupElectionFromFiles.php lives
//            ]);
//        }

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'TruthElectionDb\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            TruthElectionServiceProvider::class,
            TruthElectionDbServiceProvider::class,
            SchemalessAttributesServiceProvider::class,
            ActionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('data.validation_strategy', 'always');
        config()->set('data.max_transformation_depth', 6);
        config()->set('data.throw_when_max_transformation_depth_reached', 6);
        config()->set('data.normalizers', [
            \Spatie\LaravelData\Normalizers\ModelNormalizer::class,
            // Spatie\LaravelData\Normalizers\FormRequestNormalizer::class,
            \Spatie\LaravelData\Normalizers\ArrayableNormalizer::class,
            \Spatie\LaravelData\Normalizers\ObjectNormalizer::class,
            \Spatie\LaravelData\Normalizers\ArrayNormalizer::class,
            \Spatie\LaravelData\Normalizers\JsonNormalizer::class,
        ]);
        config()->set('data.date_format', "Y-m-d\TH:i:sP");
        $migration = include __DIR__.'/../database/migrations/01_create_precincts_table.php.stub';
        $migration->up();
        $migration = include __DIR__.'/../database/migrations/02_create_ballots_table.php.stub';
        $migration->up();
        $migration = include __DIR__.'/../database/migrations/03_create_positions_table.php.stub';
        $migration->up();
        $migration = include __DIR__.'/../database/migrations/04_create_candidates_table.php.stub';
        $migration->up();
        $migration = include __DIR__.'/../database/migrations/05_create_election_returns_table.php.stub';
        $migration->up();
    }

    protected function loadConfig()
    {
        $this->app['config']->set(
            'truth-election',
            require __DIR__ . '/../config/truth-election.php'
        );
        $this->app['config']->set(
            'truth-election-db',
            require __DIR__ . '/../config/truth-election-db.php'
        );
    }
}
