<?php
/**
 * Plugin Name: Menu Restrict for Anonymous users
 * Plugin URI: https://venugopalphp.wordpress.com
 * Description: This plugin restricted the seleted menu so anonymous user won't access this menu item links directly 
 * Version: 1.0
 * Author: Venugopal.
 * Author URI: https://avsmartinfo.in
 * License: GPL2
 */



// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'VG_MR_VERSION',		'1.0' );
define( 'VG_MR_ADMIN_PAGE',	esc_url( admin_url( 'admin.php' ) ).'?page=vg-menu-restrict' );
define( 'VG_MR_PLUGIN_URL',	plugin_dir_url( __FILE__ ) );


/**
 * Defining Class for Menu Restrict Plugin
 *
 * @date  10th July 2017
 * @since VG Menu Restrict 1.0
 * @author Venugopal
 *
 */

Class VG_menu_restrict {

	/**
	 * Constructor
	 *
	 * This function will construct all the neccessary actions, filters and functions for the VG Menu Restrict plugin to work 
	 *
	 * @date  10th July 2017
 	 * @since VG Menu Restrict 1.0
 	 * @param	N/A
	 * @return	N/A
     * 
	 */


	public function __construct()
	{
		add_action( 'admin_init',  array($this, 'VG_menu_restrict_styles') );
		add_action( 'admin_menu', array($this, 'VG_menu_restrict_plugin_menu') );
		
	}


	/**
	 * Menu restrict styles - Admin Menu
	 *
	 * @date  19th July 2017
 	 * @since VG Menu Restrict 1.0
 	 * @param	N/A
	 * @return	N/A
     * 
	 */

	public function VG_menu_restrict_styles(){

		wp_enqueue_style( 'vg-menu-restrict-style', VG_MR_PLUGIN_URL.'css/vg-menu-restrict.css' );
		ob_start();
	}


	/**
	 * Menu restrict - Admin Menu
	 *
	 * @date  10th July 2017
 	 * @since VG Menu Restrict 1.0
 	 * @param	N/A
	 * @return	N/A
     * 
	 */

	public function VG_menu_restrict_plugin_menu(){

		add_menu_page('Menu Restrict','Menu Restrict','manage_options','vg-menu-restrict',array($this,'VG_menu_restrict_plugin_load'), 'dashicons-admin-network', 61);

	}


	/**
	 * Menu restrict - Admin settings
	 * 	
	 * Display the nav menu list dropdown for admin select and update
	 *
	 * @date  10th July 2017
 	 * @since VG Menu Restrict 1.0
 	 * @param	N/A
	 * @return	N/A
     * 
	 */

	public function VG_menu_restrict_plugin_load(){

			if(isset($_REQUEST['menu-restrict-submit'])){

				$this->VG_menu_restrict_to_option();
			}
		
		 require(dirname( __FILE__ ).'/admin-menu-restrict-settings.php');

	}


	/**
	 * Menu restrict - Data Save
	 * 	
	 * After Submit data will save to option table
	 *
	 * @date  10th July 2017
 	 * @since VG Menu Restrict 1.0
 	 * @param	N/A
	 * @return	N/A
     * 
	 */

	public function VG_menu_restrict_to_option(){

		$getRequest  = sanitize_text_field($_REQUEST['menu-to-restrict']);

		if(isset($getRequest)){

			update_option('vg-menu-restrict-name',$getRequest);
		}



	}

	
}


/**
 *
 * Initialize class object
 *
 */

$VG_menu_restrict_obj = new VG_menu_restrict();


/**
 * Menu restrict - Action
 * 	
 * In this function, Menu will restrict 
 *
 * @date  10th July 2017
 * @since VG Menu Restrict 1.0
 * @param	N/A
 * @return	N/A
 * 
 */

function VG_menu_restrict_action(){


	 $gettingSelectedMenu =  get_option('vg-menu-restrict-name');

		$splitMenu = explode('_', $gettingSelectedMenu);

		$menuId  = $splitMenu[0];
		$menuName = $splitMenu[1];


		$restricted_MenuItems = wp_get_nav_menu_items( $menuId, array( 'order' => 'DESC' ) );


       global $post;

      $current_pageId = $post->ID;


       foreach ($restricted_MenuItems as $menuitems_list) {

       	$menulistItem_pageId = $menuitems_list->object_id;
       	if($menulistItem_pageId == $current_pageId){

       		if(!is_user_logged_in()) {

       			wp_safe_redirect( wp_login_url( get_permalink() ) );  exit;

       		}



       	}


       }


   }

add_action( 'wp_head', 'VG_menu_restrict_action' );
