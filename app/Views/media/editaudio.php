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
                <li class="breadcrumb-item active" aria-current="page">Edit Audio Details</li>
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
                    <option value="<?php echo $res->id; ?>" <?php echo $audio->branch==$res->id?"selected":""; ?>><?php echo $res->name; ?></option>
                  <?php  } ?>
                </select>
              </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Audio Title (Users can search for this audio using this title)</label>
              <div class="form-line">
                  <input type="text" class="form-control" id="title" placeholder="Audio Title" required="" autofocus="" value="<?php echo $audio->title; ?>">
              </div>
          </div>

          <div class="form-group">
             <label>Audio Description (Users can search for this audio using this description)</label>
              <div class="form-line">
                  <textarea type="text" class="form-control" id="description" placeholder="Audio Description" required="" autofocus=""><?php echo $audio->description; ?></textarea>
              </div>
          </div>

          <div id="link_div" style="margin-top:20px; display:none;">
            <div class="form-group" style="margin-top:20px;">
                <label>CoverPhoto Link</label>
                <div class="form-line">
                    <input type="url" class="form-control" id="thumbnail_link" placeholder=" Coverphoto Link" autofocus="" value="<?php echo $audio->cover_photo; ?>">
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                   <label id="video-label">Audio File Link</label>

                <div class="form-line">
                    <input type="url" class="form-control" id="media_link" placeholder="Audio File Link" autofocus="" value="<?php echo $audio->source; ?>">
                </div>

            </div>
          </div>

          <div class="form-group" style="margin-top:20px;">
              <label>Audio Duration (format 00:00:00)</label>
              <div class="form-line">
                  <input type="text" class="form-control" id="duration" name="duration" placeholder="Audio duration" required="" autofocus="" value="<?php echo $audio->duration; ?>">
              </div>
          </div>

            <div class="form-group" style="margin-top:20px;">
                <div class="form-line">
                  <label>Allow users stream this audio for free?<br>
                  <small>If set to No, users will pay to listen to this audio.</small></label>
                    <select class="form-control" id="is_free" required="" autofocus="">
                        <option value="0" <?php echo $audio->is_free==0?"selected":""; ?>>YES</option>
                        <option value="1" <?php echo $audio->is_free==1?"selected":""; ?>>NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">

                <div class="form-line">
                  <label>Allow users download this audio to their device?  <br>
                  <small>Only Available for uploaded audio files.</label>
                    <select class="form-control" id="can_download" required="" autofocus="">
                        <option value="0" <?php echo $audio->can_download==0?"selected":""; ?>>YES</option>
                        <option value="1" <?php echo $audio->can_download==1?"selected":""; ?>>NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">

                <div class="form-line">
                  <label>Allow users preview a few seconds of this audio?</label>
                    <select class="form-control" id="can_preview" required="" autofocus="">
                        <option value="0" <?php echo $audio->can_preview==0?"selected":""; ?>>YES</option>
                        <option value="1" <?php echo $audio->can_preview==1?"selected":""; ?>>NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px; display:none;">
                <label>How many seconds preview is allowed for this audio? (If user cant preview this audio, leave default value)</label>
                <div class="form-line">
                    <input type="number" class="form-control" id="preview_duration" placeholder="Preview Duration in seconds" required="" autofocus="" value="<?php echo $audio->preview_duration; ?>">
                </div>
            </div>
            <input type="hidden" required="" id="id" autofocus="" value="<?php echo $audio->id; ?>">

          <div class="box-footer text-center">
             <button id="submit" onclick="updateAudio(event)" class="btn btn-primary waves-effect" type="submit">UPDATE AUDIO</button>
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
