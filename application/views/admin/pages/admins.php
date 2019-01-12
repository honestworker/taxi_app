<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title" style="margin-top: 10px;">&nbsp;&nbsp;&nbsp;<b>Administrators</b></h3>
            <br><br>
        </div>
        <!-- /.box-header -->
        <div class="box-body admin-table">
            <table id="admin_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($admins as $admin) {
                ?>
                <tr data-id="<?php echo $admin->id; ?>">
                    <td><?php echo $admin->first_name; ?></td>
                    <td><?php echo $admin->last_name; ?></td>
                    <td><?php echo $admin->email; ?></td>
                    <td><?php echo $admin->created_at; ?></td>
                    <td>
                    <?php if ( $admin->status == 'activated' ) { ?>
                        <span class="label label-success" style="font-size: 85%;"><?php echo $admin->status; ?></span>
                    <?php } else { ?>
                        <span class="label label-warning" style="font-size: 85%;"><?php echo $admin->status; ?></span>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if ($admin->status == 'activated') { ?>
                        <button type="button" class="btn btn-warning" onclick="viewActionUserModal('admin', 'disable', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-success" onclick="viewActionUserModal('admin', 'active', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } ?>
                        <button type="button" class="btn btn-danger" onclick="viewActionUserModal('admin', 'delete', $(this).parent().parent().data('id'))"><i class="fa fa-trash"></i></button>
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

<!-- Active Admin Modal -->
<div class="modal modal-info fade" id="admin-active-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Active Administrator</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to active this administrator?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary active-admin" onclick="actionUser('admin', 'active')">Active</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Disable Admin Modal -->
<div class="modal modal-info fade" id="admin-disable-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Disable Administrator</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to disable this administrator?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning disable-admin" onclick="actionUser('admin', 'disable')">Disable</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Delete Admin Modal -->
<div class="modal modal-info fade" id="admin-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete Administrator</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this administrator?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-admin" onclick="actionUser('admin', 'delete')">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>