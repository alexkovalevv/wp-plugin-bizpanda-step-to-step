/**
 * Настройки опций мультизамков для превью
 * @author Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright Alex Kovalev 04.05.2017
 * @version 1.0
 */


(function($) {
	'use strict';
	if( !window.bizpanda ) {
		window.bizpanda = {};
	}
	if( !window.bizpanda.stepToStepOptions ) {
		window.bizpanda.stepToStepOptions = {};
	}

	window.bizpanda.stepToStepOptions = {
		init: function() {
			var self = this;

			$.bizpanda.filters.add('opanda-preview-options', function(options) {
				var extraOptions = self.getOptions();
				console.log($.extend(true, options, extraOptions));
				return $.extend(true, options, extraOptions);
			});
		},
		getOptions: function() {
			var rawOptions = $("#bizpanda_step_to_step_combo_items_options").val(),
				options = {};

			if( rawOptions ) {
				options = JSON.parse(rawOptions);
			}

			var resultOptions = {
				groups: {
					order: []
				},
				stepToStep: {}
			};

			if( options ) {
				for( var i = 0; i < options.length; i++ ) {
					var optionGroup = options[i][1];
					var stepIndex = i + 1;

					if( !options[i][1] ) {
						continue;
					}

					var lockerId = options[i][1].lockerId || null;

					resultOptions.groups.order.push(options[i][1].groupName);
					resultOptions.stepToStep['step' + stepIndex] = {
						title: '',
						lockerOptions: {
							text: {},
							connectButtons: {},
							socialButtons: {},
							subscription: {}
						}
					};
					resultOptions.stepToStep['step' + stepIndex]['title'] = options[i][1].title;

					if( lockerId && void 0 != window.stepToStepLockersOptions && window.stepToStepLockersOptions[lockerId] ) {
						resultOptions.stepToStep['step' + stepIndex]['lockerOptions'] = window.stepToStepLockersOptions[lockerId];
					}

					if( options[i][1].groupName == 'custom-screens' ) {

						resultOptions.stepToStep['step' + stepIndex]['lockerOptions']['text'] = {
							header: '',
							message: options[i][1].customScreenDescription || ''
						};
					}
				}
			}

			$(document).trigger('onp-sl-filter-preview-options');

			return resultOptions;
		}
	};

	$(function() {
		window.bizpanda.stepToStepOptions.init();
	});

})(jQuery);
