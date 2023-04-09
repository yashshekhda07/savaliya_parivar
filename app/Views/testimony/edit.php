<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Testimonies</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Edit Testimony</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/edittestimonydata" enctype="multipart/form-data" style="margin-top:30px;">
          <input type="hidden" class="form-control" name="id" required="" autofocus="" value="<?php echo $testimony->id; ?>">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Location/Branch (Testimony will be accessed by selected location/branch)</label>
                <select class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>" <?php echo $testimony->branch==$res->id?"selected":""; ?>><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Name of Testifier</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="testifier" value="<?php echo $testimony->testifier; ?>" placeholder="Name of Testifier">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Testimony Title</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" value="<?php echo $testimony->title; ?>" placeholder="Testimony Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Testimony Content</label>
              <div class="form-line">
                  <textarea class="editor1" name="content"><?php echo $testimony->content; ?></textarea>
              </div>
          </div>


          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">Update Testimony</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
