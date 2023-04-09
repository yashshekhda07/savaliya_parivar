<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/core.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/script.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/process.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/layout-settings.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/scripts/dashboard3.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/dropify/dist/js/dropify.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/vendors/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/js/common.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/src/plugins/dropzone/src/dropzone.js"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>

    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone(".dropzone", {
        url: "<?php echo base_url(); ?>/savenewphoto",
        paramName: "file",
        autoProcessQueue: false,
        uploadMultiple: true, // uplaod files in a single request
        parallelUploads: 100, // use it with uploadMultiple
        maxFilesize: 100, // MB
        maxFiles: 20,
        acceptedFiles: ".jpg, .jpeg, .png, .gif",
        addRemoveLinks: true,
        // Language Strings
        dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb",
        dictInvalidFileType: "Invalid File Type",
        dictCancelUpload: "Cancel",
        dictRemoveFile: "Remove",
        dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed",
        dictDefaultMessage: "Drop files here to upload",
    });

    function uploadphotos(event) {
        event.preventDefault();
        var title = $('#title').val();
        var description = $('#description').val();

        if (title == "") {
            error_alert("You need to enter a title to continue");
            return;
        }

        myDropzone.on("sendingmultiple", function (file, xhr, formData) {
            formData.append("title", title);
            formData.append("description", description);
            console.log(formData);
        });
        myDropzone.on('successmultiple', function (file, response) {
            console.log(file);
            console.log(response);
            swal({
                title: 'Success',
                text: "Uploads were successful",
                type: 'success',
                confirmButtonColor: "#DD6B55",
                showCancelButton: false,
                confirmButtonText: 'Sure'
            }, function () {
                document.location.reload();
            });
            //alert(response);
            //document.location.reload();
        });
        myDropzone.processQueue();
    }

    function googleTranslateElementInit() {
        setCookie('googtrans', '/en/gu/', 1);
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }

    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }
</script>
</body>
</html>
