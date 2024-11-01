<?php
/*
Plugin Name: vooPlayer - Ultimate Video Player for WordPress
Plugin URI: https://www.vooplayer.com
Description: vooPlayer WordPress plugin is an add-on to the vooPlayer SaaS platform which allows you to fully customize, analyze and add superpowers to any video
Author: vooPlayer
Version: 4.0.4
*/
?>
<?php
//wp_oembed_add_provider( '#https?://vooplayer\.com/video/.*#i', 'https://api.vooplayer.com/services/oembed', true );

wp_embed_register_handler( 'vooplayer123456789', '#https?://vooplayer\.com/video/.*#i', 'wp_embed_handler_vooplayer123456789' );

function wp_embed_handler_vooplayer123456789( $matches, $attr, $url, $rawattr ) {

$embed = sprintf(
		"<script src='https://plugin.cdn.vooplayer.com/assets/vooplayer.js'> </script><iframe class='vooplayer' data-playerId='1651' allowtransparency='true' style='max-width:100%' name='vooplayerframe' allowfullscreen='true' src='https://plugin.cdn.vooplayer.com/publish/1651' frameborder='0' scrolling='no'> </iframe>"
		);

	return apply_filters( 'embed_vooplayer123456789', $embed, $matches, $attr, $url, $rawattr );
}

class Vooplayer
{
	var $plugin_base;
	var $plugin_name;
	var $plugin_dir;
	var $plugin_url;
	var $namespace;
	var $min_words_per;
	var $max_words_per;
	//var $service_url = "http://183.177.126.17/vooplayer/";
	var $service_url = "https://api.vooplayer.com/";
	function __construct() {
		$this->plugin_base =  plugin_basename( __FILE__ );
		$this->plugin_name = trim( dirname( $this->plugin_base ), '/' );
		$this->plugin_dir = WP_PLUGIN_DIR . '/' . $this->plugin_name;
		$this->plugin_url = WP_PLUGIN_URL . '/' . $this->plugin_name;
		$this->namespace = "vooplayer";
		// Hooks
		add_action('admin_menu', array($this,'admin_menu'));
		add_shortcode("vooplayer",array($this,'show_player'));
		add_shortcode("VOOPLAYER",array($this,'show_player'));
		//wp_enqueue_script('jquery');
		//wp_enqueue_style($this->namespace."_css",$this->plugin_url."/style.css");
		add_action('init', array($this,'add_vooplayer_button'));

		add_action( 'wp_enqueue_scripts', array( $this, 'callJqueryScriptAndStyle' ) );

		$file   = basename( __FILE__ );
		$folder = basename( dirname( __FILE__ ) );
		$hook = "in_plugin_update_message-{$folder}/{$file}";
		add_action( $hook, 'your_update_message_cb', 20, 2 ); // 10:priority, 2:arguments #

		function your_update_message_cb( $plugin_data, $r ){
			$response = file_get_contents("http://main.vooplayer.com/downloads/wp_plugin_versions.json");
			$versions = json_decode($response);
			$currVer =(int)str_replace(".","",$plugin_data["Version"]);
			$nextVer =(int)str_replace(".","",$plugin_data["new_version"]);
			$nextVersionHtml="";
			foreach($versions as $version){
				if ($nextVersionC==$version->c){
					$nextVersionHtml=$version->html;
					break;
				}
			}
			if($nextVersionHtml!=""){
				echo $nextVersionHtml;
			}
		}

	}

	function callJqueryScriptAndStyle() {
			wp_enqueue_script('jquery');
			wp_enqueue_style($this->namespace."_css",$this->plugin_url."/style.css");
	}

	function admin_menu()
	{
		if ( current_user_can( 'list_users' )){
			add_menu_page( __( 'vooPlayer', $this->namespace), __( 'vooPlayer', $this->namespace ), 8, $this->namespace, array($this,'my_vooplayer'),$this->plugin_url."/images/icon.jpg");
			add_submenu_page($this->namespace, __( 'Settings', $this->namespace ), __( 'Settings', $this->namespace ),8, $this->namespace."_settings", array($this,'settings'));
		}
		else
		{
		}

	}

