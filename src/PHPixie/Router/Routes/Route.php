<?php

namespace PHPixie\Routes;

interface Route
{
    public function match($serverRequest);
}