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
        <form action="<?php echo site_url('signup');?>" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="first_name" placeholder="First name">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="confirm_password" placeholder="Password Confirmation">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign Up</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        
        <!-- <div class="social-auth-links text-center">-->
        <!--    <p>- OR -</p>-->
        <!--    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using-->
        <!--Facebook</a>-->
        <!--    <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using-->
        <!--Google+</a>-->
        <!--</div> -->
        
        <a href="<?php echo site_url('login');?>" class="text-center">Log In</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->