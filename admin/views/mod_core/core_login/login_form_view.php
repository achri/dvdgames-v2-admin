<div id="outerdiv">
<div id="innerdiv">
<form id="myform" class="ui-state-default ui-corner-all cssform" action="<?php echo site_url($link_controller); ?>" method="post">
<fieldset class="ui-widget-content ui-corner-all"><legend><?php echo $login_form?></legend>

<p>
<label for="user"><?php echo $login_username ?></label>
<input type="text" id="usr_id" name="usr_id" value="<?php echo $user_val ?>" <?php echo $user_readonly ?> />
</p>

<p>
<label for="password"><?php echo $login_password ?></label>
<input type="password" id=usr_pwd name="usr_pwd" value="" />
</p>


<div style="margin-left: 150px;">
<input type="submit" value="<?php echo $login_submit ?>" class="fg-button ui-state-default ui-corner-all" /> 
<input type="reset" value="<?php echo $login_reset ?>" class="fg-button ui-state-default ui-corner-all" />
</div>

</fieldset>
</form>

<div class="error_msg" style="text-align:center;">
	<?php echo $login_msg; ?>
</div>
</div>
</div>