	<!-- jQuery 3 -->
	<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Growl -->
	<script src="<?php echo base_url();?>assets/bower_components/bootstrap-growl-master/jquery.bootstrap-growl.min.js"></script>
	<!-- iCheck -->
	<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
	<!-- Custom -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/custom/css/style.css">
	<script>
	$(function() {
		<?php
			$flash_data = $this->session->flashdata('flash_data');
			if ( !empty( $flash_data ) ) {
				if ( $flash_data['alerts']['error'] ) {
					foreach ( $flash_data['alerts']['error'] as $error) { ?>
						setTimeout(function() {
							$.bootstrapGrowl('<?php echo $error; ?>', {
								type: 'danger',
								allow_dismiss: true
							});
						}, 300);
					<?php }
				}
				if ( $flash_data['alerts']['info'] ) {
					foreach ( $flash_data['alerts']['info'] as $info) { ?>
						setTimeout(function() {
							$.bootstrapGrowl('<?php echo $info; ?>', {
								type: 'info',
								allow_dismiss: true
							});
						}, 300);
					<?php }
				}
				if ( $flash_data['alerts']['success'] ) {
					foreach ( $flash_data['alerts']['success'] as $success) { ?>
						setTimeout(function() {
							$.bootstrapGrowl('<?php echo $success; ?>', {
								type: 'success',
								allow_dismiss: true
							});
						}, 300);
					<?php }
				}
			}
		?>
	});
	</script>
	<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' /* optional */
			});
		});
	</script>
</body>
</html>
