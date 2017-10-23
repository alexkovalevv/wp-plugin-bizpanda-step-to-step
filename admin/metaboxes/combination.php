<?php
	/**
	 * Добавляет метабокс с настройками режимов
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright Alex Kovalev 27.04.2017
	 * @version 1.0
	 */

	/**
	 * The class configure the metabox Social Options.
	 *
	 * @since 1.0.0
	 */
	class BZDA_STS_ADN_CombinationMetabox extends FactoryMetaboxes000_FormMetabox {

		/**
		 * A visible title of the metabox.
		 *
		 * Inherited from the class FactoryMetabox.
		 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $title;

		/**
		 * A prefix that will be used for names of input fields in the form.
		 *
		 * Inherited from the class FactoryFormMetabox.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $scope = 'bizpanda_step_to_step';

		/**
		 * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
		 * Inherited from the class FactoryMetabox.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $priority = 'core';

		public $cssClass = 'factory-bootstrap-000 factory-fontawesome-000';

		// Настройки закладок
		public $items;

		// Настройки закладок без прототипа
		public $itemsWithOutPrototype;

		public function __construct($plugin)
		{
			parent::__construct($plugin);
			
			$this->title = __('Комбинации замков', 'bizpanda-step-to-step-addon');
		}

		public function prepareOptions($postId)
		{
			global $post;

			$itemsRaw = get_post_meta($postId, 'bizpanda_step_to_step_combo_items_options', true);
			$items = @json_decode($itemsRaw, ARRAY_A);

			if( empty($items) ) {
				if( !class_exists('OPanda_Items') ) {
					require_once OPANDA_BIZPANDA_DIR . '/includes/panda-items.php';
				}
				$lockers = get_posts(array(
					'post_type' => OPANDA_POST_TYPE,
					'meta_key' => 'opanda_item',
					'meta_value' => OPanda_Items::getAvailableNames(),
					'numberposts' => -1
				));

				$items = array();

				//----------------------------------------------
				//       ДЕМО ДАННЫЕ ПО УМОЛЧАНИЮ
				//----------------------------------------------
				if( !empty($lockers) ) {

					$defaultLocker = null;
					$defaultLocker1 = null;
					$defaultLocker2 = null;

					$prepareLockers = array();

					foreach($lockers as $locker) {
						$lockerType = OPanda_Items::getItemNameById($locker->ID);
						$lockerGroup = 'social-buttons';

						if( $lockerType == 'signin-locker' ) {
							$lockerGroup = 'connect-buttons';
						} else if( $lockerType == 'email-locker' ) {
							$lockerGroup = 'subscription';
						}

						$isDefault = (int)get_post_meta($locker->ID, 'opanda_is_default', true);

						$prepareLockers[$lockerGroup][$locker->ID] = array(
							'id' => $locker->ID,
							'is_default' => $isDefault,
							'group_name' => $lockerGroup
						);
					}

					if( BizPanda::hasPlugin('sociallocker') ) {
						if( isset($prepareLockers['social-buttons']) ) {
							$searchDefaultLocker1Key = $this->searchForId(1, $prepareLockers['social-buttons']);

							if( $searchDefaultLocker1Key === false ) {
								reset($prepareLockers['social-buttons']);
								$searchDefaultLocker1Key = key($prepareLockers['social-buttons']);
							}

							$defaultLocker1 = $prepareLockers['social-buttons'][$searchDefaultLocker1Key];
						}
					}

					if( BizPanda::hasPlugin('optinpanda') ) {
						if( isset($prepareLockers['subscription']) ) {
							$searchDefaultLocker2Key = $this->searchForId(1, $prepareLockers['subscription']);

							if( $searchDefaultLocker2Key === false ) {
								reset($prepareLockers['subscription']);
								$searchDefaultLocker2Key = key($prepareLockers['subscription']);
							}

							$defaultLocker = $defaultLocker2 = $prepareLockers['subscription'][$searchDefaultLocker2Key];
						}

						if( !BizPanda::hasPlugin('sociallocker') ) {
							$defaultLocker2 = null;
						}
					} else if( BizPanda::hasPlugin('sociallocker') ) {
						if( isset($prepareLockers['connect-buttons']) ) {
							$searchDefaultLocker2Key = $this->searchForId(1, $prepareLockers['connect-buttons']);

							if( $searchDefaultLocker2Key === false ) {
								reset($prepareLockers['connect-buttons']);
								$searchDefaultLocker2Key = key($prepareLockers['connect-buttons']);
							}

							$defaultLocker2 = $prepareLockers['connect-buttons'][$searchDefaultLocker2Key];
						}
					}

					if( !empty($defaultLocker1) && !empty($defaultLocker2) ) {
						$items = array(
							array(
								861933,
								array(
									'title' => __('Получите скидку на товар 20%', 'bizpanda-step-to-step-addon'),
									'type' => 'locker',
									'lockerId' => $defaultLocker1['id'],
									'customScreenDescription' => '',
									'groupName' => 'social-buttons'
								)
							),
							array(
								861934,
								array(
									'title' => __('Повышайте скидку до 30%', 'bizpanda-step-to-step-addon'),
									'type' => 'locker',
									'lockerId' => $defaultLocker2['id'],
									'customScreenDescription' => '',
									'groupName' => $defaultLocker2['group_name']
								)
							),
							array(
								861935,
								array(
									'title' => __('Получите купон на скидку', 'bizpanda-step-to-step-addon'),
									'type' => 'custom',
									'lockerId' => null,
									'customScreenDescription' => __('Ваш купон на скидку SACSSF343', 'bizpanda-step-to-step-addon'),
									'groupName' => 'custom-screens'
								)
							)
						);
					} else if( !empty($defaultLocker) ) {
						$items = array(
							array(
								861933,
								array(
									'title' => __('Получите скидку на товар 20%', 'bizpanda-step-to-step-addon'),
									'type' => 'locker',
									'lockerId' => $defaultLocker['id'],
									'customScreenDescription' => '',
									'groupName' => 'subscription'
								)
							),
							array(
								861934,
								array(
									'title' => __('Получите купон на скидку', 'bizpanda-step-to-step-addon'),
									'type' => 'custom',
									'lockerId' => null,
									'customScreenDescription' => __('Ваш купон на скидку SACSSF343', 'bizpanda-step-to-step-addon'),
									'groupName' => 'custom-screens'
								)
							)
						);
					}
				}
			}

			$this->itemsWithOutPrototype = $items;

			$items[] = array(
				0,
				array(
					'title' => '',
					'type' => '',
					'lockerId' => ''
				)
			);

			$this->items = $items;
		}

		/**
		 * Configures a metabox.
		 */
		public function configure($scripts, $styles)
		{

			$styles->add(BZDA_STS_ADN_PLUGIN_URL . '/admin/assets/css/item.edit.000002.css');
			$scripts->add(BZDA_STS_ADN_PLUGIN_URL . '/admin/assets/js/metabox.combination.000002.js');
			$scripts->add(BZDA_STS_ADN_PLUGIN_URL . '/admin/assets/js/item.step-to-step-options.000002.js');
		}

		/**
		 * Configures a form that will be inside the metabox.
		 *
		 * @see FactoryMetaboxes000_FormMetabox
		 * @since 1.0.0
		 *
		 * @param FactoryForms000_Form $form A form object to configure.
		 * @return void
		 */
		public function form($form)
		{
			global $post;
			$this->prepareOptions($post->ID);

			$items[] = array(
				'type' => 'html',
				'html' => array($this, 'lockersSettingHtml')
			);

			if( empty($this->itemsWithOutPrototype) ) {
				$this->itemsWithOutPrototype = array();
			}

			$items[] = array(
				'type' => 'hidden',
				'name' => 'combo_items_options',
				'default' => @json_encode($this->itemsWithOutPrototype)
			);

			$items[] = array(
				'type' => 'hidden',
				'name' => 'step_to_step_nonce',
				'value' => wp_create_nonce("bizpanda_step_to_step_nonce")
			);

			$form->add($items);
		}

		public function lockersSettingHtml()
		{
			global $post;
			?>

			<div id="onp-bzda-items-combination">
				<div class="onp-bzda-items-contanier">
					<?php
						$printLockersOptions = array();

						foreach($this->items as $item): ?>
							<?php
							$lockerId = isset($item[1]['lockerId'])
								? intval($item[1]['lockerId'])
								: null;

							$groupName = isset($item[1]['groupName'])
								? $item[1]['groupName']
								: null;

							$groupNameCamelCase = $this->dashesToCamelCase($groupName);

							if( $lockerId ) {
								if( !class_exists('OPanda_AssetsManager') ) {
									require_once OPANDA_BIZPANDA_DIR . '/includes/assets.php';
								}
								$lockData = OPanda_AssetsManager::getLockerDataToPrint($lockerId);
								$lockerOptions = isset($lockData['options'])
									? $lockData['options']
									: array();

								$groupOptions = isset($lockerOptions[$groupNameCamelCase])
									? $lockerOptions[$groupNameCamelCase]
									: array();

								$groupOptions['text'] = isset($lockerOptions['text'])
									? $lockerOptions['text']
									: array();

								$printLockersOptions[$lockerId] = $groupOptions;
							}

							$attrParamsStr = ' class="onp-bzda-combo-item';

							if( !isset($item[0]) || empty($item) || !$item[0] ) {
								$attrParamsStr .= ' onp-bzda-combo-item-prototype';
								$attrParamsStr .= '"';
							} else {
								$attrParamsStr .= '"';
								$attrParamsStr .= ' data-item-id="' . $item[0] . '"';
							}

							?>
							<div<?= $attrParamsStr ?>>
								<div class="onp-bzda-item-move"></div>
								<div class="onp-bzda-item-title"><?= $item[1]['title'] ?></div>
								<div class="onp-bzda-item-sub-title">
									<div>
										<b><?php _e('Тип', 'bizpanda-step-to-step-addon'); ?>:</b>
										<span><?= $groupName ?></span>
									</div>
									<?php if( !empty($lockerId) ): ?>
										<div>
											<b><?php _e('Название замка', 'bizpanda-step-to-step-addon'); ?>:</b>
											<span><?= get_the_title($lockerId) ?></span>
										</div>
									<?php endif; ?>
								</div>
								<button class="button button-default onp-bzda-item-remove-button">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</button>
								<a href="#onp-bzda-combo-settting-modal" class="button button-default onp-bzda-item-setting-button">
									<i class="fa fa-cog" aria-hidden="true"></i>
								</a>
							</div>
						<?php endforeach; ?>
				</div>
				<button id="onp-bzda-new-combo-item" class="button button-default">
					+ <?php _e('Добавить шаг', 'bizpanda-step-to-step-addon'); ?></button>
				<?php echo '<script>window.stepToStepLockersOptions = ' . @json_encode($printLockersOptions) . ';</script>'; ?>
			</div>

			<div class="modal fade" id="onp-bzda-combo-settting-modal" tabindex="-1" role="dialog" aria-labelledby="onp-bzda-combo-settting-modal-label" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="onp-bzda-combo-settting-modal-label">
								<?php _e('Редактирование', 'bizpanda-step-to-step-addon'); ?>:
								<span>Социальный замок</span>
							</h4>
						</div>
						<div class="modal-body">
							<div class="onp-bzda-combo-settting-section">
								<label style="display: block;" class="onp-bzda-combo-label" for="onp-bzda-combo-title-input">
									<?php _e('Название шага', 'bizpanda-step-to-step-addon'); ?>:
								</label>
								<input type="text" style="width:100%;" id="onp-bzda-combo-title-input" placeholder="<?php _e('Поделиться в соц. сети (скидка 10%)', 'bizpanda-step-to-step-addon'); ?>" class="form-control">

								<div class="onp-bzda-combo-hint">
									<?php _e('Введите заголовок шага. Например: "Поделиться в соц. сети (скидка 10%)". Данный
									текст будет отображен в хлебных крошках замка.', 'bizpanda-step-to-step-addon'); ?>
								</div>
							</div>
							<div class="onp-bzda-combo-settting-section">
								<strong><?php _e('Выберите тип экрана', 'bizpanda-step-to-step-addon'); ?></strong>

								<div class="btn-group" id="onp-bzda-choce-combo-item-type" data-toggle="buttons-radio">
									<button data-target="#onp-bzda-combo-locker-create-tab" type="button" class="btn btn-default onp-bzda-combo-locker-screen-button value active" data-name="locker">
										<i class="fa fa-lock" aria-hidden="true"></i> <?php _e('Замок', 'bizpanda-step-to-step-addon'); ?>
									</button>
									<button data-target="#onp-bzda-combo-custom-create-tab" type="button" class="btn btn-default onp-bzda-combo-custom-screen-button value" data-name="custom">
										<i class="fa fa-desktop" aria-hidden="true"></i> <?php _e('Произвольный', 'bizpanda-step-to-step-addon'); ?>
									</button>
								</div>
								<div class="onp-bzda-combo-hint">
									<?php _e('В составлении комбинации выможете использовать только два типа экранов, экран с
									замком или произвольный экран.
									Произвольный экран нужен для того, чтобы разместить какой-то текст, после выподнения
									определенного шага. Этот текст может быть
									купоном на скидку или ссылкой, неважно.', 'bizpanda-step-to-step-addon'); ?>
								</div>
							</div>
							<div id="onp-bzda-combo-locker-create-tab" class="onp-bzda-combo-settings-tab">
								<div class="onp-bzda-combo-settting-section">
									<label style="display: block;" class="onp-bzda-combo-label" for="onp-bzda-combo-locker-select">
										<?php _e('С какого замка импортировать настройки?', 'bizpanda-step-to-step-addon'); ?>
									</label>
									<select style="width:250px;" class="onp-bzda-combo-fieild form-control" id="onp-bzda-combo-locker-select">
										<?php $lockers = $this->getLockers(); ?>
										<?foreach($lockers as $lockerId => $locker):?>
											<option value="<?= $lockerId ?>" data-locker-type="<?= $locker['locker_type'] ?>"><?= $locker['title'] ?></option>
										<?php endforeach; ?>
									</select>

									<div class="onp-bzda-combo-hint">
										<?php _e('Выбрав замок вы автоматичеси импортируете настройки его содержания. Настройки
										темы, видимости и дополнительные настройки выбранного замка не используются, а
										действуют общие настройки примененный для мультизамка.', 'bizpanda-step-to-step-addon'); ?>
									</div>
								</div>
							</div>
							<div id="onp-bzda-combo-custom-create-tab" class="onp-bzda-combo-settings-tab hide">
								<div class="onp-bzda-combo-settting-section">
									<label style="display: block;" class="onp-bzda-combo-label">
										<?php _e('Содержание экрана', 'bizpanda-step-to-step-addon'); ?>:
									</label>
									<?php wp_editor('', 'onp_bdza_combo_custom_screen_description_editor'); ?>
									<div class="onp-bzda-combo-hint">
										<?php _e('Основной текст или html код, который будет отображен в произвольном экране.', 'bizpanda-step-to-step-addon'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Отмена', 'bizpanda-step-to-step-addon'); ?></button>
							<button type="button" id="onp-bzda-save-combo-item-options" class="btn btn-primary"><?php _e('Сохранить', 'bizpanda-step-to-step-addon'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php
		}

		private function getLockers()
		{
			$lockers = get_posts(array(
				'post_type' => OPANDA_POST_TYPE,
				'meta_key' => 'opanda_item',
				'meta_value' => OPanda_Items::getAvailableNames(),
				'numberposts' => -1
			));

			$result = array();
			foreach($lockers as $locker) {

				$itemType = get_post_meta($locker->ID, 'opanda_item', true);

				if( $itemType == 'step-to-step' ) {
					continue;
				}

				$result[$locker->ID] = array(
					'title' => empty($locker->post_title)
						? '(no titled, ID=' . $locker->ID . ')'
						: $locker->post_title,
					'locker_type' => $itemType
				);
			}

			return $result;
		}

		private function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
		{
			$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

			if( !$capitalizeFirstCharacter && isset($str[0]) ) {
				$str[0] = strtolower($str[0]);
			}

			return $str;
		}

		private function searchForId($id, $array)
		{
			foreach($array as $key => $val) {
				if( $val['is_default'] === $id ) {
					return $key;
				}
			}

			return null;
		}
	}

	global $bizpanda;

	FactoryMetaboxes000::register('BZDA_STS_ADN_CombinationMetabox', $bizpanda);
/*@mix:place*/
