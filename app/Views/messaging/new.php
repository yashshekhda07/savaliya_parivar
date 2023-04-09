<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Messaging (Mails/SMS)</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Email/SMS</li>
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

        <form method="POST" action="<?php echo base_url(); ?>/sendnewmessage" enctype="multipart/form-data" style="margin-top:30px;">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Location/Branch</label>
                <select id="branchpicker" class="form-control" name="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group">
              <div class="form-line">
                <label>Members List</label>
                <select id="listpicker" class="form-control" name="list" required="" autofocus="">
                  <option value="0">All Members</option>
                  <?php foreach ($lists as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->title; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>
          <h5 style="margin-top:20px;">Message Formats (Select how you want the members to recieve this message.)</h5>
          <hr>
         <div class="form-group">
           <div class="row">
             <div class="col-md-12 col-sm-12">
               <div class="custom-control custom-checkbox mb-5">

                 <input type="checkbox" class="custom-control-input" name="formats[]['sms']" id="smsgateway" value="sms" <?php if($istwilioenabled==1 && $istermiienabled == 1){ ?> disabled <?php }else{ ?>checked<?php } ?>>
                 <label class="custom-control-label" for="smsgateway">Text Message <?php if($istwilioenabled==1 && $istermiienabled == 1){ ?> <small>(To enable this feature, Go to Settings to setup any of the sms gateways.)</small> <?php } ?></label>
                 <!-- To send SMS to members, Go to Settings to setup any of the sms gateways. -->
               </div>
               <div class="custom-control custom-checkbox mb-5">

                 <input type="checkbox" class="custom-control-input" name="formats[]['email']" id="email" value="email" <?php if($isemailenabled==1){ ?> disabled <?php }else{ ?>checked<?php } ?>>
                 <label class="custom-control-label" for="email">Email Message <?php if($isemailenabled==1){ ?> <small>(To enable this feature, Go to Settings to setup the email sender configuration.)</small> <?php } ?></label>

                 <!--<h6>To send email to members, Go to Settings to setup the email sender configuration.</h6>-->

               </div>

             </div>
           </div>
         </div>

         <div id="smsgatewaydiv" class="form-group" <?php if($istwilioenabled==1 && $istermiienabled == 1){ ?> style="display:none;" <?php } ?>>
             <div class="form-line">
               <label>SMS Gateway</label>
               <select class="form-control" id="smsgatewayselect" name="smsgateway" <?php if($istwilioenabled==0 || $istermiienabled == 0){?> required <?php } ?> ?>>
                <?php if($istwilioenabled==0 && $istermiienabled == 0){?> <option value="">Select SMS Gateway</option> <?php } ?>
                <?php if($istwilioenabled == 0){ ?> <option value="twilio">TWILIO</option> <?php } ?>
                <?php if($istermiienabled == 0){ ?> <option value="termii">TERMII</option> <?php } ?>
               </select>
             </div>
         </div>


          <div class="form-group" style="margin-top:20px;">
              <label>Message Subject (This is ignored for text messages)</label>
              <div class="form-line">
                  <input type="text" class="form-control" name="title" placeholder="Message Title" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Message Content</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" name="message" placeholder="Message content" required="" autofocus="" required></textarea>
              </div>
          </div>

          <div class="box-footer text-center">
             <button class="btn btn-primary waves-effect" type="submit">SEND NEW</button>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
