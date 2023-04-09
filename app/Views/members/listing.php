<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Members</h4>
                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <!--            <a href="newMember" class="btn btn-primary btn-sm"> New Member</a>-->
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
                        <table class="table responsive table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Phone number</th>
                                <th>Whatsapp number</th>
                                <th>Village</th>
                                <th>District</th>
                                <th>Taluko</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($results as $user_data) { ?>
                                <tr>
                                    <td><?php echo $user_data['id']; ?></td>
                                    <td><?php echo $user_data['name']; ?></td>
                                    <td><?php echo $user_data['phonenumber']; ?></td>
                                    <td><?php echo $user_data['whatsappnumber']; ?></td>
                                    <td><?php echo $user_data['villagename']; ?></td>
                                    <td><?php echo $user_data['taluko']; ?></td>
                                    <td><?php echo $user_data['jillo']; ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="editMember/<?php echo $user_data['id']; ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a data-type="members" data-id="<?php echo $user_data['id']; ?>" class="dropdown-item" onclick="delete_item(event)">
                                                    <i data-type="members" data-id="<?php echo $user_data['id']; ?>" class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>