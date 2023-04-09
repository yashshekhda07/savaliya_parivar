<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Admin Users</h4>
            </div>

          </div>
          <div class="col-md-6 col-sm-12 text-right">
            <a href="newAdmin" class="btn btn-primary btn-sm"> New Admin User</a>
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
          <table id="categories-table" class="table table-bordered table-striped table-hover exportable">
              <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th class="text-center">Actions</th>
              </tr>
              </thead>
            <tbody>
                <?php
                $count=1;
                forEach($userRecords as $record){
                ?>
                <tr>
                  <td><?php echo $count; ?></td>
                  <td><?php echo $record->fullname; ?></td>
                  <td><?php echo $record->email; ?></td>
                  <td class="text-center">
                    <div class="dropdown">
                      <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <i class="dw dw-more"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="<?php echo base_url().'/editAdmin/'.$record->id; ?>"><i class="dw dw-edit2"></i> Edit</a>
                        <a data-type="admin" data-id="<?php echo $record->id; ?>" class="dropdown-item" onclick="delete_item(event)">
                        <i data-type="admin" data-id="<?php echo $record->id; ?>" class="dw dw-delete-3"></i> Delete</a>
                      </div>
                    </div>

                  </td>
                 </tr>
                 <?php $count++;}
                 ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
