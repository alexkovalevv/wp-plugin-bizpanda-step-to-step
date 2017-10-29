<?php global $bizpanda_sts_addon; ?>

<div class="onp-help-section">
	<h1><?php _e('Настройка и использование дополнения "Шаг за шагом"', 'bizpanda-step-to-step-addon'); ?></h1>

	<p><?php _e('Прочитайте эту инструкцию, чтобы быстро настроить дополнение и начать работать с ним.') ?></p>
</div>
<div class="onp-help-section">
	<h4>1. <?php _e('Активация лицензии', 'bizpanda-step-to-step-addon'); ?></h4>

	<p>
		<a href="<?php onp_licensing_000_manager_link($bizpanda_sts_addon->pluginName); ?>" target="_blank"><?php _e('Активируйте лицензию на дополнение.', 'bizpanda-step-to-step-addon'); ?></a> <?php _e('Введите ключ, который вы получили при покупке дополнение и нажмите "отправить ключ".', 'bizpanda-step-to-step-addon') ?>
	</p>
</div>
<div class="onp-help-section">
	<h4>2. <?php _e('Редактирование', 'bizpanda-step-to-step-addon'); ?></h4>

	<p>
		<?php _e('В меню плагина перейдите на закладку "Все замки", выберите замок "Шаг за шагом" из списка или создайте новый замок. В итоге вы должны увидеть страницу редактирования замка.', 'bizpanda-step-to-step-addon') ?></p>

	<p class='onp-img'>
		<img src='https://snag.gy/grxv1t.jpg'/>
	</p>
</div>
<div class="onp-help-section">
	<h4>3. <?php _e('Базовые настройки', 'bizpanda-step-to-step-addon'); ?></h4>

	<p>
		<?php _e('Выберите тему для комбинации замков и режим наложения.', 'bizpanda-step-to-step-addon') ?>
	</p>

	<p class='onp-img'>
		<img src='https://snag.gy/mvJ9eM.jpg'/>
	</p>
</div>
<div class="onp-help-section">
	<h4>4. <?php _e('Настройка комбинации замков', 'bizpanda-step-to-step-addon'); ?></h4>

	<p>
		<?php _e('Ниже превью, вы можете найти настройки комбинации замков. Нажмите кнопку "Добавить шаг"', 'bizpanda-step-to-step-addon') ?>
	</p>

	<p class='onp-img'>
		<img src='https://snag.gy/1GHZNq.jpg'/>
	</p>

	<?php _e('В открывшемся окне, вы увидите настройки экрана первого шага.', 'bizpanda-step-to-step-addon') ?><br>
	<?php _e('Введите заголовок шага например "Поделиться в соц. сетях". Выберите тип экрана <b>замок</b>, а в поле "С какого замка импортировать настройки?" установите <b>Социальный замок</b>', 'bizpanda-step-to-step-addon') ?>

	<p class='onp-img'>
		<img src='https://snag.gy/hGqjpS.jpg'/>
	</p>

	<?php _e('Сохраните настройки. В превью вы увидите новый экран с настройками выбранного вами социального замка.', 'bizpanda-step-to-step-addon') ?>

	<p class='onp-img'>
		<img src='https://snag.gy/2vG8E1.jpg'/>
	</p>

	<?php _e('Нажмите снова кнопку "Добавить шаг" и настройте экран, точно также как и предыдущий, но вместо социального замка выберите <b>замок авторизации</b>.', 'bizpanda-step-to-step-addon') ?>
	<br><br>
	<?php _e('Хорошо, теперь у нас есть два пошаговых экрана. Вы можете поменять их местами простым перетаскиванием панели шага.', 'bizpanda-step-to-step-addon') ?>
	<p class='onp-img'>
		<img src='https://snag.gy/GOdWJ4.jpg'/>
	</p>
	<?php _e('Когда пользователь выполнит условия первого шага, ему будет открыт второй шаг. При выполнении второго шага замок будет открыт.', 'bizpanda-step-to-step-addon') ?>
	<br><br>
	<?php _e('Но есть еще один тип экрана, который мы можем показать после выполнении всех шагов. Это произвольный экран.', 'bizpanda-step-to-step-addon') ?>
	<br><br>
	<?php _e('Произвольный экран может использоваться в тех случаях, когда нужно показать пользователю купон или дополнительную информацию о его вознаграждении.', 'bizpanda-step-to-step-addon') ?>
	<br><br>
	<?php _e('Нажмите кнопку "Добавить шаг" придумайте заголовок к примеру "Получите ваш купон" и выберите тип экрана "произвольный", в поле "cодержание экрана" введите код купона или произвольный текст.', 'bizpanda-step-to-step-addon') ?>
	<br>

	<p class='onp-img'>
		<img src='https://snag.gy/yOklFe.jpg'/>
	</p>

	<?php _e('Сохраните настройки замка.', 'bizpanda-step-to-step-addon') ?>

	<p class='onp-img'>
		<img src='https://snag.gy/e2u71P.jpg'/>
	</p>
</div>
<div class="onp-help-section">
	<h4>5. <?php _e('Установка замка на странице', 'bizpanda-step-to-step-addon'); ?></h4>

	<p>
		<?php _e('Установить очень проста. Перейдите в редактор записей или страниц, выделите контент, который нужно скрыть, нажмите на иконку социального замка или панды, выберите замок "шаг за шагом", чтобы обернуть контент шорткодами.', 'bizpanda-step-to-step-addon') ?>
	</p>

	<p>
		<?php _e('Сохраните настройки записи.', 'bizpanda-step-to-step-addon') ?>
	</p>

	<p class='onp-img'>
		<img src='https://cconp.s3.amazonaws.com/bizpanda/social-locker/help/en_US/5.png'/>
	</p>

	<p>
		<?php _e('Поздравляем! Вы успешно настроили замок "шаг за шагом", теперь посмотрим на результат.', 'bizpanda-step-to-step-addon') ?>
	</p>

	<p class='onp-img'>
		<img src='https://snag.gy/jNgFPz.jpg'/>
	</p>
</div>