<html>
<head>
    <title><?php if (isset($title)) echo $title;?></title>

    <?php 
		if (isset($extraHeadContent)) echo $extraHeadContent;
		if (isset($extraSubHeadContent)) echo $extraSubHeadContent;
	?>
	<base href="<?php echo base_url(); ?>">
	
	<script type=\"text/javascript\">
	$(document).ready(function () {
		$('#usr_id').focus();
	});
	</script>
	
	<style type="text/css">
	/* INPUT UPPERCASE */
	.uppercase { text-transform: uppercase}

	/* TAB SMALL */
	.tab_small {
		font-size: 14px;
	}
	</style>
</head>
<body>
	<div id="glare">
	   <div id="glare-image"></div>
	</div>

	<div style="height:100%; width:100%;" class="tfont">

	<div id="headerdiv" >
	<font size="10pt">
	DVD GAME ONLINE <br>
	ADMINISTRATOR
	</font>
	</div>

	<?php $this->load->view($link_view.'/login_form_view')?>

	<div id="footerdiv" >
	&copy; <?php echo date('Y'); ?> @HR13 &trade;
	<br>
	<center><i><strong>GO&nbsp;Release</strong>&nbsp;v2.0.1</i></center>
	</div>

	</div>
	<div class="dialog-content" title="DIALOG"></div>
<div id="glare-foot">
  <div id="glare-foot-image"></div>
</div>
</body>
</html>
