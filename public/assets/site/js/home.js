// $(function(){
// 	$(window).scroll(function(){
// 	  if($(this).scrollTop()>=200){
// 		$('.header').addClass('scrolled');
// 	  }else{
// 		$('.header').removeClass('scrolled');
// 	  }
// 	});
//   });

  $(".search_tab").click(function() {
	$('.search_tab').removeClass('active');
	$(this).addClass('active');
    $('html, body').animate({
        scrollTop: $(".serach_bar").offset().top-100
    }, 1000);
});