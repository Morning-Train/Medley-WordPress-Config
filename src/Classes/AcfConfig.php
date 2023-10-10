<?php

namespace MorningMedley\WordPressConfig\Classes;

use Illuminate\Container\Container;

class AcfConfig
{

    private ?array $paths = null;
    private ?string $savePath = null;
    private Container $app;
    private ?array $hideAdminOn = null;

    public function __construct(Container $app, array $args)
    {
        $this->app = $app;

        if (! empty($args['paths'])) {
            \add_filter('acf/settings/load_json', [$this, 'addLoadPaths']);
            $this->paths = $args['paths'];
        }
        if (! empty($args['savePath'])) {
            \add_filter('acf/settings/save_json', [$this, 'addSavePath'], 99);
            $this->savePath = $args['savePath'];
        }
        if (isset($args['hideAdmin'])) {
            \add_filter('acf/settings/show_admin', '__return_false');
        } elseif (! empty($args['hideAdminOn'])) {
            \add_filter('acf/settings/show_admin', [$this, 'hideAdminOn']);
            $this->hideAdminOn = $args['hideAdminOn'];
        }
    }

    public function addLoadPaths(array $paths): array
    {
        if ($this->paths === null) {
            return $paths;
        }

        return [...$paths, ...$this->paths];
    }

    public function addSavePath()
    {
        return $this->savePath;
    }

    public function hideAdminOn(): bool
    {
        return !in_array(\wp_get_environment_type(), $this->hideAdminOn);
    }
}
