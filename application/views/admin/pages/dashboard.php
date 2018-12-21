<?php
$admin_counts = $driver_counts = $user_counts = 0;
if (isset($users_counts)) {
    if (isset($users_counts['admins'])) {
        $admin_counts = $users_counts['admins'];
    }
    if (isset($users_counts['drivers'])) {
        $driver_counts = $users_counts['drivers'];
    }
    if (isset($users_counts['users'])) {
        $user_counts = $users_counts['users'];
    }
}
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo $admin_counts;?></h3>
                    <p>Administrators</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?php echo (base_url() . 'admins'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $driver_counts;?></h3>
                    <p>Drivers</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-people""></i>
                </div>
                <a href="<?php echo (base_url() . 'drivers'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo $user_counts;?></h3>
                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people"></i>
                </div>
                <a href="<?php echo (base_url() . 'users'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->