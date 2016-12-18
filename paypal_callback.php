<?php
/*	Project:	EQdkp-Plus
 *	Package:	Chat Plugin
*	Link:		http://eqdkp-plus.eu
*
*	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
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

define('EQDKP_INC', true);
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path.'common.php');

class PayPalCallback extends page_generic {



	public function display(){
		$blnDebugMode = (defined('DEBUG') > 3 || defined('DEBUG_PAYPAL')) ? true : false;
		$url = 'https://www.paypal.com/cgi-bin/webscr';
		if ($blnDebugMode) {
			// IPN simulator notification
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$_POST['cmd'] = '_notify-validate';

		$strResult = register('urlfetcher')->post($url, http_build_query($_POST));

		if (strstr($strResult, "VERIFIED") === false) {
			throw new Exception('request not validated');
			exit;
		}

		if (!isset($_POST['custom'])) {
			throw new Exception('invalid custom item');
			exit;
		}
		$tokenParts = explode(':', $_POST['custom'], 2);

		if (count($tokenParts) != 2) {
			throw new Exception('invalid custom item');
			exit;
		}

		// get status
		$transactionType = (!empty($_POST['txn_type']) ? $_POST['txn_type'] : '');
		$paymentStatus = (!empty($_POST['payment_status']) ? $_POST['payment_status'] : '');

		$status = '';
		if ($transactionType == 'web_accept' || $transactionType == 'subscr_payment') {
			if ($paymentStatus == 'Completed') {
				$status = 'completed';
			}elseif($paymentStatus == 'Pending'){
				$status = 'pending';
			}
		}
		if ($paymentStatus == 'Refunded' || $paymentStatus == 'Reversed') {
			$status = 'reversed';
		}
		if ($paymentStatus == 'Canceled_Reversal') {
			$status = 'canceled_reversal';
		}

		$intPaymentID = intval($tokenParts[0]);

		$arrPayment = $this->pdh->get('donations', 'data', array($intPaymentID));
		if(count($arrPayment)){
			//Check Token
			if($arrPayment['token'] != $tokenParts[1]){
				throw new Exception('invalid token');
				exit;
			}
				
			if ($status == 'completed' || $blnDebugMode) {
				// validate payment amount
				if ($_POST['mc_gross'] != $arrPayment['amount'] || $_POST['mc_currency'] != $arrPayment['currency']) {
					throw new Exception('invalid payment amount');
					exit;
				}

				// calculate real amount without fees
				$amount = (isset($_POST['mc_fee'])) ? ((float)$arrPayment['amount'] - (float)$_POST['mc_fee']) : (float)$arrPayment['amount'];

				$this->pdh->put('donations', 'finishPayment', array($intPaymentID, 'verified', $amount));
				$this->pdh->process_hook_queue();
			} else {
				$this->pdh->put('donations', 'updatedStatus', array($intPaymentID, $status));
				$this->pdh->process_hook_queue();
			}
				
		} else {
			throw new Exception('could not find dataset');
			exit;
		}

	}

	public function __construct(){
		register("pm");
		$handler = array(

		);
		parent::__construct(false, $handler);
		$this->process();
	}
}


registry::register('PayPalCallback');