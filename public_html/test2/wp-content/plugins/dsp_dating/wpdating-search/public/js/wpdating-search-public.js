
jQuery(document).ready(function () {

    //load the initial data to be displayed when page is loaded
    var search_status = jQuery('#search_status').val();
    var page_index = jQuery('#page_index').val();
    if ('0' === search_status && '0' === page_index){
        load_initial_data();
    }

    if ('0' !== page_index){
        jQuery("#search_status").attr("value", 3);
        saved_search(page_index);
    }

    jQuery('#results').on('scroll', function() {
        if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
            var page = jQuery('#dsp_search_page').val();
            var search_status = jQuery('#search_status').val();
            jQuery("#dsp_search_page").attr("value", ++page );
            //document.getElementById("page").value = ++page;

            if ('1' === search_status){
                quick_search(2);
            }else if ('0' === search_status){
                load_initial_data();
            }else if ('2' === search_status){
                search_filter(0);
            }else if ('3' === search_status){
                saved_search(page_index);
            }
        }
    })
});

function quick_search(status = 1)
{
    if (1 === status){
        jQuery("#dsp_search_page").attr("value", 1);
        jQuery("#search_status").attr("value", 1);
    }
    var page = jQuery('#dsp_search_page').val();
    var data = jQuery('#quick_search_form').serializeArray();
    if (1 == page){
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'new_quick_search',
                'data'  :  data,
                'page'  :  page
            },
            function(response) {
                jQuery("#results").html(response);
                jQuery('#results').scrollTop(0)
            }
        );
    }else{
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'new_quick_search',
                'data'  :  data,
                'page'  :  page
            },
            function(response) {                
                if(response){
                    jQuery("#results").append(response);
                }
                
            }
        );
    }
}

function load_initial_data()
{
    var page = jQuery('#dsp_search_page').val();
    if (1 == page){
        jQuery.post(
            ajax_object.ajax_url,
            {
                'action': 'load_initial_data',
                'page'  :  page
            },
            function(response) {
                jQuery("#results").html(response);
            }
        );
    }else{
        jQuery.post(
            ajax_object.ajax_url,
            {
                'action': 'load_initial_data',
                'page'  :  page
            },
            function(response) {
                jQuery("#results").append(response);
            }
        );
    }
}

function country_selection() {
    var country_id = document.getElementById('country_id').value;
    var city_select = document.getElementById('city_id');
    var state_select = document.getElementById('state_id');
    jQuery.post(
        ajax_object.ajax_url,
        {
            'action'      : 'get_state_by_country_id',
            'country_id'  :  country_id
        },
        function(response) {
            state_select.innerHTML = response;
        });

    jQuery.post(
        ajax_object.ajax_url,
        {
            'action'      : 'get_city_by_country_id',
            'country_id'  :  country_id
        },
        function(response) {
            city_select.innerHTML = response;
        });
}

function state_selection() {
    var state_id = document.getElementById('state_id').value;
    var city_select = document.getElementById('city_id');
    jQuery.post(
        ajax_object.ajax_url,
        {
            'action'      : 'get_city_by_state_id',
            'state_id'    :  state_id
        },
        function(response) {
            city_select.innerHTML = response; 
        });
}

function search_filter(status = 1) {
    if (1 == status){
        jQuery("#search_status").attr("value", 2);
        jQuery("#dsp_search_page").attr("value", 1);

        //document.getElementById("page").value = 1;
        //document.getElementById("search_status").value = 2;
    }
    var page = jQuery('#dsp_search_page').val();
    var data = jQuery('#quick_search_form').serializeArray();
    var data1 = jQuery('#filter_search_form').serializeArray();

    var data2 = data.concat(data1);
    if (1 == page){
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'new_search_filter',
                'data'  :  data2,
                'page'  :  page
            },
            function(response) {
                jQuery("#results").html(response);
                jQuery('#results').scrollTop(0);
            }
        );
    }else{
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'new_search_filter',
                'data'  :  data2,
                'page'  :  page
            },
            function(response) {
                if(response){
                jQuery("#results").append(response);
                }
            }
        );
    }


}

function saved_search(page_index)
{
    var page = jQuery('#dsp_search_page').val();
    if (1 == page){
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'saved_search_result',
                'search_id' : page_index,
                'page'  :  page
            },
            function(response) {
                jQuery("#results").html(response);
            }
        );
    }else{
        jQuery.get(
            ajax_object.ajax_url,
            {
                'action': 'saved_search_result',
                'search_id' : page_index,
                'page'  :  page
            },
            function(response) {
                if(response){
                    jQuery("#results").append(response);
                }
            }
        );
    }
}
