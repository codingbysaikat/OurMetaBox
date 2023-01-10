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
        add_action('plugin_loaded', array($this, 'umbox_text_domin_load'));
        add_action('admin_menu', array($this, 'umbox_post_metabox'));
        add_action('save_post', array($this, 'umbox_image_post_metabox'));
        add_action('save_post', array($this, 'umbox_save_post_location'));
        add_action('admin_enqueue_scripts', array($this, 'umbx_admin_scripts'));
    }
    public function umbx_admin_scripts(){
        wp_enqueue_style('meta-box-css', plugin_dir_url(__FILE__) . 'assets/admin/style.css');
        wp_enqueue_script('my-script-js', plugin_dir_url(__FILE__) . 'assets/admin/script.js', 'jQuery', time(), true);
    }
    private function is_secured($nonce_field, $action, $post_id){
        $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] : '';

        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }
        if (wp_is_post_autosave($post_id)) {
            return false;
        }
        if (wp_is_post_revision($post_id)) {
            return false;
        }
        return true;
    }
    public function umbox_image_post_metabox($post_id){
        if (!$this->is_secured('umbox_post_image_field', 'umbox_image_loaction', $post_id)) {
            return $post_id;
        }
    
        $save_image_id = esc_attr(isset($_POST['image_id'])?$_POST['image_id']:'');
        update_post_meta($post_id,'image_id', $save_image_id );
        $save_image_url = esc_attr(isset($_POST['image_url'])?$_POST['image_url']:'');
        update_post_meta($post_id,'image_url', $save_image_url);


    }
    
    public function umbox_save_post_location($post_id){
        if (!$this->is_secured('umbox_location_field', 'umbox_loaction', $post_id)) {
            return $post_id;
        }

        $location = isset($_POST['umbox_loaction']) ? $_POST['umbox_loaction'] : '';
        $country = isset($_POST['umbox_country']) ? $_POST['umbox_country'] : '';
        $fovarite = isset($_POST['umbox_checkbox']) ? $_POST['umbox_checkbox'] : '';
        $colors = isset($_POST['omb_clr']) ? $_POST['omb_clr'] : array();

        if ($location == '' && $country == '') {
            return $post_id;
        }
        $location = sanitize_text_field($location);
        $country = sanitize_text_field($country);
        $fovarite = sanitize_text_field($fovarite);
        update_post_meta($post_id, 'umbox_loaction', $location);
        update_post_meta($post_id, 'umbox_country', $country);
        update_post_meta($post_id, 'umbox_checkbox', $fovarite);
        update_post_meta($post_id, 'omb_clr', $colors);
    }
    public  function umbox_post_metabox(){
        add_meta_box(
            'umbox_post_location',
            __('Location Info', 'umbox'),
            array($this, 'umbox_display_loaction'),
            'post',
            'side',


        );
        add_meta_box(
            'umbox_post_image',
            __('Upload Image', 'umbox'),
            array($this, 'umbox_image_media'),
            'post',
            'side'
        );
    }
    public function umbox_image_media($post){
        $image_url = get_post_meta($post->ID,'image_url',true);
        $image_id = get_post_meta($post->ID,'image_id',true);
        $umbx_field_label = __('Image');
        $umbx_image_name = __('Upload Image');
        wp_nonce_field('umbox_image_loaction', 'umbox_post_image_field');
        $media_html = <<< EOD
    <div class= 'fields'>
    <div class= 'fields_c'>
    <label>{$umbx_field_label}</label>
    <div class= 'input_c'>
    <button class='btn' id='upload_image'>{$umbx_image_name} </button>
    </div class='image_fields'>
    <input type='hidden' name='image_id' id='image_id' value= "{$image_id}">
    <input type='hidden' name='image_url' id='image_url' value="{$image_url}">
    <div class='attach_image'>
    <div>
    </div>
  
    </div>
    </div>
    </div>


    EOD;
        echo $media_html;
    }

    public  function umbox_display_loaction($post){
        $location = get_post_meta($post->ID, 'umbox_loaction', true);
        $country = get_post_meta($post->ID, 'umbox_country', true);
        $fovarite = get_post_meta($post->ID, 'umbox_checkbox', true);
        $save_colors = get_post_meta($post->ID, 'omb_clr', true);
        $checked = $fovarite == 1 ? 'checked' : '';
        $label1 = __('OUR LOCATION', 'umbox');
        $label2 = __('Your Country', 'umbox');
        $label3 =  __('Yur favortite', 'umbox');
        $label4 = __('colors', 'umbox');
        wp_nonce_field('umbox_loaction', 'umbox_location_field');
        $colors = array('red', 'green', 'Yellow', 'Blue', 'pink', 'orange');
        $meta_input = <<<EOD
        <p>
        <label for= 'umbox_loaction'>{$label1}</label><br>
        <input type='text' name= 'umbox_loaction' id='umbox_loaction'placeholder='location' value='{$location}'>
        </p>
        <br>
        <p>
        <label for= 'umbox_country'>{$label2}</label><br>
        <input type='text' name= 'umbox_country' id='umbox_country'placeholder='country' value='{$country}'>
        </p>
        <p>
        <label for= 'umbox_country'>{$label3}</label><br>
        <input type='checkbox' name= 'umbox_checkbox' id='umbox_checkbox' value='1' {$checked}>
        </p>

        <p>
        <label >{$label4}</label><br>
        EOD;
        foreach ($colors as $color) {
            $checked = in_array($color, $save_colors) ? 'checked' : '';
            $meta_input .= <<< EOD
            <label for='omb_clr_{$color}'>{$color}</label>
            <input type='checkbox' name= 'omb_clr[]' id='omb_clr_{$color}' value='{$color}' {$checked} />
            EOD;
        }

        $meta_input .= '</p>';
        echo $meta_input;
    }

    public function umbox_text_domin_load()
    {
        load_plugin_textdomain('umbox', false, dirname(__FILE__ . 'languages'));
    }
}

new ourmeta();
