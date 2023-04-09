<?php
$session = session();
?>
<html>
  <head>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
    body {
      background: #f7f7f7;
      }

      .form-box {
      max-width: 500px;
      margin: auto;
      padding: 50px;
      background: #ffffff;
      border: 10px solid #f2f2f2;
      }

      h1, p {
      text-align: center;
      }

      input, textarea {
      width: 100%;
      }
    </style>
    <body>
      <div class="form-box">
          <h1>Reset Your Password</h1>
          <form method="POST" action="<?php echo base_url(); ?>/changeUserPassword">
            <input type="hidden" name="email" required value="<?php echo $email; ?>">
            <input type="hidden" name="activation_id" value="<?php echo $activation_id; ?>">
            <div class="form-group">
              <label for="password1">Password</label>
              <input class="form-control" id="password1" type="password" name="password1" required>
            </div>
            <div class="form-group">
              <label for="password2">Repeat Password</label>
              <input class="form-control" id="password2" type="password" name="password2" required>
            </div>
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
            <input class="btn btn-primary" type="submit" value="Submit" />
            </div>
          </form>
        </div>
    </body>
</html>
