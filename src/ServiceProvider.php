<?php

namespace MorningMedley\WordPressConfig;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use MorningMedley\WordPressConfig\Classes\DisableCommentsConfig;
use MorningMedley\WordPressConfig\Classes\PostTypeConfig;
use MorningMedley\WordPressConfig\Classes\TaxonomyConfig;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app->bind('wordpress.post-types', PostTypeConfig::class);
        $this->app->bind('wordpress.disableComments', DisableCommentsConfig::class);
        $this->app->bind('wordpress.taxonomies', TaxonomyConfig::class);
    }

    public function boot(): void
    {
        $base = $this->app->config['wordpress'];
        if (isset($base['config'])) {
            unset($base['config']);
        }
        foreach (array_merge($base, $this->app->config['wordpress.config']) as $configwordpress => $val) {
            $key = "wordpress." . $configwordpress;
            if ($this->app->bound($key)) {
                $this->app->makeWith($key, ['args' => $val]);
            }
        }
    }

}
