<?php

namespace MorningMedley\WordPressConfig\Classes;

class WordPressConfig
{

    public function __construct(array $args)
    {
        if(isset($args['disableComments'])){
            new DisableCommentsConfig($args['disableComments']);
        }
    }

}
