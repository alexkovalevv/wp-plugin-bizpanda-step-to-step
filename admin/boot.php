<?php
	/**
	 * Общие хуки и вызовы для админ панели
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright Alex Kovalev 30.04.2017
	 * @version 1.0
	 */

	require_once BZDA_ADN_PLUGIN_DIR . '/panda-items/step-to-step/admin/ajax/load-locker-options.php';

	// Визуальный редактор по умолчанию
	add_filter('wp_default_editor', create_function('', 'return "tinymce";'));

	/**
	 * Демо настройки для мультизамков, устанавливаются при активации.
	 *
	 * @since 1.0.0
	 */
	function onp_bizpanda_evo_activation($plugin, $helper)
	{
		// default social locker
		$helper->addPost('opanda_default_social_locker_id', array(
			'post_type' => OPANDA_POST_TYPE,
			'post_title' => __('Замок шаг за шагом (требует настройки и публикации)', 'plugin-addon-popup-locker'),
			'post_name' => 'opanda_default_step_to_step_locker',
			'post_status' => 'draft'
		), array(
			'opanda_item' => 'step-to-step',
			'opanda_style' => 'darkness',
			'opanda_overlap' => 'transparence',
			'opanda_mobile' => 1,
			'opanda_highlight' => 1,
			'opanda_is_system' => 1,
			'opanda_is_default' => 1,
			'opanda_lock_mode' => 'inline',
			'opanda_open_locker_trigger' => 'visible',
		));
	}

	add_action('bizpanda_after_activation', 'onp_bizpanda_evo_activation', 10, 2);

	/**
	 * Удаляем заголовок и общее описания замка, так как для каждого шага у нас будет уникальное описание и заголовок.
	 */
	function onp_bzda_adn_basic_options($options)
	{
		global $post;

		$itemType = get_post_meta($post->ID, 'opanda_item', true);

		if( empty($itemType) ) {
			$itemType = isset($_GET['opanda_item'])
				? $_GET['opanda_item']
				: null;
		}

		if( $itemType != 'step-to-step' ) {
			return $options;
		}

		foreach($options as $key => $option) {
			if( $option['name'] == 'header' || $option['name'] == 'message' ) {
				unset($options[$key]);
			}

			// По умолчанию для мультизамков тема darkness
			if( $option['name'] == 'style' ) {
				$options[$key]['default'] = 'darkness';
			}

			// По умолчанию для мультизамков режим наложения transparence
			if( $option['name'] == 'overlap' ) {
				$options[$key]['default'] = 'transparence';
			}
		}

		return $options;
	}

	add_filter('bizpanda_basic_options', 'onp_bzda_adn_basic_options');

	/**
	 * Registers metaboxes for Social Locker.
	 *
	 * @see opanda_item_type_metaboxes
	 * @since 1.0.0
	 */

	function onp_bzda_adn_step_to_step_metaboxes($metaboxes)
	{
		$metaboxes[] = array(
			'class' => 'BZDA_EVO_CombinationMetabox',
			'path' => BZDA_ADN_PLUGIN_DIR . '/panda-items/step-to-step/admin/metaboxes/combination.php'
		);
		
		return $metaboxes;
	}

	add_filter('bizpanda_step-to-step_type_metaboxes', 'onp_bzda_adn_step_to_step_metaboxes', 10, 1);

	/**
	 * Печатаем скрипты в метабоксе превью
	 */
	function onp_bzda_adn_bizpanda_print_scripts_to_preview_head()
	{
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo BZDA_ADN_PLUGIN_URL ?>/panda-items/step-to-step/assets/css/step-to-step.min.css">
		<script type="text/javascript" src="<?php echo BZDA_ADN_PLUGIN_URL ?>/panda-items/step-to-step/assets/js/step-to-step.min.js"></script>
	<?php
	}

	add_action('bizpanda_print_scripts_to_preview_head', 'onp_bzda_adn_bizpanda_print_scripts_to_preview_head', 10, 2);