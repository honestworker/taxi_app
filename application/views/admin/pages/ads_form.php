<?php
$method = 'create';
if (isset($ad)) {
    if ($ad) {
        $method = 'edit';
    }
}
?>
<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header">
            <h3 class="box-title">
            <h3 class="box-title" style="margin-top: 10px;">&nbsp;&nbsp;&nbsp;<b>
            <?php if ($method == 'edit') {
                ?>Edit <?php
            } else {
                ?>Create <?php
            }
            ?>
            Advertisement</b></h3>
            <br><br>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
		    <form action="<?php echo site_url('ads/store');?>" method="post" enctype="multipart/form-data">
                <div class="col-md-6 col-xs-12">
                    <input class="hidden" type="text" name="id" id="ad_id" value="<?php if ($method == 'edit') echo $ad->id;?>">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" id="ad_name" value="<?php if ($method == 'edit') echo $ad->name;?>" required />
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input class="form-control" type="text" name="link" id="ad_link" value="<?php if ($method == 'edit') echo $ad->link;?>" required />
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" id="ad_status">
                            <option <?php if ($method == 'edit') { if ($ad->status == 'active') echo 'selected'; }?> value="active">Active</option>
                            <option <?php if ($method == 'edit') { if ($ad->status == 'disable') echo 'selected'; }?> value="disable">Disable</option>
                        </select>
                    </div>
                    <?php if ($method == 'edit') { ?>
                    <div class="form-group">
                        <label>Created At</label>
                        <span class="form-control"><?php echo $ad->created_at;?></span>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php if ($method == 'edit') echo "Upadate"; else echo "Create";?></button>
                        <button type="button" class="btn btn-danger"><a style="color: white;" href="<?php echo base_url(); ?>ads">Cancel</a></button>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <input type="file" id="images" name="images[]" <?php if ($method != 'edit') echo "required";?> />
                    <output id="list">
                    <?php if ($method == 'edit') { ?>
                        <span>
                            <img class="thumb" src="<?php echo (base_url() . 'public/images/ads/' . $ad->image); ?>">
                        </span>
                    <?php } ?>
                    </output>
                </div>
            </form>
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