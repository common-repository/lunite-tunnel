<?php


if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class LunitePluginActivate
{
    /**
     * Static Function to enable Activate Function
     */
    public static function luniteActivate()
    {
        flush_rewrite_rules();
    }
}