	function help()
	{
		echo "<script language='javascript'>window.open('https://vooplayer.helpscoutdocs.com');</script>";
	}

	function settings()
	{
		include("settings.php");
	}

	function my_vooplayer()
	{
		/*if (get_option("voo_valid_id") == '')
		{
			echo "<script language='javascript'>location.href = '".admin_url('admin.php?page=vooplayer_settings')."';</script>";
			exit();
		}*/
		echo "<script language='javascript'>location.href = '".admin_url('admin.php?page=vooplayer_settings')."';</script>";
		//include("showvoo.php");
	}

	function list_videos()
	{
	}
	function split_tests()
	{
	}

	public $tabs = array(
		// The assoc key represents the ID
		// It is NOT allowed to contain spaces
		 'LOGIN' => array(
		 	 'title'   => 'Login'
		 	,'content' => 'This screen will validate your vooPlayer account login details and authenticate you to operate your vooPlayer account here. <br/>You have to validate your vooPlayer account login details only once.<br/>You can change associated vooPlayer account any time by login in from this screen.<br/><br/>Username - Your vooPlayer account Username.<br/>Password - Your vooPlayer account Password.'
		 ),
		 'FORGOT' => array(
		 	 'title'   => 'Lost Password?'
		 	,'content' => 'You will be redirected to vooPlayer web site to retrieve password.<br/><br/>Password will be e-mailed to you.'
		 ),
		 'Register' => array(
		 	 'title'   => 'New User'
		 	,'content' => 'You can create your vooPlayer Free account using this screen. You can operate your vooPlayer account directly on vooPlayer web site using these account details.<br/></br>Username - Your vooPlayer account Username.<br/>Password - Your vooPlayer account Password.<br/>Email Address - Email address associated with your vooPlayer account.All communication will be done on this email address.'
		 )
	);

	public function add_tabs($contextual_help, $screen_id, $screen)
	{
		if (strpos($screen_id, "vooplayer")!==false)
		{
			foreach ( $this->tabs as $id => $data )
			{
				get_current_screen()->add_help_tab( array(
					 'id'       => $id
					,'title'    => __( $data['title'], $this->namespace )
					// Use the content only if you want to add something
					// static on every help tab. Example: Another title inside the tab
					,'content'  => '<p><u>This plugin is only a interface to operate your vooPlayer account in Wordpress Admin.</u></p>'
					,'callback' => array( $this, 'prepare' )
				) );
			}
		}
	}

	public function prepare( $screen, $tab )
	{
	    	printf(
			 '<p>%s</p>'
			,__(
	    			 $tab['callback'][0]->tabs[ $tab['id'] ]['content']
				,'dmb_textdomain'
			 )
		);
	}

	public function api_callback()
	{
		echo "1111111111111111111111111111111111111111";
		$valid = 0;
		if ($_REQUEST["mid"] >0)
		{
			update_option('voo_valid_id',$_REQUEST["mid"]);
			update_option('voo_login_id',$_REQUEST["name"]);
			$valid = 1;
		}
		else if ($_REQUEST["mid"] < 0)
		{
			$valid = $_REQUEST["mid"];
		}
		echo "<script language='javascript'>window.parent.api_callback('".$_REQUEST["act"]."','".$valid."');</script>";
		exit();
	}

