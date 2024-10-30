<?php
/**
 * Plugin Name: Buddypress Xprofile Fields Custom Css Classes
 * Plugin URI:  http://codepixlabs.com/plugins/buddypress-xprofile-fields-custom-css-classes
 * Description: Add custom classes to xprofile fields for ease of styling.
 * Author:      codepixlabs
 * Author URI:  http://codepixlabs.com
 * Version:     1.0
 * Text Domain: xprofile-fields-custom-css-classes
 * License:     GPLv2 or later (license.txt)
 *
 */
// If this file is called directly, abort.
if (! defined('WPINC') ) {
    die;
}


if(	! class_exists('Buddypress_XProfile_Field_Custom_Classes') ) :
    /**
    *
    */
    class Buddypress_XProfile_Field_Custom_Classes
    {

        protected static $_instance = null;
      /**
    	 * Main Buddypress_XProfile_Field_Custom_Classes Instance
    	 *
    	 * Ensures only one instance of Buddypress_XProfile_Field_Custom_Classes is loaded or can be loaded.
    	 *
    	 * @static
    	 * @return Buddypress_XProfile_Field_Custom_Classes - Main instance
    	 */
    	public static function instance() {

    		if ( is_null( self::$_instance ) )
    			self::$_instance = new self();

    		return self::$_instance;
    	}
    	/**
    	 * Initiate construct
    	 */
        public function __construct()
        {
            
            if( bp_is_active( 'xprofile' ) ) {

                add_action('xprofile_field_after_contentbox', array( $this, 'add_content_box' ), 12, 1);
                add_action('xprofile_fields_saved_field', array( $this, 'save_options' ), 12, 1);
                add_filter('bp_field_css_classes',array( $this, 'bp_field_css_classes') );
            
            }
        }

        /**
         * add_content_box description
         * @param [type] $field [description]
         */
        public function add_content_box( $field )
        {
            do_action('before_xprofile_custom_classes_field', $field);
            $wrapper_classes = bp_xprofile_get_meta($field->id, 'field', 'xprofile_wrapper_classes');
            
                ?>
            <div class="postbox xprofile-custom-classes">
              <h3>
                <label for="">
                    <?php _e('Custom Classes', 'xprofile-fields-custom-css-classes'); ?>
                </label>
              </h3>
              <div class="inside">
                <table>
                  <tr>
                <td><label>
                    <?php _e('Wrapper Class', 'xprofile-fields-custom-css-classes'); ?>
                  </label>
                  <input type="text" name="wrapper_classes" value="<?php echo $wrapper_classes; ?>" />
                  <p>
                    <?php _e('This class will be assigned to field wrapper', 'xprofile-fields-custom-css-classes'); ?>
                  </p></td>
                  </tr>
                </table>
              </div>
            </div>
        <!-- #post-body -->
    <?php
        }

        public function save_options($field) {
            bp_xprofile_update_field_meta($field->id, 'xprofile_wrapper_classes', $_POST['wrapper_classes']);
        }

        public function bp_field_css_classes($classes) {
          global $profile_template;
          $wrapper_classes = bp_xprofile_get_meta($profile_template->field->id, 'field', 'xprofile_wrapper_classes');
          
          $classes[] = $wrapper_classes;
          return $classes;
        }
    }
endif;

add_action('bp_init', 'xprofile_custom_classes_init');
function xprofile_custom_classes_init(){
    Buddypress_XProfile_Field_Custom_Classes::instance();
}
