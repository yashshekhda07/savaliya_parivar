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
                                <li class="breadcrumb-item active" aria-current="page">Edit Member Details</li>
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

                    <form method="POST" action="<?php echo base_url(); ?>/editMemberData" enctype="multipart/form-data" style="margin-top:30px;">
                        <input name="id" value="<?php echo $member->id; ?>" type="hidden" class="form-control" required="" autofocus="">
                        <div class="form-group" style="display:none;">
                            <div class="form-line">
                                <label>Church Branch</label>
                                <select class="form-control" name="branch" required="" autofocus="">
                                    <?php foreach ($branches as $res) { ?>
                                        <option <?php echo $member->branch == $res->id ? "selected" : ""; ?> value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Optional Member Photo</label>
                            <div class="form-line">
                                <input data-default-file="<?php echo $member->thumbnail; ?>" type="file" name="thumbnail" data-allowed-file-extensions="png jpg jpeg PNG" class="thumbs_dropify">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <div class="form-line">
                                <input value="<?php echo $member->name; ?>" type="text" class="form-control" name="name" required="" autofocus="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                <label class="weight-600">Gender</label>
                                <div class="custom-control custom-radio mb-5">
                                    <input <?php echo $member->gender == "male" ? "checked" : ""; ?> type="radio" id="customRadio1" name="gender" class="custom-control-input" value="Male">
                                    <label class="custom-control-label" for="customRadio1">Male</label>
                                </div>
                                <div class="custom-control custom-radio mb-5">
                                    <input <?php echo $member->gender == "female" ? "checked" : ""; ?> type="radio" id="customRadio2" name="gender" class="custom-control-input" value="Female">
                                    <label class="custom-control-label" for="customRadio2">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="form-line">
                                <input type="number" value="<?php echo $member->phonenumber; ?>" class="form-control" name="phonenumber" required="" autofocus="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Whatsapp Number</label>
                            <div class="form-line">
                                <input type="number" value="<?php echo $member->whatsappnumber; ?>" class="form-control" name="whatsappnumber" required="" autofocus="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Date of birth</label>
                            <div class="form-line">
                                <input type="date" value="<?php echo $member->dob; ?>" class="form-control" name="dob" required="" autofocus="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Villlage name</label>
                            <div class="form-line">
                                <input type="text" value="<?php echo $member->villagename; ?>" class="form-control" name="villagename">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jillo</label>
                            <div class="form-line">
                                <input type="text" value="<?php echo $member->jillo; ?>" class="form-control" name="jillo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Taluko</label>
                            <div class="form-line">
                                <input type="text" value="<?php echo $member->taluko; ?>" class="form-control" name="taluko">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Occupation</label>
                            <div class="form-line">
                                <input type="text" value="<?php echo $member->occupation; ?>" class="form-control" name="occupation">
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
