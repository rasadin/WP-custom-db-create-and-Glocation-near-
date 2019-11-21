//Create new DB table for location information
function eb_location_info() {
    global $wpdb;
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "location_information";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        `lat` text NOT NULL,
        `lng` text NOT NULL,
        `post_id` mediumint(255) NOT NULL,
    UNIQUE (`post_id`)
    
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    // starts output buffering
    ob_start();
    // does the inserting, in case the form is filled and submitted
        $args = array(
            'post_type'         => 'eventposts',
        );

        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post(); 
            $table = $wpdb->prefix."location_information";
            $wpdb->replace(
            $table,
            array(
                'lat'        => get_post_meta(get_the_ID(), '_event_booking_venue_latitude',true),
                'lng'        => get_post_meta(get_the_ID(), '_event_booking_venue_longitude',true ),
                'post_id'    => get_the_ID()
            )
        );
        ?>
        </div>
       <?php endwhile; wp_reset_postdata();
    
    }    
// adds a shortcode you can use: [eb-location-info]
add_shortcode('eb-location-info', 'eb_location_info');




//Create new DB table for location information
function select_close_location() {
        global $wpdb;
        $latitude  = -0;
        $longitude = 0;

        $table = $wpdb->prefix."location_information";
        $result = $wpdb->get_results (
            "
            SELECT *,(acos(sin(lat * 0.0175) * sin($latitude * 0.0175) + cos(lat * 0.0175) * cos($latitude * 0.0175) * cos(($longitude * 0.0175) - (lng * 0.0175))) * 3959)  distance FROM $table 
            WHERE (acos(sin(lat * 0.0175) * sin($latitude * 0.0175) + cos(lat * 0.0175) * cos($latitude * 0.0175) * cos(($longitude * 0.0175) - (lng * 0.0175))) * 3959 <= 100 ) order by distance LIMIT 8
            "
            );


?>
