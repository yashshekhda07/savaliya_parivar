<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Groups</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><?php echo $group->title; ?></li>
                <li class="breadcrumb-item active" aria-current="page">Add Members to this group</li>
              </ol>
            </nav>
          </div>

        </div>
      </div>
      <!-- Default Basic Forms Start -->
      <div class="pd-20 card-box mb-30">
        <div class="pd-20 card-box mb-30">
          <?php if (session()->getFlashdata('success') !== NULL) : ?>
              <div class='alert alert-success mt-2'>
                  <?php echo session()->getFlashdata('success'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
          <?php endif; ?>
          <?php if (session()->getFlashdata('error') !== NULL) : ?>
              <div class='alert alert-danger mt-2'>
                  <?php echo session()->getFlashdata('error'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
          <?php endif; ?>

        <form method="POST" action="<?php echo base_url(); ?>/savenewmembersgroup" enctype="multipart/form-data" style="margin-top:30px;">
          <input type="hidden" name="id" value="<?php echo $group->id; ?>">
          <div class="form-group" style="margin-top:20px;">

            <label> Add Members to Group</label>
            <select name="members[]" class="selectpicker form-control" data-size="5" multiple data-actions-box="true" data-style="btn-outline-secondary">
              <?php foreach ($members as $res) { ?>
                  <option value="<?php echo $res->email; ?>"><?php echo $res->firstname. " ".$res->lastname." (".$res->email.")"; ?></option>
              <?php } ?>
            </select>
          </div>



          <div class="box-footer text-center" style="margin-top:50px;">
             <button class="btn btn-primary waves-effect" type="submit">ADD To GROUP</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
