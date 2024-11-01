<html>
<head>
<title>vooPlayer Shortcode</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce_popup.js?v=3211"></script>
<?php
wp_enqueue_script('jquery');
global $wp_scripts;
wp_print_scripts();
?>
<script language="javascript">

var type = 'video';
var typeSearch = 'video';
var selectedId = 0;


function confirmSelection()
{

  // Default params
  var resizeCode = '';
  var sizeCode = '';
  var timeStart = '';
  var timeEnd = '';
  var floatCode = '';
  var popupCode = '';

  // Resize check
  var resizeValue = jQuery("#vp_shortCode-resize").is(':checked');
  if(resizeValue) {
    resizeCode = '';
  } else {
    var heightValue = jQuery("#vp_shortCode-height").val();
    var widthValue = jQuery("#vp_shortCode-width").val();
    var sizeCode = 'height="'+heightValue+'" width="'+widthValue+'" '
  }

  //Time check
  var startValue = jQuery("#vp_shortCode-start").val();
  if(startValue>0) {
    timeStart = 'start="'+startValue+'" '
  }
  var endValue = jQuery("#vp_shortCode-end").val();
  if(endValue>0) {
    timeEnd = 'end="'+endValue+'" '
  }
  var timeCode = timeStart + timeEnd;

  //Float check
  var floatValue = jQuery("#vp_shortCode-float").is(':checked');
  if(floatValue) {
    var floatSide = jQuery("#vp_shortCode-floatSide").val();
    var floatValue = jQuery("#vp_shortCode-floatValue").val();
    var floatUnit = jQuery("#vp_shortCode-floatUnit").val();
    floatCode = 'float="'+floatSide+'-'+floatValue+floatUnit+'" ';
  }

  //Popup check
  var popupValue = jQuery("#vp_shortCode-popup").is(':checked');
  if(popupValue) {
    var popupFullCode = '';
    var popupType = jQuery("#vp_shortCode-popupType").val();
    var popupValue = jQuery("#vp_shortCode-popupValue").val();
    if(popupValue == "") {
      var popupValue = jQuery("#vp_shortCode-popupValueImage").val();
    };
    var popupFullValue = jQuery("#vp_shortCode-popupFull").is(':checked');
    if(popupFullValue){
      popupFullCode = 'full="true"'
    }
    popupCode = 'popup="'+popupType+'" popupvalue="'+popupValue+'" '+ popupFullCode;
  }

  // Combined params
  var shortCodeParams = resizeCode + sizeCode + timeCode + floatCode + popupCode
  if(selectedId%1===0){
    selectedId=btoa(selectedId);
  }
  contents = '[vooplayer type="'+type+'" id="'+ selectedId +'" '+ shortCodeParams +']';
	tinyMCEPopup.execCommand('mceInsertContent', false, contents);
	tinyMCEPopup.close();
}


function showSize() {
  var resizeValue = jQuery("#vp_shortCode-resize").is(':checked');
  if(resizeValue) {
    jQuery("#vp_shortCode-sizeMenu").hide();
  } else {
    jQuery("#vp_shortCode-sizeMenu").show();
  }
}

function showFloat() {
  var floatValue = jQuery("#vp_shortCode-float").is(':checked');
  if(floatValue) {
    jQuery("#vp_shortCode-floatMenu").show();
    jQuery("#vp_shortCode-popupMenu").hide();
    jQuery("#vp_shortCode-popup").attr('checked', false)
  } else {
    jQuery("#vp_shortCode-floatMenu").hide();
  }
}

function showPopup() {
  var popupValue = jQuery("#vp_shortCode-popup").is(':checked');
  if(popupValue) {
    jQuery("#vp_shortCode-popupMenu").show();
    jQuery("#vp_shortCode-floatMenu").hide();
    jQuery("#vp_shortCode-float").attr('checked', false)
  } else {
    jQuery("#vp_shortCode-popupMenu").hide();
  }
}

function setpopupType() {
  var popupType = jQuery("#vp_shortCode-popupType").val();
  if(popupType == 'image') {
    jQuery(".vp_shortCode-popupTypeLink").hide();
    jQuery(".vp_shortCode-popupTypeImage").show();
  } else {
    jQuery(".vp_shortCode-popupTypeLink").show();
    jQuery(".vp_shortCode-popupTypeImage").hide();

  }
}


function getItems(items)
{
  clearSelection();
  type = items;
  typeSearch = items;
  jQuery(".vp_menu-item").removeClass('vp_menu-active');
  jQuery(".vp_menu-" + items).addClass('vp_menu-active');
  jQuery(".video-item").hide();
  jQuery(".video-item-" + items).show();
  jQuery("#videoItemSearch").val('');
}

