<?php

use Kint\Zval\InstanceValue;

if ( ! function_exists('active_link'))
{
    function active_link($controller)
    {
        $current = current_url(true);
        return $controller==$current->getSegment(2)?'select':'';
    }   
}