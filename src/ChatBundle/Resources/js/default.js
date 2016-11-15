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
        function () {
            refreshMessages();
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

/**
 * Sends AJAX call to either approve or hide message.
 *
 * @param messageId
 * @param decision
 */
function decideMessageFate(messageId, decision) {

    var url = $('#sendMessage').data('decide-url');

    sendPost(
        url,
        {messageId: messageId, toApprove: decision},
        function (response) {
            if (decision == 0) {
                $('#message' + messageId).remove();
            } else {
                refreshMessages();
            }
        }, function (response) {
            console.log('success: ', response);
        }
    )
}

/**
 * Refreshes message list.
 */
function refreshMessages() {
    sendPost($messageButton.data('ret-url'), {}, function (response) {
        $('#allMessages').html(response['html']);

        var notifications = response['notifications'];

        var msg = '';
        for(var field in notifications) {
            if(notifications.hasOwnProperty(field)) {
                msg += 'Message: ' + notifications[field]['message'] + ' was ';
                msg += notifications[field]['success'] ? 'approved. ' : 'hidden. ';
            }
        }

        if(msg !== '') {
            alert(msg);
        }

        console.log(notifications);
        console.log(msg);

    }, function (response) {
        console.log(response);
    });
}

window.onload = function () {

    // Remove unwanted part of an url.
    window.history.pushState('Chat', 'Chat', '/');

    $messageButton = $('#sendMessage');
    if($messageButton.length != 0) {
        //setInterval(function () {
        //    refreshMessages();
        //}, 1000);
    }
};
