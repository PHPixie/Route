<?php

interface Matcher
{
    public function match($segment);
    public function generate($segment, $parameters);
}