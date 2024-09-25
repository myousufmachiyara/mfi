/*
Name: 			Tables / Advanced - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	4.0.0
*/

(function($) {

	'use strict';

	var datatableInit = function() {

		$('#datatable-default').dataTable({
			dom: '<"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>', // Removed pagination part
			"order": [[0, "desc"]],
			"pageLength": 25
		});

	};

	

	$(function() {
		datatableInit();
	});

}).apply(this, [jQuery]);