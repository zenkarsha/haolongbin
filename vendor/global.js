// scroll to top
$(window).scroll(function (event) {
  var scroll = $(window).scrollTop();
  var height = $(window).height();
  if(scroll > height*0.5) $('.gototop').show();
  else $('.gototop').hide();
});
$('.gototop').click(function(){
  $('html,body').animate({scrollTop: 0},'fast');
});
