<?php
class Fusion_Tables_Search_Wiget extends WP_Widget {

    function __construct() {

        parent::__construct(

        // base ID of the widget
            'fusion_tables_search_widget',

            // name of the widget
            __('Fusion Tables Search Widget', 'ftsw' ),

            // widget options
            array (
                'description' => __( 'Search box for a Fusion Table', 'ftsw' )
            )

        );

    }

    function form( $instance ) {

        $title = $instance[ 'title' ];
        $fusion_table = $instance[ 'fusion_table' ];
        $search_column = $instance[ 'search_column' ];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'fusion_table' ); ?>">Fusion Table</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'fusion_table' ); ?>" name="<?php echo $this->get_field_name( 'fusion_table' ); ?>" value="<?php echo esc_attr( $fusion_table ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'search_column' ); ?>">Column to search</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'search_column' ); ?>" name="<?php echo $this->get_field_name( 'search_column' ); ?>" value="<?php echo esc_attr( $search_column ); ?>">
        </p>

        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'fusion_table' ] = strip_tags( $new_instance[ 'fusion_table' ] );
        $instance[ 'search_column' ] = strip_tags( $new_instance[ 'search_column' ] );
        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );
        echo $before_widget;
        echo $before_title . $instance['title'] . $after_title;

        $api_key = get_option( 'mt_google_api_key' );
        $table = $instance['fusion_table'];
        $column = $instance['search_column'];

        $search_string = "table=".$table."&column=".$column."&api_key=".$api_key;

        ?>
        <div style="text-align: center">
            <input type="text" placeholder="Start typing..." class="search" id="searchInput_<?php echo $instance['fusion_table'];?>" />

            <button style="margin:5px;" class="primary" id="searchSingle_<?php echo $instance['fusion_table'];?>">Submit</button>
        </div>

        <div id="result_<?php echo $instance['fusion_table'];?>">
        </div>

        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.autocomplete.css">
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.js"></script>
        <script type='text/javascript' src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.autocomplete.js"></script>

        <script type="text/javascript">
            $().ready(function() {
                $("#searchInput_<?php echo $instance['fusion_table'];?>").autocomplete("<?php echo plugin_dir_url( __FILE__ ); ?>get_rows.php?<?php echo $search_string;?>", {
                    width: 260,
                    matchContains: true,
                    //mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });
            });
        </script>
        <?php
    }

}
?>
<?php
/*******************************************************************************
register the widget.
 *******************************************************************************/
?>
<?php
function fusion_tables_search_widget() {

    register_widget( 'Fusion_Tables_Search_Wiget' );

}
add_action( 'widgets_init', 'fusion_tables_search_widget' );
?>