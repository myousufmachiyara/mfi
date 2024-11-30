@include('../layouts.header')
	<body>
		<section class="body">
		    @include('../layouts.pageheader')
            <div class="inner-wrapper cust-pad">
                <section role="main" class="content-body" style="margin:0px">
                <div class="row">
                    <div class="col-12 mb-3">								
                        <section class="card">
                            <header class="card-header" style="display: flex;justify-content: space-between;">
                                <h2 class="card-title">New Invoice</h2>
                                <div class="card-actions">
                                    <button type="button" class="btn btn-primary" onclick="addNewRow_btn()"> <i class="fas fa-plus"></i> Add New Item </button>
                                </div>
                            </header>
                            <div class="card-body">
                                <div class="row">
                                    <div class="row row-gutter-sm mb-5">
                                        <div class="col-lg-8">
                                            <div class="filters-sidebar-wrapper bg-light rounded">
                                                <div class="card card-modern">
                                                    <div class="card-header">
                                                        <div class="card-actions">
                                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                                        </div>
                                                        <h4 class="card-title">ELECTRONICS</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list list-unstyled mb-0">
                                                            <li><a href="#">Smart TVs</a></li>
                                                            <li><a href="#">Cameras</a></li>
                                                            <li><a href="#">Headphones</a></li>
                                                            <li><a href="#">Games</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr class="solid opacity-7">
                                                <div class="card card-modern">
                                                    <div class="card-header">
                                                        <div class="card-actions">
                                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                                        </div>
                                                        <h4 class="card-title">PRICE</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="slider-range-wrapper">
                                                            <div class="m-md slider-primary slider-modern ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-plugin-slider="" data-plugin-options="{ &quot;half_values&quot;: true, &quot;values&quot;: [ 25, 270 ], &quot;range&quot;: true, &quot;max&quot;: 300 }" data-plugin-slider-output="#priceRange" data-plugin-slider-link-values-to="#priceRangeValues">
                                                                <input id="priceRange" type="hidden" value="25, 270">
                                                            <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 8.33333%; width: 81.6667%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 8.33333%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 90%;"></span></div>
                                                            <form class="d-flex align-items-center justify-content-between mb-2" method="get">
                                                                <span id="priceRangeValues" class="price-range-values">
                                                                    Price $<span class="min price-range-low">25</span> - $<span class="max price-range-high">270</span>
                                                                </span>
                                                                <input type="hidden" class="hidden-price-range-low" name="priceLow" value="">
                                                                <input type="hidden" class="hidden-price-range-high" name="priceHigh" value="">
                                                                <button type="submit" class="btn btn-primary btn-h-1 font-weight-semibold rounded-0 btn-px-3 btn-py-1 text-2">FILTER</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="solid opacity-7">
                                                <div class="card card-modern">
                                                    <div class="card-header">
                                                        <div class="card-actions">
                                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                                        </div>
                                                        <h4 class="card-title">SIZES</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list list-inline list-filter mb-0">
                                                            <li class="list-inline-item">
                                                                <a href="#">S</a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <a href="#" class="active">M</a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <a href="#">L</a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <a href="#">XL</a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <a href="#">2XL</a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <a href="#">3XL</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr class="solid opacity-7">
                                                <div class="card card-modern">
                                                    <div class="card-header">
                                                        <div class="card-actions">
                                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                                        </div>
                                                        <h4 class="card-title">BRANDS</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list list-unstyled mb-0">
                                                            <li><a href="#">Adidas <span class="float-right">18</span></a></li>
                                                            <li><a href="#">Camel <span class="float-right">22</span></a></li>
                                                            <li><a href="#">Samsung Galaxy <span class="float-right">05</span></a></li>
                                                            <li><a href="#">Seiko <span class="float-right">68</span></a></li>
                                                            <li><a href="#">Sony <span class="float-right">03</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="row row-gutter-sm">
                                                
                                                <div class="col-sm-6 col-xl-3 mb-4">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <div class="image-frame-badges-wrapper">
                                                                        <span class="badge badge-ecommerce badge-danger">27% OFF</span>
                                                                    </div>
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-1.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <div class="image-frame-badges-wrapper">
                                                                        <span class="badge badge-ecommerce badge-success">NEW</span>
                                                                        <span class="badge badge-ecommerce badge-danger">27% OFF</span>
                                                                    </div>
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-2.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-3.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-4.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4 mb-lg-0">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-5.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4 mb-lg-0">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-6.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3 mb-4 mb-sm-0">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-7.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xl-3">
                                                    <div class="card card-modern card-modern-alt-padding">
                                                        <div class="card-body bg-light">
                                                            <div class="image-frame mb-2">
                                                                <div class="image-frame-wrapper">
                                                                    <a href="ecommerce-products-form.html"><img src="img/products/product-8.jpg" class="img-fluid" alt="Product Short Name"></a>
                                                                </div>
                                                            </div>
                                                            <small><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-grey text-color-hover-primary text-decoration-none">CATEGORY</a></small>
                                                            <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Short Name</a></h4>
                                                            <div class="stars-wrapper">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div class="product-price">
                                                                <div class="regular-price on-sale">$59.00</div>
                                                                <div class="sale-price">$49.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row row-gutter-sm justify-content-between">
                                                <div class="col-lg-auto order-2 order-lg-1">
                                                    <p class="text-center text-lg-left mb-0">Showing 1-8 of 60 results</p>
                                                </div>
                                                <div class="col-lg-auto order-1 order-lg-2 mb-3 mb-lg-0">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination pagination-modern pagination-modern-spacing justify-content-center justify-content-lg-start mb-0">
                                                            <li class="page-item">
                                                                <a class="page-link prev" href="#" aria-label="Previous">
                                                                    <span><i class="fas fa-chevron-left" aria-label="Previous"></i></span>
                                                                </a>
                                                            </li>
                                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item"><a class="page-link" href="#" disabled="true">...</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">15</a></li>
                                                            <li class="page-item">
                                                                <a class="page-link next" href="#" aria-label="Next">
                                                                    <span><i class="fas fa-chevron-right" aria-label="Next"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                            </div>
                            
                        </section>
                    </div>
                </div>
                </section>
            </div>
        </section>
        @include('../layouts.footerlinks')
	</body>
</html>