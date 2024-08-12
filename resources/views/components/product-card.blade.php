<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">
    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
</head>

<body class="">
    <div class="row">

        <div class="swiper product-swiper ">
            <div class="swiper-wrapper">
                <div class="swiper-slide row d-flex col-md-4">
                    <div class="product-card position-relative col-md-4 m-5 w-25">
                        <div class="image-holder">
                            <img src="{{ asset('assets/images/product-item1.jpg') }}" alt="product-item"
                                class="img-fluid">
                        </div>
                        <div class="cart-concern position-absolute">
                            <form action="{{ route('basket.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-medium btn-black">
                                    Add to Cart
                                    <svg class="cart-outline">
                                        <use xlink:href="#cart-outline"></use>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                            <h3 class="card-title text-uppercase">
                                <a href="#">{{ $product->name }}</a>
                            </h3>
                            <span class="item-price text-primary">{{ $product->price }}</span>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="swiper-pagination position-absolute text-center"></div>

</body>
