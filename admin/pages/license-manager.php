<?php

	/**
	 * License page is a place where a user can check updated and manage the license.
	 *
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017, OnePress Ltd
	 *
	 * @since 1.0.0
	 * @package bizpand-popups-addon
	 */
	class BZDA_STS_ADN_LicenseManagerPage extends OnpLicensing000_LicenseManagerPage {

		public $internal = true;

		public function configure()
		{
			$config['faq'] = true;
			$config['trial'] = false;
			$config['premium'] = true;
			$config['purchasePrice'] = true;

			$config = apply_filters('bizpanda_sts_addon_license_manager_config', $config);

			foreach($config as $key => $configValue) {
				$this->$key = $configValue;
			}
		}
	}

	FactoryPages000::register($bizpanda_sts_addon, 'BZDA_STS_ADN_LicenseManagerPage');
	/*@mix:place*/

