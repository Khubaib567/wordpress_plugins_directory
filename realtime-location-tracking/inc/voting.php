<?php

function custom_post_voting_callback() {
    global $wpdb;

    $post_id = intval($_POST['pid']);
    $user_id = intval($_POST['uid']);
    // sanitize_text_field is safer for strings like 'like'/'dislike'
    $vote_type = sanitize_text_field($_POST['vote_type']); 
    $table_votes = $wpdb->prefix . 'votes';

    if (!empty($post_id) && !empty($user_id)) {
        
        // 1. Check if the user has already voted for this post
        $user_voted = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_votes WHERE post_id = %d AND user_id = %d",
            $post_id,
            $user_id,
        ));

        if ($user_voted > 0) {
            // 2. Return a flag indicating the user already exists/voted
            wp_send_json_error([
                'user_exist' => true,
                'message'    => 'You have already voted for this post!'
            ]);
        }

        // 3. If they haven't voted, proceed with insertion
        $query = $wpdb->insert(
            $table_votes,
            array(
                'post_id'   => $post_id,
                'user_id'   => $user_id,
                'vote_type' => $vote_type
            ),
            array('%d', '%d', '%s')
        );

        if ($query) {
            wp_send_json_success([
                'user_exist' => false,
                'message'    => 'Your vote has been recorded!'
            ]);
        } else {
            wp_send_json_error([
                'message'    => 'Database Error! ' . $wpdb->last_error,
                'last_query' => $wpdb->last_query,
            ]);
        }
    }
    
    wp_die(); // Required for all WordPress AJAX handlers
}

// Ensure the action name matches your jQuery 'action' key exactly
add_action('wp_ajax_custom_post_voting_callback', 'custom_post_voting_callback');
