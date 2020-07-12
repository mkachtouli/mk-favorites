jQuery(document).ready(function($){
    $('.mk-button').on('click', function(e){
        e.preventDefault();
        var user_id = $(this).attr('user_id');
        var post_id = $(this).attr('post_id');
        var method = $(this).attr('method');
        if (method == "Like") {
          $(this).attr('method', 'Unlike')
          $('#' + post_id).replaceWith('<img class="favicon" id="' + post_id + '" src="wp-content/plugins/mk-favorites/includes/favon.jpg">')
        } else {
         $(this).attr('method', 'Like')
         $('#' + post_id).replaceWith('<img class="favicon" id="' + post_id + '" src="wp-content/plugins/mk-favorites/includes/favoff.png">')
        }
        $.ajax({
            url: '/wp-content/plugins/mk-favorites/includes/add_remove_mk_favorites.php',
            type: 'GET',
            data: {user_id: user_id, post_id: post_id, method: method},
            cache: false,
            success: function(data){
                console.log(data);
            }
        });
    });
});
