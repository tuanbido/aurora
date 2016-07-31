<?php
include ROOT . DS . 'app' . DS . 'Lib' . DS . 'PHPExcel' . DS . 'PHPExcel.php';
include ROOT . DS . 'app' . DS . 'Lib' . DS . 'PHPExcel' . DS . 'PHPExcel' . DS . 'Writer' . DS . 'Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

foreach ($adminConfig['fields']['export'] as $col => $field) {
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
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $fieldName);
}

foreach ($records as $row => $record) {
	foreach ($adminConfig['fields']['export'] as $col => $field) {
		if (strstr($field, '.')) {
			$tmp = explode('.', $field);
			$value = $record[$tmp[0]][$tmp[1]];
		} else {
			$value = $record[$model][$field];
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row + 2, $value);
	}
	reset($adminConfig['fields']['export']);
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');