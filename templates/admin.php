<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// check user capabilities
if (! current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    add_settings_error('lunite_messages', 'lunite_message', __('Settings Saved', 'lunite'), 'updated');
}


// show error/update messages
settings_errors('lunite_messages');
?>
    <div class="mx-w-56 bg-white mx-auto br-1 box-shadow-1 mt-20">
        <div class="header_section">
            <h1>Lunite Tunnel</h1>
        </div>
        <div class="form-section-lunite">
            <form action="options.php" method="post">
                <?php
                settings_fields('lunite-api');
                do_settings_sections('lunite-admin-page');
                // output save settings button
                submit_button('Save Settings');
                ?>
            </form>
        </div>

    </div>
<?php
