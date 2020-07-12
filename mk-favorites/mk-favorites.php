<?php
/*
Plugin Name: MK Favorites
Plugin URI: https://github.com/mkachtouli/mk-favorites
Description: The plugin can provide a way to save favorites posts
Version: 1.0
Author: Mohamed EL KACHTOULI
Author URI: https://medkachtouli.netlify.com
*/

//Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

function callback_for_setting_up_scripts() {
    wp_enqueue_script("jquery");
    wp_enqueue_script( 'main', plugins_url( '/includes/main.js', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');


include_once('includes/db_mk_favorites.php');

//include_once(plugins_url( '/includes/add_remore_mk_favorites.php', __FILE__ ));
register_activation_hook( __FILE__, 'create_mk_favorites_database_table' );

add_shortcode( 'mk_favorites_list', 'get_favorites_list' );
add_shortcode( 'mk_like_btn', 'set_btn_like' );




function set_btn_like(){ 
    $user_id = get_current_user_id();
    $post_id = get_the_ID();
    global $wpdb;
    $table_name = $wpdb->prefix . 'mk_favorites';

    $sql = "SELECT * FROM $table_name
    WHERE user__id = $user_id AND post__id = $post_id";
    $results = $wpdb->get_results( $sql );

    if (empty($results)) {
        echo "<div class = 'mk-button' method = 'Like'  user_id = ".$user_id." post_id = ".$post_id."><img id=".$post_id." src='/wp-content/plugins/mk-favorites/includes/favoff.png'> </div>";
    }
    else {
        echo  "<div class = 'mk-button' method = 'Unlike'  user_id = ".$user_id." post_id = ".$post_id."><img id=".$post_id." src='/wp-content/plugins/mk-favorites/includes/favon.jpg'> </div>";
    }
}

function get_favorites_list() {
    if( !is_user_logged_in() ) :
        echo '<p><b>Login first to see your favorite posts</b></p>';
        wp_login_form();
    else:
        $user_id = get_current_user_id();
        global $wpdb;
        $table_name = $wpdb->prefix . 'mk_favorites';

        $sql = "SELECT post__id FROM $table_name
        WHERE user__id = $user_id";
        $results = $wpdb->get_results( $sql );
        $posts_liked = array();
        foreach ($results as $key => $value) {
            $posts_liked[] = get_object_vars($value)["post__id"];
        }

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array(
            'posts_per_page' => 6,
            'paged' => $paged,
            'post_type' => 'post',
            'post__in' => $posts_liked,
			'post_status' => 'publish',
			'order' => 'DESC',
		);
		$query = new WP_Query( $args );
        ?>
        <?php echo '<div><h2>My favorite posts</h2><a href="' . wp_logout_url(get_permalink()) . '" title="Logout">Logout</a></div>'; ?>
        <?php if ( $query->have_posts() and !empty($posts_liked) ) : ?>
            
            <?php while( $query->have_posts() ) :
                $query->the_post(); ?>
                <div>
                <a href="<?php the_permalink(); ?>">
                    <h2><?php the_title(); ?></h2>
                    <p><?php the_excerpt(); ?></p>
                </a></div>
            <?php endwhile; ?>

            <?php // Pagination
                $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
                the_posts_pagination( array(
                   'mid_size' => 1,
                   'prev_text' => __( '< Back' ),
                   'next_text' => __( 'Next >' ),
                   'screen_reader_text' => __( 'Posts navigation' )
                ) ); 
            ?>
           
        <?php else : ?>
            <p><?php echo '<p>No favorites added yet!</p>'; ?></p>
        <?php endif; ?>
    
    <?php
		wp_reset_postdata();

    endif;
} 

function mk_like_btn_shortcode_to_a_post( $content ) {
    global $post;
    if( ! $post instanceof WP_Post ) return $content;
  
    
    switch( $post->post_type ) {
      case 'post':
        return $content . '[mk_like_btn]';
  
      default:
        return $content;
    }
  }
  
  add_filter( 'the_content', 'mk_like_btn_shortcode_to_a_post' );

?>
