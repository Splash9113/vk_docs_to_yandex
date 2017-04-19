/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//   el: '#app'
// });

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).on('click', 'a.jquery-postback', function (e) {
  e.preventDefault(); // does not go through with the link.

  var $this = $(this).parents('.js-file-to-upload');
  var link = $(this);
  SendDataToUpload($this, link);
});

$(document).on('click', 'button.upload-checked', function (e) {
  $.each($('.check-doc'), function (index, value) {
    if ($(value).prop('checked')) {
      var $this = $(value).parents('.js-file-to-upload');
      var link = $this.find('a.jquery-postback');
      SendDataToUpload($this, link);
    }
  });
});

$('.check-all').on('click', function (e) {
  $('.check-doc').prop('checked', $('.check-all')[0].checked);
});

function SendDataToUpload($this, link) {
  link.text('');
  link.append('<img class="loader-gif" src="img/loader.gif">');

  $.post({
    type: 'post',
    url: $this.data('url'),
    data: {
      link: $this.data('link'),
      title: $this.data('title'),
      doc_id: $this.data('id')
    }
  }).done(function (data) {
    link.addClass('done').text('Done');
  }).fail(function (data) {
    link.addClass('error').text('Error');
  }).always(function (data) {
    link.removeClass('jquery-postback');
  });
}