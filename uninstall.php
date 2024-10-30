<?php

/**
 *
 * Trigger the File on Plugin Uninstall
 *
 *
 *
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class uninstall
{
    public function luniteUninstall()
    {
        if (! defined('WP_UNINSTALL_PLUGIN')) {
            die();
        }
    }
}
