$(function() {
	"use strict";

	if ($('.data-table').length) {
		$('.data-table').DataTable({
			lengthMenu: [[10, 30, 50, -1], [10, 30, 50, 'Show all']],
			order: []
		});
	}

	if ($('.data-table-all').length) {
		$('.data-table-all').DataTable({
			lengthMenu: [[-1], ['All']],
			order: []
		});
	}

	if ($('.data-table-created').length) {
		$('.data-table-created').DataTable({
			lengthMenu: [[-1], ['All']],
			order: [[7, 'desc']]
		});
	}

	if ($('.pagination_table').length) {
		$('.pagination_table').DataTable({
			ordering: false,
			order: [],
			"info": false,
			"paging": false,
			"searching": false,
			"autoWidth": false,
			
		});
	}
	if ($('.scrollable_table').length) {
		$('.scrollable_table').DataTable({
			lengthMenu: [[10, 30, 50, -1], [10, 30, 50, 'Show all']],
			ordering: true,
			order: [],
			"info": false,
			"paging": false,
			"searching": false,
			"autoWidth": false,
		});
	}
	if ($('#all-reports').length) {
		var table = $('#all-reports').DataTable({
			lengthMenu: [[-1], ['Show all']],order: [],
			buttons: [ 'copy', 'excel', 'pdf', 'print'],
		});
		table.buttons().container().appendTo( '#all-reports_wrapper .col-md-6:eq(0)' );
	}
	
	var table = $('#example2').DataTable({
		lengthChange: false,
		buttons: [ 'copy', 'excel', 'pdf', 'print']
	});
	table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
});