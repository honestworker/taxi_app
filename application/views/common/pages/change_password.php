<div class="register-box">
	<?php
		$flash_data = $this->session->flashdata('flash_data');
		if ( !empty( $flash_data ) ) {
			if ( $flash_data['errors'] ) {
				foreach ( $flash_data['errors'] as $error) { ?>
					<div class="alert alert-error"><?php echo $error; ?></div>
				<?php }
			}
		}
	?>

    <div class="register-box-body">
        <div class="register-logo">
            <img style="height:100px;" src="<?php echo base_url();?>assets/custom/images/icon.jpg">
        </div>
        <form action="<?php echo site_url('change_password');?>" method="post">
            <input type="hidden" name="active_code"  value="<?php echo $active_code; ?>">
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="confirm_password" placeholder="Password Confirmation">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                </div>
                <!-- /.col -->
                <div class="col-xs-8">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Change Password</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        
        <a href="<?php echo site_url('login');?>">Log In</a><br>
        <a href="<?php echo site_url('signup');?>">Sign Up</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->