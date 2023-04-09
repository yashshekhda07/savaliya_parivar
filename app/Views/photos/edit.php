<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Photo Gallery</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Edit Photo Details</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/editPhotoData" enctype="multipart/form-data" style="margin-top:30px;">
          <input type="hidden" class="form-control" name="id"  value="<?php echo $photo->id; ?>">

          <div class="form-group" style="margin-top:20px;">
              <label>Photo Title</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="Photo Title" required="" autofocus="" value="<?php echo $photo->title; ?>">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Photo Description</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" name="description" placeholder="Photo Description" rows="5"><?php echo $photo->description; ?></textarea>
              </div>
          </div>



          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">Update Photo</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
