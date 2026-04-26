jQuery(document).ready(function($){
    // console.log("ABC");

    $('.like-btn, .dislike-btn').on('click', function(e){
        e.preventDefault();

        
        var $html = $(this);
        var $container = $html.closest('.ajax-shortcode');

        // console.log("Container: " , typeof($container[0]) )

        // console.log("Button: " , $button);


        // alert($container);
        
        // Determine the action (like or dislike) based on the class
        var actionType = $container.find('.like-btn') ? 'like' : 'Dislike';


        // console.log("Child Element: " , childElement);

        // Gather data from the parent container
        var data = {
            action: 'process_voting', // This must match your wp_ajax_ PHP hook
            post_id: $container.data('post'),
            user_id: $container.data('user'),
            vote_type: actionType
        };


        // // console.log("Data: " , data )

        // // alert(data.user_id);

        if(!data.user_id){
            alert('You must login to move');
        }else{  
            // alert('success');
            $.ajax({
                url : custom_ajax.ajax_url,
                type : 'POST',
                data : {
                    action : 'custom_post_voting_callback',
                    pid : data.post_id,
                    uid : data.user_id,
                    vote_type  : data.vote_type
                },
                success : function(response) {
                    if (!response.success && response.data.user_exist) {
                        alert(response.data.message); // "You have already voted..."
                    } else if (response.success) {
                        alert(response.data.message); // "Your vote has been recorded!"
                    }
                }
            })
        }
    })
});