function selectVideo(id)
{
  selectedId = id;
  jQuery(".video-item").hide();
  jQuery(".video-item button").hide();
  jQuery(".video-item a").hide();
  jQuery(".video-id-" + id).show();
  jQuery(".vp_shortcode-options").show();
}

function clearSelection(id)
{
  selectedId = 0;
  jQuery(".video-item button").show();
  jQuery(".video-item a").show();
  jQuery(".vp_shortcode-options").hide();
}


function myFunction() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('videoItemSearch');
    filter = input.value.toUpperCase();
    ul = document.getElementById("videoItemList");
    li = ul.getElementsByClassName('video-item-'+type);
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("span")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

jQuery.noConflict();
jQuery(document).ready(function(){
  if(top.tinymce){
    var args = top.tinymce.activeEditor.windowManager.getParams();
    var wp = args.wp;
  }
  else{
    jQuery('.upload_image_button').hide();
  }
	// jQuery("#loading").hide();
	jQuery("#imagecontainer").show();

  var custom_uploader;
  jQuery('.upload_image_button').click(function(e) {
      e.preventDefault();
      var $upload_button = jQuery(this);
      //Extend the wp.media object
      custom_uploader = wp.media.frames.file_frame = wp.media({
          title: 'Choose Image',
          button: {
              text: 'Choose Image'
          },
          multiple: false
      });
      //When a file is selected, grab the URL and set it as the text field's value
      custom_uploader.on('select', function() {
          var attachment = custom_uploader.state().get('selection').first().toJSON();
          $upload_button.siblings('input[type="text"]').val(attachment.url);
      });
      //Open the uploader dialog
      custom_uploader.open();
    });

});

</script>
</head>
<body>

<div class="vp_menu">
  <span class="vp_menu-item vp_menu-video vp_menu-active" onclick="getItems('video')">
    <i class="material-icons">video_library</i> Videos
  </span>
  <span class="vp_menu-item vp_menu-funnel" onclick="getItems('funnel')"> <i class="material-icons">filter_list</i> Funnels</span>
  <span class="vp_menu-item vp_menu-ab" onclick="getItems('ab')"> <i class="material-icons">call_split</i> AB</span>
  <span class="vp_menu-item vp_menu-playlist" onclick="getItems('playlist')"> <i class="material-icons">playlist_play</i> Playlists</span>
  <span class="vp_menu-item vp_menu-contest" onclick="getItems('contest')"> <i class="material-icons">card_giftcard</i> Contests</span>
  <div class="vp_search">
    <input type="text" id="videoItemSearch" onkeyup="myFunction()" placeholder="Search Items">
  </div>
</div>

<div style="height:520px; overflow-y:scroll; overflow-x: hidden; margin-top:5px;width:100%;display:none" id="imagecontainer">
<div id="videoItemList">

<div id="loading" style="display:block"><img src="images/loading.gif"></div>
<?php
if (!class_exists("Curl"))
	require "curl.class.php";
$POST['apiid'] = "voo_wp";
$POST['valid'] = get_option('voo_valid_id');
$curl = new Curl();
$page = $curl->post ($this->service_url.'/user/videos/wp', $POST);
$videolist = json_decode($page);
$i=0;
$editLink = 'https://app.vooplayer.com/#/';
foreach($videolist as $key=>$video){
  $video->encodedId = base64_encode($video->vid);
  if(!isset($video->thumbnail)){
    $video->thumbnail = 'http://placehold.it/75x50/fff/000?text='.$video->type;
  }
	echo '<div class="video-item video-item-'.$video->type.' video-id-'.$video->vid.'">
  <img src="'.$video->thumbnail.'" /><span class="video-info">'.$video->vtitle.'<br /> <a href="'.$editLink.''.$video->type.'/'.$video->encodedId.'" target="_blank">Edit Video</a></span>
  <span style="float:right;padding:10px">
    <button class="vp_shortcode-button" type="button" onclick="selectVideo('.$video->vid.')">Select</button>

  </span>
  </div>';
}
  echo "<style> #loading {display:none !important} </style>";
?>
</div>

