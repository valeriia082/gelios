jQuery(document).ready(function($) {   var selectedValues = [];

    // Arrays to store selected values
    var selectedTags = [];
    var selectedCategories = [];

    // Event handler for checkbox change
    $('input[name="tags"], input[name="categories"]').change(function() {
        // Clear previous selected values
        selectedTags = [];
        selectedCategories = [];

        // Iterate over all selected tags checkboxes
        $('input[name="tags"]:checked').each(function() {
            selectedTags.push($(this).val());
        });

        // Iterate over all selected categories checkboxes
        $('input[name="categories"]:checked').each(function() {
            selectedCategories.push($(this).val());
        });
        console.log(ajax_filter_params.ajax_url);

        console.log('Selected tags:', selectedTags);
        console.log('Selected categories:', selectedCategories);

        $.ajax({
            type: 'POST',
            dataType : "json",
            url : ajax_filter_params.ajax_url,
            data : {action: "ajax_filter_posts", selected_tags : selectedTags, selected_categories: selectedCategories},
            beforeSend: function() {
                // Display a loading indicator or any pre-filtering UI changes
            },
            success: function(response) {
                console.log("success : " + response.data);
                $('#ajax-filter-results').html(response.data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });

});
