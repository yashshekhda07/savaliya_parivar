<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Prayer Requests</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Edit Prayer Request</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/editprayerdata" enctype="multipart/form-data" style="margin-top:30px;">
          <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $prayer->id; ?>">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Location/Branch (Prayer Request will be accessed by selected location/branch)</label>
                <select class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>" <?php echo $prayer->branch==$res->id?"selected":""; ?>><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Name of Requester</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="requester" value="<?php echo $prayer->requester; ?>" placeholder="Name of Testifier">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Prayer Request Title</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" value="<?php echo $prayer->title; ?>" placeholder="Testimony Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Prayer Request Content</label>
              <div class="form-line">
                  <textarea class="editor1" name="content"><?php echo $prayer->content; ?></textarea>
              </div>
          </div>


          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">Update Prayer</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
