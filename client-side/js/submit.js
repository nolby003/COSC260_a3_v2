// enter the URL of your web server!
var url = "http://localhost:8888/cosc260_a3/server-side/register.php";

$(function() {
  $('#registration').submit(function(e) {
    e.preventDefault();
    send_request();
  });
});


// send POST request with all form data to specified URL
function send_request() {
  // remove messages
  remove_msg();

  // make request
  $.ajax({
    url: url,
    method: 'POST',
    data: $('#registration').serialize(),
    dataType: 'json',
    success: function(data) {

      // log data to console
      console.log(data);

      // display user_id on page
      $('#server_response').addClass('success');
      $('#server_response span').text('user_id: ' +data.user_id); // pass user id as HTTP response message to client
    },
    error: function(data) {

      // parse JSON
      try {
        var $e = JSON.parse(data.responseText);

        // log error to console
        console.log(data);

        // display error on page
        $('#server_response').addClass('error');
        $('#server_response span').text($e.protocol+" "+ $e.error+" - "+$e.reason+": "+$e.message); //pass HTTP custom response to client
      }
      catch (error) {
        console.log('Could not parse JSON error message: ' +$e.error);
      }
    }
  });
}

// remove all messages displayed on page
function remove_msg() {
  var $server_response = $('#server_response');
  if ($server_response.hasClass('success')) {
    $server_response.removeClass('success');
  }
  else if ($server_response.hasClass('error')) {
    $server_response.removeClass('error');
  }
  $('#server_response span').text('');
}