<script>
	$("#scan").focus();
	$("#form-processing").submit(function() {
		$("#btn-scan").html('<i class="fas fa-spinner fa-spin"></i> PROCESSING...');
	});
	function cancel_process(data) {
		url = data.dataset.url;
		id = data.dataset.id;
		window.location.href = url;
		$("#btn-cancel-"+id).html('<i class="fas fa-spinner fa-spin"></i>');
	}
</script>
