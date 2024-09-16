<?php

namespace MorningMedley\WordPressConfig;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use MorningMedley\WordPressConfig\Classes\AcfConfig;
use MorningMedley\WordPressConfig\Classes\PostTypeConfig;
use MorningMedley\WordPressConfig\Classes\TaxonomyConfig;
use MorningMedley\WordPressConfig\Classes\WordPressConfig;

class WordPressConfigServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . "/config/acf.php", 'acf');

        $this->app->bind('wordpress.post-types', PostTypeConfig::class);
        $this->app->bind('wordpress.config', WordPressConfig::class);
        $this->app->bind('wordpress.taxonomies', TaxonomyConfig::class);
        $this->app->bind('wordpress.acf', AcfConfig::class);
    }

    public function boot(): void
    {
        $postTypes = $this->app['config']->get('post-types');
        if (! empty($postTypes)) {
            $this->app->makeWith('wordpress.post-types', ['args' => $postTypes]);
        }

        $taxonomies = $this->app['config']->get('taxonomies');
        if (! empty($taxonomies)) {
            $this->app->makeWith('wordpress.taxonomies', ['args' => $taxonomies]);
        }

        $acfConfig = $this->app['config']->get('acf');
        if (! empty($acfConfig)) {
            $this->app->makeWith('wordpress.acf', ['args' => $acfConfig]);
        }

        $wordPressConfig = $this->app['config']->get('wordpress');
        if (! empty($wordPressConfig)) {
            $this->app->makeWith('wordpress.config', ['args' => $wordPressConfig]);
        }
    }
}
