<?php
/*	Project:	EQdkp-Plus
 *	Package:	feedposter Plugin
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


/*+----------------------------------------------------------------------------
  | donationsAdminDonations
  +--------------------------------------------------------------------------*/
class donationsAdminDonations extends page_generic {

	/**
	* Constructor
	*/
	public function __construct(){
		// plugin installed?
		if (!$this->pm->check('donations', PLUGIN_INSTALLED))
			message_die($this->user->lang('donations_plugin_not_installed'));
		
		$this->user->check_auth('a_donations_manage');

		$handler = array(
			'complete'	=> array('process' => 'complete', 'csrf'=>true),
			'edit'		=> array('process' => 'edit'),
		);
		parent::__construct(false, $handler, array('donations', 'deleteinfo'), null, 'selected_ids[]');

		$this->process();
	}


	public function complete(){
		$intDonationID = $this->in->get('complete', 0);
		$don_ids = $this->in->getArray('selected_ids', 'int');
		if(count($don_ids) > 0){
			foreach($don_ids as $intDonationID){
				$isCompleted = $this->pdh->get('donations', 'completed', array($intDonationID));
				if($isCompleted){
					//$result = $this->pdh->put('donations', 'complete', array($intDonationID, 0));
					//$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('donation_incomplete_suc'), 'color' => 'green');
				} else {
					$result = $this->pdh->put('donations', 'complete', array($intDonationID, 1));
					$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('donation_complete_suc'), 'color' => 'green');
				
				}
			}
		} elseif ($intDonationID){
			$isCompleted = $this->pdh->get('donations', 'completed', array($intDonationID));
			if($isCompleted){
				$result = $this->pdh->put('donations', 'complete', array($intDonationID, 0));
				$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('donation_incomplete_suc'), 'color' => 'green');
				
			} else {
				$result = $this->pdh->put('donations', 'complete', array($intDonationID, 1));
				$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('donation_complete_suc'), 'color' => 'green');
				
			}
		}

		$this->display($message);
		
	} //close function


	public function delete(){
		$del_ids = $this->in->getArray('selected_ids', 'int');

		if ($del_ids) {
			foreach($del_ids as $intFieldID){
				$this->pdh->put('donations', 'delete', array(intval($intFieldID)));
			}

			$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('del_suc'), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('donations'), 'text' => $this->user->lang('del_nosuc'), 'color' => 'red');
		}
		$this->display($message);
	}
	
	public function update(){
		$intDonationID = $this->in->get('donation', 0);
		
		$strUsername = $this->in->get('username');
		$intUserId = $this->in->get('user_id');
		if($strUsername != ""){
			$intUserId = ANONYMOUS;
		}
		$intDate = $this->time->fromformat($this->in->get('date'));
		$fltAmount = $this->in->get('amount', 0.0);
		$strMethod = $this->in->get('method');
		$strDescription = $this->in->get('description');
		$intPublic = $this->in->get('public', 0);
		$strCurrency = $this->config->get('paypal_currency', 'donations');
		
		if($intDonationID){
			$blnResult = $this->pdh->put('donations', 'update', array($intDonationID, $intUserId, $strUsername, round($fltAmount, 2), $strCurrency, $strDescription, $intPublic, $strMethod, $intDate));
		} else {
			$blnResult = $this->pdh->put('donations', 'add', array($intUserId, $strUsername, round($fltAmount, 2), $strCurrency, $strDescription, $intPublic, '', $strMethod, 'manual', 1));
		}
		
		if($blnResult){
			$message = array('title' => $this->user->lang('success'), 'text' => $this->user->lang('save_suc'), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('error'), 'text' => $this->user->lang('save_nosuc'), 'color' => 'red');	
		}
		
		$this->display($message);
	}
	
	public function edit(){
		$intDonationID = $this->in->get('edit', 0);
		
		$arrUsers = array('-1' => ' - ');
		foreach($this->pdh->get('user', 'id_list') as $intUserID){
			$arrUsers[$intUserID] = $this->pdh->get('user', 'name', array($intUserID));
		}
	

		if($intDonationID){
			$arrData = $this->pdh->get('donations', 'data', array($intDonationID));

			
			$this->tpl->assign_vars(array(
				'D_USERNAME'			=> $arrData['username'],
				'D_AMOUNT'				=> number_format($arrData['amount'], 2),
				'D_DESCRIPTION'			=> $arrData['description'],
				'DD_METHOD'				=> (new hdropdown('method', array('options' => array('paypal' => 'PayPal', 'manual' => $this->user->lang('donations_method_manual')), 'value' => $arrData['method'])))->output(),
				'DD_PUBLIC'				=> (new hdropdown('public', array('options' => array(1 => $this->user->lang('donations_public'), 0 => $this->user->lang('donations_hide_name')), 'value' => $arrData['public'])))->output(),
				'D_DATEPICKER'			=> (new hdatepicker('date', array('value' => $arrData['date'])))->output(),
				'DD_USER' 				=> (new hsingleselect('user_id', array('options' => $arrUsers, 'filter' => true, 'value' => $arrData['user_id'])))->output(),
			));
		} else {
			$this->tpl->assign_vars(array(
				'D_USERNAME'			=> "",
				'D_AMOUNT'				=> number_format(0, 2),
				'D_DESCRIPTION'			=> "",
				'DD_METHOD'				=> (new hdropdown('method', array('options' => array('paypal' => 'PayPal', 'manual' => $this->user->lang('donations_method_manual')), 'value' => 'manual')))->output(),
				'DD_PUBLIC'				=> (new hdropdown('public', array('options' => array(1 => $this->user->lang('donations_public'), 0 => $this->user->lang('donations_hide_name')), 'value' => 1)))->output(),
				'D_DATEPICKER'			=> (new hdatepicker('date', array('value' => $this->time->time)))->output(),
				'DD_USER' 				=> (new hsingleselect('user_id', array('options' => $arrUsers, 'filter' => true, 'value' => $this->user->id)))->output(),
			));
		}
		
		$strDonationname = $this->pdh->get('donations', 'deleteinfo', array($intDonationID));
		$this->tpl->assign_vars(array(
			'DONATIONNAME'	=> (($intDonationID) ? $strDonationname : $this->user->lang('donations_add')),
			'DONATION_ID'	=> $intDonationID,
			'D_CURRENCY'	=> $this->config->get('paypal_currency', 'donations'),
		));
		
		// -- EQDKP ---------------------------------------------------------------
		$this->core->set_vars(array(
				'page_title'		=> (($intDonationID) ? $strDonationname : $this->user->lang('donations_add')).' - '.$this->user->lang('donations_manage'),
				'template_path'		=> $this->pm->get_data('donations', 'template_path'),
				'template_file'		=> 'admin/manage_donations_edit.html',
				'display'			=> true
		));
	}

	/**
	* display
	* Display the page
	*
	* @param    array  $messages   Array of Messages to output
	*/
	public function display($message=false){
		if($message){
			$this->pdh->process_hook_queue();
			$this->core->messages($message);
		}

		$view_list = $this->pdh->get('donations', 'id_list', array());
		 
		$hptt_page_settings = array(
				'name'				=> 'hptt_admin_donations',
				'table_main_sub'	=> '%donationID%',
				'table_subs'		=> array('%donationID%'),
				'page_ref'			=> 'donations.php',
				'show_numbers'		=> false,
				'show_select_boxes'	=> true,
				'selectboxes_checkall'=>true,
				'show_detail_twink'	=> false,
				'table_sort_dir'	=> 'desc',
				'table_sort_col'	=> 1,
				'table_presets'		=> array(
						array('name' => 'donations_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => ''),
						array('name' => 'donations_date',	'sort' => true, 'th_add' => 'width="20"', 'td_add' => ''),
						array('name' => 'donations_username',	'sort' => true, 'th_add' => 'width="20"', 'td_add' => ''),
						array('name' => 'donations_method','sort' => true, 'th_add' => 'width="20"', 'td_add' => ''),
						array('name' => 'donations_description',	'sort' => true, 'th_add' => '', 'td_add' => ''),
						array('name' => 'donations_amount',	'sort' => true, 'th_add' => 'width="20"', 'td_add' => ''),
				),
		);
		$hptt = $this->get_hptt($hptt_page_settings, $view_list, $view_list, array(), 'completed');
		$page_suffix = '&amp;start='.$this->in->get('start', 0);
		$sort_suffix = '?sort='.$this->in->get('sort');
		$intLimit = $this->user->data['user_rlimit'];
		$start	  = $this->in->get('start', 0);
		 
		$item_count = count($view_list);
		 
		$this->confirm_delete($this->user->lang('donations_confirm_delete_donation'));
		 
		$this->tpl->assign_vars(array(
				'ITEM_LIST'			=> $hptt->get_html_table($this->in->get('sort'), $page_suffix, $start, $intLimit),
				'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count(),
				'PAGINATION'		=> generate_pagination('donations.php'.$this->SID.$sort_suffix, $item_count, $intLimit, $start),
				'COMPLETED_COUNT'	=> count($view_list),
		));

		
		// -- EQDKP ---------------------------------------------------------------
		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('donations_manage'),
			'template_path'		=> $this->pm->get_data('donations', 'template_path'),
			'template_file'		=> 'admin/manage_donations.html',
			'display'			=> true
		));
	}
}
registry::register('donationsAdminDonations');
?>