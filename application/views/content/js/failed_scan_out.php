<script src="<?= base_url("assets/vendor/DataTables/datatables.min.js") ?>"></script>
<script>
	var table = $(".datatable").DataTable({
		responsive:true,
		columnDefs: [
                { "orderable": false, "targets": 1 } // Disable ordering on the second column (index 1)
            ],
		lengthMenu: [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		pageLength: -1
	});

	function cancelScanOut(id) {
		$.ajax({
			type:"get",
			url:'<?= base_url("cancel_scan_out"); ?>',
			data:{
				barcode:id,
			},
			dataType:"JSON",
			beforeSend:function() {
				loading_page("Cancel Scan Out...","");
			},
			success:function(r) {
				d = JSON.parse(JSON.stringify(r));
				if(d.status == 200){
					swal.fire({
						title:"Sukses",
						html:"Cancel scan out selesai",
						icon:"success",
					}).then((result) => {
						if(result.isConfirmed){
							location.reload();
						}
					})
				}else{
					swalalert("Error","Cancel Scan out gagal.","error");
				}
			},
			error:function(a,b,c) {
				console.log(a.responseText);
			}
		});
	}
</script>
