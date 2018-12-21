<div class="login-box">	
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

	<!-- /.login-logo -->
	<div class="login-box-body">
		<div class="login-logo">
			<img style="height:100px;" src="<?php echo base_url();?>assets/custom/images/icon.jpg">
		</div>
		<form action="<?php echo site_url('login');?>" method="post">
			<div class="form-group has-feedback">
				<input type="email" class="form-control" name="email" placeholder="Email">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" name="password" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
				<!-- /.col -->
			</div>
		</form>
		
		<!--<div class="social-auth-links text-center">-->
		<!--	<p>- OR -</p>-->
		<!--	<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using-->
		<!--	Facebook</a>-->
		<!--	<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using-->
		<!--	Google+</a>-->
		<!--</div>-->
		<!-- /.social-auth-links -->
		
		<a href="<?php echo site_url('forgot');?>">Forgot password</a><br>
		<a href="<?php echo site_url('signup');?>">Sign Up</a>
	
	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->