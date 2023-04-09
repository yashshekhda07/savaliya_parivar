<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Videos</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Video</li>
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

        <form id="upload-form">

          <div class="form-group" style="display:none;">
              <div class="form-line">
                <label>Church Location/Branch(Video will be accessed by selected location/branch)</label>
                <select class="form-control" id="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Video Title (Users can search for this video using this title)</label>
              <input type="text" class="form-control" id="title" placeholder="Video Title" required="" autofocus="">
          </div>

          <div class="form-group">
             <label>Video Description (Users can search for this video using this description)</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" id="description" placeholder="Video Description" required="" autofocus=""></textarea>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label>Media Type</label>
                  <select class="form-control" id="media_type" required="" autofocus="">
                      <option value="mp4_video" selected>Upload MP4 Video</option>
                      <option value="video_link">mp4 video link</option>
                      <option value="youtube_video" >Youtube video id</option>
                  </select>
              </div>
          </div>

          <div id="upload_div" style="margin-top:20px;">
            <div class="form-group">
                <label>Video CoverPhoto (Please resize your image before uploading)</label>
                <div class="form-line">
                    <input id="thumbnail" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" required data-height="100">
                </div>
            </div>

              <div class="form-group">

                  <div class="form-line">
                      <input id="video-file" type="file" name="video" data-allowed-file-extensions="mp4" class="dropify3" required data-height="100">
                  </div>

              </div>
          </div>

          <div id="link_div" style="margin-top:20px; display:none;">
            <div class="form-group" style="margin-top:20px;">
                <label>CoverPhoto Link</label>
                <div class="form-line">
                    <input type="url" class="form-control" id="thumbnail_link" placeholder=" Coverphoto Link" autofocus="">
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                   <label id="video-label">Video Link</label>

                <div class="form-line">
                    <input type="url" class="form-control" id="media_link" placeholder="Video Link" autofocus="">
                </div>

            </div>
          </div>


          <div class="form-group" style="margin-top:20px;">
              <label>Video Duration (format 00:00)</label>
              <div class="form-line">
                  <input type="text" class="form-control" id="duration" name="duration" placeholder="Video duration" required="" autofocus="">
              </div>
          </div>

            <div class="form-group" style="margin-top:20px; display:none;">

                <div class="form-line">
                  <label>Allow users stream this audio for free?<br>
                  <small>If set to No, users will pay to watch this video.</small></label>
                    <select class="form-control" id="is_free" required="" autofocus="">
                        <option value="0" selected>YES</option>
                        <option value="1">NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">

                <div class="form-line">
                  <label>Allow users download this video to their device? <br>
                  <small>Only Available for uploaded videos.</small></label>
                    <select class="form-control" id="can_download" required="" autofocus="">
                        <option value="0">YES</option>
                        <option value="1" selected>NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">

                <div class="form-line">
                  <label>Allow users preview a few seconds of this audio?</label>
                    <select class="form-control" id="can_preview" required="" autofocus="">
                        <option value="0">YES</option>
                        <option value="1">NO</option>
                    </select>
                </div>
            </div>


          <div class="box-footer text-center" style="margin-top:20px;">
             <button id="submit" onclick="uploadNewVideo(event)" class="btn btn-primary waves-effect" type="submit">UPLOAD NEW VIDEO</button>
             <ol class="breadcrumb align-center" id="loader" style="display:none;">
               <li><span style="font-size:18px; color:grey; font-style:italic;" id="publish_hint">Processing Request, Please Wait..</span>
                 <br>
                 <div class="preloader pl-size-xs">
                     <div class="spinner-layer pl-teal">
                         <div class="circle-clipper left">
                             <div class="circle"></div>
                         </div>
                         <div class="circle-clipper right">
                             <div class="circle"></div>
                         </div>
                     </div>
                 </div>
               </li>
             </ol>
          </div>

        </form>


      </div>
    </div>

  </div>
</div>
