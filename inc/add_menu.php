<?php
add_action( 'admin_menu', 'fusion_tables_search_widget_menu' );

function fusion_tables_search_widget_menu() {
add_options_page( 'Fusion Table Search Options', 'Fusion Table Search', 'manage_options', 'ftsw-identifier', 'fusion_tables_search_widget_options' );
}

/** Step 3. */
function fusion_tables_search_widget_options() {
if ( !current_user_can( 'manage_options' ) )  {
wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
echo '<div class="wrap">';

    require_once("config_options.php");

    echo '</div>';
}