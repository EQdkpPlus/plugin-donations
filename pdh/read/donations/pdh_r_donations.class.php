<?php
/*	Project:	EQdkp-Plus
 *	Package:	Donation Plugin
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


if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}
				
if ( !class_exists( "pdh_r_donations" ) ) {
	class pdh_r_donations extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $donations = null;

	public $hooks = array(
		'donations_update',
	);		
			
	public $presets = array(
		'donations_id' => array('id', array('%donationID%'), array()),
		'donations_date' => array('date', array('%donationID%'), array()),
		'donations_token' => array('token', array('%donationID%'), array()),
		'donations_user_id' => array('user_id', array('%donationID%'), array()),
		'donations_username' => array('username', array('%donationID%'), array()),
		'donations_amount' => array('amount', array('%donationID%'), array()),
		'donations_currency' => array('currency', array('%donationID%'), array()),
		'donations_status' => array('status', array('%donationID%'), array()),
		'donations_completed' => array('completed', array('%donationID%'), array()),
		'donations_description' => array('description', array('%donationID%'), array()),
		'donations_public' => array('public', array('%donationID%'), array()),
		'donations_method' => array('method', array('%donationID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_donations_table');
			
			$this->donations = NULL;
	}
					
	public function init(){
			$this->donations	= $this->pdc->get('pdh_donations_table');				
					
			if($this->donations !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __plugin_donations');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->donations[(int)$drow['id']] = array(
						'id'				=> (int)$drow['id'],
						'date'				=> (int)$drow['date'],
						'token'				=> $drow['token'],
						'user_id'			=> (int)$drow['user_id'],
						'username'			=> $drow['username'],
						'amount'			=> (float)$drow['amount'],
						'currency'			=> $drow['currency'],
						'status'			=> $drow['status'],
						'completed'			=> (int)$drow['completed'],
						'description'		=> $drow['description'],
						'public'			=> (int)$drow['public'],
						'method'			=> $drow['method'],

					);
				}
				
				$this->pdc->put('pdh_donations_table', $this->donations, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->donations === null) return array();
			return array_keys($this->donations);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID];
			}
			return false;
		}
				
		/**
		 * Returns id for $donationID				
		 * @param integer $donationID
		 * @return multitype id
		 */
		 public function get_id($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['id'];
			}
			return false;
		}

		/**
		 * Returns date for $donationID				
		 * @param integer $donationID
		 * @return multitype date
		 */
		 public function get_date($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['date'];
			}
			return false;
		}

		/**
		 * Returns token for $donationID				
		 * @param integer $donationID
		 * @return multitype token
		 */
		 public function get_token($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['token'];
			}
			return false;
		}

		/**
		 * Returns user_id for $donationID				
		 * @param integer $donationID
		 * @return multitype user_id
		 */
		 public function get_user_id($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['user_id'];
			}
			return false;
		}

		/**
		 * Returns username for $donationID				
		 * @param integer $donationID
		 * @return multitype username
		 */
		 public function get_username($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['username'];
			}
			return false;
		}

		/**
		 * Returns amount for $donationID				
		 * @param integer $donationID
		 * @return multitype amount
		 */
		 public function get_amount($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['amount'];
			}
			return false;
		}

		/**
		 * Returns currency for $donationID				
		 * @param integer $donationID
		 * @return multitype currency
		 */
		 public function get_currency($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['currency'];
			}
			return false;
		}

		/**
		 * Returns status for $donationID				
		 * @param integer $donationID
		 * @return multitype status
		 */
		 public function get_status($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['status'];
			}
			return false;
		}

		/**
		 * Returns completed for $donationID				
		 * @param integer $donationID
		 * @return multitype completed
		 */
		 public function get_completed($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['completed'];
			}
			return false;
		}

		/**
		 * Returns description for $donationID				
		 * @param integer $donationID
		 * @return multitype description
		 */
		 public function get_description($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['description'];
			}
			return false;
		}

		/**
		 * Returns public for $donationID				
		 * @param integer $donationID
		 * @return multitype public
		 */
		 public function get_public($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['public'];
			}
			return false;
		}

		/**
		 * Returns method for $donationID				
		 * @param integer $donationID
		 * @return multitype method
		 */
		 public function get_method($donationID){
			if (isset($this->donations[$donationID])){
				return $this->donations[$donationID]['method'];
			}
			return false;
		}

	}//end class
}//end if
?>