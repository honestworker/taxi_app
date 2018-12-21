<?php
$admin_url = $_SERVER['REQUEST_URI'];
$admin_url_array = explode('/', $admin_url);
$first_url = $main_url = "";
if ($admin_url_array) {
    if (count($admin_url_array)) {
        if (count($admin_url_array) > 1) {
            $main_url = $admin_url_array[1];
        }
    }
}
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo (base_url() . 'public/images/users/' . $this->session->userdata('avatar')); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $this->session->userdata('name'); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php if ($main_url == '' || $main_url == 'dashboard') { echo 'active menu-open'; } ?>"><a href="<?php echo base_url();?>admin"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="treeview <?php if ($main_url == 'admins' || $main_url == 'drivers' || $main_url == 'users') { echo 'active menu-open'; } ?>">
                <a href="#">
                    <i class="fa fa-user-circle-o"></i> <span>Account</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if ($main_url == 'admins') { echo 'active'; } ?>"><a href="<?php echo base_url(); ?>admins"><i class="fa fa-user-secret"></i> Admins</a></li>
                    <li class="<?php if ($main_url == 'drivers') { echo 'active'; } ?>"><a href="<?php echo base_url(); ?>drivers"><i class="fa fa-car"></i> Drivers</a></li>
                    <li class="<?php if ($main_url == 'users') { echo 'active'; } ?>"><a href="<?php echo base_url(); ?>users"><i class="fa fa-user-o"></i> Users</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url();?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <?php 
                if ($main_url == 'admins' || $main_url == 'drivers' || $main_url == 'users') {
            ?>
            <li class="active">
            <?php
                if ($main_url == 'admins') {
                    ?><a href="<?php echo base_url();?>admins">Admins</a><?php
                } else if ($main_url == 'drivers') {
                    ?><a href="<?php echo base_url();?>drivers">Drivers</a><?php
                } else if ($main_url == 'users') {
                    ?><a href="<?php echo base_url();?>users">Users</a><?php
                }
            ?>
            </li>
            <?php
                }
            ?>
        </ol>
    </section>