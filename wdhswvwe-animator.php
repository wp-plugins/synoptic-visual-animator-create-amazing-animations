<?php
/*
Plugin Name: Synoptic Visual Animator: create amazing animations
Version: 1.0
Plugin URI: http://www.wdh.im/projects/synoptic-visual-animator-create-amazing-animations/
Description: Synoptic Visual Animator is an extension for Synoptic Web Designer: best WordPress design tool which helps you to create awesome animations for your website in few seconds. Transform your website from static website into a dinamic website.
Author: Web Developers House
Author URI: http://www.wdh.im

Change log:

        1.0 (2015-06-09)
	
		* Initial release.
		
Installation: Upload the folder wdhsvwe-animator from the zip file to "wp-content/plugins/" and activate the plugin in your wp-admin panel or upload wdhsvwe-animator.zip in the "Add new" area.
 */

global $wdhSVWE;

if (!class_exists("wdhSVWE_Animator") && class_exists("wdhSVWE")) {

// Including Files
    include_once 'wdhswvwe-animator-config.php';

global $wdhSVWE_extend, $wdhSVWE_Animator;

    if ($wdhSVWE["version"] >= 1.1) {
    // Language File
        include_once 'languages/'.$wdhSVWE['LANGUAGE'].'.php';

        class wdhSVWE_Animator {

            function wdhSVWE_Animator(){ // Constructor
                // Add Frontend CSS
                add_action('wp_enqueue_scripts', array(&$this, 'addCSS'));

                // Add Admin CSS
                add_action('admin_enqueue_scripts', array(&$this, 'addCSS'));

                // Installation
                $this->installUpdate();

                // Core
                $this->core();

            }

            function core(){
                global $wdhSVWE;
                global $wdhSVWE_extend;
                $groups = array();
                $fields = array();

                // Setup Group Panel Icon
                $groups[0]['name']   = 'animator';
                $groups[0]['text']   = $wdhSVWE['TXT_ANIMATOR_PM_ANIMATIONS'];
                $groups[0]['icon']   = 'icon-animator-settings'; 
                $groups[0]['id']     = 'wdh-svwe-panel-group-animator'; 
                $groupName           = $groups[0]['name'];

                // Setup Fields in Panel
                // -----------------------------------------------------------------
                $fields[$groupName]                     = array();

                // Animation Effect Field
                $fields[$groupName][0]['name']          = 'animation_effect';  
                $fields[$groupName][0]['label']         = $wdhSVWE['TXT_ANIMATOR_ANIMATION_EFFECT'];  
                $fields[$groupName][0]['info']          = $wdhSVWE['TXT_ANIMATOR_ANIMATION_EFFECT_INFO'];
                $fields[$groupName][0]['type']          = 'select';
                $fields[$groupName][0]['values']        = 'none|bounce|flash|pulse|rubberBand|shake';
                $fields[$groupName][0]['css']           = 'animation-name';
                $fields[$groupName][0]['css_web_kit']   = true;

                // Animation Duration Field
                $fields[$groupName][1]['name']          = 'animation_duration';  
                $fields[$groupName][1]['label']         = $wdhSVWE['TXT_ANIMATOR_ANIMATION_DURATION'];  
                $fields[$groupName][1]['info']          = $wdhSVWE['TXT_ANIMATOR_ANIMATION_DURATION_INFO'];
                $fields[$groupName][1]['type']          = 'size';
                $fields[$groupName][1]['values']        = 's|m|ms';
                $fields[$groupName][1]['min']           = 1;
                $fields[$groupName][1]['max']           = 1000;
                $fields[$groupName][1]['range']         = 1;
                $fields[$groupName][1]['css']           = 'animation-duration';
                $fields[$groupName][1]['css_web_kit']   = true;
                $fields[$groupName][1]['always']        = true;

                // Animation Delay Field
                $fields[$groupName][2]['name']          = 'animation_delay';  
                $fields[$groupName][2]['label']         = $wdhSVWE['TXT_ANIMATOR_ANIMATION_DELAY'];  
                $fields[$groupName][2]['info']          = $wdhSVWE['TXT_ANIMATOR_ANIMATION_DELAY_INFO'];
                $fields[$groupName][2]['type']          = 'size';
                $fields[$groupName][2]['values']        = 's|m|ms';
                $fields[$groupName][2]['min']           = 0;
                $fields[$groupName][2]['max']           = 1000;
                $fields[$groupName][2]['range']         = 1;
                $fields[$groupName][2]['css']           = 'animation-delay';
                $fields[$groupName][2]['css_web_kit']   = true;
                $fields[$groupName][2]['always']        = true;

                // Animation Period Field
                $fields[$groupName][3]['name']          = 'animation_period';  
                $fields[$groupName][3]['label']         = $wdhSVWE['TXT_ANIMATOR_ANIMATION_PERIOD'];  
                $fields[$groupName][3]['info']          = $wdhSVWE['TXT_ANIMATOR_ANIMATION_PERIOD_INFO'];
                $fields[$groupName][3]['type']          = 'select';
                $fields[$groupName][3]['values']        = $this->getPeriod();
                $fields[$groupName][3]['css']           = 'animation-iteration-count';
                $fields[$groupName][3]['css_web_kit']   = true;
                $fields[$groupName][3]['always']        = true;

                // Create Panel Group
                $wdhSVWE_extend->createPanelGroup($groups, $fields);
            }

            function installUpdate(){
                global $wdhSVWE;
                $wdhSVWE_extend_Animator_version = $wdhSVWE["animator_version"];
                $wdhSVWE_extend_Animator_version_database = get_option('SVWE_extend_Animator_version');

                // Check if SVWE_extend_Animator_version exist and create fields if not exist
                if (!isset($wdhSVWE_extend_Animator_version_database)) {
                    add_option('SVWE_extend_Animator_version', $wdhSVWE_extend_Animator_version);
                    // Adding our fields
                    $this->createFields();
                } else if ($wdhSVWE_extend_Animator_version != $wdhSVWE_extend_Animator_version_database){
                    update_option('SVWE_extend_Animator_version', $wdhSVWE_extend_Animator_version); 
                    // Adding our fields
                    $this->createFields();
                }
            }

            function createFields(){
                global $wdhSVWE_extend;

                $fields = array();

                // Animation Effect Field
                $fields[0]['table']     = 'css';                // table: general_settings, settings, history, css
                $fields[0]['name']      = 'animation_effect';   // field name
                $fields[0]['type']      = 'VARCHAR(128)';       // field type
                $fields[0]['default']   = 'none';               // default value
                $fields[0]['collate']   = 'utf8_unicode_ci';    // collation
                $fields[0]['null']      = 'NOT NULL';           // NOT NULL or NULL

                // Animation Duration Field
                $fields[1]['table']     = 'css';                // table: general_settings, settings, history, css
                $fields[1]['name']      = 'animation_duration'; // field name
                $fields[1]['type']      = 'VARCHAR(12)';        // field type
                $fields[1]['default']   = '1s';                 // default value
                $fields[1]['collate']   = 'utf8_unicode_ci';    // collation
                $fields[1]['null']      = 'NOT NULL';           // NOT NULL or NULL

                // Animation Delay Field
                $fields[2]['table']     = 'css';                // table: general_settings, settings, history, css
                $fields[2]['name']      = 'animation_delay';    // field name
                $fields[2]['type']      = 'VARCHAR(12)';        // field type
                $fields[2]['default']   = '0s';                 // default value
                $fields[2]['collate']   = 'utf8_unicode_ci';    // collation
                $fields[2]['null']      = 'NOT NULL';           // NOT NULL or NULL

                // Animation period Field
                $fields[3]['table']     = 'css';                // table: general_settings, settings, history, css
                $fields[3]['name']      = 'animation_period';   // field name
                $fields[3]['type']      = 'VARCHAR(12)';        // field type
                $fields[3]['default']   = '1';                  // default value
                $fields[3]['collate']   = 'utf8_unicode_ci';    // collation
                $fields[3]['null']      = 'NOT NULL';           // NOT NULL or NULL

                // Add fields
                $wdhSVWE_extend->addFields($fields);
            }

            function addCSS(){
                global $wdhSVWE_extend;

                if ($wdhSVWE_extend->userIsAdmin()){// admin.  
                    // Register Styles.
                    wp_register_style('SVWE_Animate_CSS', plugins_url('css/animate.min.css', __FILE__));

                    // Register Styles.
                    wp_register_style('SVWE_Animator_CSS', plugins_url('css/wdh.svwe.animator.css', __FILE__));

                    // Enqueue Styles.
                    wp_enqueue_style('SVWE_Animate_CSS');
                    wp_enqueue_style('SVWE_Animator_CSS');
                }
            }

            function getPeriod(){
                $period = '';

                for ($i = 1; $i<= 50; $i++){
                    $period .= $i.'|';
                }

                $period .= 'infinite';

                return $period;
            }
        }
    } else {
        function wdhsvwe_required_12() {
            $class = "update-nag";
            $message = "To use the <b>Synoptic Visual Animator: create amazing animations</b> you must have installed <a href='https://wordpress.org/plugins/synoptic-web-designer-best-design-tool/' target='_blank'>Synoptic Web Designer: best WordPress design tool</a> 1.1 or higher.Please <strong>Synoptic Web Designer: best WordPress design tool</strong> update your plugin.";
            echo"<div class=\"$class\"> <p>$message</p></div>"; 
        }
        add_action( 'admin_notices', 'wdhsvwe_required_12' ); 
    }
}

if (class_exists("wdhSVWE_Animator") && class_exists("wdhSVWE") && $wdhSVWE["animator_ENABLED"] == true){
    $wdhSVWE_Animator = new wdhSVWE_Animator();
} else {
    
    if(!class_exists("wdhSVWE")) {
        
        function wdhsvwe_required() {
            $class = "update-nag";
            $message = "To use the <b>Synoptic Visual Animator: create amazing animations</b> you must to install first the <a href='https://wordpress.org/plugins/synoptic-web-designer-best-design-tool/' target='_blank'>Synoptic Web Designer: best WordPress design tool</a>.";
            echo"<div class=\"$class\"> <p>$message</p></div>"; 
        }
        add_action( 'admin_notices', 'wdhsvwe_required' ); 
    }
}

// Uninstall
function wdhSVWE_AnimatorUninstall() {
    // Delete Option
    delete_option('SVWE_extend_Animator_version');
}
    
// Uninstall Hook
register_uninstall_hook(__FILE__, 'wdhSVWE_AnimatorUninstall');