	function show_player($atts)
	{
    $vp_publishPath = '';
		if (isset($atts['type']) && $atts['type'] != 'video') {
      $vp_publishPath = $atts['type']."/";
    }
		$urlString='';
		if (isset($atts['float'])) {
      $urlString = "?float=".$atts['float'];
    }
		if (isset($atts['start'])) {
			if($urlString==''){
				$urlString = "?s=".$atts['start'];
			}
			else{
				$urlString = $urlString."&s=".$atts['start'];
			}
    }
		if (isset($atts['end'])) {
			if($urlString==''){
				$urlString = "?e=".$atts['end'];
			}
			else{
				$urlString = $urlString."&e=".$atts['end'];
			}
    }

		$aTxt='';
		$attsString='';
		if(isset($atts['popup'])){
			if($atts['popup']=='image' && isset($atts['popupvalue'])){
				$aTxt="<img src='".$atts['popupvalue']."'/>";
			}
			if($atts['popup']=='link' && isset($atts['popupvalue'])){
				$aTxt=$atts['popupvalue'];
			}
			if (isset($atts['full'])) {
	      $attsString = $attsString  ." data-full='".$atts['id']."' style='display:none' data-width='100%' data-height='100%' ";
        if($urlString==''){
  				$urlString = "?fullScreen=true";
  			}
  			else{
  				$urlString = $urlString."&fullScreen=true";
  			}
      }
			else{
				if (isset($atts['width']) && isset($atts['height'])) {
		      $attsString = $attsString." data-width='".$atts['width']."' data-height='".$atts['height']."'";
		    }
				else{
					 $attsString = $attsString." style='max-width:100%'";
				}
			}
			return "<script src='https://plugin.cdn.vooplayer.com/assets/vooplayer.js'></script><a class='fancyboxIframe vooplayer' ".$attsString." href='https://plugin.cdn.vooplayer.com/publish/" .$vp_publishPath.$atts['id'].$urlString."' data-playerId='".$atts['id']."' data-fancybox-type='iframe'>".$aTxt."</a>";
		}
		else{
			if (isset($atts['width']) && isset($atts['height'])) {
	      $attsString = $attsString." width='".$atts['width']."' height='".$atts['height']."'";
	    }
			else{
				 $attsString = $attsString." style='max-width:100%'";
			}
		}
		return "<script src='https://plugin.cdn.vooplayer.com/assets/vooplayer.js'></script><iframe class='vooplayer' data-playerId='".$atts['id']."' allowtransparency='true' name='vooplayerframe' allowfullscreen='true'".$attsString." src='https://plugin.cdn.vooplayer.com/publish/".$vp_publishPath.$atts['id'].$urlString."'  frameborder='0' scrolling='no'> </iframe>";
	}

	/*Editor Button*/
	function add_vooplayer_button() {
		// Don't bother doing this stuff if the current user lacks permissions
	    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", array($this,"add_vooplayer_tinymce_plugin"));
			add_filter('mce_buttons', array($this, 'register_vooplayer_button'));
		}
	}

	function register_vooplayer_button($buttons) {
	   array_push($buttons, "|", "vooplayer");
	   return $buttons;
	}

	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_vooplayer_tinymce_plugin($plugin_array) {
		global $pluginName, $persiteurl;
		$plugin_array['vooplayer'] = $this->plugin_url."/js/editor_plugin.js";
		return $plugin_array;
	}

	function videolist()
	{
		include('videolist.php');
		exit();
	}

/*Activation notice*/

	function activation_notice(){
		if(function_exists('admin_url')){
			//echo '<div class="update-nag">'.__( 'Please', $this->namespace ).' <a href="' . admin_url( 'options-general.php?page='.$this->plugin_name ) . '">'.__( 'Click here', $this->namespace ).'</a> '.__( 'to Login or register to vooPlayer.', $this->namespace ).'.</div>';
			echo ' <div class="notice notice-info is-dismissible">
							<p><strong>vooPlayer has been installed.</strong>
							<a href="' . admin_url( 'options-general.php?page='.$this->plugin_name ) . '">'.__( 'Click here to log in', $this->namespace ). '</a></p>
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">Dismiss this notice.</span>
							</button>
						</div>';
		}
	}

}

$Vooplayer = new Vooplayer();
add_filter('contextual_help', array($Vooplayer,'add_tabs'), 10, 3);
add_action('wp_ajax_voo_call', array($Vooplayer,'api_callback'));
add_action('wp_ajax_nopriv_voo_call', array($Vooplayer,'api_callback'));
add_action('wp_ajax_voo_videolist', array($Vooplayer,'videolist'));
if(get_option("voo_valid_id")==""){
	add_action( 'admin_notices', array($Vooplayer,'activation_notice'));
}
?>
