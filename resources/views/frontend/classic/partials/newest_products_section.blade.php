@if (count($newest_products) > 0)
    <section class="product_inner">
        <div class="container">
            <!-- Top Section -->
            <div class="product_box mb-md-3">
                <!-- Title -->
                <h4>New offer</h4>
                <h2>
                    <span class="">{{ translate('New Products') }}</span>
                 </h2>
                <p>Looking for the latest trends in clothing, shoes and accessories? Welcome to our 'Latest Drops' edit, bringing you all the latest styles from all your fave brands.</p>
                <!-- Links -->
                <!-- <div class="d-flex">
                    <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_newest')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                    <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="{{ route('search',['sort_by'=>'newest']) }}">{{ translate('View All') }}</a>
                    <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_newest')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                </div> -->
            </div>
            <!-- Products Section -->
           <div class="px-sm-3 product_slider_main">
            <div class="product_slider_wrapper">
                <div class="product_slide_group"> {{-- Each chunk will be a slide --}}
                        <div class="row no-gutters"> {{-- Use row for horizontal alignment within a slide --}}
                            @foreach ($newest_products as $key => $new_product)
                            <div class="col-6 col-md-3"> {{-- Adjust column classes for responsiveness, 4 items per row means 33.33% width each, so col-4 or col-md-3 if you want 4 on medium/large screens --}}
                            <div class="carousel-box position-relative">
                                                <div class="px-3">
                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $new_product])
                                 </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endif