<?php

namespace PHPixie\Route;

class RouteConfig
{
    public static function translator()
    {
        return new Config\Translator();
    }

    public static function group()
    {
        return new Config\Resolver\Group();
    }

    public static function mount()
    {
        return new Config\Resolver\Mount();
    }

    public static function pattern()
    {
        return new Config\Resolver\Pattern();
    }

    public static function prefix()
    {
        return new Config\Resolver\Prefix();
    }
}