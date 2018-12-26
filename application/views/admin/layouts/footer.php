    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2018-2019 <a href="https://github.com/honestworker">Honest Worker</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url();?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url();?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url();?>assets/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url();?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- Growl -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-growl-master/jquery.bootstrap-growl.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url();?>assets/dist/js/pages/dashboard.js"></script>
<!-- Custom -->
<script src="<?php echo base_url();?>assets/custom/js/custom.js"></script>

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

</body>

</html>