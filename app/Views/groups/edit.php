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
                <li class="breadcrumb-item active" aria-current="page">Edit Details</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/editGroupData" enctype="multipart/form-data" style="margin-top:30px;">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Branch (Only selected church will see this group)</label>
                <select class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>" <?php echo $group->branch==$res->id?"selected":""; ?>><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <input type="hidden" name="id" value="<?php echo $group->id; ?>">
          <div class="form-group" style="margin-top:20px;">
              <label> Group Leader Name</label>
              <div class="form-line">
                  <input type="text" value="<?php echo $group->leader; ?>" class="form-control" name="leader" placeholder="Group Leader Name" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label> Group Title</label>
              <div class="form-line">
                  <input type="text" value="<?php echo $group->title; ?>" class="form-control" name="title" placeholder="Group Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label> Group Description</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" name="description" placeholder="Group Description" required="" autofocus="" rows="5"><?php echo $group->description; ?></textarea>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label> Group Meeting Location</label>
              <div class="form-line">
                  <input type="text" value="<?php echo $group->location; ?>" class="form-control" name="location" placeholder="Group Meeting Location" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label> Group Meeting Days/Times (add days with tie seperated with a comma)</label>
              <div class="form-line">
                  <input type="text" value="<?php echo $group->time; ?>" class="form-control" name="time" placeholder="Group Meeting Days/Times" required="" autofocus="">
              </div>
          </div>

          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
