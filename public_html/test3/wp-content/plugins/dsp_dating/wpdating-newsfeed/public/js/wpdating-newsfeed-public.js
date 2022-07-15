jQuery(document).ready(function () {
    load_feeds();
});

function load_feeds() {
    var user = jQuery('#user').val();
    var page = jQuery('#dsp_feed_page').val();
    jQuery(".timeline_ajax_loader_wrap").show();
    if ('1' === page){
        jQuery.post(
            ajax_object.ajax_url,
            {
                    'action': 'fetch_news_feed',
                    'page'  :  page,
                    'user_id':  user
            },
            function(response) {
                jQuery(".timeline_ajax_loader_wrap").hide();
                document.getElementById("news-feed-page").innerHTML = response;
                jQuery("#news_feed_box").fadeIn();
            });
    }else{
        jQuery.post(
            ajax_object.ajax_url,
            {
                'action': 'fetch_news_feed',
                'page'  :  page,
                'user_id':  user
            },
            function(response) {
                jQuery(".timeline_ajax_loader_wrap").hide();
                document.getElementById("news-feed-page").innerHTML += response;
                jQuery("#news_feed_box").fadeIn();
            });
    }
}

function load_more() {
    var page = jQuery('#dsp_feed_page').val();
    page = ++page;
    jQuery('#dsp_feed_page').attr('value',page);
    removeElement('loadMore');
    jQuery('#news_feed_box').fadeOut();
    load_feeds();
}

function removeElement(elementId) {
    // Removes an element from the document
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}

function update_news_feed(user) {
    jQuery('#user').attr('value',user);
    jQuery('#dsp_feed_page').attr('value',1);
    // document.getElementById("user").value = user;
    // document.getElementById("page").value = 1;
    jQuery('#news_feed_box').fadeOut();
    load_feeds();
}