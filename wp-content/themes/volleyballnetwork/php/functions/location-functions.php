<?php
/* Get user IP */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; // Get first IP in the list
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
/* Get user postal aprox code */
function get_user_postal_code() {
    $ip = getUserIP();
    $api_url = "http://ip-api.com/json/{$ip}";

    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    return $data['zip'] ?? 'UNK-NOW';
}
/* Get venue by postal code for admin lists */
function get_venue_id_by_postal_code( $postal_code ) {

    // Sanitize the postal code input (important for security)
    $postal_code = sanitize_text_field( $postal_code );

    // Query arguments
    $args = array(
        'post_type'      => 'venue', // Your custom post type
        'meta_query'     => array(
            array(
                'key'       => 'venue_postal_code', // Your meta key
                'value'     => $postal_code,
                'compare'   => '=', // Or 'LIKE' for partial matches, etc.  See below for more on 'compare'
            ),
        ),
        'posts_per_page' => 1, // We only need one ID
        'fields'         => 'ids', // Return only the IDs
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        return $query->posts[0]; // Return the first ID found
    } else {
        return false; // Return false if no venue is found
    }
}

// Hook to get the postal code location
function get_lat_lng_from_postal($postal_code) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($postal_code);
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response));

    if (!empty($data[0])) {
        return [
            'lat' => $data[0]->lat,
            'lng' => $data[0]->lon
        ];
    }

    return false;
}
/* Calculate distance between two points */
function haversine_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Earth's radius in KM

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $earth_radius * $c;
}

function search_programs_by_postal() {
    if (!isset($_GET['postal_code'])) {
        return;
    }

    $user_postal = sanitize_text_field($_GET['postal_code']);
    $user_location = get_lat_lng_from_postal($user_postal);

    if (!$user_location) {
        echo "<p>Invalid postal code. Try again.</p>";
        return;
    }

    $args = [
        'post_type' => 'program',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key'     => 'postal-code',
                'compare' => 'EXISTS'
            ],
        ]
    ];

    $query = new WP_Query($args);
    $programs = [];

    while ($query->have_posts()) {
        $query->the_post();
        $program_postal = get_post_meta(get_the_ID(), 'postal-code', true);
        $program_location = get_lat_lng_from_postal($program_postal);

        if ($program_location) {
            $distance = haversine_distance(
                $user_location['lat'], $user_location['lng'],
                $program_location['lat'], $program_location['lng']
            );

            $programs[] = [
                'title' => get_the_title(),
                'distance' => $distance,
                'link' => get_permalink()
            ];
        }
    }

    wp_reset_postdata();

    usort($programs, function ($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });

    echo "<h2>Programs Near You</h2>";
    echo "<ul>";
    foreach ($programs as $program) {
        echo "<li><a href='{$program['link']}'>{$program['title']}</a> - {$program['distance']} km away</li>";
    }
    echo "</ul>";
}
// Add the search results to a page or template
// add_action('wp', 'search_programs_by_postal');

?>