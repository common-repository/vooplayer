<?php
if (isset($_POST['act']) && $_POST['act']=="logout")
{
	update_option('voo_valid_id','');
	update_option('voo_login_id','');
}
?>
<div id="vooplayer_wrapper">
	<div class="vooplayer_header">
		<div class="vooplayer_logo" align="left">
      <img src="<?php echo $this->plugin_url?>/images/logo.png">
      <div style="float:right">
        <a style="color:white;text-decoration:none;" target="_blank" href="https://www.vooplayer.com">Learn more</a>
      </div>
    </div>
	</div>

	<div id="poststuff">
		<div class="postbox">
			<h3 class="hndle">
        <a href="//vooplayer.com" target="_blank">
          <img src="<?php echo $this->plugin_url?>/images/headline.png" />
        </a>
</h3>

			<?php if (get_option("voo_valid_id")!=""){?>
			<div class="inside col">
				<div style="padding:10px;">
				<span class="msg" style="color:#000"><?php _e('You are logged in as', $this->namespace); ?> <b><?php echo get_option("voo_login_id"); ?></b></span><span>&nbsp;&nbsp;<a href='#' id="logout"><?php _e('Log Out', $this->namespace); ?></a></span>
        <p>Learn more about <a href="https://docs.vooplayer.com/integrations/wordpress-plugin-tutorial" target="_blank">using vooPlayer WordPress plugin</a>.</p>
				</div>
			</div>
			<?php } else {?>
			<div class="inside col" style="width:300px;border-right:1px solid #dfdfdf;">
				<h4 class="coltitle"><?php _e('Members Sign in', $this->namespace); ?></h4>
				<div style="padding-left:10px;color: rgb(102,101,102);font-family: 'Open Sans', sans-serif;">
				Already have a vooPlayer account?<br/>
				Please login with the related username and password as you login on vooplayer.com
				</div>
				<form id="vooplayer_login" name="vooplayer_login" method="post" class="apicall">
					<input type="hidden" name="act" value="login" class="act">
					<input type="hidden" name="returnurl" value="<?php echo admin_url("admin-ajax.php?action=voo_call");?>">
					<table>
						<tr>
							<td><input type="text" name="amember_login" id="login_amember_login" size="15" placeholder='<?php _e('vooPlayer.com Username', $this->namespace); ?>' class="voo_input"></td>
						</tr>
						<tr>
							<td><input type="password" name="amember_pass" id="login_amember_pass" size="15" placeholder='<?php _e('vooPlayer.com Password', $this->namespace); ?>' class="voo_input"></td>
						</tr>
						<tr>
							<td><a href="https://app.vooplayer.com/#/forgot"><?php _e('Forgot Password', $this->namespace); ?>?</a></td>
						</tr>
						<tr>
							<td><input type="submit" name="login"  value="<?php _e('Login', $this->namespace); ?>" class="button-primary">&nbsp;<img src='<?php echo $this->plugin_url?>/images/loading.gif' id='login_wait' class="wait" style='display:none;'></td>

						</tr>
					</table>
					<div id="login_msg" style="height:35px;color:#00000; margin-top:10px;" class="msg"></div>
				</form>
			</div>

      <div class="inside col" style="width:400px">
				<h4 class="coltitle"><?php _e('Not a member?', $this->namespace); ?></h4>
				<div style="padding-left:10px;color: rgb(102,101,102);font-family: 'Open Sans', sans-serif;">
				Find out more about everything vooPlayer has to offer...<br/> <br/>
        <a href="//vooplayer.com" target="_blank">
          <img src="<?php echo $this->plugin_url?>/images/headline.png" />
        </a>
				</div>
			</div>

			<?php }?>
			<div style="clear:both;float:none;"></div>

		</div>
	</div>
</div>
<form method="post" id="frmlogout">
<input type="hidden" name="act" value="logout">
</form>
<form method="post" id="frmaweber">
<input type="hidden" name="amember_login" id="aweber_amember_login" size="15"><input type="hidden" name="email" size="30" id="aweber_email"><input type="hidden" name="pass" size="30" id="aweber_pass">
</form>
<script language="javascript">
	function api_callback(act,valid)
	{
		jQuery("#"+act+"_wait").hide();
		if (act == "login")
		{
			actname = "Login successfull.";
		}
		if (act == "reg")
		{
			actname = "Congratulations for creating an account with vooPlayer. You may now login by entering your details on the form on the left.";
		}
		jQuery('.msg').hide();
		jQuery("#"+act+"_msg").show();
		if (valid>0)
		{
			jQuery("#"+act+"_msg").html(actname);

			if (act == "reg")
			{
				actname = "Registration";
				jQuery("#frmaweber").attr('target','vooCall');
				jQuery("#aweber_email").val(jQuery("#reg_email").val());
				jQuery("#aweber_amember_login").val(jQuery("#reg_amember_login").val());
				jQuery("#aweber_pass").val(jQuery("#reg_amember_pass").val());
				jQuery("#frmaweber").attr('action','<?php echo $this->service_url;?>members/webservice_interspire.php');
				jQuery("#frmaweber").submit();
				jQuery("#"+act+"_wait").show();
			}
			var t=setTimeout(function(){location.href = '<?php echo admin_url("admin.php?page=vooplayer")?>';},2000);
		}
		else
		{
			if (valid == -1)
			{
				jQuery("#"+act+"_msg").html("User name already exists. Try again.");
			}
			else if (valid == -2)
			{
				jQuery("#"+act+"_msg").html("Email id already exists. Try again.");
			}
			else
			{
				jQuery("#"+act+"_msg").html("Login failed. Try again.");
			}
		}
		jQuery('#apiid').remove();
	}
	jQuery(document).ready(function(){
		jQuery('#logout').click(function(){
			jQuery("#frmlogout").submit();
			return false;
		});
		jQuery('<i'+'fra'+'me id="vooCall" name="vooCall" frameborder="0" width="1px" height="1px">').appendTo('body');
		jQuery(".apicall").submit(function(){
			var act = jQuery(this).find(".act").val();
			var error = false;
			if (jQuery("#"+act+"_amember_login").length > 0 && jQuery.trim(jQuery("#"+act+"_amember_login").val())== "")
			{
				error = true;
				jQuery("#"+act+"_amember_login").css('background','#FFC1B9');
			}
			if (jQuery("#"+act+"_amember_pass").length > 0 && jQuery.trim(jQuery("#"+act+"_amember_pass").val())== "")
			{
				error = true;
				jQuery("#"+act+"_amember_pass").css('background','#FFC1B9');
			}
			if (jQuery("#"+act+"_email").length > 0 && jQuery.trim(jQuery("#"+act+"_email").val())== "")
			{
				error = true;
				jQuery("#"+act+"_email").css('background','#FFC1B9');
			}
			if (error)
			{
				return false;
			}
			jQuery('#apiid').remove();
			jQuery('<input type="hidden" name="ap'+'ii'+'d" id="ap'+'ii'+'d" value="v'+'o'+'o_'+'wp">').appendTo(this);
			jQuery(this).attr('target','vooCall');
			jQuery(this).attr('action','<?php echo $this->service_url;?>user/login/wp');

			jQuery('.wait').hide();
			jQuery("#"+act+"_wait").show();
			return true;
		});
		jQuery('.voo_input').focus(function(){
			jQuery(this).css('background','#ffffff');
		});
	});
</script>
