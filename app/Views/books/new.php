<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Christian Books/Literatures</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Add New</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/saveNewBook" enctype="multipart/form-data" style="margin-top:30px;">



          <div class="form-group">
              <label>Book CoverPhoto</label>
              <div class="form-line">
                  <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
              </div>
          </div>

          <div class="form-group">
              <label>Book PDF file</label>
              <div class="form-line">
                  <input type="file" name="book" data-allowed-file-extensions="pdf" class="pdf_dropify">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
               <label>Book Title</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="Book Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
               <label>Book Writer/Author</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="author" placeholder="Book Writer/Author" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:30px;">
                <label>Book Description</label>
              <div class="form-line">
                <textarea name="description" rows="3" class="form-control"></textarea>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
               <label>Number of Pages</label>
              <div class="form-line">
                  <input type="number" class="form-control" name="pages" required="" autofocus="">
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
