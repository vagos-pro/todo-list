<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Meilisearch\Client;

class ScoutMeilisearchSettingsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $scoutConfig = config('scout.meilisearch');

        if ($scoutConfig) {
            $client = new Client($scoutConfig['host'], $scoutConfig['key']);
            $indexSettings = $scoutConfig['index-settings'] ?? [];

            foreach ($indexSettings as $modelClass => $settings) {
                $indexName = (new $modelClass)->searchableAs();

                if (!empty($settings['filterableAttributes'])) {
                    $client->index($indexName)->updateFilterableAttributes($settings['filterableAttributes']);
                }
                if (!empty($settings['searchableAttributes'])) {
                    $client->index($indexName)->updateSearchableAttributes($settings['searchableAttributes']);
                }
                if (!empty($settings['sortableAttributes'])) {
                    $client->index($indexName)->updateSortableAttributes($settings['sortableAttributes']);
                }
            }
        }
    }
}
