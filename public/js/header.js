// 页面头部导航随着页面的滚动样式的改变
$( window ).scroll(function() {
    var sctop = $(this).scrollTop();
    if(sctop>=10){
        $('.fusion-header-wrapper').addClass('fusion-is-sticky');
        $('.fusion-header').addClass('fusion-sticky-shadow');
    }else{
        $('.fusion-is-sticky').removeClass('fusion-is-sticky');
        $('.fusion-sticky-shadow').removeClass('fusion-sticky-shadow');
    }
});
