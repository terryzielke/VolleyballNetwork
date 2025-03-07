<?php
/**
 * AJAX handler for setting country and state terms on city select.
 */
function get_city_meta_callback() {
    $term_id = intval($_POST['term_id']);

    $country_id = get_term_meta($term_id, 'city_country', true);
    $state_id = get_term_meta($term_id, 'city_state', true);

    wp_send_json_success(array(
        'country_id' => $country_id,
        'state_id' => $state_id
    ));
}

add_action('wp_ajax_get_city_meta', 'get_city_meta_callback');
add_action('wp_ajax_nopriv_get_city_meta', 'get_city_meta_callback');
/** */
?>