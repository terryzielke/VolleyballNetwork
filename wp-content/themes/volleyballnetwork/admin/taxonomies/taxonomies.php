<?php
function register_volleyball_taxonomies() {
    // Country Taxonomy
    register_taxonomy('country', array('venue', 'local-league', 'city'), array(
        'label' => __('Country'),
        'rewrite' => array('slug' => 'country'),
        'hierarchical' => true, // Set to true for parent-child relationships
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rest_base' => 'country',
    ));

    // State Taxonomy
    register_taxonomy('state', array('venue', 'local-league', 'city'), array(
        'label' => __('State'),
        'rewrite' => array('slug' => 'state'),
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rest_base' => 'state',
    ));

    // City Taxonomy
    register_taxonomy('city', array('venue', 'local-league', 'city'), array(
        'label' => __('City'),
        'rewrite' => array('slug' => 'city'),
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rest_base' => 'city',
    ));
    
    // Ages Taxonomy
    register_taxonomy('age', 'program', array(
        'label' => __('Age'),
        'rewrite' => array('slug' => 'age'),
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
		'show_in_rest' => true,
        'rest_base' => 'age',
    ));

    // Gender Taxonomy
    register_taxonomy('gender', 'program', array(
        'label' => __('Gender'),
        'rewrite' => array('slug' => 'gender'),
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
		'show_in_rest' => true,
        'rest_base' => 'gender',
    ));
}
add_action('init', 'register_volleyball_taxonomies');


/**
 * Add country field to state taxonomy
 */
