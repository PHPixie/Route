<?php

interface Route
{
    public function match($segment);
    public function generatePath($subpath, $attributes);
    public function generateUri($uri, $subpath, $attributes);
}