<?php
/*	Project:	EQdkp-Plus
 *	Package:	GuildRequest Plugin
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

class donate_pageobject extends pageobject
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('email' => 'MyMailer');
    return array_merge(parent::__shortcuts(), $shortcuts);
  }
  
  /**
   * Constructor
   */
  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('donations', PLUGIN_INSTALLED))
      message_die($this->user->lang('donations_plugin_not_installed'));
	
    $handler = array(
    	'donate' => array('process' => 'donate', 'csrf' => true),
    );
    parent::__construct(false, $handler);

    $this->process();
  }


  public function donate(){
  	$this->user->check_auth('u_donations_donate');
  	
  	//Check Captcha for Guests
  	if(!$this->user->is_signedin()){
  		if($this->config->get('enable_captcha')){
  			require($this->root_path.'libraries/recaptcha/recaptcha.class.php');
  			$captcha = new recaptcha;
  			$response = $captcha->check_answer($this->config->get('lib_recaptcha_pkey'), $this->env->ip, $this->in->get('g-recaptcha-response'));
  			if (!$response->is_valid) {
  				$this->core->message($this->user->lang('lib_captcha_wrong'), $this->user->lang('error'), 'red');
  				$this->display;
  				return;
  			}
  		}
  	}
  	
  	$minValue = $this->config->get('min_value', 'donations');
  	
  	$strUsername = $this->in->get('username');
  	$fltValue = $this->in->get('value', 0.0);
  	
  	if($fltValue < $minValue) $this->display();
	
  	$strToken = md5(rand());
  	
  	$intID = $this->pdh->put('donations', 'add', array($this->user->id, $strUsername, $fltValue, $this->config->get('paypal_currency', 'donations'),
  	$this->in->get('description'), $this->in->get('public', 0), $strToken, 'paypal', 'start', 0		
  	));
  	$this->pdh->process_hook_queue();
  	
  	if(!$intID) $this->display();

	$strPayPalItemname = $this->user->lang('donations_menu').': '.$this->config->get('guildtag');
	$strGoalType = $this->config->get('goal_type', 'donations');
	if($strGoalType == 'monthly_sum'){
		$strPayPalItemname .= ' ('.$this->time->date('F Y').')';
	} elseif($strGoalType == 'fixed_sum'){
		$fltGoalValue = round($this->config->get('goal_value', 'donations'), 2);
		$strPayPalItemname .= ' ('.$fltGoalValue.' '.$this->config->get('paypal_currency', 'donations').' Goal)';
	}

  	
  	$this->tpl->assign_vars(array(
  			'S_DONATE_REDIRECT' 	=> true,
  			'S_DONATE_BASIC_URL'	=> $this->env->buildlink().$this->routing->build('Donate', '', '', true, true),
  			'AMOUNT' 		=> str_replace(',', '.', $fltValue),
  			'TARGET_ADDRESS' 	=> $this->config->get('paypal_email', 'donations'),
  			'CURRENCY' 		=> $this->config->get('paypal_currency', 'donations'),
  			'NOTIFY_URL' 		=> $this->env->buildlink().'plugins/donations/paypal_callback.php',
  			'DONATE_TOKEN' 		=> $intID.':'.$strToken,
  			'DONATE_ID'		=> $intID,
			'DONATION_ITEM' 	=> $strPayPalItemname,
  			'PAYPAL_URL'		=> (defined('DEBUG') > 3 || defined('DEBUG_PAYPAL')) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr',
  	));
  	
  	
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('donations_menu'),
  			'template_path' => $this->pm->get_data('donations', 'template_path'),
  			'template_file' => 'donate.html',
  			'display'       => true
  	));
  }
  
  public function display()
  {
	$this->user->check_auths(array('u_donations_donate', 'u_donations_donationlist'), 'or');
	
	//Cancel Message
	if($this->in->get('cancel')){
		$intID = $this->in->get('cancel');
		$this->pdh->put('donations', 'updatedStatus', array($intID, 'canceled'));
		$this->pdh->process_hook_queue();
		$this->tpl->assign_vars(array(
				'S_DONATE_CANCEL' => true,
				'DONATION_CANCEL_TEXT' => $this->bbcode->toHTML($this->config->get('cancel', 'donations')),
		));
	}
	
	//Success Message
	if($this->in->get('success')){
		$intID = $this->in->get('success');
		//$this->pdh->put('donations', 'updatedStatus', array($intID, 'paybal_back'));
		$this->pdh->process_hook_queue();
		$this->tpl->assign_vars(array(
				'S_DONATE_SUCCESS' => true,
				'DONATION_SUCCESS_TEXT' => $this->bbcode->toHTML($this->config->get('thankyou', 'donations')),
		));
	}
	
	//Custom Value Dropdown
	$fltMinValue = (float)$this->config->get('min_value', 'donations');
	
	$arrCustomAmounts = array();
	$strCustomAmounts = $this->config->get('custom_values', 'donations');
	if($strCustomAmounts && strlen($strCustomAmounts)){
		$arrParts = explode(';', $strCustomAmounts);
		foreach($arrParts as $val){
			if((float)$val < $fltMinValue) continue;
			$arrCustomAmounts[$val] = $val;
		}
	}


	//Progressbar
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
		$this->tpl->assign_vars(array(
			'S_SUM_ONLY' 		=> true,
			'DONATION_SUM' 		=> number_format(round($fltTotalSum, 2),2),
		));

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

			$this->tpl->assign_vars(array(
				'S_MONTHLY_SUM_COVERED'=> true,
				'COVERED_MONTHS_CLASS' => ($intCoveredMonths > 6) ? 6 : $intCoveredMonths,
				'COVERED_MONTHS_TEXT' => sprintf($this->user->lang('donations_covered_months_text'), $intCoveredMonths),
			));

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

			$this->tpl->assign_vars(array(
				'S_MONTHLY_SUM_PROGRESS'=> true,
				'DONATION_MONTH' => $this->time->date('F Y'),
				'FIXED_SUM_PROGRESSBAR' => $this->jquery->progressbar('donate_big_progressbar_monthlysum', $percent, array('text' => $displayPercent.'% ('.number_format($fltTotalSum,2).' '.$strCurrency.' / '.number_format($fltGoalValue,2).' '.$strCurrency.')'))
			));
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
		

		$this->tpl->assign_vars(array(
			'S_FIXED_SUM' 		=> true,
			'FIXED_SUM_PROGRESSBAR' => $this->jquery->progressbar('donate_big_progressbar_fixedsum', $percent, array('text' => $displayPercent.'% ('.number_format($fltTotalSum,2).' '.$strCurrency.' / '.number_format($fltGoalValue,2).' '.$strCurrency.')'))
		));
	}
	
	//Wall of Donators
	$arrCompletedIDs = $this->pdh->get('donations', 'completed_id_list');
	foreach($arrCompletedIDs as $donationID){
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

		$this->tpl->assign_block_vars('wallofdonators_row', array(
			'VALUE' 	=> '<span class="'.$class.'">'.$fltAmount.'</span>',
			'CURRENCY'	=> sanitize($arrData['currency']),
			'DATE'		=> $this->time->user_date($arrData['date']),
			'COMMENT'	=> sanitize($arrData['description']),
			'USERNAME'	=> ($arrData['public']) ? $strUsername : '<i style="font-style:italic;">Anonymous</i>',
		));
	}


	//Template Vars
	$this->tpl->assign_vars(array(
		'DONATE_DD_PUBLIC' 			=> (new hdropdown('public', array('options' => array(1 => $this->user->lang('donations_public'), 0 => $this->user->lang('donations_hide_name')), 'value' => 1)))->output(),
		'DONATION_PLACEHOLDER' 		=> $this->config->get('min_value', 'donations'),
		'DONATION_TEXT'				=> $this->bbcode->toHTML($this->config->get('donation', 'donations')),
		'DONATION_CURRENCY' 		=> $this->config->get('paypal_currency', 'donations'),
		'S_DONATION_FREE' 			=> ($this->config->get('free_or_custom', 'donations') == 'free') ? true : false,
		'DD_DONATION_AMOUNTS' 		=> (new hdropdown('value', array('options' => $arrCustomAmounts)))->output(),
		'S_CAN_DONATE'				=> $this->user->check_auth('u_donations_donate', false),
	));
	
	//CAPTCHA
	if(!$this->user->is_signedin() && $this->config->get('enable_captcha')) {
		require($this->root_path.'libraries/recaptcha/recaptcha.class.php');
		$captcha = new recaptcha;
		$this->tpl->assign_vars(array(
				'CAPTCHA'			=> $captcha->get_html($this->config->get('lib_recaptcha_okey')),
				'S_DISPLAY_CATPCHA'		=> true,
		));
	}
	
	
	$this->core->set_vars(array (
      'page_title'    => $this->user->lang('donations_menu'),
      'template_path' => $this->pm->get_data('donations', 'template_path'),
      'template_file' => 'donate.html',
      'display'       => true
    ));
  }

}
?>
