<?php
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>
<script type="text/javascript">
//$(function() {
	
//});
</script>
<table width="99%" border=0>
<tr valign="top">
	<td>
		<?php $this->load->view($link_view.'/order_form_view')?>
	</td>
</tr>
<tr valign="top">
	<td>
		<?php $this->load->view($link_view.'/order_grid_view')?>
	</td>
</tr>
</table>
