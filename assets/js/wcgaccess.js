(function ($) {
  $('#grant_product_access').submit(function (e) {
    e.preventDefault();

    var $form = $(this);
    var last_response_len = false;

    $('#grant_results').html('');

    $.ajax({
      url: dwgaccess.ajaxurl,
      type: 'POST',
      data: {
        email: $form.find('input[name=email]').val(),
        _dwnonce: $form.find('input[name=dwgaccess_nonce]').val(),
        action: 'dwgaccess_grant_access'
      },
      cache: false,
      async: true,
      success: function (res) {
        $('#grant_results').append(res.data.message);
      }
    })
  })
})(jQuery)
