<?php
	/**
	 * ajax хук, который подгружает опции замков
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright Alex Kovalev 05.05.2017
	 * @version 1.0
	 */

	add_action('wp_ajax_bizpanda_step_to_step_get_locker_options', 'bizpanda_step_to_step_ajax_get_locker_options');
	add_action('wp_ajax_nopriv_bizpanda_step_to_step_get_locker_options', 'bizpanda_step_to_step_ajax_get_locker_options');

	function bizpanda_step_to_step_ajax_get_locker_options()
	{
		check_ajax_referer('bizpanda_step_to_step_nonce');

		$lockerId = isset($_REQUEST['lockerId']) && !empty($_REQUEST['lockerId'])
			? intval($_REQUEST['lockerId'])
			: null;

		if( !class_exists('OPanda_AssetsManager') ) {
			require_once OPANDA_BIZPANDA_DIR . '/includes/assets.php';
		}

		$lockData = OPanda_AssetsManager::getLockerDataToPrint($lockerId);

		$lockerOptions = isset($lockData['options'])
			? $lockData['options']
			: array();

		echo json_encode($lockerOptions);
		exit;
	}