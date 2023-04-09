<html>
<head>
<title> Donate </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
<script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
</head>
<body>


  <div class="container">
  <br>  <p class="text-center">Donate to <?php echo $settings->churchname; ?></p>
  <br>

  <div class="row">
  	<div class="col-sm-10">
  <article class="card">
  <div class="card-body p-5">

<!--  <ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
  	<li class="nav-item" <?php if($settings->paystack_api_key == ""){ ?> style="display:none;" <?php } ?>>
  		<a class="nav-link <?php if($settings->paystack_api_key != '') echo ' active'; ?>" data-toggle="pill" href="#nav-tab-card">
  	 Paystack</a></li>
  	<li class="nav-item" <?php if($settings->flutterwaves_api_key == ""){ ?> style="display:none;"<?php } ?>>
  		<a class="nav-link <?php if($settings->paystack_api_key == '' && $settings->flutterwaves_api_key != '') echo ' active'; ?>" data-toggle="pill" href="#nav-tab-paypal">
  		 FlutterWaves</a></li>

  </ul>-->

  <div class="tab-content">
  <div class="tab-pane fade <?php if($settings->prefered_gateway == 'paystack') echo 'show active'; ?>" id="nav-tab-card" <?php if($settings->prefered_gateway != "paystack"){ ?> style="display:none;"<?php } ?>>
  	<p style="display:none;" class="alert alert-success">Some text success or error</p>
  	<form role="form" id="paymentForm">
      <input type="hidden" class="form-control" id="paystack-branch" value="1">
      <div class="form-group">
    		<label for="username">Reason for donation</label>
    		<input type="text" class="form-control" id="reason" name="reason" placeholder="" required="">
    	</div>
      <div class="form-group">
    		<label for="username">Email Address</label>
    		<input type="email" id="email-address" class="form-control" name="email" placeholder="" required="">
    	</div>
      <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
      		<label for="username">First Name</label>
      		<input type="text" id="first-name" class="form-control" name="name" placeholder="" required="">
      	</div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="username">Last Name</label>
          <input type="text" id="last-name" class="form-control" name="name" placeholder="" required="">
        </div>
      </div>
     </div>
  	 <!-- form-group.// -->
    <div class="form-group">
  		<label for="username">Amount</label>
  		<input type="number" id="amount" class="form-control" name="amount" placeholder="" required="">
  	</div>




  	<button onclick="payWithPaystack(event)" class="subscribe btn btn-primary btn-block" type="button"> Proceed With Paystack  </button>
  	</form>
  </div> <!-- tab-pane.// -->



  <div class="tab-pane fade <?php if($settings->prefered_gateway == 'flutterwaves') echo 'show active'; ?>" id="nav-tab-paypal" <?php if($settings->prefered_gateway != "flutterwaves"){ ?> style="display:none;"<?php } ?>>
    <form role="form" id="paymentForm2">
      <input type="hidden" class="form-control" id="raves-branch" value="1">
      <div class="form-group">
    		<label for="username">Reason for donation</label>
    		<input type="text" class="form-control" id="raves-reason" name="raves-reason" placeholder="" required="">
    	</div>
      <div class="form-group">
    		<label for="username">Email Address</label>
    		<input type="email" id="raves-email-address" class="form-control" name="email" placeholder="" required="">
    	</div>
      <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
      		<label for="username">First Name</label>
      		<input type="text" id="raves-first-name" class="form-control" name="name" placeholder="" required="">
      	</div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="username">Last Name</label>
          <input type="text" id="raves-last-name" class="form-control" name="name" placeholder="" required="">
        </div>
      </div>
     </div>
  	 <!-- form-group.// -->
    <div class="form-group">
  		<label for="username">Amount</label>
  		<input type="number" id="raves-amount" class="form-control" name="amount" placeholder="" required="">
  	</div>
  	<button onclick="makePayment(event)" class="subscribe btn btn-primary btn-block" type="button"> Proceed With FlutterWaves  </button>
  	</form>
  </div>


  <div class="tab-pane fade <?php if($settings->prefered_gateway == 'payu') echo 'show active'; ?>" id="nav-tab-paypal" <?php if($settings->prefered_gateway != "payu"){ ?> style="display:none;"<?php } ?>>
    <form role="form" action='https://test.payu.in/_payment' method='post'>
      <input type="hidden" class="form-control" value="1">
      <div class="form-group">
    		<label for="username">Reason for donation</label>
    		<input type="text" class="form-control" name="reason" placeholder="" required="">
    	</div>
      <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
      		<label for="username">First Name</label>
      		<input type="text" class="form-control" name="firstname" placeholder="" required="">
      	</div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="username">Last Name</label>
          <input type="text" class="form-control" name="lastname" placeholder="" required="">
        </div>
      </div>
     </div>
      <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
      		<label for="username">Email Address</label>
      		<input type="text" class="form-control" name="email" placeholder="" required="">
      	</div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="username">Phone Number</label>
          <input type="text" class="form-control" name="phone" placeholder="" required="">
        </div>
      </div>
     </div>

  	 <!-- form-group.// -->
    <div class="form-group">
  		<label for="username">Amount</label>
  		<input type="number" class="form-control" name="amount" placeholder="" required="">
  	</div>
    <input type="hidden" name="key" value="<?php echo $settings->payu_api_key; ?>" />
    <input type="hidden" name="txnid" value="<?php echo $transid; ?>" />
    <input type="hidden" name="productinfo" value="donations" />
    <input type="hidden" name="surl" value="https://apiplayground-response.herokuapp.com/" />
    <input type="hidden" name="furl" value="https://apiplayground-response.herokuapp.com/" />
    <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
  	<button class="subscribe btn btn-primary btn-block" type="button"> Proceed With Payu  </button>
  	</form>
  </div>


  </div> <!-- tab-content .// -->

  </div> <!-- card-body.// -->
  </article> <!-- card.// -->


  	</aside> <!-- col.// -->
  </div> <!-- row.// -->

  </div>
  <!--container end.//-->


