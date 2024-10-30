<?php
/*
Plugin Name: LH Offline Indicator
Description: Basic offline support using the offline js library
Plugin URI: https://lhero.org/portfolio/lh-offline-indicator/
Version: 1.00
Author: Peter Shaw
Author URI: https://shawfactor.com
License: GPLv2 or later
*/


class LH_offline_indicator_plugin {

var $plugin_version = '1.00';
var $namespace = 'lh_offline_indicator';
var $script_atts;
  
  
public function add_script_atts($script_handle, $atts) { 
  
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$script_handle] = $atts; 
  
}

}
  
  
  public function remove_script_atts($script_handle) { 
  


unset($this->script_atts[$script_handle]); 
  


}


 /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The    ID to register with WordPress
     * @file_path        The path to the actual file
     * @is_script        Optional argument for if the incoming file_path is a JavaScript source file.
     */
    private function load_file( $name, $file_path, $is_script = false, $deps = array(), $in_footer = true, $atts = array() ) {
        $url  = plugins_url( $file_path, __FILE__ );
        $file = plugin_dir_path( __FILE__ ) . $file_path;
        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, $this->plugin_version, $in_footer ); 
                wp_enqueue_script( $name );
            }
            else {
                wp_register_style( $name, $url, $deps, $this->plugin_version );
                wp_enqueue_style( $name );
            } // end if
        } // end if
	  
	  if (isset($atts) and is_array($atts) and isset($is_script)){
		
		
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$name] = $atts; 
  
}

		  
	 add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	   

	   
if (isset($this->script_atts[$handle][0]) and !empty($this->script_atts[$handle][0])){
  
$atts = $this->script_atts[$handle];
  
$this->remove_script_atts($handle);
		 
return str_replace( ' src', ' '.implode(" ", $atts).' src', $tag );

		 

	 } else {
	   
 return $tag;	   
	   
	   
	 }
	

}, 10, 2 );
 

	
	  
	}
		
    } // end load_file


    /**
     * Registers and enqueues stylesheets for the front end
     * public facing site.
     */
    private function register_scripts_and_styles() {
        if (!is_admin() ) {

            // include the offline library script
            $this->load_file( $this->namespace.'-offline-script', '/scripts/offline-min.js', true, array(), true, array('defer="defer"'));
	    	$this->load_file( $this->namespace.'-language-style', '/styles/offline-language-english.css' );
            $this->load_file( $this->namespace.'-offline-style', '/styles/offline-theme-chrome.css' );
        }

    } // end register_scripts_and_styles
  
  
  
private function get_short_language() {

return substr(get_bloginfo ( 'language' ), 0, 2);

}
  


    /**
     * Runs when the plugin is initialized
     */
    function init_lh_offline_indicator() {

        // Load JavaScript and stylesheets
        $this->register_scripts_and_styles();

    }



public function __construct() {

//Hook up to the init action
add_action( 'init', array( &$this, 'init_lh_offline_indicator' ) );
  
}




}


$lh_offline_indicator_instance = new LH_offline_indicator_plugin();

?>