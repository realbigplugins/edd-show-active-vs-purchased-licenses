<?php
/**
 * Creates the Admin Page
 *
 * @since	  {{VERSION}}
 *
 * @package	EDD_Show_Active_vs_Purchased_Licenses
 * @subpackage EDD_Show_Active_vs_Purchased_Licenses/core/admin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EDD_Show_Active_vs_Purchased_Licenses_Admin_Page' ) ) {

	/**
	 * EDD_Show_Active_vs_Purchased_Licenses_Admin_Page class
	 *
	 * @since	  {{VERSION}}
	 */
	final class EDD_Show_Active_vs_Purchased_Licenses_Admin_Page {

        function __construct() {

            add_filter( 'edd_tools_tabs', array( $this, 'add_tools_tab' ) );

            add_filter( 'option_hicpo_options', array( $this, 'force_disable_cpt_ordering_plugin' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_filter( 'edd_product_dropdown_args', array( $this, 'edd_product_dropdown_args' ) );

            add_action( 'edd_tools_tab_show_active_vs_purchased_licenses', array( $this, 'show_tab' ) );

            add_action( 'wp_ajax_edd_show_active_vs_purchased_licenses_get_data', array( $this, 'get_data' ) );

        }

        public function add_tools_tab( $tabs ) {

            $tabs['show_active_vs_purchased_licenses'] = __( 'Show Active vs Purchased Licenses', 'edd-show-active-vs-purchased-licenses' );

            return $tabs;

        }

        public function force_disable_cpt_ordering_plugin( $value ) {

            if ( ! is_admin() ) return $value;

            if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'show_active_vs_purchased_licenses' ) return array();

            return $value;

        }

        public function enqueue_scripts() {

            if ( ! isset( $_GET['tab'] ) || $_GET['tab'] !== 'show_active_vs_purchased_licenses' ) return;

            wp_enqueue_script( 'edd-show-active-vs-purchased-licenses-admin' );

        }

        public function edd_product_dropdown_args( $args ) {

            if ( ! isset( $_GET['tab'] ) || $_GET['tab'] !== 'show_active_vs_purchased_licenses' ) return $args;

            $args['post_status'] = 'publish';

            return $args;

        }

        public function show_tab() {

            require_once __DIR__ . '/views/html-admin-page.php';

        }

        public function get_data() {

            if ( ! check_admin_referer( 'edd_show_active_vs_purchased_licenses_get_data', 'nonce' ) ) {
                header( 'HTTP/1.1 500 ' . __( 'Invalid Nonce sent', 'edd-show-active-vs-purchased-licenses' ) );
                header( 'Content-Type: application/json; charset=UTF-8' );
                wp_send_json_error();
            }

            if ( ! isset( $_REQUEST['download_id'] ) || ! $_REQUEST['download_id'] ) {
                header( 'HTTP/1.1 500 ' . __( 'A Download ID was not sent', 'edd-show-active-vs-purchased-licenses' ) );
                header( 'Content-Type: application/json; charset=UTF-8' );
                wp_send_json_error();
            }

            $download_id = $_REQUEST['download_id'];

            // Parent holds Bundle Subscription
            $licenses = edd_software_licensing()->licenses_db->get_licenses( array( 'download_id' => $download_id, 'number' => -1 ) );

            $data = array(
               'totals' => array(
                   'sales' => count( $licenses ),
                   'total_limited' => 0,
                   'total_unlimited' => 0,
                   'active_limited' => 0,
                   'active_unlimited' => 0,
                   'active_unlimited_total' => 0,
                   'revenue' => 0,
                   'commissions' => 0,
                   'referrals' => 0,
                   'gross' => 0,
                ),
               'standalone' => array(
                   'sales' => 0,
                   'total_limited' => 0,
                   'total_unlimited' => 0,
                   'active_limited' => 0,
                   'active_unlimited' => 0,
                   'active_unlimited_total' => 0,
                   'revenue' => 0,
                   'commissions' => 0,
                   'referrals' => 0,
                   'gross' => 0,
                ),
               'bundled' => array(
                   'sales' => 0,
                   'total_limited' => 0,
                   'total_unlimited' => 0,
                   'active_limited' => 0,
                   'active_unlimited' => 0,
                   'active_unlimited_total' => 0,
                   'revenue' => 0,
                   'commissions' => 0,
                   'referrals' => 0,
                   'gross' => 0,
                ),
            );
            
            foreach ( $licenses as $license ) {

                $payment = edd_get_payment( $license->payment_id );

                if ( $payment->total == 0 ) {
                    $data['totals']['sales']--;
                    continue;
                }

                $data['totals']['revenue'] += $payment->total;

                $license_parent = false;
                $license_parent_id = $license->parent;

                while ( $license_parent_id > 0 ) {

                    $license_parent = edd_software_licensing()->get_license( $license->parent );

                    $license_parent_id = $license_parent->parent;

                }

                $commissions = 0;

                if ( function_exists( 'edd_commissions' ) ) {

                    $commission_download = $download_id;

                    if ( $license_parent ) {
                        // Get the Top-Most Parent Download, in order to find our Bundle
                        $commission_download = $license_parent->download_id;
                    }

                    $paid_commissions = edd_commissions()->commissions_db->get_commissions( array( 'download_id' => $commission_download, 'payment_id' => $license->payment_id, 'status' => 'paid' ) );

                    foreach ( $paid_commissions as $commission ) {

                        $commissions += $commission->amount;

                    }

                }

                $data['totals']['commissions'] += $commissions;

                $referrals = 0;

                if ( function_exists( 'affiliate_wp' ) ) {

                    $search = htmlentities2( get_the_title( $license->download_id ) );

                    if ( $license_parent ) {
                        $search = htmlentities2( get_the_title( $license_parent->download_id ) );
                    }

                    // Since we're checking the Payment ID, we do not have to worry about cases where they've bought the bundle not showing up
                    // However, this could cause edge cases where if two Downloads were in the same purchase with different Referral payouts, they would both be calculated.
                    // We check against the Download Title to attemtp to mitigate this, but depending on how things are calculated it could still be incorrect (If the Row saves the Referral for all Downloads in a Purchase instead of per-Download)
                    $paid_referrals = affiliate_wp()->referrals->get_referrals( array( 'reference' => $license->payment_id, 'description' => $search, 'context' => 'edd', 'status' => 'paid' ) );

                    foreach ( $paid_referrals as $referral ) {

                        $referrals += $referral->amount;

                    }

                }

                $data['totals']['referrals'] += $referrals;

                // If unlimited, this is 0 so it is fine to add it here
                $data['totals']['total_limited'] += $license->activation_limit;

                if ( $license->activation_limit == 0 ) {
                    $data['totals']['total_unlimited']++;
                    $data['totals']['active_unlimited'] = ( $license->activation_count > 0 ) ? $data['totals']['active_unlimited'] + 1 : $data['totals']['active_unlimited'];
                    $data['totals']['active_unlimited_total'] += $license->activation_count;
                }
                else {   
                    $data['totals']['active_limited'] += $license->activation_count;
                }

                if ( $license->parent > 0 ) {

                    $data['bundled']['sales']++;

                    $data['bundled']['commissions'] += $commissions;
                    $data['bundled']['referrals'] += $referrals;

                    $data['bundled']['revenue'] += $payment->total;

                    $data['bundled']['total_limited'] += $license->activation_limit;

                    if ( $license->activation_limit == 0 ) {
                        $data['bundled']['total_unlimited']++;
                        $data['bundled']['active_unlimited'] = ( $license->activation_count > 0 ) ? $data['bundled']['active_unlimited'] + 1 : $data['bundled']['active_unlimited'];
                        $data['bundled']['active_unlimited_total'] += $license->activation_count;
                    }
                    else {
                        $data['bundled']['active_limited'] += $license->activation_count;
                    }
                    
                }
                else {

                    $data['standalone']['sales']++;

                    $data['standalone']['commissions'] += $commissions;
                    $data['standalone']['referrals'] += $referrals;

                    $data['standalone']['revenue'] += $payment->total;

                    $data['standalone']['total_limited'] += $license->activation_limit;

                    if ( $license->activation_limit == 0 ) {
                        $data['standalone']['total_unlimited']++;
                        $data['standalone']['active_unlimited'] = ( $license->activation_count > 0 ) ? $data['standalone']['active_unlimited'] + 1 : $data['standalone']['active_unlimited'];
                        $data['standalone']['active_unlimited_total'] += $license->activation_count;
                    }
                    else {
                        $data['standalone']['active_limited'] += $license->activation_count;
                    }

                }

            }

            $data['totals']['gross'] = $data['totals']['revenue'] - $data['totals']['commissions'] - $data['totals']['referrals'];
            $data['bundled']['gross'] = $data['bundled']['revenue'] - $data['bundled']['commissions'] - $data['bundled']['referrals'];
            $data['standalone']['gross'] = $data['standalone']['revenue'] - $data['standalone']['commissions'] - $data['standalone']['referrals'];

            ob_start();

            include __DIR__ . '/views/html-ajax-response.php';

            $html = ob_get_clean();

            wp_send_json_success( array( 'response' => $html ) );

        }

    }

}

$instance = new EDD_Show_Active_vs_Purchased_Licenses_Admin_Page();