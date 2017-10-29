<?php

	/**
	 * Activator for the Business Panda.
	 *
	 * @see Factory000_Activator
	 * @since 1.0.0
	 */
	class BizpandaStsActivation extends OPanda_Activation {

		/**
		 * Runs activation actions.
		 *
		 * @since 1.0.0
		 */
		public function activate()
		{
			$this->setupLicense();

			// Демо настройки для мультизамков, устанавливаются при активации.

			$this->addPost('opanda_default_social_locker_id', array(
				'post_type' => OPANDA_POST_TYPE,
				'post_title' => __('Замок шаг за шагом (требует настройки и публикации)', 'bizpanda-step-to-step-addon'),
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

			// Redirect to help page
			factory_000_set_lazy_redirect(opanda_get_admin_url('how-to-use', array('onp_sl_page' => 'bizpanda-sts-addon')));
		}

		/**
		 * Setups the license.
		 *
		 * @since 1.0.0
		 */
		protected function setupLicense()
		{
			$this->plugin->license->setDefaultLicense(array(
				'Category' => 'free',
				'Build' => 'premium',
				'Title' => 'OnePress Zero License',
				'Description' => __('Please, activate the plugin to get started. Enter a key
                                    you received with the plugin into the form below.', 'plugin-sociallocker')
			));
		}
	}

	$bizpanda_sts_addon->registerActivation('BizpandaStsActivation');