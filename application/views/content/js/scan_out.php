<script src="<?= base_url("assets/vendor/DataTables/datatables.min.js") ?>"></script>
<script>
	var table = $("#datatable").DataTable({
		responsive:true,
		columnDefs: [
                { "orderable": false, "targets": 1 } // Disable ordering on the second column (index 1)
            ],
		lengthMenu: [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		]
	});
	table.pageLength = -1;
	function toggleCheckboxes(source) {
		// Get all checkboxes with the name 'check-data[]'
		var checkboxes = document.getElementsByName('check-data[]');
		
		// Loop through all checkboxes and set their checked status to match the source checkbox
		if(source.checked){
			table.page.len(-1).draw(); // Set to "All" entries and redraw the table
		}else{
			table.page.len(10).draw(); // Set to "All" entries and redraw the table
		}
		for (var i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = source.checked;
		}
	}
	function submitCheckboxData() {
            // Get all checkboxes
            const checkboxes = document.querySelectorAll('input[name="check-data[]"]:checked');
		
            // Create an array of values
            const values = Array.from(checkboxes).map(checkbox => checkbox.value);
		if(!values){
			swalalert("Warning","Tidak ada data yang dipilih.","warning");
			return;
		}


            // Create JSON object
            const jsonData = JSON.stringify(values);
		var list_part = '<div class="row">';
		values.forEach(e => {
			parsingItem = e.split(" ");
			replaceID = e.replaceAll(" ","-");
			list_part += '<div class="col-1"><i class="fas fa-spinner fa-spin" id="loading-part-'+replaceID+'"></i></div><div class="col-11 text-left">'+parsingItem[0]+"<br>"+$("#part-name-"+replaceID).html()+'</div>';
		});
		list_part += '</div>';
		loading_page("Process Scan Out...",list_part);
		
		// Send data using fetch
		fetch('http://localhost:3000/api/scan_out_spd', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: jsonData
		})
		.then(response => response.json())
		.then(data => {
			if(data.message == "success"){
				var e = JSON.stringify(data.itemProcess);
				$.ajax({
					type:"post",
					url:'<?= base_url("update_scan_out"); ?>',
					data:{
						barcode:e,
					},
					dataType:"JSON",
					success:function(r) {
						d = JSON.parse(JSON.stringify(r));
						if(d.status == 200){
							$("#btn-syncronize").trigger("click");
						}else{
							console.log(e);
							swalalert("Error","Scan out "+e+" gagal.","error");
						}
					},
					error:function(a,b,c) {
						console.log(a.responseText);
					}
				});
			}
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	}
</script>
