// This file is using only ES5 syntax, as
// anything newer could possibly not work on every machine.

/**
 * Message submit on click action.
 * Sends AJAX saving the message.
 */
$('#sendMessage').click(function () {

    var message = $('#message').val().trim();

    sendPost(
        $(this).data('url'),
        {message: message},
        function (response) {
            console.log('success: ', response);
        }, function (response) {
            console.log('success: ', response);
        }
    );
});

/**
 * Sends POST request at given url, with given parameters as
 * request body. On success executes success callback. On
 * failure executes error callback.
 *
 * @param url
 * @param params
 * @param success
 * @param error
 */
function sendPost(url, params, success, error) {
    $.post(
        url,
        params
    ).done(success).fail(error);
}
