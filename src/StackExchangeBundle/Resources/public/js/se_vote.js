var SE_VOTE = {

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
        SE_VOTE.question_vote.on('click',
            '.se_question_question_vote',
            function(e) {

                var that = $(this);
                var form_data = that.data();
                var score = form_data.score;

                // Get the form
                SE_VOTE.get(
                    form_data.url,
                    {value : score},
                    function(data, status) {
                        // Post it
                        var form = $($.trim(data)).children('form')[0];
                        var form_data = $(form).data();

                        SE_VOTE.post(
                            form.action,
                            SE_VOTE.serializeObject(form),
                            function(data) {
                                var scoreHolder = SE_VOTE.question_vote.find('.se_question_vote_score_holder');
                                scoreHolder.html(data);
                            },
                            function (data) {
                            }
                        );
                    }
                );
            }

        );

        SE_VOTE.answer_vote.on('click',
            '.se_answer_answer_vote',
            function(e) {

                var that = $(this);
                var container = that.closest('.se_answer_vote_container');
                var form_data = that.data();
                var score = form_data.score;

                // Get the form
                SE_VOTE.get(
                    form_data.url,
                    {value : score},
                    function(data, status) {
                        console.log(data);
                        // Post it
                        var form = $($.trim(data)).children('form')[0];
                        var form_data = $(form).data();

                        SE_VOTE.post(
                            form.action,
                            SE_VOTE.serializeObject(form),
                            function(data) {
                                console.log(data);
                                var scoreHolder = container.find('.se_answer_vote_score_holder');
                                console.log(scoreHolder);
                                scoreHolder.html(data);
                            },
                            function (data) {
                                console.log(data);
                            }
                        );
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

SE_VOTE.get= function(url, data, success, error) {
    // make data serialization equals to that of jquery
    var params = jQuery.param(data);
    url += '' != params ? '?' + params : '';

    this.request('GET', url, undefined, success, error);
};

SE_VOTE.request = function(method, url, data, success, error) {
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

SE_VOTE.question_vote = window.se_question_vote_container || $('.se_question_vote_container');
SE_VOTE.answer_vote = window.se_answer_vote_container || $('.se_answer_vote_container');

SE_VOTE.initializeListeners();