<?php

namespace MorningMedley\WordPressConfig\Classes;

class PostTypeConfig
{
    private array $postTypes;

    public function __construct(array $args)
    {
        $this->postTypes = $args;
        \add_action('init', [$this, 'registerPostTypes']);
    }

    public function registerPostTypes()
    {
        foreach ($this->postTypes as $slug => $postTypeArgs) {
            $slug = $postTypeArgs['slug'] ?? $slug;
            $this->registerPostType($slug, $postTypeArgs);
        }
    }

    private function registerPostType(string $slug, array $postTypeArgs)
    {
        \register_post_type($slug, $postTypeArgs);
    }
}
