<?php
/**
 * Add division select field to user profiles.
 */

function add_user_division_field( $user ) {
    $divisions = get_posts( array(
        'post_type'      => 'division',
        'posts_per_page' => -1, // Get all posts
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );

    $selected_division = get_user_meta( $user->ID, 'user_division', true );
    ?>
    <h3>Division Assignment</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_division">Assigned Division</label></th>
            <td>
                <select name="user_division" id="user_division">
                    <option value="">Select Division</option>
                    <?php
                    if ( $divisions ) {
                        foreach ( $divisions as $division ) {
                            $selected = selected( $selected_division, $division->ID, false );
                            echo '<option value="' . esc_attr( $division->ID ) . '" ' . $selected . '>' . esc_html( $division->post_title ) . '</option>';
                        }
                    }
                    ?>
                </select><br />
                <span class="description">Select the division assigned to this user.</span>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'add_user_division_field' );
add_action( 'edit_user_profile', 'add_user_division_field' );

/**
 * Save division select field value.
 */
function save_user_division_field( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( isset( $_POST['user_division'] ) ) {
        update_user_meta( $user_id, 'user_division', sanitize_text_field( $_POST['user_division'] ) );
    }
}
add_action( 'personal_options_update', 'save_user_division_field' );
add_action( 'edit_user_profile_update', 'save_user_division_field' );

/**
 * Example of how to retrieve and display the division.
 */
function display_user_division( $user_id ) {
    $division_id = get_user_meta( $user_id, 'user_division', true );
    if ( $division_id ) {
        $division = get_post( $division_id );
        if ( $division ) {
            echo '<p>Assigned Division: ' . esc_html( $division->post_title ) . '</p>';
        } else {
            echo "<p>Assigned Division: Division post not found</p>";
        }

    } else {
        echo "<p>Assigned Division: None</p>";
    }
}

// Example usage:
// display_user_division( get_current_user_id() );
?>