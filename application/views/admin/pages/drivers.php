<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title">Drivers</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body driver-table">
            <table id="driver_table" class="table table-bordered table-striped">
            <thead>
            <tr>
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
                    <td><?php echo $driver->status; ?></td>
                    <td>
                        <?php
                        if ($driver->status == 'disabled') {
                        ?>
                            <button type="button" class="btn btn-success"><i class="fa fa-check"></i></button>
                        <?php
                        } else {
                        ?>
                            <button type="button" class="btn btn-warning"><i class="fa fa-check"></i></button>
                        <?php
                        }
                        ?>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
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
                <button type="button" class="btn btn-primary active-driver">Active</button>
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
                <button type="button" class="btn btn-warning disable-driver">Disable</button>
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
                <button type="button" class="btn btn-danger delete-driver">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>