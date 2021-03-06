<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title" style="margin-top: 10px;">&nbsp;&nbsp;&nbsp;<b>Advertisements</b></h3>
            <br><br>
            <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> <a style="color: white;" href="<?php echo base_url(); ?>ads/create">Create Advertisement</a></button>
        </div>
        <!-- /.box-header -->
        <div class="box-body ad-table">
            <table id="ad_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Link</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($ads as $ad) {
                ?>
                <tr data-id="<?php echo $ad->id; ?>">
                    <td><?php echo $ad->name; ?></td>
                    <td><img src="<?php echo (base_url() . 'public/images/ads/' . $ad->image); ?>" style="height: 100px;"></td>
                    <td><?php echo $ad->link; ?></td>
                    <td><?php echo $ad->created_at; ?></td>
                    <td>
                    <?php if ( $ad->status == 'active' ) { ?>
                        <span class="label label-success" style="font-size: 85%;"><?php echo $ad->status; ?></span>
                    <?php } else { ?>
                        <span class="label label-warning" style="font-size: 85%;"><?php echo $ad->status; ?></span>
                    <?php } ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info"><a style="color: white;" href="<?php echo base_url() . 'ads/edit/' . $ad->id;?>"><i class="fa fa-eye"></i></a></button>                        
                    <?php if ($ad->status == 'active') { ?>
                        <button type="button" class="btn btn-warning" onclick="viewActionAdModal('ad', 'disable', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-success" onclick="viewActionAdModal('ad', 'active', $(this).parent().parent().data('id'))"><i class="fa fa-check"></i></button>
                    <?php } ?>
                        <button type="button" class="btn btn-danger" onclick="viewActionAdModal('ad', 'delete', $(this).parent().parent().data('id'))"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Link</th>
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

<!-- Active Advertisement Modal -->
<div class="modal modal-info fade" id="ad-active-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Active Advertisement</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to active this Advertisement?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary active-ad" onclick="actionAd('ad', 'active')">Active</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Disable Advertisement Modal -->
<div class="modal modal-info fade" id="ad-disable-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Disable Advertisement</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to disable this Advertisement?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning disable-ad" onclick="actionAd('ad', 'disable')">Disable</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Delete Advertisement Modal -->
<div class="modal modal-info fade" id="ad-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Delete Advertisement</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delte this Advertisement?</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete-ad" onclick="actionAd('ad', 'delete')">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>