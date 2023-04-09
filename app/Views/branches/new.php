<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Church Locations</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Location</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/savenewbranch" style="margin-top:30px;">

          <div class="form-group" style="margin-top:20px;">
              <label>Branch Name</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="name" placeholder="Branch Name" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Location Address</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="address" placeholder="Location Address" required="" autofocus="" required>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Location Pastor/CareTaker</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="pastor" placeholder="Location Pastor/CareTaker" autofocus="" required>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Location Contact Phone</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="phone" placeholder="Location Contact Phone" autofocus="" required>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Location Contact Email</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="email" placeholder="Location Contact Email" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Location Latitude</label>
              <div class="form-line">
                  <input type="number" step="any" class="form-control" name="latitude" placeholder="Location Latitude" autofocus="" value="0.0">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
               <label>Location Longitude</label>
              <div class="form-line">
                  <input type="number" step="any" class="form-control" name="longitude" placeholder="Location Longitude"  autofocus="" value="0.0">
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
