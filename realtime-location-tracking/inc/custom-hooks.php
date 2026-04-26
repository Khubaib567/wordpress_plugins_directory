<?php

function my_footer_text() {
    echo 'Cophyrights &copy; 2025 , Custom Hook';
}

function my_post_title($title) {
    $emoji = "📄";
    if(is_singular( 'post' )){
        return $emoji . $title;
    }

    return $title;
}

function my_excerpt_length($excerpt) {
    return 10;
}

function my_post_content($title){
    $text = '<h1>Overview</h1>';
    if(is_singular('post')){
        return $text . $title;
    }

    return $title;
}


add_action( 'admin_footer' , 'my_footer_text' , 20 );

add_filter('the_title' , 'my_post_title');
add_filter('excerpt_length' , 'my_excerpt_length' , 999);
add_filter('the_content' , 'my_post_content');


