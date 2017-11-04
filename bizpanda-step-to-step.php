<?php
	/**
	 * Plugin Name:[Bizpanda addon] Step to step
	 * Plugin URI: http://byoneress.com
	 * Description: This allows you to use several locks of different types in one widget and open them one at a time. The user will receive a bonus when he opens all the settings in the queue locks. With this add-on you can get twice as many leads.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.0
	 * Author URI: https://profiles.wordpress.org/webcraftic
	 */

	define('BZDA_STS_ADN_PLUGIN_URL', plugins_url(null, __FILE__));
	define('BZDA_STS_ADN_PLUGIN_DIR', dirname(__FILE__));

	//require_once BZDA_STS_ADN_PLUGIN_DIR . '/admin/activation.php';

	function onp_bzda_step_to_step_init()
	{
		if( defined('OPTINPANDA_PLUGIN_ACTIVE') || defined('SOCIALLOCKER_PLUGIN_ACTIVE') ) {
			global $bizpanda, $bizpanda_sts_addon;

			//todo: We eliminate compatibility problems with plugins that have an old factory.
			$sl_bizpanda_ver_old = defined('SOCIALLOCKER_BIZPANDA_VERSION') && SOCIALLOCKER_BIZPANDA_VERSION < 126;
			$op_bizpanda_ver_old = defined('OPTINPANDA_BIZPANDA_VERSION') && OPTINPANDA_BIZPANDA_VERSION < 126;

			if( $sl_bizpanda_ver_old || $op_bizpanda_ver_old ) {
				return;
			}

			load_textdomain('bizpanda-step-to-step-addon', BZDA_STS_ADN_PLUGIN_DIR . '/langs/' . get_locale() . '.mo');

			require_once BZDA_STS_ADN_PLUGIN_DIR . '/admin/classes/plugin.class.php';

			$bizpanda_sts_addon = new BZDA_STS_ADN_ADN_Factory000_Plugin(__FILE__, array(
				'name' => 'bizpanda-step-to-step-addon',
				'title' => '[Bizpanda addon] Step to step',
				'plugin_type' => 'addon',
				'version' => '1.0.0',
				'assembly' => BUILD_TYPE,
				'lang' => get_locale(),
				'api' => 'http://api.byonepress.com/1.1/',
				'account' => 'http://accounts.byonepress.com/',
				'updates' => BZDA_STS_ADN_PLUGIN_DIR . '/plugin/updates/'
			));

			// requires factory modules extend global bizpanda
			$bizpanda_sts_addon->load(array(
				array('libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
				array('libs/onepress/api', 'onp_api_000'),
				array('libs/onepress/licensing', 'onp_licensing_000'),
				array('libs/onepress/updates', 'onp_updates_000')
			));

			BizPanda::registerPlugin($bizpanda_sts_addon, 'bizpanda-step-to-step-addon', BUILD_TYPE);

			if( is_admin() ) {
				require_once BZDA_STS_ADN_PLUGIN_DIR . '/admin/boot.php';
			}

			require_once BZDA_STS_ADN_PLUGIN_DIR . '/plugin/boot.php';
		}
	}

	add_action('bizpanda_init', 'onp_bzda_step_to_step_init', 20);

	/**
	 * Activates the plugin.
	 *
	 * TThe activation hook has to be registered before loading the plugin.
	 * The deactivateion hook can be registered in any place (currently in the file plugin.class.php).
	 */
	function onp_bzda_sts_adn_activation()
	{
		if( defined('OPTINPANDA_PLUGIN_ACTIVE') || defined('SOCIALLOCKER_PLUGIN_ACTIVE') ) {

			//todo: We eliminate compatibility problems with plugins that have an old factory.
			$sl_bizpanda_ver_old = defined('SOCIALLOCKER_BIZPANDA_VERSION') && SOCIALLOCKER_BIZPANDA_VERSION < 126;
			$op_bizpanda_ver_old = defined('OPTINPANDA_BIZPANDA_VERSION') && OPTINPANDA_BIZPANDA_VERSION < 126;

			if( $sl_bizpanda_ver_old || $op_bizpanda_ver_old ) {
				return;
			}

			onp_bzda_step_to_step_init();

			global $bizpanda_sts_addon;
			$bizpanda_sts_addon->activate();
		}
	}

	register_activation_hook(__FILE__, 'onp_bzda_sts_adn_activation');


