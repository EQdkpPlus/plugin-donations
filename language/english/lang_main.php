<?php
/*	Project:	EQdkp-Plus
 *	Package:	Donations Plugin
*	Link:		http://eqdkp-plus.eu
*
*	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
*
*	This program is free software: you can redistribute it and/or modify
*	it under the terms of the GNU Affero General Public License as published
*	by the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*	GNU Affero General Public License for more details.
*
*	You should have received a copy of the GNU Affero General Public License
*	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('EQDKP_INC'))
{
	header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
    'donations'                   	=> 'Donations',

    // Description
    'donations_short_desc'        	=> 'Manage donations for your guild',
    'donations_long_desc'         	=> 'A donation management for your guild, which accepts payments via PayPal',

    'donations_plugin_not_installed'	=> 'The Donations plugin is not installed.',
		'donations_donate'				=> 'Donate',
		'donations_donationslist'		=> 'View Donationslist',

    'donations_fs_general'        	=> 'General',
    'donations_f_min_value' 		=> 'Minimum value for donations',
    'donations_f_free_or_custom'		=> 'Selectable values',
    'donations_f_custom_values'		=> 'Predefined values',
    'donations_f_custom_free'		=> 'The user can freely choose the value',
    'donations_f_custom_custom'		=> 'Possible donation values',
    'donations_f_help_custom_values'	=> 'Insert the possible values for donations, separated by semicolons',
    'donations_fs_goal'        		=> 'Donation Goal',
    'donations_f_goal_type'			=> 'Select the type of donation goal',
    'donations_f_goal_no'        		=> 'No goal',
    'donations_f_goal_monthly'        	=> 'Monthly amount',
    'donations_f_goal_fixedsum'        	=> 'Total amount',
    'donations_f_goal_value'        	=> 'Donation goal',
    'donations_f_goal_display_type'        	=> 'Presentation of the donation status',
    'donations_f_goal_display_type_progess' => 'Progress bar',
    'donations_f_goal_display_type_covered' => 'Covered months',
    'donations_fs_paypal'			=> 'PayPal',
    'donations_f_paypal_email'		=> 'Recipients address',
	'donations_f_help_paypal_email' => 'Insert your PayPal Email-address. Also, you can enter your PayPal.me address, e.g. paypal.me/EQdkpPlus. Donations using PayPal.me must be activated manually.',
    'donations_f_paypal_currency'		=> 'Currency',
    'donations_fs_texts'			=> 'Information texts',
    'donations_f_donation'			=> 'Description on the donation page',
    'donations_f_thankyou'			=> 'Acknowledgment notice after a successfull donation',
    'donations_f_cancel'			=> 'Notice when a donation is interrupted',
    'donations_f_goal_start'		=> 'Start of the donation campaign',
    'donations_f_help_goal_start'		=> 'Will be used to calculate the covered months',
    'donations_menu'			=> 'Donations',
    'donation_success_message'		=> 'Thank you very much for your donation! It will be visible here as soon as it is verified.',
    'donation_cancel_message'		=> 'Too bad that you canceled your donation.',
    'donations_donate_button'		=> 'Donate now',

    'plugin_statistics_donations'		=> 'Donations',
    'donations_covered_months_text' 	=> '%d covered months',
    'donations_month' 			=> 'Month',
    'donations_months' 			=> 'Months',
    'donations_amount' 			=> 'Value',
    'donations_via_paypal' 			=> 'Donations via PayPal',
    'donations_recent_goal'			=> 'Recent donation goal',
    'donations_public'			=> 'Public donation',
    'donations_wallofdonators'		=> 'Recent donations',
    'donations_hide_name'			=> 'Hide name',

    'donations_manage'				=> 'Manage donations',
    'donations_add'					=> 'Add donation/payoff',
    'donations_confirm_delete_donation' => 'Are you sure, that you want to delete this donations %s?',
    'donation_incomplete_suc'		=> 'This donation was successfully disabled.',
    'donation_complete_suc'			=> 'This donation was successfully enabled.',
    'donation_complete_selected'	=> 'Enable selected',
    'donation_provider'				=> 'Payment method',
    'donations_method_manual'		=> 'Manual',

    'donations_ntfy_new_donation'	=> '{PRIMARY} has donated \'{ADDITIONAL}\'',
    'donations_ntfy_new_donation_grouped' => 'There are {COUNT} new donations',
    'user_sett_f_ntfy_donations_new_donation' => 'New donation',
	'user_sett_f_ntfy_donations' => 'Donations',

    //Portal
    'donations_donations'		=> 'Donations',
    'donations_f_show_button'	=> 'Show donation button',
    'donations_f_show_progress' => 'Show donation progress',
    'donations_f_text'			=> 'Donation text',
    'donations_months_short'	=> 'Mo.',
		
	'donations_f_show_count'	=> 'Count of latest donations',
	'donations_f_text_latest'	=> 'Descriptiontext (optional)',
	'latestdonations' 			=> 'Latest donations',
);

?>
