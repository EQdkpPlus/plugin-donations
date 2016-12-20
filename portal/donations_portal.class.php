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
  | donations_portal
  +--------------------------------------------------------------------------*/
class donations_portal extends portal_generic{

	/**
	* Portal path
	*/
	protected static $path = 'donations';
	/**
	* Portal data
	*/
/**
	* Portal data
	*/
	protected static $data = array(
		'name'			=> 'Donations Module',
		'version'		=> '0.1.0',
		'author'		=> 'GodMod',
		'contact'		=> 'https://eqdkp-plus.eu',
		'description'	=> 'Displays donation standing, donation button and some text',
		'lang_prefix'	=> 'donations_',
		'multiple'		=> false,
	);
	
	protected static $apiLevel = 20;

	protected static $multiple = false;
	
	public function get_settings($state){
		
		$settings = array(
				'show_button'	=> array(
						'type'		=> 'radio',
						'default'	=> 1,
				),
				'show_progress'	=> array(
						'type'		=> 'radio',
						'default'	=> 1,
				),
				'text' => array(
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
		$strText = $this->config('text');
		$output = "";
		if($strText && strlen($strText)){
			$output .= $this->bbcode->toHTML($strText);	
		}
		
		if($this->config('show_progress') || $this->config('show_progress') === false){
			$strGoalType = $this->config->get('goal_type', 'donations');
			if ($strGoalType == 'no'){
				//Sum up and display value only
				$strCurrency = $this->config->get('paypal_currency', 'donations');
			
				$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
			
				$fltTotalSum = 0;
			
				foreach($arrCompletedIDs as $donationID){
					$myval = (float)$this->pdh->get('donations', 'amount', array($donationID));
					$myval = round($myval, 2);
					$fltTotalSum += $myval;
				}
				
				$output .= '<br /><br /><div class="sum_only"><b style="font-weight: bold;">'.$this->user->lang('donations_recent_goal').'</b>: <div style="font-size:1em;" class="bubble">';
				$output .= number_format(round($fltTotalSum, 2),2).' '.$strCurrency.'</div></div>';
			
			} elseif($strGoalType == 'monthly_sum'){
				$strDisplayType = $this->config->get('goal_display_type', 'donations');
				if($strDisplayType == 'covered_months'){
					//Covered Months
					$intStartdate = $this->config->get('goal_start', 'donations');
					$fltGoalValue = round($this->config->get('goal_value', 'donations'), 2);
						
					$min_date = min($intStartdate, time());
					$max_date = max($intStartdate, time());
					$i = 0;
						
					while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
						$i++;
					}
			
					$intCoveredMonths = $fltTotalSum = 0;
					$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
					foreach($arrCompletedIDs as $donationID){
						$myval = (float)$this->pdh->get('donations', 'amount', array($donationID));
						$myval = round($myval, 2);
						$fltTotalSum += $myval;
					}
						
					$fltSumMinus = $fltTotalSum - ($fltGoalValue * $i);
						
					if($fltSumMinus > 0){
						$intCoveredMonths = floor($fltSumMinus / $fltGoalValue);
					}
			
					$intClass = ($intCoveredMonths > 6) ? 6 : $intCoveredMonths;
					$strCovered = sprintf($this->user->lang('donations_covered_months_text'), $intCoveredMonths);
					
					$this->tpl->add_css('.portal.donationtable {
    text-align: center;
}

.portal .donationBar {
  background-color: #333333;
  border-radius: 5px;
  height: 20px;
  padding: 3px;
  box-shadow: inset 0 1px 5px rgba(0, 0, 0, .5), 0 1px 0 rgba(255, 255, 255, .5);
  margin: 0px;
  margin-bottom: 10px;
}

/* tiefrot */
.portal .months_0 > span {
    background-color: #ff0000;
      background-image: -webkit-linear-gradient(#ff0000,#cc0000);
      background-image: linear-gradient(#ff0000,#cc0000);
    width: 2% !important;
}

/* helleres rot */
.portal .months_1 > span {
    background-color: #ff3600;
      background-image: -webkit-linear-gradient(#ff3600,#cc2b00);
      background-image: linear-gradient(#ff3600,#cc2b00);
    width: 16% !important;
}

.portal .months_2 > span {
    background-color: #ff6c00;
      background-image: -webkit-linear-gradient(#ff6c00,#cc5600);
      background-image: linear-gradient(#ff6c00,#cc5600);
    width: 32% !important;
}

.portal .months_3 > span {
    background-color: #ffba00;
      background-image: -webkit-linear-gradient(#ffba00,#e6a700);
      background-image: linear-gradient(#ffba00,#e6a700);
    width: 48% !important;
}

.portal .months_4 > span {
    background-color: #fff600;
      background-image: -webkit-linear-gradient(#fff600,#e6dd00);
      background-image: linear-gradient(#fff600,#e6dd00);
    width: 64% !important;
}

.portal .months_5 > span {
    background-color: #baff00;
      background-image: -webkit-linear-gradient(#baff00,#a7e600);
      background-image: linear-gradient(#baff00,#a7e600);
    width: 80% !important;
}

/* hellgruen */
.portal .months_6 > span {
  background-color: #a5df41;
  background-image: -webkit-linear-gradient(#a5df41,#4ca916);
  background-image: linear-gradient(#a5df41,#4ca916);
  width: 100% !important;
}

.portal .donationBar > span {
  border-radius: 3px;
  color: transparent;
  display: block;
  height: 100%;
  max-width: 100%;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, .5);
width: 1%;
}');
					
					
$output .= '<br /><table width="95%" class="portal donationtable">
	<tbody><tr>
		<td>1 '.$this->user->lang('donations_months_short').'</td>
		<td>2 '.$this->user->lang('donations_months_short').'</td>
		<td>3 '.$this->user->lang('donations_months_short').'</td>
		<td>4 '.$this->user->lang('donations_months_short').'</td>
		<td>5 '.$this->user->lang('donations_months_short').'</td>
		<td>6 '.$this->user->lang('donations_months_short').'</td>
	</tr>
</tbody></table>

<p class="donationBar months_'.$intClass.'">
	<span></span>
</p>
<center>'.$strCovered.'</center>';
			
				} else {
					//Progressbar
					$fltGoalValue = round($this->config->get('goal_value', 'donations'), 2);
					$strCurrency = $this->config->get('paypal_currency', 'donations');
			
					$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
			
					$fltTotalSum = 0;
			
					$currentMonth = date('m');
					$currentYear = date('Y');
			
					foreach($arrCompletedIDs as $donationID){
						$date = $this->pdh->get('donations', 'date', array($donationID));
						$myCurrentMonth = date('m', $date);
						$myCurrentYear = date('Y', $date);
						if($myCurrentMonth != $currentMonth || $myCurrentYear != $currentYear) continue;
			
						$myval = (float)$this->pdh->get('donations', 'amount', array($donationID));
						$myval = round($myval, 2);
						$fltTotalSum += $myval;
					}
			
					$percent = ($fltTotalSum <= 0) ? 0 : (($fltTotalSum / $fltGoalValue)*100);
					$percent = $displayPercent = round($percent,0);
					if($percent > 100) $percent = 100;
			
					$strDonationMonth = $this->time->date('F Y');
					$strProgressbar = $this->jquery->progressbar('donate_portal_progressbar_fixedsum', $percent, array('text' => $displayPercent.'% ('.number_format($fltTotalSum,2).' '.$strCurrency.' / '.number_format($fltGoalValue,2).' '.$strCurrency.')'));
					
					$output .= '<br /><br /><div class="sum_monthly"><b style="font-weight: bold;">'.$strDonationMonth.'</b>:<br />';
					$output .= $strProgressbar.'</div>';
						
				} 
			} else {
				//Fixed Sum
				//Sum up and display progress
				$fltGoalValue = round($this->config->get('goal_value', 'donations'), 2);
				$strCurrency = $this->config->get('paypal_currency', 'donations');
				
				$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
		
				$fltTotalSum = 0;
		
				foreach($arrCompletedIDs as $donationID){
					$myval = (float)$this->pdh->get('donations', 'amount', array($donationID));
					$myval = round($myval, 2);
					$fltTotalSum += $myval;
				}
				
				$percent = ($fltTotalSum <= 0) ? 0 : (($fltTotalSum / $fltGoalValue)*100);
				$percent = $displayPercent = round($percent,0);
				if($percent > 100) $percent = 100;
				
				$strProgressbar = $this->jquery->progressbar('donate_portal_progressbar_fixedsum', $percent, array('text' => $displayPercent.'% ('.number_format($fltTotalSum,2).' '.$strCurrency.' / '.number_format($fltGoalValue,2).' '.$strCurrency.')'));
				$output .= '<br /><br /><div class="sum_fixed">';
				$output .= $strProgressbar.'</div>';
			}
		}
		
		if(($this->config('show_button') || $this->config('show_button') === false) && $this->user->check_auth('u_donations_donate', false)){
			$output .= '<br /><br /><div class="center"><a href="'.$this->routing->build('donate').'" class="button">'.$this->user->lang('donations_donate_button').'</a></div><br />';
		}
		
		return $output;
	}
}

?>