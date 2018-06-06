<?php
/**
 * Plugin Name:          WooCommerce Correios
 * Plugin URI:           https://github.com/claudiosanches/woocommerce-correios
 * Description:          Adds Correios shipping methods to your WooCommerce store.
 * Author:               Claudio Sanches
 * Author URI:           https://claudiosanches.com
 * Version:              3.7.1
 * License:              GPLv2 or later
 * Text Domain:          woocommerce-correios
 * Domain Path:          /languages
 * WC requires at least: 3.0.0
 * WC tested up to:      3.4.0
 *
 * WooCommerce Correios is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WooCommerce Correios is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce Correios. If not, see
 * <https://www.gnu.org/licenses/gpl-2.0.txt>.
 *
 * @package WooCommerce_Correios
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('admin_head', 'styling_order_dispatched' );
function styling_order_dispatched() { ?>
    <style>
        .order-status.status-order-dispatched{
			background: #ecc119;
			color:#00416b;
		}
    </style>
    
	<?php
}

function register_dispatched_order_status() {
    register_post_status( 'wc-order-dispatched', array(
        'label'                     => __('Order Dispatched','woocommerce-correios'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( "Order Dispatched <span class=\"count\">(%s)</span>", "Orders Dispatcheds <span class=\"count\">(%s)</span>", 'woocommerce-correios' )
    ) );
}
add_action( 'init', 'register_dispatched_order_status' );

// Add to list of WC Order statuses
function add_dispatched_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-order-dispatched'] = __('Order Dispatched','woocommerce-correios');
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_dispatched_to_order_statuses' );

define( 'WC_CORREIOS_VERSION', '3.7.1' );
define( 'WC_CORREIOS_PLUGIN_FILE', __FILE__ );

if ( ! class_exists( 'WC_Correios' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-correios.php';

	add_action( 'plugins_loaded', array( 'WC_Correios', 'init' ) );
}
