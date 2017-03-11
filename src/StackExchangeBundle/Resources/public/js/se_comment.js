var SE_COMMENT = {

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

    get: function(url, data, success, error, complete) {
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
        SE_COMMENT.answer_comment.on('click',
            '.se_answer_comment_add_comment',
            function(e) {

                var that = $(this);
                var form_data = that.data();

                // Get the form
                SE_COMMENT.get(
                    form_data.url,
                    {},
                    function(data, status) {
                        var editorHolder = that.siblings('.se_comment_text_editor');
                        editorHolder.html(data);
                        initTinyMCE();
                    }
                );
            }

        );

        SE_COMMENT.answer_comment.on('click',
            '.stack_exchange_comment_edit',
            function(e) {

                var that = $(this);
                var form_data = that.data();

                // Get the form
                SE_COMMENT.get(
                    form_data.url,
                    {},
                    function(data, status) {
                        var editorHolder = that.parents('.se_comment').find('.se_comment_text_editor');
                        editorHolder.html(data);
                        initTinyMCE();
                    }
                );
            }

        );

    },

    serializeObject: function(obj)
    {
        var o = {};
        var a = $(obj).serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },
};

SE_COMMENT.get= function(url, data, success, error) {
    // make data serialization equals to that of jquery
    var params = jQuery.param(data);
    url += '' != params ? '?' + params : '';

    this.request('GET', url, undefined, success, error);
};

SE_COMMENT.request = function(method, url, data, success, error) {
    // wrap the callbacks to match the callback parameters of jQuery
    var wrappedSuccessCallback = function(response){

        if('undefined' !== typeof success) {
            success(response, response.status);
        }
    };
    var wrappedErrorCallback = function(response){
        if('undefined' !== typeof error) {
            error(response.data.data, response.data.status);
        }
    };

    // todo: is there a better way to do this?
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: wrappedSuccessCallback,
        error: wrappedErrorCallback
    });
};

SE_COMMENT.answer_comment = window.se_answer_comment_container || $('.se_comment');

SE_COMMENT.initializeListeners();