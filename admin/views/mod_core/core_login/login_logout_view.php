<script language="javascript">
var $dlg_btn = {
	"LOGOUT" : function() {
		$.post('index.php/<?php echo $link_controller?>/loging_out');
		$dlg_content.dialog('close');
	},
	"CANCEL" : function() {
		$(this).dialog('close');
	}
}

konfirmasi("Are you sure want to logout ???",$dlg_btn);
</script>