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
        $display_columns = $instance[ 'display_columns' ];

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

        <p>
            <label for="<?php echo $this->get_field_id( 'display_columns' ); ?>">Columns to display</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'display_columns' ); ?>" name="<?php echo $this->get_field_name( 'display_columns' ); ?>" value="<?php echo esc_attr( $display_columns ); ?>" placeholder="Comma separated e.g Name, Address, DateAdded">
        </p>

        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'fusion_table' ] = strip_tags( $new_instance[ 'fusion_table' ] );
        $instance[ 'search_column' ] = strip_tags( $new_instance[ 'search_column' ] );
        $instance[ 'display_columns' ] = strip_tags( $new_instance[ 'display_columns' ] );
        return $instance;
    }

    function widget( $args, $instance ) {
        extract( $args );
        echo $before_widget;
        echo $before_title . $instance['title'] . $after_title;

        $api_key = get_option( 'mt_google_api_key' );
        $table = $instance['fusion_table'];
        $column = $instance['search_column'];
        $display_columns = $instance['display_columns'];

        $search_string = "table=".$table."&column=".$column."&api_key=".$api_key."&display_columns=".$display_columns;

        ?>
        <div id="input-div">
            <input type="text" placeholder="Start typing..." class="search searchInput" id="searchInput_<?php echo $instance['fusion_table'];?>"/><img src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/search.png" alt="" id="searchButton_<?php echo $instance['fusion_table'];?>">
        </div>
        <h3 id="resultTitle_<?php echo $instance['fusion_table'];?>"></h3>
        <div id="loading_<?php echo $instance['fusion_table'];?>" style="text-align:center">
            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/indicator.gif">
        </div>
        <div id="result_<?php echo $instance['fusion_table'];?>">
        </div>

        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.autocomplete.css">
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.js"></script>
        <script type='text/javascript' src="<?php echo plugin_dir_url( __FILE__ ); ?>../assets/jquery.autocomplete.js"></script>

        <script type="text/javascript">
            $().ready(function() {
                $("#loading_<?php echo $instance['fusion_table'];?>").hide();
                $("#searchInput_<?php echo $instance['fusion_table'];?>").autocomplete("<?php echo plugin_dir_url( __FILE__ ); ?>get_rows.php?<?php echo $search_string;?>", {
                    matchContains: true,
                    //mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });

                $("#searchButton_<?php echo $instance['fusion_table'];?>").click(function(){
                    var name = $("#searchInput_<?php echo $instance['fusion_table'];?>").val();

                    $("#resultTitle_<?php echo $instance['fusion_table'];?>").html("Results");

                    $("#result_<?php echo $instance['fusion_table'];?>").html("");

                    $("#loading_<?php echo $instance['fusion_table'];?>").show();

                    $.ajax({url:"<?php echo plugin_dir_url( __FILE__ ); ?>get_rows.php?mode=single&q=" + name + "& <?php echo $search_string;?>",success:function(result){
                        $("#searchInput_<?php echo $instance['fusion_table'];?>").val("");

                        $("#result_<?php echo $instance['fusion_table'];?>").html(result);

                        $("#loading_<?php echo $instance['fusion_table'];?>").hide();
                    }});
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