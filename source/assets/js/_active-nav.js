$(document).ready(function(){
  $('.primary').each(function() {
    console.log(window.location.pathname + ' - ' + $(this).attr('href'))
    if ($(this).attr('href')  ===  window.location.pathname) {
      $(this).addClass('current');
    }
  });
})