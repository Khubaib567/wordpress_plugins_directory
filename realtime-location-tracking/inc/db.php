<?php

function plugin_reaction_table() {
    global $wpdb;
    $db_version = MSR_PLUGIN_DB_VERSION;
    $table_reactions = $wpdb->prefix . 'reactions';
    $table_votes = $wpdb->prefix . 'votes';

    $charset_collate = $wpdb->get_charset_collate();

    // Note: Two spaces after PRIMARY KEY and KEY is a dbDelta requirement.
    $sql_reactions = "CREATE TABLE $table_reactions (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        username varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        password_hash char(255) NOT NULL,
        is_active tinyint(1) DEFAULT 1,
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY idx_unique_email (email),
        KEY idx_username (username)
    ) $charset_collate;";

    
    // Note: Two spaces after PRIMARY KEY and KEY is a dbDelta requirement.
    $sql_votes = "CREATE TABLE IF NOT EXISTS $table_votes (
    -- Use UNSIGNED BIGINT for IDs to support up to 18 quintillion rows
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    post_id bigint(20) UNSIGNED NOT NULL,
    user_id bigint(20) UNSIGNED NOT NULL,
    
        -- TINYINT or SMALLINT is much faster than VARCHAR for fixed categories
        -- Use 1 for upvote, -1 for downvote, or an ENUM
        vote_type tinyint(1) NOT NULL DEFAULT 1, 
        
        voted_at timestamp DEFAULT CURRENT_TIMESTAMP,
        
        PRIMARY KEY (id),
        -- Combined index: Optimizes queries like 'Find all votes for post X'
        KEY idx_post_voted (post_id, voted_at),
        -- Unique constraint: Prevents a user from voting on the same post twice
        UNIQUE KEY uk_user_post (user_id, post_id),
        -- Index for time-based reports
        KEY idx_voted_at (voted_at)
    ) $charset_collate ENGINE=InnoDB;";



    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql_reactions );
    dbDelta( $sql_votes );

    // update_option ensures the version is refreshed if it changes later
    update_option( 'msr_db_version', $db_version );
}



// function msr_db_upgrade(){
//     global $wpdb;
//     $installed_ver = get_option( 'msr_db_version' );
//     $current_version = MSR_PLUGIN_DB_VERSION;

//     if( $installed_ver != $current_version) {
        
//     }
// }

