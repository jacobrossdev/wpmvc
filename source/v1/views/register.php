<?php include __DIR__ . '/parts/header.php'; ?>
<style>
	#match-label,
	#strength-label {
		padding: 3px;
		max-width: 150px;
	}
</style>
<div id="depositor-directory-header" class="responsive">
	<div class="container">
		<h1>Almost Done!</h1>
		<p>You are one step closer to accessing our Directory! Please choose a password to secure your access.</p>
	</div>
</div>
<div id="main" class="depositor-directory">
	<div class="container">
		<div class="row">
			<div class="card" style="width: 50%; min-width: 300px; margin: 0 auto;">

				<div class="error-container">
					<?php if(!empty($data['errors'])) foreach($data['errors'] as $error) : ?>
						<p class="error-label"><?php echo $error; ?></p>
					<?php endforeach;?>
				</div>

				<form id="depositor-directory-form" method="POST" action="<?php echo site_url() . '/depositor/directory/register'; ?>">
					<p><label for="EmailEntry">Enter your email address: <span class="tooltip">?</span>
						<span class="tooltip-popup">
							For added security, please enter the email you registered with to access the Depositor Directory.
						</span>
					</label>
					<input type="text" name="email-address" id="EmailEntry" placeholder="" value=""></p>

					<p><label for="PassEntry">Enter a password:</label>
					<input type="password" name="password1" id="PassEntry" placeholder="" value=""></p>
					<div id="strength-label"></div>

					<p><label for="PassEntry2">Confirm password:</label>
					<input type="password" name="password2" id="PassEntry2" placeholder="" value=""></p>
					<div id="match-label"></div>

					<input type="hidden" name="hash" value="<?php echo $data['hash']?>">

					<div class="form-footer">
						<input type="submit" name="submit-password" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($){
    // timeout before a callback is called

	if( window.location.pathname.search('<?php echo $data['hash']?>') < 0 ){
		var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '/' + '<?php echo $data['hash']; ?>';
		window.history.pushState({path:newurl},'',newurl);
	}

    let timeout;

    // traversing the DOM and getting the input and span using their IDs

    let password = $(document).find('#PassEntry');
    let strengthBadge = $(document).find('#strength-label');
    let matchBadge = $(document).find('#match-label');

    // The strong and weak password Regex pattern checker

    let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})')
    let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))')
    

    function test_strength(password){
    	let strength = 0;


    	if( password.length > 5 ){
	  		if( password.match(/[a-z]/) && password.match(/[A-Z]/) )
	  			strength += 1
				if( password.match(/\d/))
					strength += 1
				if( password.match(/[^a-zA-Z\d]/))
					strength += 1
				if( password.length > 7 )
	    		strength += 1
    	}


			if (strength < 3) {
        strengthBadge[0].style.backgroundColor = '#ff7474'
        strengthBadge[0].textContent = 'Weak Password'
		  } else if (strength === 3) {
        strengthBadge[0].style.backgroundColor = '#fde26e'
        strengthBadge[0].textContent = 'Medium Strength'
		  } else if (strength > 3) {
        strengthBadge[0].style.backgroundColor = "#63eb6b"
        strengthBadge[0].textContent = 'Strong Password'
		  }
    }

    function test_match(){

    	let pass1 = $('#PassEntry').val();
    	let pass2 = $('#PassEntry2').val();
    	if( pass1 == pass2 ){
        matchBadge[0].style.backgroundColor = '#63eb6b'
        matchBadge[0].textContent = 'Password Match!'
    	}
    }

    // Adding an input event listener when a user types to the  password input 

    $(document).find('#PassEntry').on('input', function(e){
    	console.log('fire');
    	test_strength(e.target.value);
    });

    $(document).find('#PassEntry2').on('input', function(e){
    	test_match();
    });
	})
</script>
<?php include __DIR__ . '/parts/footer.php' ?>