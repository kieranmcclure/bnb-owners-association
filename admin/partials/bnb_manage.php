<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://splash.ie
 * @since      1.0.0
 *
 * @package    Splash_Theme
 * @subpackage Splash_Theme/admin/partials
 */
?>

<h1>B&B Owners Association</h1>
<p>Splash Internal Usage only. Please do not edit anything on this page unless expressly directed to.<br>If you have any questions or queries please contact Splash directly at 064 6676100 or email support@splash.ie</p>

<?php

	/**
	 * The form to be loaded on the plugin's admin page
	 */
	if( current_user_can( 'edit_users' ) ) {		

            //Retrieve our options if already set
            $this->options = get_option('options');
           // Build the Form
        ?>				
                    
            <div class="colorForm_form">
            <form action="options.php" method="post" >	
                <?php 
                    settings_fields('bnb_manage_options_group');
                    do_settings_sections('bnb-owners-manage');
                    submit_button();
                ?>		
                
            </form>
            <br/><br/>
            <div id="nds_form_feedback"></div>
            <br/><br/>			
            <?php echo do_shortcode('[display_rooms]'); ?>
            </div>
        <?php    
    }else {  
    ?>
        <p> <?php __("You are not authorized to perform this operation.", $this->plugin_name) ?> </p>
    <?php   
    }