<div class="vp_shortcode-options" style="display:none;">
  <br>
  <br>
  <br>
  <div class="vp_shortcode-option" >
    <input type="checkbox" name="resize" checked id="vp_shortCode-resize" onclick="showSize()">
    <label for="resize">Responsive player</label> &nbsp; &nbsp; &nbsp;
    <span style="display:none" id="vp_shortCode-sizeMenu">
      <input type="number" style="width:120px" name="width" placeholder="Width (px)" checked id="vp_shortCode-width">
      <input type="number" style="width:120px" name="height" placeholder="Height (px)" checked id="vp_shortCode-height">
    </span>
    <span style="float:right">
      <input type="number" style="width:120px" name="start" placeholder="Start time" checked id="vp_shortCode-start">
      <input type="number" style="width:120px" name="end" placeholder="End time" checked id="vp_shortCode-end">
    </span>
  </div>
  <div class="vp_shortcode-option">
    <input type="checkbox" name="float" id="vp_shortCode-float" onclick="showFloat()">
    <label for="float">Float player</label> &nbsp; &nbsp; &nbsp;
    <span style="display:none" id="vp_shortCode-floatMenu">
      <label for="floatSide">Side</label>
      <select class="" name="floatSide" id="vp_shortCode-floatSide">
        <option value="right">Right</option>
        <option value="left">Left</option>
        <option value="top">Top</option>
        <option value="none">None</option>
      </select>
      <input type="number" name="floatValue" placeholder="Float Value" id="vp_shortCode-floatValue" value="25">
      <label for="floatUnit">Unit</label>
      <select class="" name="floatUnit"  id="vp_shortCode-floatUnit">
        <option value="px">px</option>
        <option value="%" selected>%</option>
      </select>
    </span>
  </div>
  <div class="vp_shortcode-option">
    <input type="checkbox" name="popup" id="vp_shortCode-popup" onclick="showPopup()">
    <label for="popup">Popup player</label> &nbsp; &nbsp; &nbsp;
    <span id="vp_shortCode-popupMenu" style="display:none">
      <select class="" name="popupType" id="vp_shortCode-popupType" onchange="setpopupType()">
        <option value="link">Text Link</option>
        <option value="image">Image</option>
      </select>
      <input class="vp_shortCode-popupTypeLink" type="text" name="height" placeholder="popup text" id="vp_shortCode-popupValue">
      <span class="vp_shortCode-popupTypeImage" style="display:none">
        <input type="text" class="selected_image" id="vp_shortCode-popupValueImage" />
        <input type="button" class="upload_image_button" value="Upload Image">
      </span>
      <input type="checkbox" name="popupFull" id="vp_shortCode-popupFull">
      <label for="popupFull">Full Screen Popup</label>
    </span>
  </div>
  <div class="vp_shortcode-option">
    <button onclick="confirmSelection()" class="vp_shortCode-button vp_shortcode-buttonImportant">Generate Shortcode</button>
    <button onclick="clearSelection();getItems(typeSearch);" style="float:right" class="vp_shortCode-button">Restart Selection</button>
  </div>
</div>
</div>
<div>
  <script type="text/javascript">
    getItems('video')
  </script>
<style media="screen">
  .vp_menu {
    display: block;
    background: #2b2b2b;
    color:white;
    font-weight: bold;
    margin-bottom: 10px;
  }
  .vp_menu-active {
    color:#ffdd33;
    font-weight: bold;
  }
  .vp_shortcode-option {
    padding:10px;
    font-size:13px;
    border: 1px solid #e6e6e6;
  }
  .vp_shortcode-option input {
    padding:3px;
    font-size: 12px;
  }
  .vp_shortcode-option select {
    padding:3px;
    font-size: 12px;
  }
  .vp_menu-item {
    display: inline-block;
    padding:10px;
    text-transform: uppercase;
  }
  .vp_menu-item i {
    font-size: 15px;
    display: inline-block;
    float: left;
    margin-right: 5px
  }
  .vp_menu-item:hover{
    cursor:pointer;
  }
  .vp_search {
    padding:5px;
    float:right;
    width:250px
  }
  .vp_search input {
    width:100%;
    padding:5px;
  }
  .video-item {
    border: 1px solid #e6e6e6;
    float:left;
    width:100%;
    font-size:12px;
    font-weight: normal;
    color:#666;
  }
  .video-item a {
    color:#0099c9;
    text-decoration: none;
  }
  .vp_shortcode-button {
    background-color: #797979; /* Green */
    border: none;
    color: white;
    padding: 7px 10px;
    border-radius: 2px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: bold;
  }
  .vp_shortcode-buttonImportant {
    background-color: #00749c !important;
  }
  .vp_shortcode-button:hover{
    background-color: #4CAF50; /* Green */
    cursor:pointer;
  }
  .video-item .video-info {
    padding:10px;
    float:left;
  }
  .video-item img {
    float:left;
    width:75px;
    height:50px;
  }
  .video-item:hover {
    background: #e6e6e6;
  }
</style>
</div>
</body>
</html>
