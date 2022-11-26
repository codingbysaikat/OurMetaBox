<?php
/**
 * Plugin Name:       Our MetaBox
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       OurMetaBoxis little meta box with WordPress post for set location
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            saikat mondal
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       umbx
 * Domain Path:       /languages 
 */

 class ourmeta{
  public  function __construct(){
        add_action('plugin_loaded', array($this,'umbox_text_domin_load'));
        add_action('admin_menu', array($this,'umbox_post_metabox'));
        add_action( 'save_post', array( $this, 'umbox_save_post_location'));
    }
    public function umbox_save_post_location($post_id){
        $location = isset($_POST['umbox_loaction'])?$_POST['umbox_loaction']:'';
        if($location == ''){
            return $post_id;
        }
        update_post_meta($post_id,'umbox_loaction',$location);
        

    }
    public  function umbox_post_metabox(){
        add_meta_box(
            'umbox_post_location',
            __('Location Info','umbox'),
            array($this,'umbox_display_loaction'),
            'post',
            'side',
           

        );
    }
    public  function umbox_display_loaction($post){
        $location = get_post_meta($post->ID,'umbox_loaction',true);
        $label = __('OUR LOCATION','umbox');
        $meta_input = <<<EOD
        <p>
        <label for= 'umbox_loaction'>{$label}</label><br>
        <input type='text' name= 'umbox_loaction' id='umbox_loaction'placeholder='location' value='{$location}'>
        </p>


        EOD;
       echo $meta_input;
    }

    public function umbox_text_domin_load(){
        load_plugin_textdomain('umbox',false, dirname(__FILE__ . 'languages'));


    }

 }

 new ourmeta();