</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/ajax.js"></script>
<script>
var paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);
function payWithPaystack(e) {
  e.preventDefault();
  var branch = document.getElementById("paystack-branch").value;
  var reason = document.getElementById("reason").value;
  var amount = document.getElementById("amount").value;
  var email = document.getElementById("email-address").value;
  var firstname = document.getElementById("first-name").value;
  var lastname = document.getElementById("last-name").value;

  if (reason == "") {
      swal("ooops", "You forgot to let us know the reason for the donation.", "error");
      return;
  }

  if (email == "") {
      swal("ooops", "You forgot to fill the email field.", "error");
      return;
  }
  if (firstname == "") {
      swal("ooops", "You forgot to fill the First Name field.", "error");
      return;
  }
  if (lastname == "") {
      swal("ooops", "You forgot to fill the Last Name field.", "error");
      return;
  }
  if (amount <= 0) {
      swal("ooops", "You need to enter a valid amount you want to donate.", "error");
      return;
  }

  let handler = PaystackPop.setup({
    key: '<?php echo $settings->paystack_api_key; ?>',
    email: email,
    currency: '<?php echo $settings->currency_code; ?>',
    amount: amount * 100,
    firstname: firstname,
    lastname: lastname,
    onClose: function(){

    },
    callback: function(response){
      console.log(response);
      senddonationtoserver(branch, email,firstname+" "+lastname,amount,response.trxref,"Paystack",reason);
    }
  });
  handler.openIframe();
}

//FlutterWaves
function makePayment(e) {
    e.preventDefault();
    var branch = document.getElementById("raves-branch").value;
    var reason = document.getElementById("raves-reason").value;
    var amount = document.getElementById("raves-amount").value;
    var email = document.getElementById("raves-email-address").value;
    var firstname = document.getElementById("raves-first-name").value;
    var lastname = document.getElementById("raves-last-name").value;
    var ref = "FLWSECK_"+Math.floor((Math.random() * 1000000000) + 1);

    if (reason == "") {
        swal("ooops", "You forgot to let us know the reason for the donation.", "error");
        return;
    }
    if (email == "") {
        swal("ooops", "You forgot to fill the email field.", "error");
        return;
    }
    if (firstname == "") {
        swal("ooops", "You forgot to fill the First Name field.", "error");
        return;
    }
    if (lastname == "") {
        swal("ooops", "You forgot to fill the Last Name field.", "error");
        return;
    }
    if (amount <= 0) {
        swal("ooops", "You need to enter a valid amount you want to donate.", "error");
        return;
    }
    FlutterwaveCheckout({
      public_key: '<?php echo $settings->flutterwaves_api_key; ?>',
      tx_ref: ref,
      amount: amount,
      currency: '<?php echo $settings->currency_code; ?>',
      //payment_options: "card, mobilemoneyghana, ussd",
      redirect_url: baseURL+"thank_you",
      customer: {
        email: email,
        name: firstname + " "+lastname,
      },
      callback: function (data) {
        console.log(data);
        senddonationtoserver(branch,email,firstname+" "+lastname,amount,ref,"FlutterWave",reason);
      },
      onclose: function() {
        // close modal
      },
    });
  }

  function senddonationtoserver(branch,email,name,amount,ref,method,reason){
    swal({
      text: "Please wait while we complete your transaction..",
    });
    var form_obj = JSON.stringify({
      branch:branch,
      email:email,
      name: name,
      amount:amount,
      reference: ref,
      method: method,
      reason:reason
    });
    //console.log(form_obj); return;
    var fd = new FormData();
    fd.append("data", form_obj);
    var form1 = document.getElementById('paymentForm');
    var form2 = document.getElementById('paymentForm2');
    form1.reset();
    form2.reset();
    makeAjaxCall( baseURL+"/savedonation", "POST",fd).then(function(response){
         console.log(response);
        // window.location.href = baseURL+"/thank_you";
        swal({
          text: "Thank you for your kind donation",
        });
    },  function(status){
        //console.log("failed with status", status);
        //window.location.href = baseURL+"/thank_you";
        swal({
          text: "Thank you for your kind donation",
        });
    });
  }

</script>
</html>
