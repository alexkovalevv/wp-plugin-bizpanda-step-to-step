<?php
	/**
	 * Plugin Name:[Bizpanda addon] Step to step
	 * Plugin URI: http://byoneress.com
	 * Description: This allows you to use several locks of different types in one widget and open them one at a time. The user will receive a bonus when he opens all the settings in the queue locks. With this add-on you can get twice as many leads.
	 * Author: Webcraftic <alex.kovalevv@gmail.com>
	 * Version: 1.0.0
	 * Author URI: http://byoneress.com
	 */

	define('BZDA_ADN_PLUGIN_URL', plugins_url(null, __FILE__));
	define('BZDA_ADN_PLUGIN_DIR', dirname(__FILE__));

	function onp_bzda_adn_init()
	{
		if( defined('OPTINPANDA_PLUGIN_ACTIVE') || defined('SOCIALLOCKER_PLUGIN_ACTIVE') ) {
			global $bizpanda;

			if( is_admin() ) {
				if( defined('WPLANG') && WPLANG != 'en_US' ) {
					load_textdomain('plugin-bizpanda-step-to-step', BZDA_ADN_PLUGIN_DIR . '/langs/' . WPLANG . '.mo');
				}
				require_once BZDA_ADN_PLUGIN_DIR . '/admin/boot.php';
			}

			require_once BZDA_ADN_PLUGIN_DIR . '/plugin/boot.php';
		}
	}

	add_action('bizpanda_init', 'onp_bzda_adn_init', 20);


