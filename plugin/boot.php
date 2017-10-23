<?php
	/**
	 * Печатаем настройки для мультизамков во фронтенд
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright Alex Kovalev 30.04.2017
	 * @version 1.0
	 */

	/**
	 * Регистрируем новый тип замка
	 * @param $items
	 * @return mixed
	 */
	function bizpanda_step_to_step_register_item($items)
	{
		global $bizpanda;

		$title = __('Замок шаг за шагом', 'bizpanda-step-to-step-addon');

		$items['step-to-step'] = array(
			'name' => 'step-to-step',
			'type' => 'premium',
			'title' => $title,
			'help' => opanda_get_help_url('step-to-step'),
			'description' => '<p>' . __('<p>Пошаговые задания для всех типов замков.</p> <p>Это будет полезным, когда нужно попросить пользователя подписаться на два сообщества сразу.</p>.', 'bizpanda-step-to-step-addon') . '</p>',
			'shortcode' => 'multilocker',
			'plugin' => $bizpanda
		);

		return $items;
	}

	add_filter('bizpanda_items', 'bizpanda_step_to_step_register_item', 1);

	/**
	 * Библиотеки мультизамков, подключаем из после осноного ядра
	 */
	function bizpanda_step_to_step_libs_assets()
	{
		wp_enqueue_script('bizpanda-evo-multilocker', BZDA_STS_ADN_PLUGIN_URL . '/plugin/assets/js/step-to-step.min.js', array('opanda-lockers'), false, true);
		wp_enqueue_style('bizpanda-evo-multilocker-style', BZDA_STS_ADN_PLUGIN_URL . '/plugin/assets/css/step-to-step.min.css');
	}

	add_action('wp_enqueue_scripts', 'bizpanda_step_to_step_libs_assets');

	function bizpanda_step_to_step_locker_options($options, $id)
	{
		$itemsRaw = get_post_meta($id, 'bizpanda_step_to_step_combo_items_options', true);
		$multilockerOptions = @json_decode($itemsRaw, ARRAY_A);

		$options['groups'] = array(
			'order' => array()
		);

		$options['stepToStep'] = array();

		if( !empty($multilockerOptions) ) {
			foreach($multilockerOptions as $key => $optionValue) {
				$stepIndex = $key + 1;

				if( !isset($optionValue[1]) || empty($optionValue[1]) ) {
					continue;
				}

				$requireLockerId = isset($optionValue[1]['lockerId'])
					? $optionValue[1]['lockerId']
					: null;

				$groupName = isset($optionValue[1]['groupName'])
					? $optionValue[1]['groupName']
					: null;

				$title = isset($optionValue[1]['title'])
					? $optionValue[1]['title']
					: '';

				if( empty($groupName) ) {
					continue;
				}

				$options['groups']['order'][] = $groupName;
				$options['stepToStep']['step' . $stepIndex] = array(
					'title' => '',
					'lockerOptions' => array(
						'text' => array(),
						'connectButtons' => array(),
						'socialButtons' => array(),
						'subscription' => array()
					)
				);

				$options['stepToStep']['step' . $stepIndex]['title'] = $title;

				if( $requireLockerId ) {
					if( !class_exists('OPanda_AssetsManager') ) {
						require_once OPANDA_BIZPANDA_DIR . '/includes/assets.php';
					}
					$lockData = OPanda_AssetsManager::getLockerDataToPrint($requireLockerId);
					$lockerOptions = isset($lockData['options'])
						? $lockData['options']
						: array();

					$groupNameCamelCase = str_replace(' ', '', ucwords(str_replace('-', ' ', $groupName)));
					$groupNameCamelCase[0] = strtolower($groupNameCamelCase[0]);

					$groupOptions = isset($lockerOptions[$groupNameCamelCase])
						? $lockerOptions[$groupNameCamelCase]
						: array();

					$groupOptions['text'] = isset($lockerOptions['text'])
						? $lockerOptions['text']
						: array();

					$options['stepToStep']['step' . $stepIndex]['lockerOptions'] = $groupOptions;
				}

				if( $groupName == 'custom-screens' ) {
					$customScreenDescription = isset($optionValue[1]['customScreenDescription'])
						? $optionValue[1]['customScreenDescription']
						: '';

					$options['stepToStep']['step' . $stepIndex]['lockerOptions']['text'] = array(
						'header' => '&nbsp;',
						'message' => $customScreenDescription
					);
				}
			}
		}

		return $options;
	}

	add_filter('bizpanda_step-to-step_item_options', 'bizpanda_step_to_step_locker_options', 10, 2);

	/**
	 * Подключаем библиотеки для мультизамков
	 */
	function bizpanda_step_to_step_lockers_assets($lockerId, $options, $fromBody, $fromHeader)
	{
		OPanda_AssetsManager::requestLockerAssets();
	}

	add_action('bizpanda_request_assets_for_step-to-step', 'bizpanda_step_to_step_lockers_assets', 10, 4);

	/**
	 * Создаем шорткод для мультизамков
	 *
	 * @since 1.0.0
	 */
	class OPanda_StepToStepShortcode extends OPanda_LockerShortcode {

		/**
		 * Shortcode name
		 * @var string
		 */
		public $shortcodeName = array(
			'multilocker'
		);
	}

	FactoryShortcodes000::register('OPanda_StepToStepShortcode', $bizpanda);