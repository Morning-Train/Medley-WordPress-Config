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
        $postTypes = $this->app->config['post-types'];
        if (! empty($postTypes)) {
            $this->app->makeWith('wordpress.post-types', ['args' => $postTypes]);
        }

        $taxonomies = $this->app->config['taxonomies'];
        if (! empty($taxonomies)) {
            $this->app->makeWith('wordpress.taxonomies', ['args' => $taxonomies]);
        }

        $base = $this->app->config['wordpress'];
        if (empty($base)) {
            return;
        }
        foreach ($base as $configwordpress => $val) {
            $key = "wordpress." . $configwordpress;
            if ($this->app->bound($key)) {
                $this->app->makeWith($key, ['args' => $val]);
            }
        }
    }

}
