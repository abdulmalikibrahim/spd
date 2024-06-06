<!-- Bootstrap core JavaScript-->
<script src="<?= base_url("assets/vendor/jquery/jquery.min.js") ?>"></script>
<script src="<?= base_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url("assets/vendor/jquery-easing/jquery.easing.min.js") ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url("assets/js/sb-admin-2.min.js") ?>"></script>

<!-- Page level plugins -->
<script src="<?= base_url("assets/vendor/chart.js/Chart.min.js") ?>"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url("assets/js/demo/chart-area-demo.js") ?>"></script>
<script src="<?= base_url("assets/js/demo/chart-pie-demo.js") ?>"></script>
<script src="<?= base_url("assets/vendor/sweetalert2/dist/sweetalert2.all.min.js") ?>"></script>
<script>
	function loading_page(title,pesan,img_loading = false) {
		if(title == ''){
			title = "Please Wait...";
		}
		if(img_loading){
			title = "<font style='color:white'><div class='mb-3'><i class='fas fa-spinner fa-spin' style='font-size:5rem;'></i></div>"+title+"</font>"
		}else{
			title = "<font style='color:white'>"+title+"</font>"
		}
		swal.fire({
			title: title,
			html:"<font style='color:white'>"+pesan+"</font>",
			background:'rgba(0,0,0,0)',
			showConfirmButton: false,
			allowOutsideClick: false
		});
	}
	function swalalert(title,pesan,icon) {
		swal.fire({
			title: title,
			html:pesan,
			icon:icon,
		});
	}
	function sycnronize(data) {
		loading_page('<i class="fas fa-spinner fa-spin" style="font-size:5rem;"></i><br><div class="mt-4">Syncronize...</div>',"Mohon tunggu proses selesai.");
		url = data.dataset.url;
		fetch(url, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json'
			}
		})
		.then(response => response.json())
		.then(data => {
			if(data.message == "success"){
				swal.fire({
					title:"Sukses",
					html:"Syncronize selesai",
					icon:"success",
				}).then((result) => {
					if(result.isConfirmed){
						location.reload();
					}
				})
			}
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	}
</script>
<?php
if(!empty($js_add)){
	$this->load->view("content/js/".$js_add);
}
if(!empty($this->session->flashdata("swal"))){
	echo $this->session->flashdata("swal");
}
?>
