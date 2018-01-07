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
    'donations'                   	=> 'Spenden',

    // Description
    'donations_short_desc'        	=> 'Verwalte Spenden für deine Gilde',
    'donations_long_desc'         	=> 'Eine Spendenverwaltung für deine Gilde, welche Zahlungen über PayPal akzeptiert',

    'donations_plugin_not_installed'	=> 'Das Spenden-Plugin ist nicht installiert.',
	'donations_donate'				=> 'Spenden',
	'donations_donationslist'		=> 'Spendenliste einsehen',

    'donations_fs_general'        	=> 'Allgemeines',
    'donations_f_min_value' 		=> 'Mindestbetrag einer Spende',
    'donations_f_free_or_custom'		=> 'Wählbare Beträge',
    'donations_f_custom_values'		=> 'Vorgaben für Spendenbeitrag',
    'donations_f_custom_free'		=> 'Benutzer kann Betrag festlegen',
    'donations_f_custom_custom'		=> 'Benutzer kann aus Vorgaben auswählen',
    'donations_f_help_custom_values'	=> 'Trage hier, durch Strichpunkt getrennt, Werte ein, die Benutzer als Betrag auswählen können',
    'donations_fs_goal'        		=> 'Spendenziel',
    'donations_f_goal_type'			=> 'Spendenziel-Typ auswählen',
    'donations_f_goal_no'        		=> 'Kein Spendenziel',
    'donations_f_goal_monthly'        	=> 'Monatliche Summe',
    'donations_f_goal_fixedsum'        	=> 'Fixe Summe',
    'donations_f_goal_value'        	=> 'Spendenziel',
    'donations_f_goal_display_type'        	=> 'Darstellung des Spendenstatus',
    'donations_f_goal_display_type_progess' => 'Fortschrittsbalken',
    'donations_f_goal_display_type_covered' => 'Noch abgedeckte Monate',
    'donations_fs_paypal'			=> 'PayPal',
    'donations_f_paypal_email'		=> 'Ziel-Emailadresse',
	'donations_f_help_paypal_email' => 'Trage deine PayPal-Email-Adresse ein. Alternativ kannst du auch eine PayPal.me Adresse angeben, z.B. paypal.me/EQdkpPlus. Zahlungen über PayPal.me müssen aber manuell freigeschaltet werden.',
    'donations_f_paypal_currency'		=> 'Währung',
    'donations_fs_texts'			=> 'Informationstexte',
    'donations_f_donation'			=> 'Beschreibung auf der Spendenseite',
    'donations_f_thankyou'			=> 'Dankesnachricht nach erfolgter Spende',
    'donations_f_cancel'			=> 'Nachricht wenn Spende abgebrochen wurde',
    'donations_f_goal_start'		=> 'Start der Spendenkampagne',
    'donations_f_help_goal_start'		=> 'Wird für die Berechnung der noch abgedeckten Monate benötigt',
    'donations_menu'			=> 'Spenden',
    'donation_success_message'		=> 'Vielen Dank für deine Spende! Sobald diese bestätigt wurde, wird sie auf dieser Seite sichtbar sein.',
    'donation_cancel_message'		=> 'Schade, dass du deine Spende abgebrochen hast.',
    'donations_donate_button'		=> 'Jetzt spenden',

    'plugin_statistics_donations'		=> 'Spenden',
    'donations_covered_months_text' 	=> '%d abgedeckte Monate',
    'donations_month' 			=> 'Monat',
    'donations_months' 			=> 'Monate',
    'donations_amount' 			=> 'Betrag',
    'donations_via_paypal' 			=> 'Spenden über PayPal',
    'donations_recent_goal'			=> 'Aktueller Spendenstand',
    'donations_public'			=> 'Öffentliche Spende',
    'donations_wallofdonators'		=> 'Bisherige Spenden',
    'donations_hide_name'			=> 'Name verbergen',

    'donations_manage'				=> 'Spenden verwalten',
    'donations_add'					=> 'Spende/Auszahlung hinzufügen',
    'donations_confirm_delete_donation' => 'Bist du sicher, dass die Spenden %s gelöscht werden sollen?',
    'donation_incomplete_suc'		=> 'Die Spende wurde erfolgreich deaktiviert.',
    'donation_complete_suc'			=> 'Die Spende wurde erfolgreich aktiviert.',
    'donation_complete_selected'	=> 'Ausgewählte aktivieren',
    'donation_provider'				=> 'Zahlungsmethode',
    'donations_method_manual'		=> 'Manuell',

    'donations_ntfy_new_donation'	=> '{PRIMARY} hat eine neue Spende i.H.v. \'{ADDITIONAL}\' getätigt',
    'donations_ntfy_new_donation_grouped' => 'Es wurden {COUNT} neue Spenden getätigt',
    'user_sett_f_ntfy_donations_new_donation' => 'Spenden: neue Spende',

    //Portal
    'donations_donations'		=> 'Spenden',
    'donations_f_show_button'	=> 'Spenden-Button anzeigen',
    'donations_f_show_progress' => 'Spenden-Fortschritt anzeigen',
    'donations_f_text'			=> 'Spenden-Text',
    'donations_months_short'	=> 'Mo.',
		
	'donations_f_show_count'	=> 'Anzahl der letzten angezeigten Spenden',
	'donations_f_text_latest'	=> 'Optionaler Beschreibungstext',
	'latestdonations' 			=> 'Letzte Spenden',
);

?>
