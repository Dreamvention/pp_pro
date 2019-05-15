<style type="text/css">

#form_pp_pro .fieldset-pp-pro{
	position: relative;
}
#form_pp_pro .lds-spinner {
	display: inline-block;
	position: absolute;
	width: 64px;
	height: 64px;
	left: 50%;
	top: 50%;
	margin-left: -32px;
	margin-top: -32px;
}
#form_pp_pro .lds-spinner div {
	transform-origin: 32px 32px;
	animation: lds-spinner 1.2s linear infinite;
}
#form_pp_pro .lds-spinner div:after {
	content: " ";
	display: block;
	position: absolute;
	top: 3px;
	left: 29px;
	width: 5px;
	height: 14px;
	border-radius: 20%;
	background: #000;
}
#form_pp_pro .lds-spinner div:nth-child(1) {
	transform: rotate(0deg);
	animation-delay: -1.1s;
}
#form_pp_pro .lds-spinner div:nth-child(2) {
	transform: rotate(30deg);
	animation-delay: -1s;
}
#form_pp_pro .lds-spinner div:nth-child(3) {
	transform: rotate(60deg);
	animation-delay: -0.9s;
}
#form_pp_pro .lds-spinner div:nth-child(4) {
	transform: rotate(90deg);
	animation-delay: -0.8s;
}
#form_pp_pro .lds-spinner div:nth-child(5) {
	transform: rotate(120deg);
	animation-delay: -0.7s;
}
#form_pp_pro .lds-spinner div:nth-child(6) {
	transform: rotate(150deg);
	animation-delay: -0.6s;
}
#form_pp_pro .lds-spinner div:nth-child(7) {
	transform: rotate(180deg);
	animation-delay: -0.5s;
}
#form_pp_pro .lds-spinner div:nth-child(8) {
	transform: rotate(210deg);
	animation-delay: -0.4s;
}
#form_pp_pro .lds-spinner div:nth-child(9) {
	transform: rotate(240deg);
	animation-delay: -0.3s;
}
#form_pp_pro .lds-spinner div:nth-child(10) {
	transform: rotate(270deg);
	animation-delay: -0.2s;
}
#form_pp_pro .lds-spinner div:nth-child(11) {
	transform: rotate(300deg);
	animation-delay: -0.1s;
}
#form_pp_pro .lds-spinner div:nth-child(12) {
	transform: rotate(330deg);
	animation-delay: 0s;
}
@keyframes lds-spinner {
	0% {
		opacity: 1;
	}
	100% {
		opacity: 0;
	}
}
</style>
<form id="form_pp_pro" class="form-horizontal">
	<legend><?php echo $text_credit_card; ?></legend>
	<fieldset class="fieldset-pp-pro">
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input_cc_type"><?php echo $entry_cc_type; ?></label>
			<div class="col-sm-10">
				<select name="cc_type" id="input_cc_type" class="form-control">
					<?php foreach ($cards as $card) { ?>
					<option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input_cc_number"><?php echo $entry_cc_number; ?></label>
			<div class="col-sm-10">
				<input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input_cc_number" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="input_cc_start_date"><span data-toggle="tooltip" title="<?php echo $help_start_date; ?>"><?php echo $entry_cc_start_date; ?></span></label>
			<div class="col-sm-3">
				<select name="cc_start_date_month" id="input_cc_start_date_month" class="form-control">
					<?php foreach ($months as $month) { ?>
					<option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
					<?php } ?>     
				</select>
			</div>
			<div class="col-sm-3">
				<select name="cc_start_date_year" id="input_cc_start_date_year" class="form-control">
					<?php foreach ($year_valid as $year) { ?>
					<option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input_cc_expire_date"><?php echo $entry_cc_expire_date; ?></label>
			<div class="col-sm-3">
				<select name="cc_expire_date_month" id="input_cc_expire_date_month" class="form-control">
					<?php foreach ($months as $month) { ?>
					<option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-3">
				<select name="cc_expire_date_year" id="input_cc_expire_date_year" class="form-control">
					<?php foreach ($year_expire as $year) { ?>
					<option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input_cc_cvv2"><?php echo $entry_cc_cvv2; ?></label>
			<div class="col-sm-10">
				<input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input_cc_cvv2" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="input_cc_issue"><span data-toggle="tooltip" title="<?php echo $help_issue; ?>"><?php echo $entry_cc_issue; ?></span></label>
			<div class="col-sm-10">
				<input type="text" name="cc_issue" value="" placeholder="<?php echo $entry_cc_issue; ?>" id="input_cc_issue" class="form-control" />
			</div>
		</div>
		<?php if ($jwt) { ?><input type="hidden" name="jwt" id="jwt" value="<?php echo $jwt; ?>" /><?php } ?>
		<div class="lds-spinner hidden"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</fieldset>
	<div class="buttons">
		<div class="pull-right">
			<input type="button" value="<?php echo $button_confirm; ?>" id="button_confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
		</div>
	</div>
</form>
<script type="text/javascript">

<?php if ($jwt) { ?>
$('document').ready(function() {
	showAlert({wait: true});
		
	$.ajax({
		url: '<?php echo $cardinal_url; ?>',
		type: 'post',
		data: '',
		dataType: 'script',
		success: function() {
			setupCardinal();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
<?php } ?>

$('#form_pp_pro #button_confirm').bind('click', function() {					
	showAlert({wait: true});
		
	<?php if ($jwt) { ?>
	$.ajax({
		url: 'index.php?route=extension/payment/pp_pro/cca',
		type: 'post',
		data: $('#form_pp_pro :input'),
		dataType: 'json',
		success: function(json) {			
			showAlert(json);
						
			if (json['cca']) {
				executeCardinal(json['cca']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	<?php } else { ?>
	$.ajax({
		url: 'index.php?route=extension/payment/pp_pro/send',
		type: 'post',
		data: $('#form_pp_pro :input'),
		dataType: 'json',
		success: function(json) {			
			showAlert(json);
														
			if (json['success']) {
				location = json['success'];
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	<?php } ?>
});

<?php if ($jwt) { ?>
function setupCardinal() {
	try {
		// Make sure the Cardinal namespace is available before we set anything up
		if ('Cardinal' in window) {			
			// Documentation Step 2 - Optional configuration to enable logging to developer console
			Cardinal.configure({logging: {debug: 'verbose'}});

			// Documentation Step 3 - Initalize Cardinal Cruise
			Cardinal.setup('init', {
				jwt: $('#jwt').val()
			});

			// Optional event to inform us that Songbird has initialzed properly
			Cardinal.on('payments.setupComplete', function () {
				showAlert({confirm: true});
								
				console.log('Cardinal Cruise is ready to be used');
			});

			// Documentation Step 5
			// Required event that will trigger when any kind of result is reached. Songbird will always end the transaction by calling this method, regardless of the resulting state.
			// This includes failure to initialize
			Cardinal.on('payments.validated', function (data, jwt) {
				try {
					// It is very important to make sure that the signature is verified with our ApiKey before accepting the results. Here we send the JWT to the back end to be verified via Ajax,
					// but in most flows a form would be posting to a new page to complete authorization.
					$.ajax({
						method: 'post',
						url: 'index.php?route=extension/payment/pp_pro/jwt',
						data: {'data': JSON.stringify(data), 'jwt': jwt},
						dataType: 'json',
						success: function(json) {
							showAlert(json);
														
							if (json['success']) {
								location = json['success'];
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} catch (validateError) {
					console.error('Failed while processing validate', validateError);
				}
			});
		} else {
			console.error('Cardinal namespace is not available. Please check the script Url.');
		}
	} catch (error) {
		console.error('Cardinal Cruise failed during startup', error);
	}
}

function executeCardinal(cca) {
	// Documentation Step 4
	// Start the CCA transaction, passing the order data we collected on the page
	try {
		Cardinal.start('cca', cca);
	} catch (error) {
		console.error('Error while trying to start CCA', error);
	}
}
<?php } ?>

function showAlert(json) {
	$('#form_pp_pro .alert').remove();
	$('#form_pp_pro .form-group').removeClass('has-error');
	$('#form_pp_pro .lds-spinner').addClass('hidden');
	$('#form_pp_pro #button_confirm').attr('disabled', true);
	
	if (json['wait']) {
		$('#form_pp_pro').prepend('<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <?php echo $text_wait; ?></div>');
		
		$('#form_pp_pro .lds-spinner').removeClass('hidden');
	}
	
	if (json['error']) {
		if (json['error']['warning']) {
			$('#form_pp_pro').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['error']['warning'] + '</div>');
		}
		
		for (i in json['error']) {
			var element = $('#input_' + i);
			
			element.parents('.form-group').addClass('has-error');
		}
	}
	
	if (json['confirm']) {
		$('#form_pp_pro #button_confirm').attr('disabled', false);
	}
}

</script>