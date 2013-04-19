function set_kalender() {
	$('.kalender').datepicker({
		dateFormat: 'dd-MM-yy',
	});
}

$(document).ready(function() {
	$('div#admin-tabs').tabs();
});