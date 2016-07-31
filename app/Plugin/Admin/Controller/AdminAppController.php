<?php

class AdminAppController extends AppController {

	public $components = array('Session');
	public $helpers = array('Html', 'Session', 'Js');
	public $uses = array('AdminUser');
	public $layout = 'admin_default';


	public function __construct($request, $response) {
        
		$this->components['Auth'] = Configure::read('adminAuthConfig');
		parent::__construct($request, $response);
	}


	public function _adminCheckPermissions($model, $action = 'read', $id = null) {
		$Model = ClassRegistry::getObject($model);
		if (!$Model) {
			return false;
		}
		//really bad patch, fix with proper permissions
		if ($action == 'read') {
			return true;
		}
		if ($action == 'js_edit') {
			return true;
		}
		if (in_array($action, array('reorder', 'edit_upload', 'delete_upload'))) {
			$action = 'edit';
		}
		if ($action == 'filter') {
			$action = 'index';
		}
		if ($action == 'delete_multiple') {
			$action = 'delete';
		}
		if (!in_array($action, array('index', 'add', 'view', 'delete', 'edit', 'import', 'export'))) {
			return false;
		}
		$Model->Behaviors->attach('Admin.AdminPanel');
		if (!empty($this->Content)) {
			$actions = $Model->adminConfig['actions'];
			if (!$actions[$action]) {
				return false;
			}
		}
		return true;
	}


	public function arrayPermissions($model) {
		$ret = array(
			'view' => false,
			'add' => false,
			'view' => false,
			'edit' => false,
			'delete' => false,
			'import' => false,
			'index' => false,
		);
		foreach ($ret as $action => $value) {
			$ret[$action] = $this->_adminCheckPermissions($model, $action);
		}

		return $ret;
	}


}