<?php include __DIR__ . '/parts/header.php';
$nonce = wp_create_nonce( 'security-nonce' );?>
<div id="depositor-directory-header" class="responsive">
	<div class="container">
		<h1>Depositor Directory Login</h1>
	</div>
</div>
<div id="main" class="depositor-directory">
	<div class="container">
		<div class="row">
			<div class="card" style="width: 50%; min-width: 300px; margin: 0 auto;">

				<?php 
					if ( ! is_user_logged_in() ) {
					    $args = array(
					        'redirect' => route('directory/view'), // redirect to directory
					        'form_id' => 'depositor_directory_loginform',
					        'label_username' => __( 'Username:' ),
					        'label_password' => __( 'Password:' ),
					        'label_remember' => __( 'Remember Me' ),
					        'label_log_in' => __( 'Sign In' ),
					        'remember' => true
					    );
							wp_login_form( $args );
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/parts/footer.php' ?>