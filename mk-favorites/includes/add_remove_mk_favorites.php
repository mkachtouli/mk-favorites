<?php
    $method = $_GET['method'];
    $user_id = $_GET['user_id'];
    $post_id = $_GET['post_id'];
    require_once ('../../../../wp-config.php');
    global $wpdb;
   
    $table_name = $wpdb->prefix . 'mk_favorites';

    if ($method == "Like") {
        $wpdb->insert( $table_name, array( 'user__id' => $user_id, 'post__id' => $post_id ), array( '%d', '%d') );
    }
    else {
        $wpdb->delete( $table_name, array( 'user__id' => $user_id, 'post__id' => $post_id ), array( '%d', '%d' ) );
    }

    

