<html class="avada-html-layout-wide avada-html-header-position-top no-applicationcache geolocation history postmessage websockets localstorage sessionstorage websqldatabase webworkers hashchange audio canvas canvastext video webgl cssgradients multiplebgs opacity rgba inlinesvg hslasupports svgclippaths smil no-touchevents fontface generatedcontent textshadow cssanimations backgroundsize borderimage borderradius boxshadow flexbox cssreflections csstransforms csstransforms3d csstransitions ua-windows_ntua-windows_nt-10 ua-windows_nt-10-0 ua-chrome ua-chrome-86 ua-chrome-86-0 ua-chrome-86-0-4240 ua-chrome-86-0-4240-111 ua-desktop ua-desktop-windows ua-webkit ua-webkit-537 ua-webkit-537-36 js" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" data-useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36">
@include('common.layout')
<link rel="stylesheet" id="fusion-dynamic-css-css" href="/css/700f9380ce38c7c4f0a951ab6c21a4ac.min.css" type="text/css" media="all">

<script type="text/javascript" src="/js/jquery.flexslider.js"></script>

    <body data-rsssl="1" class="page-template-default page page-id-1006 theme-Avada woocommerce-js fusion-image-hovers fusion-pagination-sizing fusion-button_size-large fusion-button_type-flat fusion-button_span-no avada-image-rollover-circle-no avada-image-rollover-yes avada-image-rollover-direction-fade fusion-body ltr fusion-sticky-header no-tablet-sticky-header no-mobile-sticky-header no-mobile-slidingbar no-mobile-totop fusion-disable-outline fusion-sub-menu-fade mobile-logo-pos-left layout-wide-mode avada-has-boxed-modal-shadow-none layout-scroll-offset-full avada-has-zero-margin-offset-top fusion-top-header menu-text-align-center fusion-woo-product-design-classic fusion-woo-shop-page-columns-4 fusion-woo-related-columns-4 fusion-woo-archive-page-columns-3 avada-has-woo-gallery-disabled mobile-menu-design-modern fusion-show-pagination-text fusion-header-layout-v1 avada-responsive avada-footer-fx-none avada-menu-highlight-style-bar fusion-search-form-classic fusion-main-menu-search-overlay fusion-avatar-square avada-dropdown-styles avada-blog-layout-grid avada-blog-archive-layout-grid avada-header-shadow-no avada-menu-icon-position-left avada-has-megamenu-shadow avada-has-mainmenu-dropdown-divider avada-has-header-100-width avada-has-100-footer avada-has-breadcrumb-mobile-hidden avada-has-titlebar-hide avada-has-pagination-padding avada-flyout-menu-direction-fade avada-ec-views-v1 do-animate fusion-no-touch" style="--viewportWidth:1920; height: auto;">
        <a class="skip-link screen-reader-text" href="/">Skip to content</a>
        <div id="boxed-wrapper" style="height: auto;">
            <div class="fusion-sides-frame"></div>
            <div id="wrapper" class="fusion-wrapper">
                <div id="home" style="position:relative;top:-1px;"></div>
                @include('common.header')
                <div id="sliders-container"></div>
                <div class="avada-page-titlebar-wrapper"></div>
                <main id="main" class="clearfix ">
                    <div class="fusion-row" style="">
                        <div class="woocommerce-container">
                            <section id="content" class="" style="width: 100%;">
                                <div class="woocommerce-notices-wrapper"></div>
                                <div id="product-1099" class="product type-product post-1099 status-publish first instock product_cat-free-gift product_cat-wine-glasses has-post-thumbnail shipping-taxable product-type-simple">

                                    <div class="avada-single-product-gallery-wrapper">
                                        <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images avada-product-gallery" data-columns="4" style="opacity: 1; transition: opacity 0.25s ease-in-out 0s;">
                                            <figure class="woocommerce-product-gallery__wrapper">
                                                <div data-thumb="{{$productData['imageArr'][0]}}" class="woocommerce-product-gallery__image" style="position: relative; overflow: hidden;">
                                                    <a href="{{$productData['imageArr'][0]}}" data-slimstat="5">
                                                        <img width="700" height="714" src="{{$productData['imageArr'][0]}}" class="wp-post-image" alt="" title="71nDzZ8+VOL._AC_SL1000_" data-caption="" data-src="{{$productData['imageArr'][0]}}" data-large_image="{{$productData['imageArr'][0]}}" data-large_image_width="980" data-large_image_height="999">
                                                    </a>
                                                    <a class="avada-product-gallery-lightbox-trigger" href="{{$productData['imageArr'][0]}}" data-rel="iLightbox[]" alt="" data-title="71nDzZ8+VOL._AC_SL1000_" data-caption="" data-slimstat="5">

                                                    </a>
                                                    <img role="presentation" alt="" src="{{$productData['imageArr'][0]}}" class="zoomImg" style="position: absolute; top: -364.353px; left: -0.48px; opacity: 0; width: 980px; height: 999px; border: none; max-width: none; max-height: none;">
                                                </div>
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="summary entry-summary">
                                        <div class="summary-container">
                                            <h1 itemprop="name" class="product_title entry-title fusion-responsive-typography-calculated" data-fontsize="26" data-lineheight="29.9px" style="--fontSize:26; line-height: 1.15;">{{$productData['title']}}</h1>
                                            <p class="price">
                                                <span class="woocommerce-Price-amount amount">
                                                    <span class="woocommerce-Price-currencySymbol">$</span>
                                                {{$productData['price']}}</span>
                                            </p>
                                            <p class="price"></p>
                                            <div class="avada-availability"></div>
                                            <div class="product-border fusion-separator sep-underline sep-solid"></div>
                                            <div class="post-content woocommerce-product-details__short-description">
                                                {!!$productData['summary']!!}</h1>
                                            </div>
                                            <div class="product_meta">
                                                <span class="sku_wrapper">SKU:
                                                    <span class="sku">{{$productData['sku']}}</span></span>
                                                <span class="posted_in">Categories:
                                                    @foreach($productData['category'] as $ck=>$cv)
                                                    <a href="/product/productCategory?id={{$ck}}" rel="tag">{{$cv}}</a>
                                                    @endforeach
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="woocommerce-tabs wc-tabs-wrapper">
                                        <ul class="tabs wc-tabs" role="tablist">
                                            <li class="description_tab" id="tab-title-description" role="tab" aria-controls="tab-description">
                                                <a href="javascript:void(0)">Description</a></li>
                                            <!-- <li class="reviews_tab active" id="tab-title-reviews" role="tab" aria-controls="tab-reviews">
                                                <a href="#tab-reviews">Reviews (0)</a></li> -->
                                        </ul>
                                        <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
                                            <div class="post-content">
                                                <h3 class="fusion-woocommerce-tab-title fusion-responsive-typography-calculated" data-fontsize="18" data-lineheight="27px" style="--fontSize:18; line-height: 1.5; --minFontSize:18;">Description</h3>
                                                <!-- {{$productData['content']}} -->
                                                <?php echo html_entity_decode($productData['content'])?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fusion-clearfix"></div>
                                    <div class="fusion-clearfix"></div>
                                    @if($productData['relate'])
                                    <section class="related products">
                                        <div class="fusion-title title sep-underline sep-solid fusion-border-below-title">
                                            <h2 class="title-heading-left fusion-responsive-typography-calculated" data-fontsize="18" data-lineheight="27px" style="--fontSize:18; line-height: 1.5; --minFontSize:18;">Related products</h2>
                                            <div class="title-sep-container">
                                                <div class="title-sep sep-underline sep-solid "></div>
                                            </div>
                                        </div>
                                        <ul class="products clearfix products-4">
                                            @foreach($productData['relate'] as $rk=>$rv)
                                            <li class="product type-product post-1341 status-publish instock product_cat-free-gift has-post-thumbnail shipping-taxable product-type-simple">
                                                <a href="/product/detail?id={{$rv['id']}}" class="product-images" aria-label="OXA Ultralight Foldable Daypack Packable Backpack 30L, Durable Hiking Backpack Travel Backpack">
                                                    <div class="featured-image">
                                                        <img width="500" height="521" src="{{$rv['img']}}" class="attachment-shop_catalog size-shop_catalog wp-post-image" alt="">
                                                        <div class="cart-loading">
                                                            <i class="fusion-icon-spinner"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="fusion-product-content">
                                                    <div class="product-details">
                                                        <div class="product-details-container">
                                                            <h3 class="product-title fusion-responsive-typography-calculated" data-fontsize="24" data-lineheight="36px" style="--fontSize:24; line-height: 1.5;">
                                                                <a href="/product/detail?id={{$rv['id']}}">{{$rv['title']}}</a></h3>
                                                            <div class="fusion-price-rating">
                                                                <span class="price">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        <span class="woocommerce-Price-currencySymbol">$</span>
                                                                    {{$rv['price']}}
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="product-buttons">
                                                        <div class="fusion-content-sep sep-none"></div>
                                                        <div class="product-buttons-container clearfix">
                                                            <a href="/product/detail?id={{$rv['id']}}" class="" style="float:right;max-width:none;text-align:center;">Details</a></div>
                                                    </div> -->
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </section>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                    <!-- fusion-row --></main>
                <!-- #main -->@include('common.footer')</div></body>