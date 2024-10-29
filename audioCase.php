<?php
/**
* Plugin Name: audioCase
* Plugin URI: https://audiocase.app/
* Description: audioCase allows you to showcase a single song presented with a clean, minimalist display featuring artwork and waveform visualizer.
* Version: 1.2.1
* Author: Monarkie Digital Content Solutions, LLC
* Author URI: https://monarkie.digital
* License: GPL3
* Text Domain: audiocase
* Requires PHP: 7.0
* Requires at least: 5.0
* Tested up to: 6.3.1
*/

/*
audioCase is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

audioCase is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with audioCase. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
*/




/* !1. HOOKS */

//1.1
//hint: registers all custom shortcodes on init
add_action('init','audiocase_register_shortcodes');


//1.2
//hint: registers custom admin column headers
add_filter('manage_edit-acase_columns','audiocase_column_headers');

//1.3
add_filter('manage_acase_posts_custom_column','audiocase_column_data',1,2);

//1.4
// Advanced Custom Fields Settings (Version: 5.9.5)
define('AUDIOCASE_ACF_PATH', plugin_dir_path( __FILE__ ) .'/lib/advanced-custom-fields/');
define('AUDIOCASE_ACF_URL', plugin_dir_url( __FILE__ ) .'/lib/advanced-custom-fields/');
$audiocase_show_acf_admin = false;
if(class_exists('ACF')){
  $audiocase_show_acf_admin = true;
}
include_once(AUDIOCASE_ACF_PATH .'acf.php');
add_filter('acf/settings/url','audiocase_acf_settings_url');
add_filter('acf/settings/show_admin','audiocase_acf_show_admin');

function audiocase_acf_settings_url($url){
  return AUDIOCASE_ACF_URL;
}
function audiocase_acf_show_admin($show_admin){
  global $audiocase_show_acf_admin;
  return $audiocase_show_acf_admin;
  return true;
}
add_action('views_edit-acase','audiocase_older_acf_warning');
function audiocase_older_acf_warning( $views ) {

  global $acf;
  $acf_ver = (float) $acf->settings['version'];
  $acf_ver_req = 6.2;

  if($acf_ver < $acf_ver_req) {
  echo wp_kses_post('<p style="color:#ff0000;font-weight:600;">ACF Version: '.esc_html($acf_ver).'<br>You are using an older version of the Advanced Custom Fields plugin. Some features of audioCase may not work unless you update or deactivate this plugin.</p>',array());
}
  return $views;
}
add_action('views_edit-acase','audiocase_rights_warning');
function audiocase_rights_warning($views){
  echo wp_kses_post('<p style="color:#00A36C;font-weight:600;"><b>Notice:</b> You should only use audio to which you own the rights.</p>',array());
  return $views;
}


//activate/deactivate/uninstall functions
add_filter( 'redirect_post_location', 'audiocase_redirect_post_location');
add_action('admin_notices','audiocase_check_wp_version');
register_uninstall_hook(__FILE__,'audiocase_uninstall_plugin');




/* !2. SHORTCODES */

//2.1
//registers custom shortcode
function audiocase_register_shortcodes() {
  add_shortcode('audiocase', 'audiocase_display_shortcode', 1000);
}

