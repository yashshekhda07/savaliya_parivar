<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Events</h4>
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

        <form method="POST" action="<?php echo base_url(); ?>/editEventData" enctype="multipart/form-data" style="margin-top:30px;">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Branch (Only selected church will see this event)</label>
                <select class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>" <?php echo $event->branch==$res->id?"selected":""; ?>><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <input type="hidden" name="id" value="<?php echo $event->id; ?>">
          <div class="form-group">
               <label>Event Date</label>
              <div class="form-line">
                  <input type="datetime" class="form-control" name="date" placeholder="Feed Date" required="" value="<?php echo $event->date; ?>">
              </div>
          </div>

          <div class="form-group">
               <label>Event Time</label>
              <div class="form-line">
                  <input type="time" class="form-control" name="time" placeholder="Event Date" required="" value="<?php echo $event->time; ?>">
              </div>
          </div>


          <div class="form-group">

              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="event Title" required="" autofocus="" value="<?php echo $event->title; ?>">
              </div>
          </div>
          <div class="form-group">

              <div class="form-line">
                  <input data-default-file="<?php echo $event->thumbnail; ?>" type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
              </div>
          </div>



          <div class="form-group" style="margin-top:30px;">
                <label>Event Details</label>
              <div class="form-line">
                <textarea class="editor" name="details"><?php echo $event->details; ?></textarea>
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
