<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4>Audios</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">New Audio</li>
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
                <label>Church Location/Branch(Audio will be accessed by selected location/branch)</label>
                <select class="form-control" id="branch" required="" autofocus="">
                  <?php foreach ($branches as $res) { ?>
                    <option value="<?php echo $res->id; ?>"><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Audio Title (Users can search for this audio using this title)</label>
              <input type="text" class="form-control" id="title" placeholder="Audio Title" required="" autofocus="">
          </div>

          <div class="form-group">
             <label>Audio Description (Users can search for this audio using this description)</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" id="description" placeholder="Audio Description" required="" autofocus=""></textarea>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <div class="form-line">
                <label>Audio Type</label>
                  <select class="form-control" id="audio_type" required="" autofocus="">
                      <option value="0" selected>Upload Audio File</option>
                      <option value="1" >Provide Audio Link</option>
                  </select>
              </div>
          </div>

          <div id="upload_div">
            <div class="form-group" style="margin-top:20px;">
                <label>Media CoverPhoto (Please resize your image before uploading)</label>
                <div class="form-line">
                    <input id="thumbnail" type="file" data-allowed-file-extensions="jpeg jpg png JPEG PNG" class="dropify2" required>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                   <label>Mp3 File (Please resize your file before uploading)</label>
                <div class="form-line">
                    <input id="audio-file" type="file" name="mp3" data-allowed-file-extensions="mp3" class="dropify" required>
                </div>

            </div>
          </div>

          <div id="link_div" style="margin-top:20px; display:none;">
            <div class="form-group" style="margin-top:20px;">
                <label>CoverPhoto Link</label>
                <div class="form-line">
                    <input type="url" class="form-control" id="thumbnail_link" placeholder="Coverphoto Link" autofocus="">
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                   <label>Audio Link</label>

                <div class="form-line">
                    <input type="url" class="form-control" id="media_link" placeholder="Audio Link" autofocus="">
                </div>

            </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Audio Duration (format 00:00)</label>
              <div class="form-line">
                  <input type="text" class="form-control" id="duration" name="duration" placeholder="Audio duration" required="" autofocus="">
              </div>
          </div>

          <div class="form-group" style="margin-top:20px; display:none;">

              <div class="form-line">
                <label>Allow users stream this audio for free?<br>
                <small>If set to No, users will pay to listen to this audio.</small></label>
                  <select class="form-control" id="is_free" required="" autofocus="">
                      <option value="0" selected>YES</option>
                      <option value="1">NO</option>
                  </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">

              <div class="form-line">
                <label>Allow users download this video to their device? <br>
                <small>Only Available for uploaded audio files.</small></label>
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
             <button id="submit" onclick="uploadNewAudio(event)" class="btn btn-primary waves-effect" type="submit">UPLOAD NEW AUDIO</button>
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
