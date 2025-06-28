@php
    // Define categories with their IDs
    $categories = [
        ['id' => 1, 'name' => 'Electronics'],
        ['id' => 2, 'name' => 'Luggage'] // Add more categories as needed
    ];
    
    // Get best selling products for each category
    $category_products = [];
    foreach ($categories as $category) {
        $products = get_category_best_selling_products($category['id'], 8); // Get 8 products per category
        if(count($products) > 0) {
            $category_products[$category['id']] = $products;
        }
    }
    
    // Determine active tab (default to first category with products)
    $active_category_id = null;
    foreach ($categories as $category) {
        if(isset($category_products[$category['id']])) {
            $active_category_id = $category['id'];
            break;
        }
    }
@endphp

@if (get_setting('best_selling') == 1 && !empty($category_products))
    <section class="product_inner">
        <div class="container">
            <div class="product_box mb-md-3">
                <h4>Special offer</h4>
                <h2>
                    <span class="">{{ translate('Best Selling') }}</span>
                </h2>
                <ul class="tabs tab-title">
                    @foreach ($categories as $category)
                        @if(isset($category_products[$category['id']]))
                            <li class="{{ $category['id'] == $active_category_id ? 'current' : '' }}">
                                <a href="javascript:void(0)" data-category="{{ $category['id'] }}" class="category-tab">{{ $category['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <p>Discover our top-selling products across different categories. Find the best deals on quality items that our customers love.</p>
            </div>
            
            @foreach ($categories as $category)
                @if(isset($category_products[$category['id']]))
                    <div class="px-sm-3 product_slider_main category-products" id="tab-{{ $category['id'] }}" >
                        <h5>{{$category['name']}}</h5>
                        <div class="product_slider_wrapper">
                            @php
                                $chunkedProducts = collect($category_products[$category['id']])->chunk(4);
                            @endphp

                            @foreach ($chunkedProducts as $chunk)
                                <div class="product_slide_group">
                                    <div class="row no-gutters">
                                        @foreach ($chunk as $product)
                                            <div class="col-6 col-md-3">
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
                @endif
            @endforeach
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.category-tab');
            if (!tabs.length) return; // No tabs found

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Get category ID from data attribute
                    const categoryId = this.getAttribute('data-category');
                    if (!categoryId) return;

                    // Update active tab
                    document.querySelectorAll('.tab-title li').forEach(li => {
                        li.classList.remove('current');
                    });
                    if (this.parentElement) {
                        this.parentElement.classList.add('current');
                    }

                    // Hide all product sections
                    document.querySelectorAll('.category-products').forEach(section => {
                        section.style.display = 'none';
                    });

                    // Show selected category's products
                    const targetSection = document.getElementById(`tab-${categoryId}`);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                    }
                });
            });
        });
    </script>
@endif