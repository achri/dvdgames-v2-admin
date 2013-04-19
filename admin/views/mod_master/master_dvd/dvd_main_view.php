<?php
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>

<?php $this->load->view($link_view.'/dvd_list_view')?>