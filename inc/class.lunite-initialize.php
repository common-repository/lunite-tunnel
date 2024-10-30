<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class LuniteInitialize
{

    /**
     * Register the action that needed for lunite plugin
     */
    public function luniteRegister()
    {
        $lunite_new_action_trigger = new LuniteActionsTrigger();
        add_action('admin_enqueue_scripts', [$this,'luniteEnqueue']);
        add_action('admin_menu', [$this,'luniteAdminPages']);
        add_action('admin_init', [$this,'luniteAdminSettings' ]);
        add_filter('plugin_action_links_'.LUNITE_PLUGIN_NAME, [$this,'luniteSettingsLink']);
        add_action('woocommerce_order_status_processing', [ $lunite_new_action_trigger,'sendLuniteWCProcessingAlertToCustomer']);
    }

    /**
     * @return mixed
     * return customized lunite settings links
     */
    public function luniteAdminSettings()
    {
        register_setting('lunite-api', 'lunite_api_options_sms_carrier', ['type' => 'string','$description' => 'SMS API Carrier','sanitize_callback' => 'sanitize_text_field']);
        register_setting('lunite-api', 'lunite_api_options_sms_user_name', ['type' => 'string','$description' => 'SMS User Name','sanitize_callback' => 'sanitize_text_field']);
        register_setting('lunite-api', 'lunite_api_options_sms_user_password', ['type' => 'string','$description' => 'SMS User Name','sanitize_callback' => 'sanitize_text_field']);
        register_setting('lunite-api', 'lunite_api_options_text_masking', ['type' => 'string','$description' => 'MT Port Text Masking','sanitize_callback' => 'sanitize_text_field']);
        register_setting('lunite-api', 'lunite_api_options_ssl_enable', ['type' => 'string','$description' => 'Check SSL Enable']);


        add_settings_section('lunite-authentication-setting-section', 'General Settings', [$this,'luniteSectionEcho'], 'lunite-admin-page');

        add_settings_field(
            'lunite-api-options-sms-carrier', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('Sms Service', 'Sms Service'),
            [$this,'luniteApiOptionsSmsCarrier'],
            'lunite-admin-page',
            'lunite-authentication-setting-section',
            [
                 'label_for' => 'lunite_sms_carrier',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
                ]
        );

        add_settings_field(
            'lunite-api-options-user-name', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('User Name', 'User Name'),
            [$this,'luniteOptionUserName'],
            'lunite-admin-page',
            'lunite-authentication-setting-section',
            [
                'label_for' => 'lunite_sms_carrier',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
            ]
        );

        add_settings_field(
            'lunite-api-options-user-password', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('Password', 'Password'),
            [$this,'luniteOptionUserPassword'],
            'lunite-admin-page',
            'lunite-authentication-setting-section',
            [
                'label_for' => 'lunite_sms_carrier',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
            ]
        );

        add_settings_field(
            'lunite-api-options-mt-port', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('Text Masking', 'Text Masking'),
            [$this,'luniteTextMasking'],
            'lunite-admin-page',
            'lunite-authentication-setting-section',
            [
                'label_for' => 'lunite_sms_carrier',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
            ]
        );

        add_settings_field(
            'lunite-api-options-ssl-enable', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('SSL Enable', 'SSL Enable'),
            [$this,'luniteOptionSSLEnable'],
            'lunite-admin-page',
            'lunite-authentication-setting-section',
            [
                'label_for' => 'lunite_ssl_enable',
                'class' => 'wporg_row',
                'wporg_custom_data' => 'custom',
            ]
        );
    }

    /**
     * @param $args
     * Options User Name
     */
    public function luniteOptionUserName($args)
    {
        $options = get_option('lunite_api_options_sms_user_name'); ?>
      <input  style="width: 100%" id="<?php echo esc_attr($args['label_for']); ?>" name="lunite_api_options_sms_user_name" value="<?php echo isset($options)? $options : '' ?>" type="text" class="form-input" placeholder="User Name" maxlength="255">
      <?php
    }

    /**
     * @param $args
     * Check if the system SSl Enable or not
     */
    public function luniteOptionSSLEnable($args)
    {
        $options = get_option('lunite_api_options_ssl_enable');
        if (empty($options)) {
            $checked = "";
        } else {
            $checked = "checked='checked'";
        } ?>
        <input id="<?php echo esc_attr($args['label_for']); ?>" name="lunite_api_options_ssl_enable" value="1"  <?php echo $checked ?> type="checkbox" class="form-input">
        <?php
    }

    /**
     * @param $args
     * Options Text Masking
     */
    public function luniteTextMasking($args)
    {
        $options = get_option('lunite_api_options_text_masking'); ?>
      <input style="width: 100%"  id="<?php echo esc_attr($args['label_for']); ?>" name="lunite_api_options_text_masking" value="<?php echo isset($options)? $options : '' ?>" type="text" class="form-input" placeholder="Text Masking Name" maxlength="255">
      <?php
    }
    /**
     * @param $args
     * Options User Password
     */
    public function luniteOptionUserPassword($args)
    {
        $options = get_option('lunite_api_options_sms_user_password'); ?>
        <input style="width: 100%"  id="<?php echo esc_attr($args['label_for']); ?>" name="lunite_api_options_sms_user_password" value="<?php echo isset($options)? $options : '' ?>" type="password" class="form-input" placeholder="Password" maxlength="255">
        <?php
    }

    /**
     * @param $args
     * Options lunite Api
     */
    public function luniteApiOptionsSmsCarrier($args)
    {
        $options = get_option('lunite_api_options_sms_carrier'); ?>

      <select
              id="<?php echo esc_attr($args['label_for']); ?>"
              name="lunite_api_options_sms_carrier" class="form-input" style="width: 100%" >

          <option value="ada-dialog-api" <?php echo isset($options) ? (selected($options, 'ada-dialog-api', false)) : (''); ?>>
              <?php esc_html_e('ADA DIALOG API', 'default'); ?>
          </option>
          <option disabled value="mobitel-agency-api" <?php echo isset($options) ? (selected($options, 'mobitel-agency-api', false)) : (''); ?>>
              <?php esc_html_e('MOBITEL AGENCY API', 'default'); ?>
          </option>

      </select>
      <?php
    }


    /**
     * @param $links
     * @return mixed
     * lunite Settings Link
     */
    public function luniteSettingsLink($links)
    {
        $lunite_settings_links = '<a href="options-general.php?page=lunite_plugin_admin_slug">Settings</a>';
        array_push($links, $lunite_settings_links);

        return $links;
    }

    /**
     * @param $args
     * Add Section Echo
     */
    public function luniteSectionEcho($args)
    {
        ?>
            <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Add Your SMS Gateway Authentication Details'); ?></p>
            <?php
    }

    /**
     *add lunite admin page links
     */
    public function luniteAdminPages()
    {
        add_menu_page('Lunite Tunnel', 'Lunite Tunnel', 'manage_options', Lunite_SLUG, [$this,'luniteAdminIndex'], 'dashicons-format-status', 110);
    }

    /**
     * Define Admin template index
     */
    public function luniteAdminIndex()
    {
        require_once(Lunite__PLUGIN_DIR . 'templates/admin.php');
    }


    /**
     * add enqueue for the admin panel
     */
    public function luniteEnqueue()
    {
        wp_enqueue_style('luniteappstyle', plugins_url('lunite/assets/lunite.css', Lunite__PLUGIN_DIR));
    }
}
