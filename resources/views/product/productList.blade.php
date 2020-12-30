<html class="avada-html-layout-wide no-applicationcache geolocation history postmessage websockets localstorage sessionstorage websqldatabase webworkers hashchange audio canvas canvastext video webgl cssgradients multiplebgs opacity rgba inlinesvg hsla supports svgclippaths smil no-touchevents fontface generatedcontent textshadow cssanimations backgroundsize borderimage borderradius boxshadow flexbox cssreflections csstransformscsstransforms3d csstransitions ua-windows_nt ua-windows_nt-10 ua-windows_nt-10-0 ua-chrome ua-chrome-86 ua-chrome-86-0 ua-chrome-86-0-4240 ua-chrome-8=6-0-4240-111 ua-desktop ua-desktop-windows ua-webkit ua-webkit-537 ua-webkit-537-36 js" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" data-useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36">
@include('common.layout')
<style>
    .prev:before,.next:after{
        content: none !important;
    }
</style>
    <body data-rsssl="1" class="archive tax-product_cat term-uncategorizedterm-27 woocommerce woocommerce-page woocommerce-js fusion-image-hovers fusion-body ltr fusion-sticky-header no-tablet-sticky-header no-mobile-sticky-header no-mobile-slidingbar no-mobile-totop fusion-disable-outline mobile-logo-pos-left layout-wide-mode fusion-top-header menu-text-align-center fusion-woo-product-design-classic mobile-menu-design-modern fusion-show-pagination-text fusion-header-layout-v1 avada-responsive avada-footer-fx-none fusion-search-form-classic fusion-avatar-square do-animate fusion-no-touch" style="height: auto;">
        <div id="wrapper" class="" style="height: auto;">
            <div id="home" style="position:relative;top:-1px;"></div>
            <div id="sliders-container"></div>
            <main id="main" role="main" class="clearfix " style="">
                <div class="fusion-row" style="">
                    <div class="woocommerce-container">
                        <section id="content" class="full-width" style="width: 100%;">
                            <header class="woocommerce-products-header"></header>
                            <ul class="products clearfix products-3">
                                @foreach($productData as $pk=>$pv)
                                <li class="post-1235 product type-product status-publish has-post-thumbnail product_cat-uncategorized product-grid-view first instock shipping-taxable product-type-simple">
                                    <a href="/product/detail?id={{$pv['id']}}" class="product-images" aria-label="Gift Test" data-slimstat="5">
                                        <div class="featured-image">
                                            <img width="500" height="664" src="{{$pv['img']}}" class="attachment-shop_catalog size-shop_catalog wp-post-image" alt="">
                                            <div class="cart-loading">
                                                <i class="fusion-icon-spinner"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="fusion-product-content">
                                        <div class="product-details">
                                            <div class="product-details-container">
                                                <h3 class="product-title">
                                                    <a href="/product/detail?id={{$pv['id']}}" data-slimstat="5">{{$pv['title']}}</a></h3>
                                                <div class="fusion-price-rating">
                                                    <span class="price">
                                                        <span class="woocommerce-Price-amount amount">
                                                            <span class="woocommerce-Price-currencySymbol">$</span>
                                                        {{$pv['price']}}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <nav class="woocommerce-pagination">

                                <!-- 显示第一页 -->
                                @if($return['firstflag']==1)
                                    <a class="prev page-numbers" href="/product/productCategory?id={{$return['id']}}&page=1" data-slimstat="5">
                                        <span class="page-text">First</span>
                                    </a>
                                @endif

                                @if($return['current']!=1)
                                <a class="prev page-numbers" href="/product/productCategory?id={{$return['id']}}&page={{$return['current']-1}}" data-slimstat="5">
                                    <span class="page-text">Previous</span>
                                </a>
                                @endif

                                @foreach($return['page'] as $key=>$val)
                                <a class="page-numbers @if($val==$return['current']) current  @endif" href="/product/productCategory?id={{$return['id']}}&page={{$val}}" data-slimstat="5">{{$val}}</a>
                                @endforeach

                                @if($return['current']!=$return['total'])
                                <a class="next page-numbers" href="/product/productCategory?id={{$return['id']}}&page={{$return['current']+1}}" data-slimstat="5">
                                    <span class="page-text">Next</span>
                                </a>
                                @endif

                                <!-- 显示最后一页 -->
                                @if($return['endflag']==1)
                                    <a class=" nextpage-numbers" href="/product/productCategory?id={{$return['id']}}&page={{$return['total']}}" data-slimstat="5" style="margin-left:5px;">
                                        <span class="page-text">Last</span>
                                    </a>
                                @endif
                            </nav>
                        </section>
                    </div>
                </div>
                <!-- fusion-row --></main>
            <!-- #main -->@include('common.footer')</div></body>

</html>