function add_state_country_field() {
    $countries = get_terms(array(
        'taxonomy' => 'country',
        'hide_empty' => false
    ));
    ?>
    <div class="form-field">
        <label for="state_country"><?php _e('Select Country'); ?></label>
        <select name="state_country" id="state_country">
            <option value=""><?php _e('None'); ?></option>
            <?php foreach ($countries as $country) : ?>
                <option value="<?php echo esc_attr($country->term_id); ?>"><?php echo esc_html($country->name); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php _e('Assign this state to a country.'); ?></p>
    </div>
    <?php
}
add_action('state_add_form_fields', 'add_state_country_field', 10, 2);
// Edit country field for state taxonomy
function edit_state_country_field($term) {
    $selected_country = get_term_meta($term->term_id, 'state_country', true);
    $countries = get_terms(array(
        'taxonomy' => 'country',
        'hide_empty' => false
    ));
    ?>
    <tr class="form-field">
        <th scope="row"><label for="state_country"><?php _e('Select Country'); ?></label></th>
        <td>
            <select name="state_country" id="state_country">
                <option value=""><?php _e('None'); ?></option>
                <?php foreach ($countries as $country) : ?>
                    <option value="<?php echo esc_attr($country->term_id); ?>" <?php selected($selected_country, $country->term_id); ?>>
                        <?php echo esc_html($country->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Assign this state to a country.'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('state_edit_form_fields', 'edit_state_country_field', 10, 2);
// Save country field for state taxonomy
function save_state_country_meta($term_id) {
    if (isset($_POST['state_country']) && $_POST['state_country'] !== '') {
        update_term_meta($term_id, 'state_country', sanitize_text_field($_POST['state_country']));
    } else {
        delete_term_meta($term_id, 'state_country');
    }
}
add_action('created_state', 'save_state_country_meta', 10, 2);
add_action('edited_state', 'save_state_country_meta', 10, 2);
// Add country column to state taxonomy
function add_state_country_column($columns) {
    $columns['state_country'] = __('Country');
    return $columns;
}
add_filter('manage_edit-state_columns', 'add_state_country_column');

function display_state_country_column($content, $column_name, $term_id) {
    if ($column_name == 'state_country') {
        $country_id = get_term_meta($term_id, 'state_country', true);
        if ($country_id) {
            $country = get_term($country_id, 'country');
            $content = $country ? esc_html($country->name) : __('None');
        } else {
            $content = __('None');
        }
    }
    return $content;
}
add_filter('manage_state_custom_column', 'display_state_country_column', 10, 3);


/**
 * Add country and state fields to city taxonomy
 */
function add_city_country_state_fields() {
    $countries = get_terms(array(
        'taxonomy' => 'country',
        'hide_empty' => false,
    ));
    $states = get_terms(array(
        'taxonomy' => 'state',
        'hide_empty' => false,
    ));
    ?>
    <div class="form-field">
        <label for="city_country"><?php _e('Select Country'); ?></label>
        <select name="city_country" id="city_country">
            <option value=""><?php _e('None'); ?></option>
            <?php foreach ($countries as $country) : ?>
                <option value="<?php echo esc_attr($country->term_id); ?>"><?php echo esc_html($country->name); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php _e('Assign this city to a country.'); ?></p>
    </div>

    <div class="form-field">
        <label for="city_state"><?php _e('Select State'); ?></label>
        <select name="city_state" id="city_state">
            <option value=""><?php _e('None'); ?></option>
            <?php foreach ($states as $state) : ?>
                <option value="<?php echo esc_attr($state->term_id); ?>"><?php echo esc_html($state->name); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php _e('Assign this city to a state.'); ?></p>
    </div>
    <?php
}
add_action('city_add_form_fields', 'add_city_country_state_fields', 10, 2);

// Edit fields for city taxonomy
function edit_city_country_state_fields($term) {
    $selected_country = get_term_meta($term->term_id, 'city_country', true);
    $selected_state = get_term_meta($term->term_id, 'city_state', true);

    $countries = get_terms(array(
        'taxonomy' => 'country',
        'hide_empty' => false,
    ));
    $states = get_terms(array(
        'taxonomy' => 'state',
        'hide_empty' => false,
    ));
    ?>
    <tr class="form-field">
        <th scope="row"><label for="city_country"><?php _e('Select Country'); ?></label></th>
        <td>
            <select name="city_country" id="city_country">
                <option value=""><?php _e('None'); ?></option>
                <?php foreach ($countries as $country) : ?>
                    <option value="<?php echo esc_attr($country->term_id); ?>" <?php selected($selected_country, $country->term_id); ?>>
                        <?php echo esc_html($country->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Assign this city to a country.'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="city_state"><?php _e('Select State'); ?></label></th>
        <td>
            <select name="city_state" id="city_state">
                <option value=""><?php _e('None'); ?></option>
                <?php foreach ($states as $state) : ?>
                    <option value="<?php echo esc_attr($state->term_id); ?>" <?php selected($selected_state, $state->term_id); ?>>
                        <?php echo esc_html($state->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Assign this city to a state.'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('city_edit_form_fields', 'edit_city_country_state_fields', 10, 2);

// Save country and state meta for city taxonomy
function save_city_country_state_meta($term_id) {
    if (isset($_POST['city_country']) && $_POST['city_country'] !== '') {
        update_term_meta($term_id, 'city_country', sanitize_text_field($_POST['city_country']));
    } else {
        delete_term_meta($term_id, 'city_country');
    }

    if (isset($_POST['city_state']) && $_POST['city_state'] !== '') {
        update_term_meta($term_id, 'city_state', sanitize_text_field($_POST['city_state']));
    } else {
        delete_term_meta($term_id, 'city_state');
    }
}
add_action('created_city', 'save_city_country_state_meta', 10, 2);
add_action('edited_city', 'save_city_country_state_meta', 10, 2);

// Add country and state columns to city taxonomy
function add_city_country_state_columns($columns) {
    $columns['city_country'] = __('Country');
    $columns['city_state'] = __('State');
    return $columns;
}
add_filter('manage_edit-city_columns', 'add_city_country_state_columns');

function display_city_country_state_columns($content, $column_name, $term_id) {
    if ($column_name == 'city_country') {
        $country_id = get_term_meta($term_id, 'city_country', true);
        if ($country_id) {
            $country = get_term($country_id, 'country');
            $content = $country ? esc_html($country->name) : __('None');
        } else {
            $content = __('None');
        }
    } elseif ($column_name == 'city_state') {
        $state_id = get_term_meta($term_id, 'city_state', true);
        if ($state_id) {
            $state = get_term($state_id, 'state');
            $content = $state ? esc_html($state->name) : __('None');
        } else {
            $content = __('None');
        }
    }
    return $content;
}
add_filter('manage_city_custom_column', 'display_city_country_state_columns', 10, 3);


/**
 * Add country taxonomy to user profile
 */
function add_country_taxonomy_to_user_profile( $user ) {
    $user_country = get_user_meta( $user->ID, 'user_country', true );
    if ( ! is_array( $user_country ) ) {
        $user_country = array();
    }
    ?>
    <div id="user-country-taxonomy-wrapper">
        <h3>Country Taxonomy Assignment</h3>
        <table class="form-table">
            <tr>
                <th><label for="user_country">Countries</label></th>
                <td>
                    <?php
                    $terms = get_terms( array(
                        'taxonomy' => 'country',
                        'hide_empty' => false,
                    ) );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $checked = in_array( $term->term_id, $user_country ) ? 'checked' : '';
                            echo '<label><input type="checkbox" name="user_country[]" value="' . esc_attr( $term->term_id ) . '" ' . $checked . '> ' . esc_html( $term->name ) . '</label><br>';
                        }
                    } else {
                        echo 'No countries found.';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
add_action( 'show_user_profile', 'add_country_taxonomy_to_user_profile' );
add_action( 'edit_user_profile', 'add_country_taxonomy_to_user_profile' );

function save_country_taxonomy_user_profile( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( isset( $_POST['user_country'] ) ) {
        update_user_meta( $user_id, 'user_country', array_map( 'intval', $_POST['user_country'] ) );
    } else {
        update_user_meta( $user_id, 'user_country', array() ); // Clear if nothing selected
    }
}
add_action( 'personal_options_update', 'save_country_taxonomy_user_profile' );
add_action( 'edit_user_profile_update', 'save_country_taxonomy_user_profile' );


/**
 * Add state taxonomy to user profile
 */
 function add_state_taxonomy_to_user_profile( $user ) {
    $user_state = get_user_meta( $user->ID, 'user_state', true );
    if ( ! is_array( $user_state ) ) {
        $user_state = array();
    }
    ?>
    <div id="user-state-taxonomy-wrapper">
        <h3>State Taxonomy Assignment</h3>
        <table class="form-table">
            <tr>
                <th><label for="user_state">States</label></th>
                <td>
                    <?php
                    $terms = get_terms( array(
                        'taxonomy' => 'state',
                        'hide_empty' => false,
                    ) );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $checked = in_array( $term->term_id, $user_state ) ? 'checked' : '';
                            echo '<label><input type="checkbox" name="user_state[]" value="' . esc_attr( $term->term_id ) . '" ' . $checked . '> ' . esc_html( $term->name ) . '</label><br>';
                        }
                    } else {
                        echo 'No states found.';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
add_action( 'show_user_profile', 'add_state_taxonomy_to_user_profile' );
add_action( 'edit_user_profile', 'add_state_taxonomy_to_user_profile' );

function save_state_taxonomy_user_profile( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( isset( $_POST['user_state'] ) ) {
        update_user_meta( $user_id, 'user_state', array_map( 'intval', $_POST['user_state'] ) );
    } else {
        update_user_meta( $user_id, 'user_state', array() ); // Clear if nothing selected
    }
}
add_action( 'personal_options_update', 'save_state_taxonomy_user_profile' );
add_action( 'edit_user_profile_update', 'save_state_taxonomy_user_profile' );


/**
 * Add city taxonomy to user profile
 */
    function add_city_taxonomy_to_user_profile( $user ) {
        $user_city = get_user_meta( $user->ID, 'user_city', true );
        if ( ! is_array( $user_city ) ) {
            $user_city = array();
        }
        ?>
        <div id="user-city-taxonomy-wrapper">
            <h3>City Taxonomy Assignment</h3>
            <table class="form-table">
                <tr>
                    <th><label for="user_city">Cities</label></th>
                    <td>
                        <?php
                        $terms = get_terms( array(
                            'taxonomy' => 'city',
                            'hide_empty' => false,
                        ) );
        
                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                            foreach ( $terms as $term ) {
                                $checked = in_array( $term->term_id, $user_city ) ? 'checked' : '';
                                echo '<label><input type="checkbox" name="user_city[]" value="' . esc_attr( $term->term_id ) . '" ' . $checked . '> ' . esc_html( $term->name ) . '</label><br>';
                            }
                        } else {
                            echo 'No cities found.';
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    add_action( 'show_user_profile', 'add_city_taxonomy_to_user_profile' );
    add_action( 'edit_user_profile', 'add_city_taxonomy_to_user_profile' );

    function save_city_taxonomy_user_profile( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
    
        if ( isset( $_POST['user_city'] ) ) {
            update_user_meta( $user_id, 'user_city', array_map( 'intval', $_POST['user_city'] ) );
        } else {
            update_user_meta( $user_id, 'user_city', array() ); // Clear if nothing selected
        }
    }
    add_action( 'personal_options_update', 'save_city_taxonomy_user_profile' );
    add_action( 'edit_user_profile_update', 'save_city_taxonomy_user_profile' );

?>