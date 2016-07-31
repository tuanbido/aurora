<script type="text/javascript">
var adminConditionals = <?php echo json_encode($adminConfig['fields']['conditional_camelized']) ?>;
var adminModel = '<?php echo $model ?>';
$(document).ready(function(){
	$.each(adminConditionals, function(index, value) {
		$('#' + adminModel + index).change(function(){
			$.each(value.Hide, function(index, value){
				if (typeof value == 'object') {
					$.each(value, function(index, value2){
						$('#' + value2 + value2).parent().hide();
					});
				} else {
					$('#admin' + adminModel + value).hide();
				}
			});
			if ($(this).attr('type') == 'checkbox') {
				val = $(this).is(':checked') ? 1 : 0;
			} else {
				val = $(this).val();
			}
			toShow = value.ShowConditions[val];
			if (toShow) {
				$.each(toShow, function(index, value) {
					if (typeof value == 'object') {
						$.each(value, function(index, value2){
							$('#' + value2 + value2).parent().fadeIn();
						});
					} else {
						$('#admin' +  adminModel + value).fadeIn();
					}
				});
			}
		}).change();
	});
});
</script>