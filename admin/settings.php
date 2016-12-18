<?php
/*	Project:	EQdkp-Plus
 *	Package:	donations Plugin
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

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'donations');

$eqdkp_root_path = './../../../';
include_once($eqdkp_root_path.'common.php');

class donationsAdminSettings extends page_generic {
	/**
	 * Constructor
	 */
	public function __construct(){
		// plugin installed?
		if (!$this->pm->check('donations', PLUGIN_INSTALLED))
			message_die($this->user->lang('donations_plugin_not_installed'));

			$handler = array(
					'save' => array('process' => 'save', 'csrf' => true, 'check' => 'a_donations_settings'),
			);
			parent::__construct('a_donations_settings', $handler);

			$this->process();
	}

	private $arrData = false;

	public function save(){
		$objForm			= register('form', array('donations_settings'));
		$objForm->langPrefix		= 'donations_';
		$objForm->validate		= true;
		$objForm->add_fieldsets($this->fields());
		$arrValues			= $objForm->return_values();

		if($objForm->error){
			$this->arrData		= $arrValues;
		}else{
			// update configuration
			$this->config->set($arrValues, '', 'donations');
			// Success message - Message, Title
			$messages[]			= array($this->user->lang('save_suc'), $this->user->lang('settings'));
			$this->display($messages);
		}
	}

	public function get_currencies() {
		//see: https://developer.paypal.com/docs/classic/api/currency_codes/

		$arrCurrencies = array(
			'AUD',
			'BRL',
			'CAD',
			'CZK',
			'DKK',
			'EUR',
			'HKD',
			'HUF',
			'ILS',
			'JPY',
			'MYR',
			'MXN',
			'NOK',
			'NZD',
			'PHP',
			'PLN',
			'GBP',
			'RUB',
			'SGD',
			'SEK',
			'CHF',
			'TWD',
			'THB',
			'USD',
		);

		return $arrCurrencies;
	}

	private function fields(){
		$arrCurrenciesRaw = $this->get_currencies();
		$arrCurrencyOptions = array();
		foreach($arrCurrenciesRaw as $val){
			$arrCurrencyOptions[$val] = $val;
		}


		$arrFields = array(
			'general' => array(
				'min_value' => array(
					'type' => 'spinner',
					'default' => 1,
					'min' => 1,
				),
				'free_or_custom' => array(
					'type' => 'radio',
					'default' => 'free',
					'options' => array('free' => $this->user->lang('donations_f_custom_free'), 'custom' => $this->user->lang('donations_f_custom_custom')),
					'dependency' => array('custom' => array('custom_values')),
				),
				'custom_values' => array(
					'type' => 'text',
					'default' => '1;2;5;10;20',
				),

			),
			'goal' => array(
				'goal_type' => array(
					'type' => 'dropdown',
					'options' => array('no' => $this->user->lang('donations_f_goal_no'), 'monthly_sum' => $this->user->lang('donations_f_goal_monthly'), 'fixed_sum' => $this->user->lang('donations_f_goal_fixedsum')),
					'dependency' => array('monthly_sum' => array('goal_value', 'goal_display_type', 'goal_start'), 'fixed_sum' => array('goal_value')),
				),
				'goal_value' => array(
					'type' => 'text',
				),
				'goal_display_type' => array(
					'type' => 'dropdown',
					'options' => array('progressbar' => $this->user->lang('donations_f_goal_display_type_progess'), 'covered_months' => $this->user->lang('donations_f_goal_display_type_covered')),
					'dependency' => array('covered_months' => array('goal_start')),
				),
				'goal_start' => array(
					'type' => 'datepicker',
					'default' => $this->time->time,
				),

			),
			'paypal' => array(
				'paypal_email' => array(
					'type' => 'text',
					'required' => true,
				),
				'paypal_currency' => array(
					'type' => 'dropdown',
					'options' => $arrCurrencyOptions,
					'default' => 'EUR'
				),
			),
			'texts' => array(
				'donation' => array('type'=>'bbcodeeditor'),
				'thankyou' => array('type'=>'bbcodeeditor'),
				'cancel'  => array('type'=>'bbcodeeditor'),
			),

		);
		
		return $arrFields;
	}

	public function display($messages=array()){
		// -- Messages ------------------------------------------------------------
		if ($messages){
			foreach($messages as $val)
				$this->core->message($val[0], $val[1], 'green');
		}

		// get the saved data
		$arrValues		= $this->config->get_config('donations');
		if ($this->arrData !== false) $arrValues = $this->arrData;

		// -- Template ------------------------------------------------------------
		// initialize form class
		$objForm				= register('form', array('donations_settings'));
		$objForm->reset_fields();
		$objForm->lang_prefix	= 'donations_';
		//$objForm->validate		= true;
		$objForm->use_dependency= true;
		$objForm->use_fieldsets	= true;
		$objForm->add_fieldsets($this->fields());

		// Output the form, pass values in
		$objForm->output($arrValues);

		$this->core->set_vars(array(
				'page_title'	=> $this->user->lang('donations').' '.$this->user->lang('settings'),
				'template_path'	=> $this->pm->get_data('donations', 'template_path'),
				'template_file'	=> 'admin/settings.html',
				'display'		=> true
		));
	}

}
registry::register('donationsAdminSettings');
?>