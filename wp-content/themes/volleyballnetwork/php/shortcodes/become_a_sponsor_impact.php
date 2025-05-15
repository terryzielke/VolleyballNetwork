<?php
    function become_a_sponsor_impact_shortcode() {
        
        // set variables
        $athletes = 5000;
        $coaches = 80;
        $venues = 20;
        $social = '2.9K';
        // get count of all venue posts
        $venue_count = wp_count_posts('venue');
        $venue = $venue_count->publish;

        // Initialize output
        $output = '<div id="become_a_sponsor_impact" class="row">';

        $output .= '<div class="col col-12 col-md-3">
                        <div class="content">
                            <h2>'.$athletes.'+</h2>
                            <h3>Young Athletes Enrolled Annually</h3>
                        </div>
                        <div class="fill" value="90"></div>
                    </div>';

        $output .= '<div class="col col-12 col-md-3">
                        <div class="content">
                            <h2>'.$coaches.'+</h2>
                            <h3>Certified Coaches and Mentors</h3>
                        </div>
                        <div class="fill" value="65"></div>
                    </div>';

        $output .= '<div class="col col-12 col-md-3">
                        <div class="content">
                            <h2>'.$venues.'</h2>
                            <h3>Partnered Venues Across the Country</h3>
                        </div>
                        <div class="fill" value="50"></div>
                    </div>';

        $output .= '<div class="col col-12 col-md-3">
                        <div class="content">
                            <h2>'.$social.'</h2>
                            <h3>Monthly Social Impressions</h3>
                        </div>
                        <div class="fill" value="80"></div>
                    </div>';
        

        // Close the container div
        $output .= '</div>';

        // Return the output
        return $output;
    }
    add_shortcode('become_a_sponsor_impact', 'become_a_sponsor_impact_shortcode');
?>