<?php


if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class LunitePluginDeactivate
{

    /**
     * Static Function to deactivate Activate Function
     */

    public static function luniteDeactivate()
    {
        flush_rewrite_rules();
    }
}
