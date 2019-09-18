<?php
/**
 * Provides helper functions.
 *
 * @since	  {{VERSION}}
 *
 * @package	EDD_Show_Active_vs_Purchased_Licenses
 * @subpackage EDD_Show_Active_vs_Purchased_Licenses/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		{{VERSION}}
 *
 * @return		EDD_Show_Active_vs_Purchased_Licenses
 */
function EDDSHOWACTIVEVSPURCHASEDLICENSES() {
	return EDD_Show_Active_vs_Purchased_Licenses::instance();
}