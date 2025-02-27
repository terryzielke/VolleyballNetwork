<?php
    function register_city_contact_info_shortcode() {
        // Query to get all city posts
        $city_args = array(
            'post_type' => 'city',
            'posts_per_page' => -1,
        );
        $cities = get_posts($city_args);

        // Initialize output
        $output = '<div class="city-contact-info-list cell-row row">';

        // Loop through each city and get the contact info meta data
        $city_args = array(
            'post_type' => 'city',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($city_args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $city_address  			= get_post_meta( get_the_ID(), 'city_address', true );
                $city_address_line_2  	= get_post_meta( get_the_ID(), 'city_address_line_2', true );
                $city_phone				= get_post_meta( get_the_ID(), 'city_phone', true );
                $city_email				= get_post_meta( get_the_ID(), 'city_email', true );
                $city_office_hours      = get_post_meta( get_the_ID(), 'city_office_hours', true );

                // Add the city name to the output
                $output .= '<div class="city-contact-info col col-12 col-md-6 col-lg-4">
                                <div class="cell">
                                    <h3>' . get_the_title() . '</h3>
                                    <div class="contact-info">
                                        <ul>'.
                                            ($city_address ? '<li><strong>Address:</strong> ' . $city_address . '</li>' : '') .
                                            ($city_address_line_2 ? '<li style="padding-left:4.8em;">' . $city_address_line_2 . '</li>' : '') .
                                            ($city_phone ? '<li><strong>Phone:</strong> ' . $city_phone . '</li>' : '') .
                                            ($city_email ? '<li><strong>Email:</strong> ' . $city_email . '</li>' : '') .
                                            ($city_office_hours ? '<li><strong>Office Hours:</strong> ' . $city_office_hours . '</li>' : '') .
                                        '</ul>
                                    </div>
                                </div>';

                // Close the city div
                $output .= '</div>';
            }
        }

        // Close the container div
        $output .= '</div>';

        // Return the output
        return $output;
    }
    add_shortcode('city_contact_info', 'register_city_contact_info_shortcode');
?>