<script src="<?= base_url("assets/vendor/DataTables/datatables.min.js") ?>"></script>
<script>
	var table = $("#datatable").DataTable({
		responsive:false,
		lengthMenu: [
			[10, 25, 50, 100, -1],
			[10, 25, 50, 100, "All"]
		],
		pageLength:-1
	});

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

	$("#card-process").hide();
	qty_process = 0;
	function sum_qty(data) {
		qty = parseInt(data.dataset.qty);
		id = data.dataset.id;
		checked = $("#plan-"+id).prop("checked");
		if(checked){
			qty_process += qty;
		}else{
			qty_process -= qty;
		}
		if(qty_process > 0){
			$("#card-process").show();
		}else{
			$("#card-process").hide();
		}
		$("#qty_process").html(qty_process);
		console.log(checked,qty);
	}

	function process_wos() {
		table.search('').draw();
		$("#form-wos").submit();
	}

	function setup_wos() {
		window.open('<?= base_url("print_wos"); ?>', '_blank');
	}
</script>
