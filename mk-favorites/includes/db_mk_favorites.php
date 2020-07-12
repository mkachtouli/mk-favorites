<?php

function create_mk_favorites_database_table()
{ 
    global $wpdb;
    $table_name = $wpdb->prefix . 'mk_favorites';
    $users = $wpdb->prefix . 'users';
    $posts = $wpdb->prefix . 'posts';

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        user__id BIGINT(20) UNSIGNED NOT NULL,
        post__id BIGINT(20) UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user__id) REFERENCES $users (id) ON DELETE CASCADE,
        FOREIGN KEY (post__id) REFERENCES $posts (id) ON DELETE CASCADE
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

}
