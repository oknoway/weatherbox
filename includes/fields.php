<?php
/**
 * Weatherbox Fields
 *
 * @package weatherbox
 */

 add_action( 'fm_post_weatherbox_project', function() {
   $fm = new Fieldmanager_Group( array(
     'name' => 'project_details',
     'children' => array(
       'url' => new Fieldmanager_Link( 'Project URL' ),
       'client' => new Fieldmanager_Textfield( 'Client' ),
       'client_url' => new Fieldmanager_Link( 'Client URL' ),
       'designer' => new Fieldmanager_Textfield( 'Designer' ),
       'designer_url' => new Fieldmanager_Link( 'Designer URL' ),
     ),
   ) );
   $fm->add_meta_box( 'Project Details', 'weatherbox_project' );
 } );
