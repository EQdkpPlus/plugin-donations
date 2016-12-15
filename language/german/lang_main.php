<?php
/*	Project:	EQdkp-Plus
 *	Package:	MediaCenter Plugin
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
		
  'donations_fs_general'        	=> 'Allgemeines',
  'donations_f_min_value' 		=> 'Mindestbetrag einer Spende',
  'donations_f_free_or_custom'		=> 'Betrag auswählbar',
 'donations_f_custom_values'		=> 'Vorgaben für Spendenbeitrag',
'donations_f_custom_free'		=> 'Betrag selber bestimmen',
'donations_f_custom_custom'		=> 'Betrag aus Vorgabe auswählen',
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
'donations_f_paypal_currency'		=> 'Währung',
'donations_fs_texts'			=> 'Informationstexte',
'donations_f_donation'			=> 'Beschreibung auf der Spendenseite',
'donations_f_thankyou'			=> 'Dankesnachricht nach erfolgter Spende',
'donations_f_cancel'			=> 'Nachricht wenn Spende abgebrochen wurde',
'donations_f_goal_start'		=> 'Start der Spendenkampagne',
'donations_f_help_goal_start'	=> 'Wird für die Berechnung der noch abgedeckten Monate benötigt',
'donations_menu'				=> 'Spenden',
		
		
"plugin_statistics_donations" => "Spenden",
);

?>
