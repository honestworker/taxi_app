<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title" style="margin-top: 10px;">&nbsp;&nbsp;&nbsp;<b>Drivers</b></h3>
            <br><br>
        </div>
        <!-- /.box-header -->
        <div class="box-body driver-table">
            <table id="driver_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Avatar</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Email Verified</th>
                <th>Phone Verified</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($drivers as $driver) {
                ?>
                <tr data-id="<?php echo $driver->id; ?>">
                    <td><?php
                        if ($driver->avatar) {
                            echo '<img src="/public/images/users/' .  $driver->avatar . '" style="max-height: 100px;">';
                        } else {
                            echo '<img src="/public/images/users/no_avatar.jpg" style="max-height: 100px;">';
                        }
                    ?></td>
                    <td><?php echo $driver->first_name; ?></td>
                    <td><?php echo $driver->last_name; ?></td>
                    <td><?php echo $driver->email; ?></td>
                    <td><?php echo $driver->phone_number; ?></td>
                    <td class="text-center">
                        <?php
                        if ($driver->email_confirmed) {
                        ?>
                        <span class="label label-primary">O</span>
                        <?php
                        } else {
                        ?>
                        <span class="label label-warning">X</span>
                        <?php
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php
                        if ($driver->phone_confirmed) {
                        ?>
                        <span class="label label-primary">O</span>
                        <?php
                        } else {
                        ?>
                        <span class="label label-warning">X</span>
                        <?php
                        }
                        ?>
                    </td>
                    <td><?php echo $driver->created_at; ?></td>
                    <td>
                    <?php if ( $driver->status == 'activated' ) { ?>
                        <span class="label label-success" style="font-size: 85%;"><?php echo $driver->status; ?></span>
                    <?php } else { ?>
                        <span class="label label-warning" style="font-size: 85%;"><?php echo $driver->status; ?></span>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if ($driver->status == 'activated') { ?>
                        <button type="button" class="btn btn-warning" onclick="viewActionUserModal('driver', 'disable', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-success" onclick="viewActionUserModal('driver', 'active', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } ?>
                        <button type="button" class="btn btn-danger" onclick="viewActionUserModal('driver', 'delete', $(this).parent().parent().data('id'))"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Avatar</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Email Verified</th>
                <th>Phone Verified</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<!-- Active Driver Modal -->
<div class="modal modal-info fade" id="driver-active-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Active Driver</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to active this Driver?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary active-driver" onclick="actionUser('driver', 'active')">Active</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Disable Driver Modal -->
<div class="modal modal-info fade" id="driver-disable-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Disable Driver</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to disable this Driver?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning disable-driver" onclick="actionUser('driver', 'disable')">Disable</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Delete Driver Modal -->
<div class="modal modal-info fade" id="driver-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete Driver</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this Driver?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-driver" onclick="actionUser('driver', 'delete')">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>