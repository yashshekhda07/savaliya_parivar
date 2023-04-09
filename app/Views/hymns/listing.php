<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Hymns</h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newHymn" class="btn btn-primary btn-sm"> New Hymn</a>
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
        <div style="overflow-x:auto;">
          <table id="hymns_table" class="table responsive table-bordered table-striped table-hover">
              <thead>
              <tr>
                <th>Id</th>
                <th>Title</th>
                <th class="text-center">Actions</th>
              </tr>
              </thead>

          </table>
        </div>
      </div>
    </div>

  </div>
</div>
