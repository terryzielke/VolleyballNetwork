jQuery(document).ready(function($) {
    $('input[name^="tax_input\\[city\\]"]').on('change', function() {
        if ($(this).is(':checked')) {
            var termId = $(this).val();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_city_meta',
                    term_id: termId
                },
                success: function(response) {
                    if (response.success) {
                        var countryId = response.data.country_id;
                        var stateId = response.data.state_id;

                        // Select the country term
                        if (countryId) {
                            $('input[name="tax_input[country][]"][value="' + countryId + '"]').prop('checked', true);
                        } else {
                            $('input[name="tax_input[country][]"]').prop('checked', false); //uncheck all if none
                        }

                        // Select the state term
                        if (stateId) {
                            $('input[name="tax_input[state][]"][value="' + stateId + '"]').prop('checked', true);
                        } else {
                            $('input[name="tax_input[state][]"]').prop('checked', false); //uncheck all if none
                        }

                        // Trigger change events (optional, if you have other listeners)
                        $('input[name="tax_input[country][]"][value="' + countryId + '"]').trigger('change');
                        $('input[name="tax_input[state][]"][value="' + stateId + '"]').trigger('change');
                    } else {
                        console.error('Error:', response.data);
                    }
                },
                error: function() {
                    console.error('An error occurred during the AJAX request.');
                }
            });
        }
    });
});