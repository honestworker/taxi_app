<div class="login-box">	
	<!-- /.login-logo -->
	<div class="login-box-body">
		<div class="login-logo">
			<img style="height:100px;" src="<?php echo base_url();?>assets/custom/images/icon.jpg">
		</div>
		<form action="<?php echo site_url('forgot');?>" method="post">
			<div class="form-group has-feedback">
				<input type="email" class="form-control" name="email" placeholder="Email">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Send Code</button>
				</div>
				<!-- /.col -->
			</div>
		</form>
		
		<a href="<?php echo site_url('login');?>">Log In</a><br>
		<a href="<?php echo site_url('signup');?>">Sign Up</a>
	
	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->