<?php

$defaultSettings = array(
	'css' => array(
		'/admin/css/brownie',
		'/admin/css/fancybox/jquery.fancybox-1.3.1',
		'/admin/css/themes/jquery-ui-1.8.16.custom',
		'/admin/css/jquery.multiselect',
	),
	'js' => array(
		'/admin/js/jquery-1.7.1.min',
		'/admin/js/jquery-ui-1.8.16.custom.min',
		'/admin/js/jquery.fancybox-1.3.1.pack',
		'/admin/js/jquery.selso',
		'/admin/js/jquery.comboselect',
		'/admin/js/jquery.jDoubleSelect',
		'/admin/js/jquery.multiselect.min',
		'/admin/js/jquery.multiselect.filter.min',
		'/admin/js/brownie',
	),
	'customHome' => false,
	'userModels' => array('AdminUser'),
	'uploadsPath' => './uploads',
	'dateFormat' => 'Y-m-d',
	'formDateFormat' => 'MDY',
	'monthNames' => true,
	'datetimeFormat' => 'Y-m-d H:i:s',
	'defaultExportType' => 'csv',
	'defaultPermissionPerAuthModel' => 'none',
	'defaultImageQuality' => '95',
);
if (file_exists(WWW_ROOT . 'css' . DS . 'brownie.css')) {
	$defaultSettings['css'][] = 'brownie';
}
if (file_exists(WWW_ROOT . 'js' . DS . 'brownie.js')) {
	$defaultSettings['js'][] = 'brownie';
}
if (file_exists(WWW_ROOT . 'js' . DS . 'tiny_mce' . DS . 'jquery.tinymce.js')) {
	$defaultSettings['js'][] = 'tiny_mce/jquery.tinymce';
} elseif (file_exists(WWW_ROOT . 'js' . DS . 'fckeditor' . DS . 'fckeditor.js')) {
	$defaultSettings['js'][] = 'fckeditor/fckeditor';
} elseif (file_exists(WWW_ROOT . 'js' . DS . 'ckeditor' . DS . 'ckeditor.js')) {
	$defaultSettings['js'][] = 'ckeditor/ckeditor';
}

Configure::write('adminSettings', Set::merge($defaultSettings, (array)Configure::read('adminSettings')));

Configure::write('adminAuthConfig', array(
	'authenticate' => array('Admin.Admin' => array('fields' => array('username' => 'email'))),
	'loginAction' => array('controller' => 'admin', 'action' => 'login', 'plugin' => 'admin', 'admin' => false),
	'loginRedirect' => array('controller' => 'admin', 'action' => 'index', 'plugin' => 'admin', 'admin' => false),
	'authError' => __d('admin', 'Please provide a valid username and password'),
));

