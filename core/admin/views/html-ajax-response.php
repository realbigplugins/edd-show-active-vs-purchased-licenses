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

?>

<table class="widefat" style="width: auto; margin-top: 1rem;">
    <thead>
        <tr>
            <th colspan="2"><strong><?php _e( 'Totals for both Bundles and Individual Sales', 'edd-show-active-vs-purchased-licenses' ); ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><strong><?php _e( 'Total Number of Sales: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['sales']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['total_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Distributed Licenses (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['active_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Unlimited) (Includes only the Unlimited Licenses sold): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['total_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Licenses (Unlimited) (Not counting how many times they\'ve been used): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['active_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Uses of Active Licenses (Unlimited) (Includes every single activation): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['totals']['active_unlimited_total']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Revenue: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['totals']['revenue'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Commissions: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['totals']['commissions'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Referrals: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['totals']['referrals'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Gross Profit: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['totals']['gross'] ) ); ?></td>
        </tr>
    </tbody>
</table>

<table class="widefat" style="width: auto; margin-top: 1rem;">
    <thead>
        <tr>
            <th colspan="2"><strong><?php _e( 'Totals for Bundle Sales Only', 'edd-show-active-vs-purchased-licenses' ); ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><strong><?php _e( 'Total Number of Sales: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['sales']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['total_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Distributed Licenses (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['active_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Unlimited) (Includes only the Unlimited Licenses sold): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['total_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Licenses (Unlimited) (Not counting how many times they\'ve been used): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['active_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Uses of Active Licenses (Unlimited) (Includes every single activation): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['bundled']['active_unlimited_total']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Revenue: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['bundled']['revenue'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Commissions: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['bundled']['commissions'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Referrals: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['bundled']['referrals'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Gross Profit: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['bundled']['gross'] ) ); ?></td>
        </tr>
    </tbody>
</table>

<table class="widefat" style="width: auto; margin-top: 1rem;">
    <thead>
        <tr>
            <th colspan="2"><strong><?php _e( 'Totals for Individual Sales Only', 'edd-show-active-vs-purchased-licenses' ); ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><strong><?php _e( 'Total Number of Sales: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['sales']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['total_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Distributed Licenses (Limited): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['active_limited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Licenses Distributed (Unlimited) (Includes only the Unlimited Licenses sold): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['total_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Active Licenses (Unlimited) (Not counting how many times they\'ve been used): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['active_unlimited']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Number of Uses of Active Licenses (Unlimited) (Includes every single activation): ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo $data['standalone']['active_unlimited_total']; ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Revenue: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['standalone']['revenue'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Commissions: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['standalone']['commissions'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Paid Referrals: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['standalone']['referrals'] ) ); ?></td>
        </tr>
        <tr>
            <th><strong><?php _e( 'Total Gross Profit: ', 'edd-show-active-vs-purchased-licenses' ); ?></strong></th>
            <td><?php echo edd_currency_filter( edd_format_amount( $data['standalone']['gross'] ) ); ?></td>
        </tr>
    </tbody>
</table>