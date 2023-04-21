<?php include __DIR__ . '/parts/header.php';?>
<style>
	#view-depositor-container .name-groups.row {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
    margin-left: -10px;
    margin-right: -10px;
    margin-bottom: 0;
	}
	/*#directory-result-container .row {
		margin-left: -15px;
		margin-right: -15px;
	}*/
	#directory-result-container .column {
		width: 300px;
	}
	#directory-result-container a:hover {
		text-decoration: none;
	}
	#directory-result-container a .card img {
		width: 100%;
	}
	#directory-result-container a .card h3 {
		color: #202020;
	}
	#extensions_message_3_21 {
		padding: 5px 0;
		font-size: 0.9em;
	}
	#directory-result-container.row {
		display: block;
	}
	#directory-result-container .letter {
		font-size: 1.3em;
		font-weight: bold;
		padding-top: 20px;
	}

	#depositor-alphabet a {
		color: rgb(118, 12, 22);
	}
	#depositor-alphabet {
		padding-top: 20px;
		font-size: 1.4em;
		display: flex;
		justify-content: center;
	}
</style>

<div id="depositor-directory-header" class="responsive">
	<div class="container">
		<h1>Depositor Directory</h1>
	</div>
</div>

<div id="main" class="depositor-directory depositor-directory-main responsive">
	<div class="container" id="depositor-alphabet" style="text-align: center;">
		<div class="row">
			<a href="#A">A</a> • 
			<a href="#B">B</a> • 
			<a href="#C">C</a> • 
			<a href="#D">D</a> • 
			<a href="#E">E</a> • 
			<a href="#F">F</a> • 
			<a href="#G">G</a> • 
			<a href="#H">H</a> • 
			<a href="#I">I</a> • 
			<a href="#J">J</a> • 
			<a href="#K">K</a> • 
			<a href="#L">L</a> • 
			<a href="#M">M</a> • 
			<a href="#N">N</a> • 
			<a href="#O">O</a> • 
			<a href="#P">P</a> • 
			<a href="#Q">Q</a> • 
			<a href="#R">R</a> • 
			<a href="#S">S</a> • 
			<a href="#T">T</a> • 
			<a href="#U">U</a> • 
			<a href="#V">V</a> • 
			<a href="#W">W</a> • 
			<a href="#X">X</a> • 
			<a href="#Y">Y</a> • 
			<a href="#Z">Z</a>
		</div>
	</div>

	<div class="container" id="view-depositor-container">
		<div class="row">
			<div class="column"></div>
		</div>
		<div class="row" id="directory-result-container">			

			<?php 
			$grouped_names = array();
			foreach($data['rows'] as $row){
				
				$letter = substr($row->depositor_lname, 0, 1);

				if( empty($grouped_names[$letter]) )
					$grouped_names[$letter] = array();

				array_push($grouped_names[$letter], array(
					'name' => $row->depositor_fname . ' ' .$row->depositor_lname,
					'image' => get_gf_image($row->image),
					'unit'=> $row->unit,
					'hash' => $row->hash
				));

			}

			foreach($grouped_names as $letter => $group){ ?>

			<div class="letter" id="<?php echo $letter; ?>"><?php echo $letter; ?></div>
			<hr />

			<div class="name-groups row">		
			<?php foreach($group as $depositor) : ?>
				

				<div class="column">
					<a href="<?php echo site_url() . '/depositor/directory/profile/'.$depositor['hash']; ?>">
						<div class="card">
							<h3><?php echo $depositor['name']; ?> </h3>
							<img src="<?php echo strlen($depositor['image']) ? $depositor['image'] : DD_ROOT_URL .'/assets/images/avatar.png';?>" alt="<?php echo $depositor['name']; ?> Photograph">
						</div>
					</a>
				</div>


			<?php endforeach; ?>
			</div>

			<?php } ?>

		</div>
	</div>
</div>
<?php include __DIR__ . '/parts/footer.php' ?>