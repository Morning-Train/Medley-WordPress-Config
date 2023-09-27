<?php

namespace MorningMedley\WordPressConfig\Classes;

class TaxonomyConfig
{
    private array $taxonomies;

    public function __construct(array $args)
    {
        $this->taxonomies = $args;
        \add_action('init', [$this, 'registerTaxonomies']);
    }

    public function registerTaxonomies()
    {
        foreach ($this->taxonomies as $slug => $args) {
            if (count($args) === 2) {
                [$object_type, $args] = $args;
            } else {
                [$slug, $object_type, $args] = $args;
            }
            \register_taxonomy($slug, $object_type, $args);
        }
    }
}
