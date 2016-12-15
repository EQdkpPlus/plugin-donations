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
  	
  	$this->tpl->assign_vars(array(
  			'S_DONATE_REDIRECT' 	=> true,
  			'S_DONATE_BASIC_URL'	=> $this->env->buildlink().$this->routing->build('Donate', '', '', true, true),
  			'AMOUNT' 				=> str_replace(',', '.', $fltValue),
  			'TARGET_ADDRESS' 		=> $this->config->get('paypal_email', 'donations'),
  			'CURRENCY' 				=> $this->config->get('paypal_currency', 'donations'),
  			'NOTIFY_URL' 			=> $this->env->buildlink().'plugins/donations/paypal_callback.php',
  			'DONATE_TOKEN' 			=> $intID.':'.$strToken,
  			'DONATE_ID'				=> $intID,
  			'PAYPAL_URL'			=> (defined('DEBUG') > 3 || defined('DEBUG_PAYPAL')) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr',
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
	
	if($this->in->get('cancel')){
		$intID = $this->in->get('cancel');
		$this->pdh->put('donations', 'updatedStatus', array($intID, 'canceled'));
		$this->pdh->process_hook_queue();
		//TODO: show message;
	}
	
	if($this->in->get('success')){
		$intID = $this->in->get('success');
		//$this->pdh->put('donations', 'updatedStatus', array($intID, 'paybal_back'));
		$this->pdh->process_hook_queue();
		//TODO: show message
	}
	
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
	
	$this->tpl->assign_vars(array(
		'DONATE_DD_PUBLIC' => new hdropdown('public', array('options' => array(1 => 'Öffentliche Spende', 0 => 'Name verbergen'), 'value' => 1)),
		'DONATION_PLACEHOLDER' => $this->config->get('min_value', 'donations'),
		'DONATION_TEXT'	=> $this->bbcode->toHTML($this->config->get('donation', 'donations')),
		'DONATION_CURRENCY' => $this->config->get('paypal_currency', 'donations'),
		'DONATION_ITEM' => $this->user->lang('donations_menu').': '.$this->config->get('guildtag'),
		'S_DONATION_FREE' => ($this->config->get('free_or_custom', 'donations') == 'free') ? true : false,
		'DD_DONATION_AMOUNTS' => new hdropdown('value', array('options' => $arrCustomAmounts)),
	));
	
	
	$this->core->set_vars(array (
      'page_title'    => $this->user->lang('donations_menu'),
      'template_path' => $this->pm->get_data('donations', 'template_path'),
      'template_file' => 'donate.html',
      'display'       => true
    ));
  }

}
?>
