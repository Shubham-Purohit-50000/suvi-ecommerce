@php
    $best_selling_products = get_best_selling_products(20);
@endphp
@if (get_setting('best_selling') == 1 && count($best_selling_products) > 0)
    <section class="product_inner">
        <div class="container">
            <div class="product_box mb-md-3">
                <h4>Special offer</h4>
                <h2>
                    <span class="">{{ translate('Best Selling') }}</span>
                </h2>
                <p>Looking for the latest trends in clothing, shoes and accessories? Welcome to our 'Latest Drops' edit, bringing you all the latest styles from all your fave brands.</p>
                <!-- {{-- <div class="d-flex">
                    <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_best_selling')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                    <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_best_selling')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                </div> --}} -->
            </div>
            <div class="px-sm-3 product_slider_main"> {{-- Added product_slider_main class here --}}
                <div class="product_slider_wrapper"> {{-- New wrapper for Slick --}}
                    @php
                        $chunkedBestSellingProducts = collect($best_selling_products)->chunk(4); // Chunk products into groups of 4
                    @endphp

                    @foreach ($chunkedBestSellingProducts as $chunk)
                        <div class="product_slide_group"> {{-- Each chunk will be a slide --}}
                            <div class="row no-gutters"> {{-- Use row for horizontal alignment within a slide --}}
                                @foreach ($chunk as $product)
                                    <div class="col-6 col-md-3"> {{-- Adjust column classes for responsiveness, 4 items per row means 33.33% width each, so col-4 or col-md-3 if you want 4 on medium/large screens --}}
                                        <div class="carousel-box position-relative">
                                            <div class="px-3">
                                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif