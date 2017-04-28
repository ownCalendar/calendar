<?php

$styles = [
	'../js/vendor/fullcalendar/dist/fullcalendar',
  '../css/public/rendering',
	'../js/vendor/jquery-timepicker/jquery.ui.timepicker',
	'public/app'
];

foreach ($styles as $style) {
	style('calendar', $style);
}

$scripts = [
	'vendor/jquery-timepicker/jquery.ui.timepicker',
	'vendor/ical.js/build/ical',
	'vendor/jstzdetect/jstz',
	'vendor/angular/angular',
	'vendor/angular-bootstrap/ui-bootstrap',
	'vendor/angular-bootstrap/ui-bootstrap-tpls',
	'vendor/fullcalendar/dist/fullcalendar',
	'vendor/fullcalendar/dist/locale-all',
	'vendor/davclient.js/lib/client',
	'vendor/hsl_rgb_converter/converter',
	'public/app',
	'public/rendering'
];


foreach ($scripts as $script) {
	script('calendar', $script);
}

 ?>

<div class="app">
	<?php echo $_['html']?>
</div>