//2.2
//returns html block for song display
function audiocase_display_shortcode( $atts ){

    $output = '';
    $post_id = $atts['id'];
    $playerID = $post_id;

    // get song title
    $songTitle = esc_textarea(get_the_title($playerID));


    // get song file url
    $song_id = get_post_meta($playerID, 'audiocase_song', true);
    $music = esc_url(wp_get_attachment_url( $song_id ));


    // get cover art url
    $artwork_id = get_post_meta($playerID, 'audiocase_cover', true);
    if(empty($artwork_id)){
      $artwork_id = 0;
      $coverArt = esc_url(plugins_url('assets/images/bg01.jpg',__FILE__));
      } else {
        $coverArt = esc_url(wp_get_attachment_url( $artwork_id ));
      }

    // get viewer action
    $viewerAction = get_post_meta($playerID, 'audiocase_action', true);
    switch( $viewerAction ) {
    case 'no':
      // get the custom song file data
      $songAction = wp_kses_post('<i class="fas fa-music fa-2x"></i>',array());
      break;
      case 'download':
        // get the custom song file data
        $audiocase_link = get_post_meta($playerID, 'audiocase_download', true);
        $songAction = wp_kses_post('<a href="'.$audiocase_link.'" download="'.$songTitle.'" target="_blank"><i class="fas fa-download fa-2x"></i></a>',array());
        break;
      case 'buy':
          // get the custom song file data
          $audiocase_link = get_post_meta($playerID, 'audiocase_buy', true);
          $songAction = wp_kses_post('<a href="'.$audiocase_link.'" target="_blank"><i class="fas fa-cart-arrow-down fa-2x"></i></a>',array());
          break;
        }


    //set variables
    $playState = 0; // 0 = stopped, 1 = playing, 2 = paused
    $stopButton = '<i class="fas fa-play-circle" onclick="butStart('.$playerID.')"></i>';
    $pauseButton = '<i class="fas fa-pause-circle" onclick="butPress('.$playerID.')"></i>';
    $playButton = '<i class="fas fa-play-circle" onclick="butPress('.$playerID.')"></i>';
    $loadingIcon = '<i class="fas fa-compact-disc fa-pulse"></i>';
    $wavDiv = '#acWav'.$playerID;

    //setup output variables for js access
    $output = '<script>
    var pid'.$playerID.' = '.$playerID.';
    wavDiv['.$playerID.'] = "'.$wavDiv.'";
    loadingIcon['.$playerID.'] = \''.$loadingIcon.'\';
    audioFile['.$playerID.'] = "'.$music.'";
    playButton['.$playerID.'] = \''.$playButton.'\';
    pauseButton['.$playerID.'] = \''.$pauseButton.'\';
    stopButton['.$playerID.'] = \''.$stopButton.'\';
    </script>';

    $output .= '
    <div class="acBlock">
    <div id="acPlayer'.$playerID.'" class="acPlayer">
    <div id="acHolder'.$playerID.'" class="acHolder">
       <div id="infoBox'.$playerID.'" class="infoBox">
          <div id="titleBox'.$playerID.'" class="titleBox">'.html_entity_decode($songTitle).'</div>
          <div id="actionBox'.$playerID.'" class="actionBox">'.$songAction.'</div>

       </div>
       <div id="coverArt'.$playerID.'" class="coverArt"><img src="'.$coverArt.'" />

       <!--<div id="playButton'.$playerID.'" class="playButton">'.$stopButton.'</div>-->
       <div id="playButton'.$playerID.'" class="playButton"> </div>
       <div id="acWav'.$playerID.'" class="wavesurfer"></div>
    </div>
    </div>
    </div>
    </div>';

    $output .='
    <script>

    wavesurfer[pid'.$playerID.'] = WaveSurfer.create({
          container: wavDiv[pid'.$playerID.'],
          responsive: true,
          hideScrollBar: true,
          cursorWidth: 0,
          cursorColor: \'#0892d0\',
          progressColor: \'#ffffff\',
          waveColor: \'#0892d0\'
      });

      wavesurfer[pid'.$playerID.'].load( audioFile[pid'.$playerID.'] );



      function butPress(pid'.$playerID.') {
        //Set up duplicate array of players that excludes newly selected player
        var wExclude = wavesurfer.slice();
        wExclude.splice(pid'.$playerID.',1);
        //Pause any players that are currently playing
        function stopPlay(wExclude){
          if(wExclude.isPlaying()){
            wExclude.pause();
          }
        }
        wExclude.forEach(stopPlay);
        wavesurfer[pid'.$playerID.'].playPause();
      }

      function butStart(pid'.$playerID.') {
        wavesurfer[pid'.$playerID.'].play();
      }


      wavesurfer[pid'.$playerID.'].on(\'loading\', function () {
        document.getElementById("playButton" + pid'.$playerID.').innerHTML = loadingIcon[pid'.$playerID.']; //loading icon
        });

      wavesurfer[pid'.$playerID.'].on(\'ready\', function () {
        document.getElementById("playButton" + pid'.$playerID.').innerHTML = playButton[pid'.$playerID.']; //play button image
        });


      wavesurfer[pid'.$playerID.'].on(\'play\', function () {
        document.getElementById("playButton" + pid'.$playerID.').innerHTML = pauseButton[pid'.$playerID.']; //play button image
        });

      wavesurfer[pid'.$playerID.'].on(\'pause\', function () {
        document.getElementById("playButton" + pid'.$playerID.').innerHTML = playButton[pid'.$playerID.']; //play button image
        });


      wavesurfer[pid'.$playerID.'].on(\'finish\', function () {
        document.getElementById("playButton" + pid'.$playerID.').innerHTML = playButton[pid'.$playerID.']; //play button image
        wavesurfer[pid'.$playerID.'].seekTo(0);
        });
    </script>';

    return $output;

}





/* !3. FILTERS */

