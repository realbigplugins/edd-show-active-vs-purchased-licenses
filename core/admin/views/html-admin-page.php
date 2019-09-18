<?php
/**
 * Holds the Admin Page HTML
 *
 * @since	  {{VERSION}}
 *
 * @package	EDD_Show_Active_vs_Purchased_Licenses
 * @subpackage EDD_Show_Active_vs_Purchased_Licenses/core/admin/views
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$download_query = new WP_Query( array(
    'post_type' => 'download',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'order' => 'ASC',
    'orderby' => 'title',
    'meta_query' => array(
        array(
            'key' => '_edd_product_type',
            'compare' => 'NOT EXISTS',
        ),
    ),
) );

$downloads = wp_list_pluck( $download_query->posts, 'post_title', 'ID' );

wp_nonce_field( 'edd_show_active_vs_purchased_licenses_get_data', 'edd-show-active-vs-purchased-licenses-nonce' );

echo EDD()->html->product_dropdown( array(
    'name' => 'download_id',
    'id' => 'edd-show-active-vs-purchased-licenses-download',
    'chosen' => true,
    'bundles' => false,
    'post_status' => 'publish',
) );

?>

<div id="edd-show-active-vs-purchased-licenses-results"></div>