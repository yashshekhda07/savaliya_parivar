<?php $session = session(); ?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Settings</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Edit Settings</li>
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

                    <form method="POST" action="<?php echo base_url(); ?>/updatesettings" style="margin-top:30px;">
                        <h5 style="margin-top:0px;">App Features (Only selected features are activated on the app.)</h5>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['audiomessages']" id="audiomessages" value="audiomessages" <?php if (strpos($settings->features, "audiomessages") !== false) echo "checked"; ?> >
                                        <label class="custom-control-label" for="audiomessages">Audio Messages</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['videomessages']" id="videomessages" value="videomessages" <?php if (strpos($settings->features, "videomessages") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="videomessages">Video Messages</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['donations']" id="donations" value="donations" <?php if (strpos($settings->features, "donations") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="donations">Donations</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['livestreams']" id="livestreams" value="livestreams" <?php if (strpos($settings->features, "livestreams") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="livestreams">Livestreams</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['events']" id="events" value="events" <?php if (strpos($settings->features, "events") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="events">Events</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['articles']" id="articles" value="articles" <?php if (strpos($settings->features, "articles") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="articles">Articles</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['bible']" id="bible" value="bible" <?php if (strpos($settings->features, "bible") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="bible">Bible</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['notes']" id="notes" value="notes" <?php if (strpos($settings->features, "notes") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="notes">Notes</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['hymns']" id="hymns" value="hymns" <?php if (strpos($settings->features, "hymns") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="hymns">Hymns</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['radio']" id="radio" value="radio" <?php if (strpos($settings->features, "radio") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="radio">Radio</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" id="photos" name="features[]['photos']" value="photos" id="photos" <?php if (strpos($settings->features, "photos") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="photos">Photos</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['groups']" id="groups" value="groups" <?php if (strpos($settings->features, "groups") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="groups">Groups</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['prayer']" id="prayer" value="prayer" <?php if (strpos($settings->features, "prayer") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="prayer">Prayer Requests</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['testimony']" id="testimony" value="testimony" <?php if (strpos($settings->features, "testimony") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="testimony">Testimonies</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['books']" id="books" value="books" <?php if (strpos($settings->features, "books") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="books">Christian Books</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['devotionals']" id="devotionals" value="devotionals" <?php if (strpos($settings->features, "devotionals") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="devotionals">Devotionals</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="features[]['gosocial']" id="gosocial" value="gosocial" <?php if (strpos($settings->features, "gosocial") !== false) echo "checked"; ?>>
                                        <label class="custom-control-label" for="gosocial">Go Social</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 style="margin-top:40px;">Miscellaneous</h5>
                        <hr>
                        <div class="form-group" style="margin-top:20px;">
                            <label>Website Link</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="website" placeholder="Website Link" value="<?php echo $settings->website; ?>">
                            </div>
                        </div>
                        <?php if ($session->get('role') == 0) { ?>
                            <div class="form-group" style="margin-top:20px;">
                                <label>Firebase Server Key</label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="fcm_server_key" placeholder="Firebase Server Key" value="<?php echo $settings->fcm_server_key; ?>">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Enforce App Login</label>
                                <small> (If set to Yes, members will be forced to login before they can use the app.)</small>
                                <select class="form-control" name="app_login" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->app_login == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->app_login == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Allow Audio/Video Message Downlaods</label><br>
                                <small> (If set to Yes, members will be allowed to download messages on the app, No will disable downloads on App.)</small><br>
                                <small> (When set to Yes, You can still allow or disallow downloads on different messages.)</small>
                                <select class="form-control" name="allow_downloads" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->allow_downloads == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->allow_downloads == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Allow Members Join Church Groups</label>
                                <small> (If set to No, members cannot join church groups from the app, admins will have to add members to groups from the dashboard.)</small>
                                <select class="form-control" name="join_groups" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->join_groups == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->join_groups == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Auto Approve Group Membership</label>
                                <small> (If set to No, admins will have to approve membership if a member joins a group from the app.)</small>
                                <select class="form-control" name="auto_approve_group_membership" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->auto_approve_group_membership == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->auto_approve_group_membership == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Allow Members Send Prayer Requests</label>
                                <small> (If set to No, members cannot post prayer requests from the app, only admins can publish prayer requests from the dashboard.)</small>
                                <select class="form-control" name="post_prayer" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->post_prayer == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->post_prayer == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Auto Approve Prayer Requests</label>
                                <small> (If set to No, admins will have to approve prayer requests from members before it is published.)</small>
                                <select class="form-control" name="auto_approve_prayer" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->auto_approve_prayer == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->auto_approve_prayer == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Allow Members Send Testimonies</label>
                                <small> (If set to No, members cannot post prayer requests from the app, only admins can publish prayer requests from the dashboard.)</small>
                                <select class="form-control" name="post_testimony" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->post_testimony == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->post_testimony == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <div class="form-line">
                                <label>Auto Approve Testimonies</label>
                                <small> (If set to No, admins will have to approve members testimonies before it is published.)</small>
                                <select class="form-control" name="auto_approve_testimony" required="" autofocus="" style="margin-top:10px;">
                                    <option value="0" <?php echo $settings->auto_approve_prayer == 0 ? "selected" : ""; ?>>YES</option>
                                    <option value="1" <?php echo $settings->auto_approve_prayer == 1 ? "selected" : ""; ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <h5 style="margin-top:40px;">Social Media Profiles</h5>
                        <hr>


                        <div class="form-group" style="margin-top:20px;">
                            <label>Facebook Page</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="facebook" placeholder="Facebook Page" value="<?php echo $settings->facebook; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Youtube Page</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="youtube" placeholder="Youtube Page" value="<?php echo $settings->youtube; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Twitter Page</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="twitter" placeholder="Twitter Page" value="<?php echo $settings->twitter; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Instagram Page</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="instagram" placeholder="Instagram Page" value="<?php echo $settings->instagram; ?>">
                            </div>
                        </div>

                        <!-- <h5 style="margin-top:40px;">Email configuration<small>(The fields below will be used to send mail to members)</small></h5>
      <hr>
      <div class="form-group" style="margin-top:20px;">
        <label>SMTP username</label>
        <div class="form-line">
          <input type="text" class="form-control" name="mail_username" placeholder="SMTP username" value="<?php echo $settings->mail_username; ?>">
        </div>
      </div>

      <div class="form-group" style="margin-top:20px;">
        <label>SMTP Password</label>
        <div class="form-line">
          <input type="text" class="form-control" name="mail_password" placeholder="SMTP Password" value="<?php echo $settings->mail_password; ?>">
        </div>
      </div>

      <div class="form-group" style="margin-top:20px;">
        <label>SMTP HOST</label>
        <div class="form-line">
          <input type="text" class="form-control" name="mail_smtp_host" placeholder="SMTP HOST" value="<?php echo $settings->mail_smtp_host; ?>">
        </div>
      </div>

      <div class="form-group" style="margin-top:20px;">
        <label>SMTP Protocol</label>
        <div class="form-line">
          <input type="text" class="form-control" name="mail_protocol" placeholder="SMTP Protocol" value="<?php echo $settings->mail_protocol; ?>">
        </div>
      </div>

      <div class="form-group" style="margin-top:20px;">
        <label>TCP port to connect to</label>
        <div class="form-line">
          <input type="number" class="form-control" name="mail_port" placeholder="TCP port to connect to" value="<?php echo $settings->mail_port; ?>">
        </div>
      </div> -->

                        <!-- h5 style="margin-top:40px;">SMS Gateway Settings<small>(The fields below will be used to send sms to members)</small></h5>
      <hr>
      <h6 style="color:red;"><a href="https://www.twilio.com/">TWILIO SMS GATEWAY</a></h6>
      <div class="form-group" style="margin-top:20px;">
        <label>Twilio Account SID</label>
        <div class="form-line">
          <input type="text" class="form-control" name="twilio_account_sid" placeholder="Account SID" value="<?php echo $settings->twilio_account_sid; ?>">
        </div>
      </div> -->

                        <div class="form-group" style="margin-top:20px;">
                            <label>Twilio Auth Token</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="twilio_auth_token" placeholder="Auth Token" value="<?php echo $settings->twilio_auth_token; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Twilio Phone Number</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="twilio_phonenumber" placeholder="Twilio Sender Phone Number" value="<?php echo $settings->twilio_phonenumber; ?>">
                            </div>
                        </div>
                        <h6 style="margin-top:30px; color:red;"><a href="https://termii.com/">TERMII SMS GATEWAY</a></h6>
                        <div class="form-group" style="margin-top:20px;">
                            <label>Termii Sender ID</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="termi_sender_id" placeholder="Sender ID" value="<?php echo $settings->termi_sender_id; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Termii API Key</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="termi_apikey" placeholder="API Key" value="<?php echo $settings->termi_apikey; ?>">
                            </div>
                        </div>

                        <!-- <h5 style="margin-top:40px;">Donation Settings<small>(The fields below will be used for donations)</small></h5>
      <hr>
      <div class="form-group" style="margin-top:20px;">
        <div class="form-line">
          <label>Preferred Donations Gateway</label>
          <select class="form-control" name="prefered_gateway">
           <option value="flutterwaves" <?php if ($settings->prefered_gateway == "flutterwaves") {
                            echo "selected";
                        } ?>>Flutterwaves</option>
           <option value="paystack" <?php if ($settings->prefered_gateway == "paystack") {
                            echo "selected";
                        } ?>>Paystack</option>
         </select>
       </div>
      </div> -->
                        <div class="form-group" style="margin-top:20px;">
                            <label>FlutterWaves Api Key</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="flutterwaves_api_key" placeholder="FlutterWaves Api Key" value="<?php echo $settings->flutterwaves_api_key; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>PayStack Api Key</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="paystack_api_key" placeholder="PayStack Api Key" value="<?php echo $settings->paystack_api_key; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px; display:none;">
                            <label>Payu Api Key (India only)</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="payu_api_key" placeholder="Payu Api Key" value="<?php echo $settings->payu_api_key; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Currency Code</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="currency_code" placeholder="Currency Code" value="<?php echo $settings->currency_code; ?>">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px;">
                            <label>Optional Donations Link (If this is set, then members will always be redirected to this link for donations)</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="donations_link" placeholder="Optional Donations Link" value="<?php echo $settings->donations_link; ?>">
                            </div>
                        </div>

                        <h5 style="margin-top:40px;">Other Settings<small></h5>
                        <hr>

                        <div class="form-group" style="margin-top:20px;">
                            <label>Church Name (This will be shown on the pages below)</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="churchname" value="<?php echo $settings->churchname; ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:30px;">
                            <label>Terms & Conditions</label>
                            <div class="form-line">
                                <textarea class="editor" name="terms"><?php echo $settings->terms; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:30px;">
                            <label>Privacy Policy</label>
                            <div class="form-line">
                                <textarea class="editor" name="privacy"><?php echo $settings->privacy; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:30px;">
                            <label>About Us</label>
                            <div class="form-line">
                                <textarea class="editor" name="aboutus"><?php echo $settings->aboutus; ?></textarea>
                            </div>
                        </div>


                        <div class="box-footer text-center" style="margin-top:20px;">
                            <button class="btn btn-primary waves-effect" type="submit">Update Settings</button>
                        </div>

                    </form>


                </div>
            </div>

        </div>
    </div>
