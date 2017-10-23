/**
 * Генератор комбинаций замков
 * @author Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright Alex Kovalev 03.05.2017
 * @version 1.0
 */


(function($) {
	'use strict';

	var itemsCombination = {
		items: [],
		init: function() {
			this.ItemPrototype = $('.onp-bzda-combo-item-prototype').detach();
			this.initEvents();
			this._loadOptions();
		},
		initEvents: function() {
			var self = this;

			/**
			 * Событие срабатывает, когда сортируются шаги
			 */
			$(".onp-bzda-items-contanier").addClass("ui-sortable").sortable({
				placeholder: "sortable-placeholder",
				opacity: 0.7,
				items: ".onp-bzda-combo-item",
				update: function(event, ui) {
					self._updateItemsOrder();
					$(document).trigger('opanda-refresh-preview-trigger');
				}
			});

			/**
			 * Событие срабатывает, когда нажата добавить новый шаг
			 */
			$('#onp-bzda-new-combo-item').click(function() {
				self._clearForm();
				$('#onp-bzda-combo-settting-modal').factoryBootstrap000_modal('show');
				$('#onp-bzda-save-combo-item-options').addClass('create');
				return false;
			});

			/**
			 * Событие срабатывает, когда нажата кнопка сохранить настройки
			 */
			$('#onp-bzda-save-combo-item-options').click(function() {
				if( $(this).hasClass('create') ) {
					self._addNewItem();
				} else {
					self._updateItem();
				}

				$(document).trigger('opanda-refresh-preview-trigger');
				return false;
			});

			/**
			 * Событие срабатывает, когда открыто или закрыто модально окно
			 */
			$('#onp-bzda-combo-settting-modal').on('hidden hidden.bs.modal', function() {
				if( self._editItemElement ) {
					delete self._editItemElement;
				}
				if( self._editItemIndex ) {
					delete self._editItemIndex;
				}

				$('#onp-bzda-save-combo-item-options').removeClass('create').removeClass('edit');

			}).on('shown shown.bs.modal', function() {
				self._loadExternalOptions();
			});

			/**
			 * Событие срабатывает, когда нажата кнопка настройки
			 */
			$(document).on('click', '.onp-bzda-item-setting-button', function() {
				self._editItem($(this).closest('.onp-bzda-combo-item'));
				$('#onp-bzda-save-combo-item-options').addClass('edit');
				return false;
			});

			/**
			 * Событие срабатывает, когда нажата кнопка удалить
			 */
			$(document).on('click', '.onp-bzda-item-remove-button', function() {
				self._removeItem($(this).closest('.onp-bzda-combo-item'));
				$(document).trigger('opanda-refresh-preview-trigger');
				return false;
			});

			/**
			 * Событие срабатывает при нажатии на переключатель типов
			 */
			$(document).on('click', '#onp-bzda-choce-combo-item-type .btn', function() {
				self._switchItemTypes($(this).data('name'));
				return false;
			});

			/**
			 * Событие срабатывает, при выбоое замка
			 */
			$(document).on('change', '#onp-bzda-combo-locker-select', function() {
				self._loadExternalOptions();
				return false;
			});

		},

		_loadOptions: function() {
			var rawOptions = $('#bizpanda_step_to_step_combo_items_options').val(),
				options;

			if( rawOptions ) {
				this.items = JSON.parse(rawOptions) || [];
			}
		},

		_loadExternalOptions: function() {
			var self = this,
				selectLockerElement = $('#onp-bzda-combo-locker-select'),
				lockerId = selectLockerElement.val() || null,
				lockerType = selectLockerElement.find('option:selected').data('locker-type') || null,
				groupName = self._camelCase(this._getLockersGroupName(lockerType));

			if( !lockerId || !groupName ) {
				console.log('Invalid lockerId or groupName. Ajax request stopped.');
				return;
			}

			if( void 0 != window.stepToStepLockersOptions && !window.stepToStepLockersOptions[lockerId] ) {
				var nonce = $("#bizpanda_step_to_step_nonce").val();

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'bizpanda_step_to_step_get_locker_options',
						lockerId: lockerId,
						_wpnonce: nonce
					},
					success: function(data, textStatus, jqXHR) {
						if( !data ) {
							throw new Error('Unexpected ajax error.');
						}

						if( data[groupName] ) {
							data[groupName].text = data.text || {};
							window.stepToStepLockersOptions[lockerId] = data[groupName];
						} else {
							console.log('Group ' + groupName + ' is not found in getting settings');
						}
					}
				});
			}
		},

		_updateItemsOrder: function() {
			var self = this;

			var resortItems = [];

			$('.onp-bzda-combo-item').each(function() {
				var itemId = $(this).data('item-id');
				if( !itemId ) {
					throw new Error('Invalid item id.');
				}

				for( var i = 0; i < self.items.length; i++ ) {
					var item = self.items[i];
					if( item[0] == itemId ) {
						resortItems.push([item[0], item[1]]);
					}
				}
			});

			self.items = resortItems;
			this._updateItemOptions();
		},

		_switchItemTypes: function(itemTypeName) {
			var buttonsGroup = $('#onp-bzda-choce-combo-item-type');
			buttonsGroup.find(".active").removeClass("active");
			$(".onp-bzda-combo-settings-tab").addClass('hide');

			var currentTypeTabButton = buttonsGroup.find(".onp-bzda-combo-" + itemTypeName + "-screen-button");
			currentTypeTabButton.addClass("active");

			var target = currentTypeTabButton.data('target');
			$(target).removeClass('hide');
		},

		_clearForm: function() {
			$('#onp-bzda-combo-title-input').val('');

			if( typeof tinyMCE != "undefined" ) {
				tinyMCE.activeEditor.setContent('');
			}

			this._switchItemTypes('locker');
		},

		_editItem: function($hodler) {
			if( !this.items || !this.items.length ) {
				throw new Error('Combo items not found.');
			}

			var itemId = $hodler.data('item-id');

			if( !itemId ) {
				throw new Error('Invalid item id.');
			}

			this._editItemElement = $hodler;

			var itemOptions = {},
				modal = $('#onp-bzda-combo-settting-modal');

			for( var i = 0; i < this.items.length; i++ ) {
				var item = this.items[i];
				if( item[0] == itemId ) {
					itemOptions = item[1];
					this._editItemIndex = i;
					console.log(item);
				}
			}

			modal.find('#onp-bzda-combo-title-input').val(itemOptions.title);

			if( itemOptions.lockerId ) {
				modal.find('#onp-bzda-combo-locker-select').val(itemOptions.lockerId);
			}

			if( !itemOptions.type ) {
				itemOptions.type = 'locker';
			}

			this._switchItemTypes(itemOptions.type);

			if( typeof tinyMCE != "undefined" ) {
				tinyMCE.activeEditor.setContent(itemOptions.customScreenDescription);
			}

			modal.factoryBootstrap000_modal('show');
		},

		_addNewItem: function() {
			var prototype = this.ItemPrototype.clone();

			var itemId = Math.floor((Math.random() * 999999) + 1);
			prototype.data('item-id', itemId);

			var itemOptions = this._getItemOptions(prototype);

			this.items.push([itemId, itemOptions]);

			$('.onp-bzda-items-contanier').append(prototype);
			prototype.removeClass('onp-bzda-combo-item-prototype');

			this._updateItemOptions();
			$('#onp-bzda-combo-settting-modal').factoryBootstrap000_modal('hide');
		},

		_updateItem: function() {
			if( !this._editItemElement || this._editItemIndex === null ) {
				throw new Error('Edit unknow item.');
			}
			var itemOptions = this._getItemOptions(this._editItemElement);

			if( !this.items[this._editItemIndex] ) {
				throw new Error('Item not found.');
			}

			this.items[this._editItemIndex][1] = $.extend(this.items[this._editItemIndex][1], itemOptions);

			this._updateItemOptions();
			$('#onp-bzda-combo-settting-modal').factoryBootstrap000_modal('hide');

			delete this._editItemElement;
			delete this._editItemIndex;
		},

		_getItemOptions: function($holder) {

			var itemOptions = {
					title: '',
					type: null,
					lockerId: null,
					customScreenDescription: ''
				},
				itemTitle = $('#onp-bzda-combo-title-input').val(),
				itemType = $('#onp-bzda-choce-combo-item-type').find('.btn.active').data('name');

			$holder.find('.onp-bzda-item-title').text(itemTitle);
			itemOptions.title = itemTitle;

			$holder.find('.onp-bzda-item-sub-title').find('span').text(itemType);

			itemOptions.type = itemType;

			if( itemType == 'locker' ) {
				var choiceLockersElement = $('#onp-bzda-combo-locker-select option:selected');
				itemOptions.lockerId = choiceLockersElement.val();
				var lockerType = choiceLockersElement.data('locker-type');
				itemOptions.groupName = this._getLockersGroupName(lockerType);
			} else {
				itemOptions.groupName = 'custom-screens';
			}

			if( typeof tinyMCE != "undefined" ) {
				tinyMCE.activeEditor.save();
				itemOptions.customScreenDescription = $('#onp_bdza_combo_custom_screen_description_editor').val();
			}

			if( itemType == 'locker' ) {
				itemOptions.customScreenDescription = '';
			}

			return itemOptions;
		},

		_getLockersGroupName: function(lockerType) {
			if( lockerType == 'signin-locker' ) {
				return 'connect-buttons';
			} else if( lockerType == 'email-locker' ) {
				return 'subscription';
			}

			return 'social-buttons';
		},

		_updateItemOptions: function() {
			var jSonStr = JSON.stringify(this.items);
			$('#bizpanda_step_to_step_combo_items_options').val(jSonStr);
		},

		_removeItem: function($holder) {
			for( var i = 0; i < this.items.length; i++ ) {
				var item = this.items[i];
				if( item[0] == $holder.data('item-id') ) {
					this.items.splice(i, 1);
				}
			}
			$holder.remove();
			this._updateItemOptions();
		},

		_camelCase: function(input) {
			return input.toLowerCase().replace(/-(.)/g, function(match, group1) {
				return group1.toUpperCase();
			});
		}
	};

	$(function() {
		itemsCombination.init();
	});

})(jQuery);
