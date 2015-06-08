$(document).ready(function(){
  $('.primary').each(function() {
    if ($(this).attr('href')  ===  window.location.pathname) {
      $(this).addClass('current');
    }
  });
})