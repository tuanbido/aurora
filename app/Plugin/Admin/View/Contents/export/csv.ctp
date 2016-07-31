<?php
foreach ($adminConfig['fields']['export'] as $field) {
	$fieldName = $field;
	if (!empty($adminConfig['fields']['names'][$field])) {
		$fieldName = $adminConfig['fields']['names'][$field];
	} else {
		$tmp = explode('.', $field); $relModel = $tmp[0]; $relField = $tmp[1];
		if (!empty($relatedBrwConfig[$relModel])) {
			$fieldName = __($relatedBrwConfig[$relModel]['names']['singular']) . ' ' .
			$relatedBrwConfig[$relModel]['fields']['names'][$relField];
		}
	}
	echo $fieldName . ',';
}
echo "\n";
foreach ($records as $record) {
	foreach ($adminConfig['fields']['export'] as $field) {
		if (strstr($field, '.')) {
			$tmp = explode('.', $field);
			$value = $record[$tmp[0]][$tmp[1]];
		} else {
			$value = $record[$model][$field];
		}
		echo str_replace(',', ' ', utf8_decode($value)) . ',';
	}
	reset($adminConfig['fields']['export']);
	echo "\n";
}