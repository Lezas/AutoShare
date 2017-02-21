
var LIKE = {
    /**
     * Shorcut post method.
     *
     * @param string url The url of the page to post.
     * @param object data The data to be posted.
     * @param function success Optional callback function to use in case of succes.
     * @param function error Optional callback function to use in case of error.
     */
    post: function(url, data, success, error, complete) {
        // Wrap the error callback to match return data between jQuery and easyXDM
        var wrappedErrorCallback = function(response){
            if('undefined' !== typeof error) {
                error(response.responseText, response.status);
            }
        };
        var wrappedCompleteCallback = function(response){
            if('undefined' !== typeof complete) {
                complete(response.responseText, response.status);
            }
        };
        $.post(url, data, success).error(wrappedErrorCallback).complete(wrappedCompleteCallback);
    },

    /**
     * Shorcut get method.
     *
     * @param string url The url of the page to get.
     * @param object data The query data.
     * @param function success Optional callback function to use in case of succes.
     * @param function error Optional callback function to use in case of error.
     */
    get: function(url, data, success, error) {
        // Wrap the error callback to match return data between jQuery and easyXDM
        var wrappedErrorCallback = function(response){
            if('undefined' !== typeof error) {
                error(response.responseText, response.status);
            }
        };
        $.get(url, data, success).error(wrappedErrorCallback);
    },



    /**
     * Initialize the event listeners.
     */
    initializeListeners: function() {

        LIKE.like_controller.on('click',
            '.like',
            function(e) {
                var that = $(this);
                console.log(that);
                that.button('loading')

                var data = that.data();
                console.log("action " + data.id);
                LIKE.post(
                    data.url,
                    {'carId':data.id},
                    // success
                    function(data) {
                        that.addClass("hidden");
                        that.siblings('.unlike').removeClass("hidden")
                        console.log(data.count);
                        that.siblings('.unlike').children('p').text(data.count)
                        that.button('reset');
                    },

                    // error
                    function(data, statusCode) {
                        that.button('reset')
                    }
                );

                e.preventDefault();
            }
        );

        LIKE.like_controller.on('click',
            '.unlike',
            function(e) {
                var that = $(this);

                that.button('loading');

                var data = that.data();
                console.log("action " + data.id);
                LIKE.post(
                    data.url,
                    {'carId':data.id},
                    // success
                    function(data) {

                        that.addClass("hidden");
                        that.siblings('.like').removeClass("hidden")
                        that.siblings('.like').children('p').text(data.count)
                        that.button('reset');
                    },

                    // error
                    function(data, statusCode) {
                        that.button('reset');

                    }
                );

                e.preventDefault();
            }
        );



    }



};
LIKE.like_controller = $('.like-controller');
$( document ).ready(function() {
    LIKE.initializeListeners();
});