//3.1
function audiocase_column_headers( $columns ){
  //creating custom colum header data
  $columns = array(
     'cb'=>'<input type="checkbox" />',
     'title'=>__('Title'),
     'song'=>__('Song File'),
     'action'=>__('Viewer Action'),
     'id'=>__('ID'),
     'sc'=>__('Shortcode'),
   );

   //return new columns
   return $columns;
}

//3.2
function audiocase_column_data( $column, $post_id){
  $output = '';

  switch( $column ) {
  case 'song':
    // get the custom song file data
    $song = get_field('audiocase_song', $post_id);
    $output .= $song;
    break;
    case 'action':
      // get the custom song viewer action
      $action = get_field('audiocase_action', $post_id);
      $output .= $action;
      break;
    case 'id':
        // get the custom song post ID
        $id = $post_id;
        $output .= $id;
        break;
    case 'sc':
          // get the custom song shortcode
          $id = $post_id;
          $sc = '[audiocase id="'.$id.'"]';
          $output .= $sc;
          break;
          }

      echo esc_html($output);
}



/* !4. EXTERNAL SCRIPTS */

    //4.1
    //add external css and javascript files
      function audiocase_manage_scripts(){

        // register stylesheet
        wp_register_style('audiocase_css', plugins_url('assets/css/ac_css.css',__FILE__));
        wp_enqueue_style('audiocase_css');

        //WaveSurfer (Ver. 6.4.0)
        wp_register_script('wavesurfer', plugins_url('assets/js/wavesurfer.js',__FILE__));
        wp_enqueue_script('wavesurfer');

        //Fontawesome
        wp_register_script('all', plugins_url('assets/js/fontawesome/all.js',__FILE__));
        wp_enqueue_script('all');

        //JS Variables
        wp_register_script('acVars', plugins_url('assets/js/acVars.js',__FILE__));
        wp_enqueue_script('acVars');

}



add_action('wp_enqueue_scripts','audiocase_manage_scripts', 100 );



/* !5. ACTIONS */
function audiocase_check_wp_version(){
  global $pagenow;

  if($pagenow == 'plugins.php' && is_plugin_active('audiocase/audioCase.php')):

  $wp_version = get_bloginfo('version');

  $tested_versions = array (
        '6.1.1',
        '6.2',
        '6.3',
        '6.3.1',
  );
      if(!in_array($wp_version,$tested_versions)):
        $notice = audiocase_get_admin_notice('audioCase has not been tested in your version of WordPress. Functionality cannot be guaranteed.','error');

        echo wp_kses_post($notice,array());

        endif;

  endif;
}


//Uninstall cleanup
function audiocase_uninstall_plugin(){
    audiocase_remove_post_data();
}


function audiocase_remove_post_data() {

	// get WP's wpdb class
	global $wpdb;

	// setup return variable
	$data_removed = false;

	try {

		// get our custom table name
		$table_name = $wpdb->prefix . "posts";

		// set up custom post types array
		$custom_post_types = 'acase';

		// remove data from the posts db table where post types are equal to our custom post types
		$data_removed = $wpdb->query(
			$wpdb->prepare(
				"
					DELETE FROM $table_name
					WHERE post_type = %s
				",
				$custom_post_types
			)
		);

    //get table prefix (in case db prefix is other than "wp_")
    $table_name_1 = $wpdb->prefix . "postmeta";
    $table_name_2 = $wpdb->prefix . "posts";


    // delete orphaned meta data
		$wpdb->query(
			$wpdb->prepare(
				"
				DELETE pm
				FROM $table_name_1 pm
				LEFT JOIN $table_name_2 wp ON wp.ID = pm.post_id
				WHERE wp.ID IS NULL
				"
			)
		);

	} catch( Exception $e ) {

		// php error

	}

	// return result
	return $data_removed;

}

// redirect to main page after edit
function audiocase_redirect_post_location ( $location ) {

    if ( 'acase' == get_post_type($_POST[$post_id]) ) {

    
        if ( isset( $_POST['save'] ) || isset( $_POST['publish'] ) )
            return admin_url( "edit.php?post_type=acase" );

    } 
    return $location;
} 



/* !6. HELPERS */
  function audiocase_get_admin_notice($message,$class){
    $output = '';

    try {
      $output = '
      <div class="'.$class.'">
      <p>'.$message.'</p>
      </div>';
    } catch (Exception $e){

    }
    return $output;
  }



/* !7. CUSTOM POST TYPES */

//7.1
// audioCases
include_once(plugin_dir_path( __FILE__ ).'cpt/acase.php');





 ?>
