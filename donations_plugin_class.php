<?php
/*	Project:	EQdkp-Plus
 *	Package:	Donations Plugin
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
  header('HTTP/1.0 404 Not Found');
  exit;
}


/*+----------------------------------------------------------------------------
  | localitembase
  +--------------------------------------------------------------------------*/
class donations extends plugin_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('user', 'config', 'pdc', 'pfh', 'pdh', 'routing');
    return array_merge(parent::$shortcuts, $shortcuts);
  }

  public $version    = '1.0.4';
  public $build      = '';
  public $copyright  = 'GodMod';
  public $vstatus    = 'Stable';
  
  protected static $apiLevel = 23;

  /**
    * Constructor
    * Initialize all informations for installing/uninstalling plugin
    */
  public function __construct()
  {
    parent::__construct();

    $this->add_data(array (
      'name'              => 'Donations',
      'code'              => 'donations',
      'path'              => 'donations',
      'template_path'     => 'plugins/donations/templates/',
      'icon'              => 'fa fa-dollar',
      'version'           => $this->version,
      'author'            => $this->copyright,
      'description'       => $this->user->lang('donations_short_desc'),
      'long_description'  => $this->user->lang('donations_long_desc'),
      'homepage'          => EQDKP_PROJECT_URL,
      'manuallink'        => false,
      'plus_version'      => '2.3',
      'build'             => $this->build,
    ));

    $this->add_dependency(array(
      'plus_version'      => '2.3'
    ));
    //Routing
    $this->routing->addRoute('Donate', 'donate', 'plugins/donations/page_objects');
    
	// -- Menu --------------------------------------------
    $this->add_menu('admin', $this->gen_admin_menu());
    $this->add_menu('main', $this->gen_main_menu());
    
    $this->add_permission('a', 'manage',	'N', $this->user->lang('manage'),	array(2,3));
    $this->add_permission('a', 'settings',	'N', $this->user->lang('settings'),	array(2,3));
    $this->add_permission('u', 'donate',	'N', $this->user->lang('donations_donate'),	array(1,2,3,4));
    $this->add_permission('u', 'donationlist',	'N', $this->user->lang('donations_donationslist'),	array(1,2,3,4));
    
    $this->add_hook('plugin_statistics', 'donations_plugin_statistics_hook', 'plugin_statistics');
    
    // -- PDH Modules -------------------------------------
    $this->add_pdh_read_module('donations');
    $this->add_pdh_write_module('donations');
    
    $this->add_portal_module('donations');
    $this->add_portal_module('latestdonations');
}

  /**
    * pre_install
    * Define Installation
    */
   public function pre_install()
  {
    // include SQL and default configuration data for installation
    include($this->root_path.'plugins/donations/sql/sql.php');

    // define installation
    for ($i = 1; $i <= count($donationsSQL['install']); $i++)
      $this->add_sql(SQL_INSTALL, $donationsSQL['install'][$i]);
	  
    $this->pdc->del_prefix('pdh_donations_');
  }
  
  
  /**
   * post_install
   * Add Default Settings
   */
  public function post_install(){
  	$this->ntfy->addNotificationType('donations_new_donation', 'donations_ntfy_new_donation', 'donations', 0, 1, true, 'donations_ntfy_new_donation_grouped', 3, 'fa-dollar');

  }

  /**
   * pre_uninstall
   * Define uninstallation
   */
  public function pre_uninstall()
  {
  	$this->ntfy->deleteNotificationType('donations_new_donation');
  	
  	$this->pdh->put('donations', 'truncate', array());
  	
  	// include SQL data for uninstallation
  	include($this->root_path.'plugins/donations/sql/sql.php');
  
  	for ($i = 1; $i <= count($donationsSQL['uninstall']); $i++)
  		$this->add_sql(SQL_UNINSTALL, $donationsSQL['uninstall'][$i]);
  }


  /**
    * gen_admin_menu
    * Generate the Admin Menu
    */
  private function gen_admin_menu()
  {

    $admin_menu = array (array(
        'name' => $this->user->lang('donations'),
        'icon' => 'fa fa-dollar',
	 1 => array (
          'link'  => 'plugins/donations/admin/donations.php'.$this->SID,
          'text'  => $this->user->lang('manage'),
          'check' => 'a_donations_manage',
          'icon'  => 'fa-list'
        ),
        2 => array (
          'link'  => 'plugins/donations/admin/settings.php'.$this->SID,
          'text'  => $this->user->lang('settings'),
          'check' => 'a_donations_settings',
          'icon'  => 'fa-wrench'
        ),
    ));


    return $admin_menu;
  }
  
  /**
   * Generate the Main Menu
   */
  private function gen_main_menu(){
  
  	$main_menu = array(
  			1 => array (
  					'link'		=> $this->routing->build('Donate', false, false, true, true),
  					'text'		=> $this->user->lang('donations_menu'),
  					'check'		=> array('OR', array('u_donations_donate', 'u_donations_donationlist')),
  			),
  				
  	);
  	return $main_menu;
  }

}
?>
