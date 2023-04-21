<?php
namespace Levlane;

class DirectoryController extends Controller{

	protected $private;

	public function __construct(){
		
		parent::__construct();

	}


	// Controller Views
	// 
	public function adminGET(){

		if( is_user_logged_in() ){
			$user = wp_get_current_user(); 
	    $roles = ( array ) $user->roles;	 
			if( in_array('depositor', $roles) ){
				$this->response->redirect('directory/view');
			}
		}
		elseif( ! is_user_logged_in() || ! current_user_can('manage_options') ){
			wp_redirect( site_url() );exit;
		}

		$rows = [];

		global $wpdb;

		if( isset($_GET['depositor-search']) ){
			$search = sprintf('%s', $_GET['depositor-search']);
			$rows = $wpdb->get_results("
				SELECT * FROM `depositor_directory_prospects`
				WHERE `confirmed` = 1
				AND `depositor_fname` LIKE '%{$search}%' 
				OR `depositor_lname` LIKE '%{$search}%' 
				OR `depositor_email` LIKE '%{$search}%'
			 	ORDER BY `decision_on` ASC
			");
		} else {
			$rows = $wpdb->get_results("
				SELECT * FROM `depositor_directory_prospects` 
				WHERE `confirmed` = 1 ORDER BY `decision_on` ASC
			");
		}

		$this->response->view('admin', [
			'rows' => $rows
		]);
	}

	public function profileGET(){

		if( ! is_user_logged_in() ){
			wp_redirect( site_url() );exit;
		}

		// PULLS UP A PROFILE
		// ----------------------------
		if( count($this->request->params) ){
			$hash = $this->request->params[0];

			global $wpdb;
			$profile = $wpdb->get_row($wpdb->prepare("SELECT * FROM `depositor_directory_prospects` WHERE `hash` = '%s'", $hash));

			$profile_meta = $wpdb->get_results("SELECT `data_meta` as `key`, `data_value` as `value` FROM `depositor_directory_prospectmeta` WHERE `depositor_user_id` = {$profile->id}");
			
			$profile = (array) $profile;

			foreach($profile_meta as $meta){
				$profile[$meta->key] 
				= $meta->value;
			}

			$this->response->view('profile', [
				'hash' => $hash,
				'profile' =>$profile
			]);
		}
	}

	public function viewGET(){

		if( ! is_user_logged_in() ){
			$this->response->redirect('directory/login');
		}

		$rows = [];
		global $wpdb;

		if( isset($_GET['depositor-search']) ){
			$search = sprintf('%s', $_GET['depositor-search']);
			$rows = $wpdb->get_results("
				SELECT *, 
				( SELECT `data_value` FROM `depositor_directory_prospectmeta` as `ddpm1` 
					WHERE `ddpm1`.`depositor_user_id` = `ddp`.`id` AND `ddpm1`.`data_meta` = 'profile-portrait' LIMIT 1) as `image`,
				( SELECT `data_value` FROM `depositor_directory_prospectmeta` as `ddpm2` 
					WHERE `ddpm2`.`depositor_user_id` = `ddp`.`id` AND `ddpm2`.`data_meta` = 'Address Line 2' LIMIT 1) as `unit`
				FROM `depositor_directory_prospects` as `ddp`
				WHERE `ddp`.`confirmed` = 1
				AND `ddp`.`approved` = 1
				AND `ddp`.`depositor_fname` LIKE '%{$search}%' 
				OR `ddp`.`depositor_lname` LIKE '%{$search}%' 
				OR `ddp`.`depositor_email` LIKE '%{$search}%'
			 	ORDER BY `depositor_lname` ASC
			");
		} else {
			$rows = $wpdb->get_results("
				SELECT *, 
				( SELECT `data_value` FROM `depositor_directory_prospectmeta` as `ddpm1` 
					WHERE `ddpm1`.`depositor_user_id` = `ddp`.`id` AND `ddpm1`.`data_meta` = 'profile-portrait' LIMIT 1) as `image`,
				( SELECT `data_value` FROM `depositor_directory_prospectmeta` as `ddpm2` 
					WHERE `ddpm2`.`depositor_user_id` = `ddp`.`id` AND `ddpm2`.`data_meta` = 'Address Line 2' LIMIT 1) as `unit`
				FROM `depositor_directory_prospects` as `ddp`
				WHERE `ddp`.`confirmed` = 1
				AND `ddp`.`approved` = 1
				ORDER BY `ddp`.`depositor_lname` ASC
			");
		}

		$this->response->view('view', [
			'rows' => $rows
		]);
	}

	public function confirmGET(){
		global $wpdb;
		$hash = $this->request->params[0];
		$wpdb->update('depositor_directory_prospects', ['confirmed'=>1], ['hash'=>$hash, 'confirmed' => 0], ['%d'], ['%s','%d']);

		$this->response->view('confirm');
	}

	public function registerGET(){

		if( !count($this->request->params) )
			$this->response->redirect('directory/error');

		$hash = $this->request->params[0];

		global $wpdb;

		$depositor_user_id = $wpdb->get_var("SELECT `depositor_user_id` FROM `depositor_directory_prospectmeta` 
			WHERE `data_meta` = 'registration-hash' AND `data_value` = '{$hash}'");

		if( !is_null($depositor_user_id) ){
			$this->response->view('register', [
				'hash' => $hash
			]);
		} else {
			$this->response->redirect('directory/error');
		}
	}

	public function registerPOST(){
		global $wpdb;

		// CHECK IF IS SECURE
		if( empty($_POST['hash']) ){
			$this->response->redirect('directory/error');
		}

		$email = sprintf('%s', $_POST['email-address']);
		$hash = sprintf('%s', $_POST['hash']);
		$errors = [];

		// VALIDATE ENTRY
		if( !filter_var($_POST['email-address'], FILTER_VALIDATE_EMAIL) ){
			$errors['email'] = 'Please enter a valid email address';
		}

		$password1 = sprintf('%s',$_POST['password1']);
		$password2 = sprintf('%s',$_POST['password2']);

		if( !strlen($password1) || !strlen($password2) ){
			$errors['password'] = 'You left one or more password fields empty';
		}

		if( $password1 !== $password2 ){
			$errors['password'] = 'The passwords you entered didn\'t match';
		}

		if( count($errors) ){
			$this->response->view('register',[
				'errors' => $errors,
				'hash' => $hash
			]);
		}
		// -- END VALIDATION

		$depositor_user_id = $wpdb->get_row(
			$wpdb->prepare("
				SELECT `depositor_user_id`, `depositor_email` FROM `depositor_directory_prospectmeta` as `ddpm`
				JOIN `depositor_directory_prospects` as `ddp` ON `ddpm`.`depositor_user_id` = `ddp`.`id`
				WHERE `ddp`.`depositor_email` = %s 
				AND `ddpm`.`data_meta` = 'registration-hash' 
				AND `ddpm`.`data_value` = %s", $email, $hash
			)
		);

		// CHECK IF HASH MATCHES EMAIL
		if( empty($depositor_user_id) ){
			$this->response->redirect('directory/error');
		}

		// Now we create a WP User account with the "depositor" role
		$userdata = [
			'role' => 'depositor',
			'user_email' => $email,
			'user_login' => $email,
			'user_pass' => $password1
		];

		$user_id = wp_insert_user( $userdata );

		if ( ! is_wp_error( $user_id ) ) {

			$this->response->redirect('directory/thankyou');

		} else {

			$this->response->view('register',[
				'errors' => [$user_id->get_error_message()],
				'hash' => $hash
			]);
		}

		die();
	}

	public function thankyouGET(){
		$this->response->view('thankyou');
	}

	public function loginGET(){
		if( !is_user_logged_in() ){
			$this->response->view('login');
		} else {
			$this->response->redirect('directory/view');
		}
	}

	public function errorGET(){
		$this->response->view('404');
	}

	
}