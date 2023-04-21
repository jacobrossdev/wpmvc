<?php include __DIR__ . '/parts/header.php'; ?>
<style>
	
	#depositor-directory-header h1 {
		margin-bottom:0;
	}
	#depositor-directory-header {
		padding-top:30px;
		padding-bottom:30px;
		background-color: #fff;
	}

	.depositor-directory-profile .profile-card {
    padding: 30px;
    border: 1px solid #efefef;
    background-color: #fff;
    margin: 0 10px 20px;
    border-radius: 3px;
    box-shadow: 0px 0px 3px -1px #959595;
    display: flex;
    flex-direction: row;
    margin-bottom: 30px;
	}
	.depositor-profile-image img {
		width: 100%;
	}
	.depositor-profile-image {
		float: left;
    padding-right: 20px;
    min-width: 300px;
    max-width: 300px;
	}
	.depositor-profile-information {
		float: none;
		overflow: hidden;
		padding-left: 20px;
		border-left : 1px solid #dedede;
		font-size: 1.3em;
	}
</style>
<div id="depositor-directory-header" class="responsive">
	<div class="container">
		<h1><?php echo $data['profile']['depositor_fname'] . ' ' . $data['profile']['depositor_lname']?></h1>
	</div>
</div>
<div id="main" class="depositor-directory depositor-directory-profile">
	<div class="container responsive">
		
			<div class="row"><div class="navigation"><a href="<?php echo site_url() . '/depositor/directory/view'; ?>">Depositor Directory</a> / <?php echo $data['profile']['depositor_fname']?> <?php echo $data['profile']['depositor_lname']?></div></div>
		<div class="row">

			
			<div class="profile-card clearfix">

				<div class="depositor-profile-image">
					<img src="<?php echo get_gf_image($data['profile']['profile-portrait']);?>" alt="<?php echo $data['profile']['depositor_fname']?> <?php echo $data['profile']['depositor_lname']?> Profile Photo">
				</div>
				<div class="depositor-profile-information">
					
					<div class="information-row">
						<p><strong>Unit:</strong><br />
							<?php echo $data['profile']['Address Line 2']?></p>
					</div>

					<div class="information-row">
						<p><strong>Originally From:</strong><br />
							<?php echo $data['profile']['State / Province']?></p>
					</div>

					<div class="information-row">
						<p><strong>Joined the Community:</strong><br />
							<?php echo date('F j, \'y', strtotime($data['profile']['created_on']))?></p>
					</div>

					<div class="information-row">
						<p><strong>Unit:</strong><br />
							<?php echo $data['profile']['Address Line 2']?></p>
					</div>

					<div class="information-row">
						<p><strong>Bio:</strong><br />
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sed leo aliquet, sagittis neque quis, aliquam augue. Etiam sit amet ullamcorper mauris. Cras mattis neque est, nec dictum nulla ullamcorper quis. Nunc mauris quam, faucibus ut tincidunt in, vulputate eget neque. Vestibulum tincidunt felis est, a efficitur nibh facilisis id. </p>
					</div>

					<div class="information-row">
						<p><strong>Contact <?php echo $data['profile']['depositor_fname']?> <?php echo $data['profile']['depositor_lname']?>:</strong><br />
							<a href="emailto:<?php echo $data['profile']['depositor_email']; ?>"><?php echo $data['profile']['depositor_email']; ?></a></p>
					</div>

				</div>

			</div>

		</div>
	</div>
</div>


<?php include __DIR__ . '/parts/footer.php' ?>