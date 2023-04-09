<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Livestream Channels</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Livestream</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/savenewlivestream" enctype="multipart/form-data" style="margin-top:30px;">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Location/Branch (Livestream will be accessed by selected location/branch)</label>
                <select class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div id="upload_div" style="margin-top:20px;">
            <div class="form-group">
                <label>Livestream CoverPhoto (Please resize your image before uploading)</label>
                <div class="form-line">
                    <input name="thumbnail" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" required data-height="100">
                </div>
            </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Livestream Title</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="Livestream Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Livestream Description</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="description" placeholder="Livestream Description" required="" autofocus="" required>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label>Livestream Source</label>
                <select class="form-control" name="source" required="" autofocus="">
                  <option value="youtube">Youtube Live Video ID</option>
                  <option value="facebook" >Facebook Live Embed Link</option>
                  <option value="m3u8" >M3U8</option>
                  <option value="rtmp" >RTMP</option>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Livestream Link</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="link" placeholder="Livestream Link" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label>Livestream Status  (When you set to not live, users wont see the livestream link on the app)</label>
                <select class="form-control" name="status" required="" autofocus="">
                  <option value="0">Live</option>
                  <option value="1" selected>Not Live</option>
                </select>
              </div>
          </div>


          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">SAVE NEW</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
