<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Group Events/Activities</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Event</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/savenewgroupevent" enctype="multipart/form-data" style="margin-top:30px;">

           <input type="hidden" class="form-control" name="groupid" value="<?php echo $groupid; ?>" required="" >
          <div class="form-group">
               <label>Event Date</label>
              <div class="form-line">
                  <input type="date" class="form-control" name="date" placeholder="Event Date" required="" >
              </div>
          </div>

          <div class="form-group">
               <label>Event Time</label>
              <div class="form-line">
                  <input type="time" class="form-control" name="time" placeholder="Event Date" required="" >
              </div>
          </div>

          <div class="form-group">

              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="Event Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group">

              <div class="form-line">
                  <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify" required>
              </div>
          </div>



          <div class="form-group" style="margin-top:30px;">
                <label>Event Details</label>
              <div class="form-line">
                <textarea class="editor" name="details">Add Event Details here</textarea>
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
