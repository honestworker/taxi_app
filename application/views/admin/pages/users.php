<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title">Users</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body user-table" style="overflow: auto;">
            <table id="user_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Avatar</th>
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
            foreach($users as $user) {
                ?>
                <tr data-id="<?php echo $user->id; ?>">
                    <td><?php
                        if ($user->avatar) {
                            echo '<img src="/public/images/users/' .  $user->avatar . '" style="max-height: 100px;">';
                        } else {
                            echo '<img src="/public/images/users/no_avatar.jpg" style="max-height: 100px;">';
                        }
                    ?></td>
                    <td><?php echo $user->first_name; ?></td>
                    <td><?php echo $user->last_name; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td><?php echo $user->created_at; ?></td>
                    <td><?php echo $user->status; ?></td>
                    <td>
                        <?php
                        if ($user->status == 'disabled') {
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
                <th>Avatar</th>
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

<!-- Active User Modal -->
<div class="modal modal-info fade" id="user-active-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Active User</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to active this User?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary active-user">Active</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Disable User Modal -->
<div class="modal modal-info fade" id="user-disable-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Disable User</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to disable this User?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning disable-user">Disable</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Delete User Modal -->
<div class="modal modal-info fade" id="user-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete User</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this User?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-user">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>