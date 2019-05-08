<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form_payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
  <div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_payment" class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="entry_username"><?php echo $entry_username; ?></label>
						<div class="col-sm-10">
							<input type="text" name="pp_pro_username" value="<?php echo $pp_pro_username; ?>" placeholder="<?php echo $entry_username; ?>" id="entry-username" class="form-control"/>
							<?php if ($error_username) { ?>
							<div class="text-danger"><?php echo $error_username; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="entry_password"><?php echo $entry_password; ?></label>
						<div class="col-sm-10">
							<input type="text" name="pp_pro_password" value="<?php echo $pp_pro_password; ?>" placeholder="<?php echo $entry_password; ?>" id="entry_password" class="form-control"/>
							<?php if ($error_password) { ?>
							<div class="text-danger"><?php echo $error_password; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="entry_signature"><?php echo $entry_signature; ?></label>
						<div class="col-sm-10">
							<input type="text" name="pp_pro_signature" value="<?php echo $pp_pro_signature; ?>" placeholder="<?php echo $entry_signature; ?>" id="entry_signature" class="form-control"/>
							<?php if ($error_signature) { ?>
							<div class="text-danger"><?php echo $error_signature; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_live_demo"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
						<div class="col-sm-10">
							<select name="pp_pro_test" id="input_live_demo" class="form-control">
								<?php if ($pp_pro_test) { ?>
								<option value="1" selected="selected"><?php echo $text_yes; ?></option>
								<option value="0"><?php echo $text_no; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_yes; ?></option>
								<option value="0" selected="selected"><?php echo $text_no; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_transaction"><?php echo $entry_transaction; ?></label>
						<div class="col-sm-10">
							<select name="pp_pro_transaction" id="input_transaction" class="form-control">
								<?php if (!$pp_pro_transaction) { ?>
								<option value="0" selected="selected"><?php echo $text_authorization; ?></option>
								<?php } else { ?>
								<option value="0"><?php echo $text_authorization; ?></option>
								<?php } ?>
								<?php if ($pp_pro_transaction) { ?>
								<option value="1" selected="selected"><?php echo $text_sale; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_sale; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_cardinal_status"><?php echo $entry_cardinal_status; ?></label>
						<div class="col-sm-10">
							<select name="pp_pro_cardinal_status" id="input_cardinal_status" class="form-control">
								<?php if ($pp_pro_cardinal_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="cardinal-section">
						<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <?php echo $text_cardinal; ?></div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input_cardinal_api_id"><?php echo $entry_cardinal_api_id; ?></label>
							<div class="col-sm-10">
								<input type="text" name="pp_pro_cardinal_api_id" value="<?php echo $pp_pro_cardinal_api_id; ?>" placeholder="<?php echo $pp_pro_cardinal_api_id; ?>" id="input_cardinal_api_id" class="form-control" />
								<?php if ($error_cardinal_api_id) { ?>
								<div class="text-danger"><?php echo $error_cardinal_api_id; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input_cardinal_api_key"><?php echo $entry_cardinal_api_key; ?></label>
							<div class="col-sm-10">
								<input type="text" name="pp_pro_cardinal_api_key" value="<?php echo $pp_pro_cardinal_api_key; ?>" placeholder="<?php echo $pp_pro_cardinal_api_key; ?>" id="input_cardinal_api_key" class="form-control" />
								<?php if ($error_cardinal_api_key) { ?>
								<div class="text-danger"><?php echo $error_cardinal_api_key; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input_cardinal_org_unit_id"><?php echo $entry_cardinal_org_unit_id; ?></label>
							<div class="col-sm-10">
								<input type="text" name="pp_pro_cardinal_org_unit_id" value="<?php echo $pp_pro_cardinal_org_unit_id; ?>"placeholder="<?php echo $pp_pro_cardinal_org_unit_id; ?>" id="input_cardinal_org_unit_id" class="form-control" />
								<?php if ($error_cardinal_org_unit_id) { ?>
								<div class="text-danger"><?php echo $error_cardinal_org_unit_id; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_order_status"><?php echo $entry_order_status; ?></label>
						<div class="col-sm-10">
							<select name="pp_pro_order_status_id" id="input_order_status" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $pp_pro_order_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
						<div class="col-sm-10">
							<input type="text" name="pp_pro_total" value="<?php echo $pp_pro_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input_total" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_sort_order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="pp_pro_sort_order" value="<?php echo $pp_pro_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input_sort_order" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_geo_zone"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
							<select name="pp_pro_geo_zone_id" id="input_geo_zone" class="form-control">
								<option value="0"><?php echo $text_all_zones; ?></option>
								<?php foreach ($geo_zones as $geo_zone) { ?>
								<?php if ($geo_zone['geo_zone_id'] == $pp_pro_geo_zone_id) { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="pp_pro_status" id="input_status" class="form-control">
								<?php if ($pp_pro_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

checkCardinal();

$('#input_cardinal_status').on('change', function() {
	checkCardinal();
});	

function checkCardinal() {
	if ($('#input_cardinal_status').val() == 1) {
		$('.cardinal-section').removeClass('hidden');
	} else {
		$('.cardinal-section').addClass('hidden');
	}
}

</script>
<?php echo $footer; ?>