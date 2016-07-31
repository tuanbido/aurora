<?php
App::uses('FormAuthenticate', 'Controller/Component/Auth');

class AdminAuthenticate extends FormAuthenticate {


	public function authenticate(CakeRequest $request, CakeResponse $response) {

		foreach (Configure::read('adminSettings.userModels') as $userModel) {
			$this->settings['userModel'] = $userModel;
			$request->data[$userModel] = $request->data['AdminUser'];
	        $authenticated = parent::authenticate($request, $response);
	        if ($authenticated) {
	        	ClassRegistry::init($userModel)->updateLastLogin($authenticated['id']);
				return array_merge($authenticated, array('model' => $userModel));
			}
		}
		$newUser = ClassRegistry::init('AdminUser')->checkAndCreate(
			$request->data['AdminUser']['email'],
			$request->data['AdminUser']['password']
		);
		if ($newUser) {
			return array_merge($newUser, array('model' => 'AdminUser'));
		}
		return false;
    }

}