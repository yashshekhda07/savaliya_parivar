<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Members</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">New Member</li>
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

                    <form method="POST" action="<?php echo base_url(); ?>/saveNewMember" enctype="multipart/form-data" style="margin-top:30px;">
                        <div class="form-group" style="display:none;">
                            <div class="form-line">
                                <label>Church Branch</label>
                                <select class="form-control" name="branch">
                                    <?php foreach ($branches as $res) { ?>
                                        <option value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Optional Member Photo</label>
                            <div class="form-line">
                                <input type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
                            </div>
                        </div>

                        <!-- name -->
                        <div class="form-group">
                            <label>Name </label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label>Last Name </label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="lastname">
                            </div>
                        </div> -->

                        <!-- gender -->
                        <div class="form-group">
                            <div class="form-line">
                                <label class="weight-600">Gender</label>
                                <div class="custom-control custom-radio mb-5">
                                    <input type="radio" id="customRadio1" name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio1">Male</label>
                                </div>
                                <div class="custom-control custom-radio mb-5">
                                    <input type="radio" id="customRadio2" name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio2">Female</label>
                                </div>
                            </div>
                        </div>

                        <!--
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="form-line">
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        -->

                        <!-- phone number  -->
                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="form-line">
                                <input type="number" class="form-control" name="phonenumber">
                            </div>
                        </div>

                        <!-- whatsapp number -->
                        <div class="form-group">
                            <label>Whatsapp Number</label>
                            <div class="form-line">
                                <input type="number" class="form-control" name="whatsapp">
                            </div>
                        </div>

                        <!-- birthdate -->
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <div class="form-line">
                                <input type="date" class="form-control" name="dob">
                            </div>
                        </div>

                        <!-- gam nu name -->
                        <div class="form-group">
                            <label>Gam nu name</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="villagename">
                            </div>
                        </div>

                        <!-- taluko -->
                        <div class="form-group">
                            <label>Taluko</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="taluko">
                            </div>
                        </div>

                        <!-- jillo -->
                        <div class="form-group">
                            <label>Jillo</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="jillo">
                            </div>
                        </div>

                        <!--
                        <div class="form-group">
                            <label>Occupation</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="occupation">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Facebook Profile Link</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="facebook">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Twitter Profile Link</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="twitter">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Linkedln Profile Link</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="linkedln">
                            </div>
                        </div>
                        -->

                        <div class="box-footer text-center">
                            <button class="btn btn-primary waves-effect" type="submit">SAVE NEW</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
