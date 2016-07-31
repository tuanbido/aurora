<?php

class AdminBackendBehavior extends ModelBehavior {

	public $cachedBelongsTo;

	public function setup(Model $Model, $config = array()) {
		// I don't know why $Model->belongsTo is empty in BrwBackendBehavior::beforeFind(), so I have to cache it
		$Model->cachedBelongsTo = $Model->belongsTo;
	}


	public function beforeFind(Model $Model, $query) {
		$authModel = AuthComponent::user('model');
		$authId = AuthComponent::user('id');
		if ($authModel and $authModel != 'AdminUser' and !empty($Model->adminConfigPerAuthUser[$authModel])) {
			switch ($Model->adminConfigPerAuthUser[$authModel]['type']) {
				case 'owned':
					if ($Model->name == $authModel) {
						$query['conditions'][$Model->name . '.id'] = $authId;
					} elseif (!empty($Model->cachedBelongsTo[$authModel])) {
						$fk = $Model->cachedBelongsTo[$authModel]['foreignKey'];
						$query['conditions'][$Model->name . '.' . $fk] = $authId;
					}
				break;
				case 'custom':
					$query = Set::merge($query, $Model->adminCustomQuery($authModel, $authId));
				break;
			}
		}
		return $query;
	}


}