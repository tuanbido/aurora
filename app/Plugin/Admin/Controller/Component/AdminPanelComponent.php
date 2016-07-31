<?php

class AdminPanelComponent extends Component{

	public $controller;
	public $isAdminPanel;

	public function initialize(Controller $Controller, $settings = array()) {
		$this->controller = $Controller;
		$this->isAdminPanel = (
			(!empty($Controller->request->params['prefix']) and $Controller->request->params['prefix'] == 'admin')
			or
			$Controller->params['plugin'] == 'admin'
  		);

		ClassRegistry::init('AdminUser')->Behaviors->attach('Admin.AdminUser');
		ClassRegistry::init('AdminImage')->Behaviors->attach('Admin.AdminUpload');
		ClassRegistry::init('AdminFile')->Behaviors->attach('Admin.AdminUpload');
		if (!empty($Controller->request->params['prefix']) and $Controller->request->params['prefix'] == 'admin') {
			if (!class_exists('AuthComponent')) {
				$Controller->Components->load('Auth', Configure::read('adminAuthConfig'));
			} else {
				foreach (Configure::read('adminAuthConfig') as $key => $value) {
					$Controller->Auth->{$key} = $value;
				}
			}
			App::build(array('views' => ROOT . DS . APP_DIR . DS . 'Plugin' . DS . 'Admin' . DS . 'View' . DS));
			$Controller->helpers[] = 'Js';
			$Controller->layout = 'admin_default';
			if (!empty($Controller->modelClass)) {
				$Controller->{$Controller->modelClass}->attachBackend();
			}
		}

		if ($this->isAdminPanel) {
			AuthComponent::$sessionKey = 'Auth.AdminUserLogged';
			$this->_menuConfig();
		}

		if (Configure::read('Config.languages')) {
			$langs3chars = array();
			$l10n = new L10n();
			foreach ((array)Configure::read('Config.languages') as $lang) {
				$catalog = $l10n->catalog($lang);
				$langs3chars[$lang] = $catalog['localeFallback'] ;
			}
			Configure::write('Config.langs', $langs3chars);
		}
	}


	public function beforeRender(Controller $controller) {
		if ($this->isAdminPanel) {
			$controller->set(array(
				'companyName' => Configure::read('adminSettings.companyName'),
				'adminHideMenu' => $controller->Session->read('admin.hideMenu')
			));
		}
		$this->controller->set('adminSettings', Configure::read('adminSettings'));
	}


	public function _menuConfig() {
		if (AuthComponent::user('id')) {
			$authModel = AuthComponent::user('model');
			if ($authModel != 'AdminUser') {
				$menu = $this->controller->adminMenuPerAuthUser[$authModel];
			} elseif (!empty($this->controller->adminMenu)) {
				$menu = $this->controller->adminMenu;
			} else {
				$menu = array();
				$models = App::objects('model');
				foreach($models as $model) {
					if (!in_array($model, array('AdminUser', 'AdminImage', 'AdminFile', 'AppModel'))) {
						$button = Inflector::humanize(Inflector::underscore(Inflector::pluralize($model)));
						$menu[$button] = $model;
					}
				}
				$menu = array(__d('brownie', 'Menu') => $menu);
			}
			$this->controller->adminMenu = $menu;
			$this->controller->set('adminMenu', $menu);
		}
	}


}