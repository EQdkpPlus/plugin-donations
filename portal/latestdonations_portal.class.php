<?php
/*	Project:	EQdkp-Plus
 *	Package:	mc_featured_media Plugin
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

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found'); exit;
}

/*+----------------------------------------------------------------------------
  | latestdonations_portal
  +--------------------------------------------------------------------------*/
class latestdonations_portal extends portal_generic{

	/**
	* Portal path
	*/
	protected static $path = 'latestdonations';
	/**
	* Portal data
	*/
/**
	* Portal data
	*/
	protected static $data = array(
		'name'			=> 'Latest Donations Module',
		'version'		=> '0.1.0',
		'author'		=> 'GodMod',
		'contact'		=> 'https://eqdkp-plus.eu',
		'description'	=> 'Displays latest donations',
		'lang_prefix'	=> 'donations_',
		'multiple'		=> false,
	);
	
	protected static $apiLevel = 20;

	protected static $multiple = false;
	
	public function get_settings($state){
		
		$settings = array(
				'show_count'	=> array(
						'type'		=> 'spinner',
						'default'	=> 5,
				),
				'text_latest' => array(
						'type' => 'bbcodeeditor',
				)
		);
		
		return $settings;
	}

	/**
	* output
	* Get the portal output
	*
	* @returns string
	*/
	public function output(){
		$strText = $this->config('text_latest');
		$output = "";
		if($strText && strlen($strText)){
			$output .= $this->bbcode->toHTML($strText);	
		}
		
		$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
		$intMax = intval($this->config('show_count'));
		$intCount = 0;
		
		$output .= '<table class="table fullwidth colorswitch">';
		foreach($arrCompletedIDs as $donationID){
			if($intCount >= $intMax) break;
			
			//Datum, Benutzername, Wert, Kommentar
			$arrData = $this->pdh->get('donations', 'data', array($donationID));
		
			if($arrData['user_id'] > 0){
				$username = $this->pdh->get('user', 'name', array($arrData['user_id']));
				$strUsername = '<a href="'.register('routing')->build('user', $username, 'u'.$arrData['user_id']).'" data-user-id="'.$arrData['user_id'].'">'.$username.'</a>';
			} else {
				$strUsername = sanitize($arrData['username']);
			}
		
		
			$fltAmount = number_format(round($arrData['amount'],2),2);
			$class = ($fltAmount < 0) ? 'negative' : 'positive';
			
			$intCount++;
			
			$output .= '<tr><td>'.$this->time->user_date($arrData['date']).'</td><td>'.(($arrData['public']) ? $strUsername : '<i style="font-style:italic;">Anonymous</i>').'</td><td><span class="'.$class.'">'.$fltAmount.'</span> '.sanitize($arrData['currency']).'</td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
}

?>