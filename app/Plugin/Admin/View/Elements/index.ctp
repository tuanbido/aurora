<div class="model-index" id="<?php echo $model ?>_index">

<div class="index clearfix">
	<h2><?php echo __($adminConfig['names']['plural'], true) ?></h2>
	<div class="actions">
		<ul>
		<?php
		if ($permissions[$model]['add']) {
			echo '
			<li class="add">' . $this->Html->link(
				CakeText::insert(
					__d('brownie', 'Add :name_singular', true),
					array('name_singular' => __($adminConfig['names']['singular']))
				),
				array_merge(array('action' => 'edit', $model), $filters)
			) . '</li>';
		}
		if ($adminConfig['actions']['import']) {
			echo '
			<li class="import">' . $this->Html->link(
				$adminConfig['action_labels']['import'],
				array('action' => 'import', $model)
			) . '</li>';
		}
		if ($adminConfig['actions']['export']) {
			$url = array_merge(array('action' => 'export', $model), $filters, $this->params['named']);
			echo '<li class="export">' . $this->Html->link($adminConfig['action_labels']['export'], $url) . '</li>';
		}

		foreach ($adminConfig['global_custom_actions'] as $customAction => $params) {
			$params['url'] = array_merge($params['url'], $this->params['named']);
			echo '
			<li class="global_custom_action ' . $params['class'] . '">
			' . $this->Html->link(__($params['title']), $params['url'], $params['options'], __($params['confirmMessage'])) . '
			</li>';
		}

		?>
		</ul>
	</div>
</div>
<?php
if ($calledFrom == 'index') {
	echo $this->element('form_filter', array('adminConfig' => $adminConfig));
}


$i = 0;
//pr($this->Paginator);
if ($records) {
	$controlsOnTop = (
		$this->Paginator->request['params']['paging'][$model]['options']['limit'] >= 20
		and
		$this->Paginator->request['params']['paging'][$model]['count'] >= 20
	);
	if ($controlsOnTop) {
		echo $this->element('pagination', array('model' => $model, 'adminConfig' => $adminConfig));
	}

	if ($adminConfig['actions']['delete']) {
		echo $this->Form->create('Content', array(
			'id' => 'deleteMultiple',
			'url' => array('controller' => 'contents', 'action' => 'delete_multiple', $model)
		));
		$deleteButton = '
		<div class="submit"><button><span>' . __d('brownie', 'Delete selected') . '</span></button></div>
		';
		if ($controlsOnTop) {
			echo $deleteButton;
		}
	}

	echo '<table id="index">';

	foreach ($records as $record):
		if ($i == 0) {
			echo '<tr>';
			if ($adminConfig['actions']['delete']) {
				echo '
				<th class="delete_multiple">
					<input type="checkbox" id="deleteCheckAll" title="' . __d('brownie', 'Select/Unselect all') . '">
				</th>';
			}
			foreach ($adminConfig['paginate']['fields'] as $field_name) {
				if (!empty($schema[$field_name])) {
					echo '
					<th class="' . $field_name . ' ' . $schema[$field_name]['class'] . '">
					' . $this->Paginator->sort(
						$field_name,
						__($adminConfig['fields']['names'][$field_name]),
						array('model' => $model, 'escape' => false)
					) . '</th>';
					if ($field_name == 'id') {
						foreach ($adminConfig['paginate']['images'] as $indexImageKey) {
							echo '
							<th class="index_image">
								' . $adminConfig['images'][$indexImageKey]['name_category'] . '
							</th>';
						}
					}
				}
			}
			if (
				$adminConfig['actions']['edit'] and
				(($adminConfig['sortable'] and empty($this->params['named']['sort'])) or !empty($isTree))
			) {
				echo '<th class="actions sortable">' . __d('brownie', 'Sort') . '</th>';
			}

			echo '<th class="actions">' . __d('brownie', 'Actions') . '</th>';

			echo '
			</tr>';
		}

		$class = ($adminConfig['actions']['delete']) ? 'row_delete_multiple' : '';

		echo '
		<tr class="'.$class.' list">';
		if ($adminConfig['actions']['delete']) {
			echo '
			<td class="delete_multiple">
				<input type="checkbox" name="data[Content][id][]" value="' . $record[$model]['id'] . '">
			</td>';
		}
		foreach($adminConfig['paginate']['fields'] as $field_name) {
			if (!empty($schema[$field_name])) {
				echo '
				<td class="' . $field_name . ' ' . $schema[$field_name]['class'] . ' field">
				' . (($record[$model][$field_name] === null or $record[$model][$field_name] === '') ? '&nbsp;' : $record[$model][$field_name] ) . '
				</td>';
				if ($field_name == 'id') {
					foreach ($adminConfig['paginate']['images'] as $indexImageKey) {
						echo '<td class="index_image field">';
						if (!empty($record['BrwImage'][$indexImageKey]['tag'])) {
							echo $record['BrwImage'][$indexImageKey]['tag'];
						} else {
							echo '&nbsp;';
						}
						echo '</td>';
					}
				}
			}
		}

		if (
			$adminConfig['actions']['edit'] and
			(($adminConfig['sortable'] and empty($this->params['named']['sort'])) or !empty($isTree))
		): ?>
			<td class="sortable actions">
			<?php
			echo $this->Html->link(__d('brownie', 'Sort up'),
				array('controller' => 'contents', 'action' => 'reorder', $model, 'up', $record[$model]['id']),
				array('class' => 'up', 'title' => __d('brownie', 'Sort up'))
			);
			echo $this->Html->link(__d('brownie', 'Sort down'),
				array('controller' => 'contents', 'action' => 'reorder', $model, 'down', $record[$model]['id']),
				array('class' => 'down', 'title' => __d('brownie', 'Sort down'))
			);
			?>
			</td>
		<?php endif ?>
			<td class="actions">
			<?php
			$paramsAction = array('filters' => $filters, 'record' => $record, 'model' => $model, 'calledFrom' => $calledFrom);
			echo $this->element('actions', $paramsAction);
			?>
			</td>
		</tr>
	<?php
		$i++;
	endforeach;
	echo '</table>';
	if ($adminConfig['actions']['delete']) {
		echo $deleteButton;
		echo $this->Form->end();
	}
} else {
	echo '<p class="norecords">'
	. __d('brownie', 'There are no %s', __($adminConfig['names']['plural']))
	. '</p>';
};

if ($records) {
	echo $this->element('pagination', array('model' => $model, 'adminConfig' => $adminConfig));
	//$this->Paginator = null;
}
?>
</div>