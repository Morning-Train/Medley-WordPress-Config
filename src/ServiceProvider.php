<?php

namespace MorningMedley\WordPressConfig;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use MorningMedley\WordPressConfig\Classes\AcfConfig;
use MorningMedley\WordPressConfig\Classes\PostTypeConfig;
use MorningMedley\WordPressConfig\Classes\TaxonomyConfig;
use MorningMedley\WordPressConfig\Classes\WordPressConfig;

class ServiceProvider extends IlluminateServiceProvider
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
        $postTypes = $this->app->config['post-types'];
        if (! empty($postTypes)) {
            $this->app->makeWith('wordpress.post-types', ['args' => $postTypes]);
        }

        $taxonomies = $this->app->config['taxonomies'];
        if (! empty($taxonomies)) {
            $this->app->makeWith('wordpress.taxonomies', ['args' => $taxonomies]);
        }

        $acfConfig = $this->app->config['acf'];
        if (! empty($acfConfig)) {
            if (! empty($acfConfig['paths'])) {
                $this->app->config->set('acf.paths', $this->absolutePath($acfConfig['paths']));
            }
            if (! empty($acfConfig['savePath'])) {
                $this->app->config->set('acf.savePath', $this->absolutePath($acfConfig['savePath']));
            }
            $this->app->makeWith('wordpress.acf', ['app' => $this->app, 'args' => $this->app->config['acf']]);
        }

        $wordPressConfig = $this->app->config['wordpress'];
        if (! empty($wordPressConfig)) {
            $this->app->makeWith('wordpress.config', ['args' => $wordPressConfig]);
        }
    }

    public function absolutePath(array|string $path): string|array
    {
        if (is_array($path)) {
            return array_map(fn($p) => $this->app->basePath($p), $path);
        }

        return $this->app->basePath($path);
    }

}
