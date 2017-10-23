<?php
	/**
	 * Общие хуки и вызовы для админ панели
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright Alex Kovalev 30.04.2017
	 * @version 1.0
	 */

	require_once(BZDA_STS_ADN_PLUGIN_DIR . '/admin/activation.php');
	require_once BZDA_STS_ADN_PLUGIN_DIR . '/admin/ajax/load-locker-options.php';
	require_once BZDA_STS_ADN_PLUGIN_DIR . '/admin/pages/license-manager.php';

	// Визуальный редактор по умолчанию
	add_filter('wp_default_editor', create_function('', 'return "tinymce";'));

	/**
	 * Удаляем заголовок и общее описания замка, так как для каждого шага у нас будет уникальное описание и заголовок.
	 */
	function bizpanda_step_to_step_basic_options($options)
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

	add_filter('bizpanda_basic_options', 'bizpanda_step_to_step_basic_options');

	/**
	 * Registers metaboxes for Social Locker.
	 *
	 * @see opanda_item_type_metaboxes
	 * @since 1.0.0
	 */

	function bizpanda_step_to_step_metaboxes($metaboxes)
	{
		$metaboxes[] = array(
			'class' => 'BZDA_STS_ADN_CombinationMetabox',
			'path' => BZDA_STS_ADN_PLUGIN_DIR . '/admin/metaboxes/combination.php'
		);
		
		return $metaboxes;
	}

	add_filter('bizpanda_step-to-step_type_metaboxes', 'bizpanda_step_to_step_metaboxes', 10, 1);

	/**
	 * Prints scripts in preview metabox
	 */
	function bizpanda_step_to_step_print_scripts_to_preview_head()
	{
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo BZDA_STS_ADN_PLUGIN_URL ?>/plugin/assets/css/step-to-step.min.css">
		<script type="text/javascript" src="<?php echo BZDA_STS_ADN_PLUGIN_URL ?>/plugin/assets/js/step-to-step.min.js"></script>
	<?php
	}

	add_action('bizpanda_print_scripts_to_preview_head', 'bizpanda_step_to_step_print_scripts_to_preview_head', 10, 2);

	// ---
	// Help
	//

	/**
	 * Registers a help section for the popup addon
	 *
	 * @since 1.0.0
	 */
	function bizpanda_sts_register_help($pages)
	{
		global $opanda_help_cats;
		if( !$opanda_help_cats ) {
			$opanda_help_cats = array();
		}

		$items = array(
			array(
				'name' => 'bizpanda-sts-quick-start',
				'title' => __('Quick start', 'bizpanda-step-to-step-addon'),
				'hollow' => true,
			)
		);

		$items = apply_filters('bizpanda_sts_register_help_pages', $items);

		array_unshift($pages, array(
			'name' => 'bizpanda-sts-addon',
			'title' => __('Step to step addon', 'bizpanda-step-to-step-addon'),
			'items' => $items
		));

		return $pages;
	}

	add_filter('bizpanda_help_pages', 'bizpanda_sts_register_help');

	/**
	 * Shows the intro page for the plugin Social Locker.
	 *
	 * @since 1.0.0
	 * @param FactoryPages000_AdminPage $manager
	 * @return void
	 */
	function bizpanda_sts_help_page($manager)
	{
		require BZDA_STS_ADN_PLUGIN_DIR . '/admin/pages/howtouse/quick-start.php';
	}

	add_action('bizpanda_help_page_bizpanda-sts-addon', 'bizpanda_sts_help_page');
	add_action('bizpanda_help_page_bizpanda-sts-addon-quick-start', 'bizpanda_sts_help_page');

	/**
	 * Add links to plugin meta
	 * @param $links
	 * @param $file
	 * @return array
	 */
	function bizpanda_sts_set_plugin_meta($links, $file)
	{
		global $bizpanda_sts_addon;

		if( $file == $bizpanda_sts_addon->relativePath ) {
			$links[] = '<a href="' . opanda_get_admin_url('how-to-use', array('opanda_page' => 'bizpanda-popups-addon')) . '" target="_blank">' . __('How to use?', 'bizpanda-step-to-step-addon') . '</a>';
		}

		return $links;
	}

	add_filter('plugin_row_meta', 'bizpanda_sts_set_plugin_meta', 10, 2);