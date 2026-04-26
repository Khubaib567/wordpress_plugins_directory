<?php

function print_shortcode_text() {
    $db_value = get_option('custom_shortcode_txt');

    if(!empty($db_value)){
        return $db_value;   
    }else{
        return 'This is Test Content';
    }
    
}

add_shortcode('TEST_SR', 'print_shortcode_text' );


function print_shortcode_text_enclosing($atts = array() , $content){
    $html = '<a href="http://wordpress-theme.local/hello-world/">' ;
    $html .= $content;
    $html .= '</a>';
    return $html;
}

add_shortcode('TEST_SR_ENCLOSING', 'print_shortcode_text_enclosing' );



function test_sr_shortcode($atts) {
    // Extract and merge attributes with defaults
    $atts = shortcode_atts(array(
        'title' => '"Wordpress',
		'url' => 'http://wordpress-theme.local/hello-world/'
    ), $atts, 'TEST-SR-PARAM');

    // Start output buffering
    ob_start();

    // Your shortcode logic here
    ?>
    <div class="test-sr-shortcode">
        <!-- Shortcode content here -->
         <h2><?php echo esc_html($atts['title']); ?></h2>
        <a href="<?php echo esc_url($atts['url']); ?>">Visit Link</a>
    </div>
    <?php

    // Return the buffered content
    return ob_get_clean();
}


add_shortcode('TEST-SR-PARAM', 'test_sr_shortcode');

// Usage example:
// [test-sr attr="value"]

function ajax_shortcode($atts) {
    $atts = shortcode_atts(array(
        'like' => 'Like',
        'dislike' => 'Dislike'
    ), $atts, 'ajax_mapping');

    // Get IDs
    $post_id = get_the_ID(); 
    $user_id = get_current_user_id();

    // 1. Encapsulate data on the parent div
    $output  = '<div class="ajax-shortcode" data-post="' . esc_attr($post_id) . '" data-user="' . esc_attr($user_id) . '">';
    
    // 2. Buttons are now clean of data attributes
    $output .= '<button class="like-btn">';
    $output .= esc_html($atts['like']);
    $output .= '</button>';

    $output .= ' <button class="dislike-btn">';
    $output .= esc_html($atts['dislike']);
    $output .= '</button>';
    
    $output .= '</div>';

    return $output;
}


add_shortcode('ajax_mapping', 'ajax_shortcode');


function my_ajax_shortcode() {
    ob_start();
    ?>
        <select id="dynamic-select">
            <option value="">Select Option</option>
            <option value="users">Users</option>
            <option value="products">Products</option>
            <option value="posts">Posts</option>
        </select>

        <div id="result"></div>
    <?php
    return ob_get_clean();
}

add_shortcode('my_ajax_ui', 'my_ajax_shortcode');


function airport_map_shortcode() {

    ob_start();
    ?>
        <div style="position:relative; max-width:300px;">
            <label for="pickup-location">Pickup Location</label>
            <input type="text" id="pickup-location" placeholder="Search airport..." autocomplete="off" />

            <ul id="pickup-suggestions" style="
                position:absolute;
                top:100%;
                left:0;
                right:0;
                background:#fff;
                border:1px solid #ccc;
                list-style:none;
                padding:0;
                margin:0;
                display:none;
                max-height:200px;
                overflow-y:auto;
                z-index:999;
            "></ul>

            <label for="dropoff-location">Dropoff Location</label>
            <input type="text" id="dropoff-location" placeholder="Search airport..." autocomplete="off" />

            <ul id="dropoff-suggestions" style="
                position:absolute;
                top:100%;
                left:0;
                right:0;
                background:#fff;
                border:1px solid #ccc;
                list-style:none;
                padding:0;
                margin:0;
                display:none;
                max-height:200px;
                overflow-y:auto;
                z-index:999;
            "></ul>
        </div>

        <div id="map" style="height:400px; margin-top:10px;"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('airport_map', 'airport_map_shortcode');


