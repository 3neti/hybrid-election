<?php

namespace TruthElectionDb\Tests;

use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use TruthElectionDb\TruthElectionDbServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadConfig();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'TruthElectionDb\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            TruthElectionDbServiceProvider::class,
            SchemalessAttributesServiceProvider::class
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
            'truth-election-db',
            require __DIR__ . '/../config/truth-election-db.php'
        );
    }
}
