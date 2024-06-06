<script src="<?= base_url("assets/vendor/DataTables/datatables.min.js") ?>"></script>
<script>
	var table = $("#datatable").DataTable({
		responsive:false,
		lengthMenu: [
			[10, 25, 50, 100, -1],
			[10, 25, 50, 100, "All"]
		],
		pageLength:-1,
		order: [[ 4, "asc" ], [ 6, "asc" ], [ 2, "asc" ]]
	});
	table.on('order.dt search.dt', function() {
		table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw();
	$("#card-count-cutting").html($("#count-cutting"));
	$("#card-count-single-part").html($("#count-single-part"));
	$("#card-count-sub-assy").html($("#count-sub-assy"));
	$("#card-count-na").html($("#count-na"));

	$("#div-reset-filter").hide();
	function searching(data) {
		type = data.dataset.type;
		table.search(type).draw();
		if(type){
			$("#div-reset-filter").show();
		}else{
			$("#div-reset-filter").hide();
		}
	}

	function setup_wos() {
		tanggal = $("#planning-date").val();
		window.open('<?= base_url("print_wos?tanggal="); ?>'+tanggal, '_blank');
	}
</script>
