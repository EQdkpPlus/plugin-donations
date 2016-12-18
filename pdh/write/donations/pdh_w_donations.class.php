<?php
/*	Project:	EQdkp-Plus
 *	Package:	DynamicTemplate Plugin
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
	die('Do not access this file directly.');
}

/*+----------------------------------------------------------------------------
  | pdh_w_donations
  +--------------------------------------------------------------------------*/
if (!class_exists('pdh_w_donations')){
	class pdh_w_donations extends pdh_w_generic
	{

		public function add($intUserID, $strUsername, $fltAmout, $strCurrency, $strDescription, $intPublic, $strToken, $strMethod, $strStatus='start', $intCompleted=0){
			
			$objQuery = $this->db->prepare("INSERT INTO __plugin_donations :p")->set(array(
				'user_id'		=> $intUserID,
				'username'		=> $strUsername,
				'date'			=> $this->time->time,
				'token'			=> $strToken,
				'amount'		=> $fltAmout,
				'currency'		=> $strCurrency,
				'status'		=> $strStatus,
				'completed'		=> $intCompleted,
				'description'	=> $strDescription,
				'public'		=> $intPublic,
				'method'		=> $strMethod,
			))->execute();
			
			$this->pdh->enqueue_hook('donations_update');
			if($objQuery) return $objQuery->insertId;
			
			return false;
		}
		
		//$intDonationID, $intUserId, $strUsername, $strCurrency, $strDescription, $intPublic, $strMethod, 'manual'
		public function update($intID,$intUserID, $strUsername, $fltAmout, $strCurrency, $strDescription, $intPublic, $strMethod, $intDate=false){
			$objQuery = $this->db->prepare("UPDATE __plugin_donations :p WHERE id=?")->set(array(
					'user_id'		=> $intUserID,
					'username'		=> $strUsername,
					'date'			=> ($intDate) ? $intDate : $this->time->time,
					'amount'		=> $fltAmout,
					'currency'		=> $strCurrency,
					'description'	=> $strDescription,
					'public'		=> $intPublic,
					'method'		=> $strMethod,
			))->execute($intID);
				
			$this->pdh->enqueue_hook('donations_update');
			if($objQuery) return $intID;
				
			return false;
		}
		
		public function updatedStatus($intID, $strStatus='completed'){
			$objQuery = $this->db->prepare("UPDATE __plugin_donations :p WHERE id=?")->set(array(
					'status'	=> $strStatus,
			))->execute($intID);
				
			$this->pdh->enqueue_hook('donations_update');
			if($objQuery) return $intID;
				
			return false;
		}
		
		public function complete($intID, $intCompleted=1){
			$objQuery = $this->db->prepare("UPDATE __plugin_donations :p WHERE id=?")->set(array(
					'completed'	=> $intCompleted,
			))->execute($intID);
		
			$this->pdh->enqueue_hook('donations_update');
			if($objQuery) return $intID;
		
			return false;
		}
		
		public function finishPayment($intID, $strStatus, $fltAmount){
			$objQuery = $this->db->prepare("UPDATE __plugin_donations :p WHERE id=?")->set(array(
					'status'	=> $strStatus,
					'amount'	=> $fltAmount,
					'completed' => 1,
			))->execute($intID);
			
			//Insert Data into Statistics Plugin
			if ($this->pm->check('statistics', PLUGIN_INSTALLED)){
				$this->pdh->put('statistics_plugin', 'insert', array('donations', intval($fltAmount)));
			}
			
			//Notify Admin
			$strLink = $this->routing->build('donate', false, false, true, true);
			$this->ntfy->add('donations_new_donation', $intID, strip_tags($this->pdh->geth('donations', 'username', array($intID))), $strLink, false, number_format($fltAmount, 2).' '.$this->pdh->geth('donations', 'currency', array($intID)), false, 'a_donations_manage');
		
			$this->pdh->enqueue_hook('donations_update');
			if($objQuery) return $intID;
		
			return false;
		}


		public function delete($intID){
			$this->db->prepare("DELETE FROM __plugin_donations WHERE id=?")->execute($intID);
			$this->pdh->enqueue_hook('donations_update');
			$this->ntfy->deleteNotification('donations_new_donation', $intID);
			return true;
		}


		public function truncate(){
			$this->db->query("TRUNCATE __plugin_donations");
			$this->pdh->enqueue_hook('donations_update');
			return true;
		}


	} //end class
} //end if class not exists

?>