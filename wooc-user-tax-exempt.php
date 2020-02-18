<?php
/**
 * Plugin Name:     Wooc User Tax Exempt
 * Plugin URI:      https://elliottrichmond.co.uk
 * Description:     A simple WooCommerce plugin to exempt users from additional tax charges
 * Author:          Elliott Richmond
 * Author URI:      https://elliottrichmond.co.uk
 * Text Domain:     wooc-user-tax-exempt
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wooc_User_Tax_Exempt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if WooCommerce is an inactive and die 
 *
 */
function is_woocommerce_active() {
    if (is_plugin_inactive( 'woocommerce/woocommerce.php' )) {
        wp_die('WooCommerce needs to be activated before running this plugin!');
    }     
}
register_activation_hook( __FILE__, 'is_woocommerce_active' );

/**
 * Callback to render the form in the backend
 * 
 * @param   object  $user   the current user
 * 
 */
function user_profile_form_callback(WP_User $user) {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wooc-user-tax-exempt/wooc-user-tax-exempt-form.php';

}
add_action('show_user_profile', 'user_profile_form_callback'); // editing your own profile
add_action('edit_user_profile', 'user_profile_form_callback'); // editing another user

/**
 * Update the users meta when editing the users profile
 * 
 * @param   int $user_id    the users ID
 */
function user_profile_update_callback($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }
    
    update_user_meta($user_id, 'user_is_vat_exempt', $_POST['user_is_vat_exempt']);
}
add_action('personal_options_update', 'user_profile_update_callback');
add_action('edit_user_profile_update', 'user_profile_update_callback');

/**
 * WooCommerce filter hook that checks if the
 * WooCommerce setting for tax charges is enabled
 */
function wooc_user_tax_exempt_callback($wc_tax_enabled) {

    $user = wp_get_current_user();
    $vat_exempt = get_user_meta($user->ID, 'user_is_vat_exempt', true); 
    if ($vat_exempt != null) {
        $wc_tax_enabled = false;
    }
    return $wc_tax_enabled;

}
add_filter( 'wc_tax_enabled', 'wooc_user_tax_exempt_callback', 10, 1);
