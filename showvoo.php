<div id="voo_wrapper">
<form method="post" action="<?php echo $this->service_url;?>members/webservice_login.php" id="voo">
</form>
<script language="javascript">
jQuery('<i'+'fra'+'me id="vooCall" name="vooCall" style="width:100%;height:1000px;overflow-x:hidden;" frameborder="0">').appendTo('#voo_wrapper');
jQuery('#voo').attr('target','vooCall');
jQuery('<input type="hidden" name="ap'+'ii'+'d" id="ap'+'ii'+'d" value="v'+'o'+'o_'+'wp">').appendTo('#voo');
jQuery('<input type="hidden" name="va'+'li'+'d" id="va'+'li'+'d" value="<?php echo get_option("voo_valid_id");?>">').appendTo('#voo');
jQuery('#voo').submit();
jQuery('#voo').remove();
</script>
</div>
