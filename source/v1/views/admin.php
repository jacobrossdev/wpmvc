<?php include __DIR__ . '/parts/header.php';?>
<style>
	#admin-depositor-container {}
	#admin-depositor-container .row {
		display: flex;
		flex-direction: row;
	}
	#admin-depositor-container .row .column {
		width: 50%;
	}

	#admin-depositor-container #depositor-search {
		width: 100%;
	}

	@media screen and (max-width:768px){
		#admin-depositor-container .row {
			flex-direction: column;
		}
		#admin-depositor-container .row .column {
			width: 100%;
		}
	}
</style>
<div id="depositor-directory-header" class="responsive">
	<div class="container">
		<h1>Depositor Directory</h1>
	</div>
</div>
<div id="main" class="depositor-directory depositor-directory-admin">
	<div class="container" id="admin-depositor-container">
		<div id="depositor-directory-bulk-update">
			<?php /*<label for="bulk_decision"> Bulk Edit: </label>*/?>
			<div class="row">
				<?php
				/*<div class="column">
					<select name="bulk_decision" id="bulk_decision">
						<option value="">-- Select Decision --</option>
						<option value="approve_prospect">Approve</option>
						<option value="decline_prospect">Decline</option>
					</select>
				</div>
				<div class="column"><input type="submit" id="submit-bulk-decision" value="Commit"></div>*/
				?>

			<div class="column">
				<div id="search-container">
					<form method="GET" action="<?php echo site_url() . "/depositor/directory/admin"; ?>">
						<div class="row row-reverse">
							<div class="column"><input type="text" id="depositor-search" name="depositor-search" value="" placeholder="Search by Name or Email"></div>
							<div class="column" style="width: auto;"><input type="submit" value="Search"></div>
						</div>
					</form>
				</div>
			</div>
			</div>
		</div>
		<div id="depositor-directory" class="admin-table">
			<style>
				th, td {
				  padding: 5px;
				}
			</style>		
			<table colpadd>
				<thead>
					<tr>
						<?php /*<th><input type="checkbox" name="select-all" id="select-all"></th>*/?>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th style="width: 143px;">Decision</th>
						<th>Decision Date</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $data['rows'] as $key => $row ) : ?>
						<tr data-prospect_id="<?php echo $row->id; ?>">
							<?php /*<td><input type="checkbox" name="selected-row[]" value="<?php echo $row->id; ?>"><?php /*</td>*/?>
							<td><?php echo $row->depositor_fname; ?></td>
							<td><?php echo $row->depositor_lname; ?></td>
							<td><?php echo $row->depositor_email; ?></td>
							<td class="decision">
								<?php if(!$row->decision_on) : ?>
									<button class="approve">Approve</button>
									<button class="denied">Decline</button>
								<?php else: ?>
									<?php echo $row->approved ? 'Approved' : 'Declined'; ?>
								<?php endif; ?>
							</td>
							<td class="decision_on">
								<?php if($row->decision_on) : ?>
									<?php echo date('F j, \'y h:iA', strtotime($row->decision_on)); ?>
								<?php endif;?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
</div>	
<script>
	jQuery(document).ready(function($){

		$('.approve').on('click', function(){
			let row = $(this).closest('tr');
			let prospect_id = $(this).closest('tr').data('prospect_id');
			approve_submission(prospect_id, row);
		});

		$('.denied').on('click', function(){
			let row = $(this).closest('tr');
			let prospect_id = $(this).closest('tr').data('prospect_id');
			denied_submission(prospect_id, row);
		});

		$('th input').on('change', function(){
			if( $(this).is(':checked') ) {
				$('table tr td input').prop('checked', true);
			} else {
				$('table tr td input').prop('checked', false);
			}
		});

		<?php /*$('#submit-bulk-decision').on('click', function(){
			let bulk_decision = $('#bulk_decision').val();
			if( bulk_decision.length ){
				let checkboxes = $('td input:checked');
				let prospect_ids = [];
				checkboxes.each(function(i,t){
					prospect_ids.push(parseInt($(t).val()));
				});
				send_decision( prospect_ids, bulk_decision, checkboxes );
			}
		});*/?>

		function approve_submission(prospect_id, row){
			send_decision( prospect_id, 'approve_prospect', row );
		}

		function denied_submission(prospect_id, row){
			send_decision( prospect_id, 'decline_prospect', row );
		}

		function send_decision( prospect_id, decision, row ){

			$.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				type: 'POST',
				dataType: 'json',
				data: {
					action: decision,
					prospect_id: prospect_id
				},
				beforeSend: function(){
					decision_processing(row);
				},
				success: function(d){
					if(d.success){
						setTimeout(function(){
							decision_complete(d, row);
						},1000);
					} else {
						alert(d.error);
					}
				},
				complete: function(){
					// modify the row so that it shows the decision was made
				}
			});
		}

		function decision_processing(row){
			row.each(function(i,t){
				$(t).closest('tr').find('td.decision').text('Processing...');
			});
		}

		function decision_complete(d, row){
			if( typeof d.data[0] !== 'undefined' ){
				for (const property in d.data) {
				  let row = $('tr[data-prospect_id="'+d.data[property].id+'"]');
					$(row).find('td.decision_on').text(d.data[property].decision_on);
				  if( d.data[property].approved ){
						$(row).find('td.decision').text('Approved');
				  } else {
						$(row).find('td.decision').text('Declined');
				  }
				}
			} else {
				$(row).find('td.decision_on').text(d.data.decision_on);
				if( d.data.approved ){
					$(row).find('td.decision').text('Approved');
				} else {
					$(row).find('td.decision').text('Declined');
				}
				if( d.data.duplicate ){
					alert(d.message);
				}
			}
		}

	})
</script>
<?php include __DIR__ . '/parts/footer.php' ?>