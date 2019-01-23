@extends('showcase.base')

@section('head')
    @parent
    <link href="{{ asset('range-slider/css/bootstrap-slider.min.css') }}" rel="stylesheet">

    <link href="{{ asset('showcase/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/recommended-items.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/features-items.css') }}" rel="stylesheet">
    <link href="{{ asset('showcase/css/home.css') }}" rel="stylesheet">

    <link href="{{ asset('js/slick-1.8.1/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('js/slick-1.8.1/slick/slick-theme.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('header-inside')
    @parent

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">
                            <li><a href="{{ route('showcase.home.index') }}" class="active">Home</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
@endsection

@section('header-below')

    <section id="slider"><!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#slider-carousel" data-slide-to="1"></li>
                            <li data-target="#slider-carousel" data-slide-to="2"></li>
                        </ol>
                        
                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="col-sm-6">
                                    <h1>Ateliê - <span>Maria Modas</span></h1>
                                    <h2>Você com estilo</h2>
                                    <p>Em qualquer estação, existem peças que são insubstituíveis e atemporais para usar onde quiser. São peças que, com seu estilo único e charmoso, conquistaram o guarda-roupa feminino e que não sairão de moda facilmente. Além de peças especiais, são modelos que combinam com todos os estilos e ocasiões.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img src="showcase/images/home/girl1.jpg" class="girl img-responsive" alt="" />
                                    <!-- <img src="showcase/images/home/pricing.png"  class="pricing" alt="" /> -->
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1>Ateliê - <span>Maria Modas</span></h1>
                                    <h2>Você na moda</h2>
                                    <p>Em qualquer estação, existem peças que são insubstituíveis e atemporais para usar onde quiser. São peças que, com seu estilo único e charmoso, conquistaram o guarda-roupa feminino e que não sairão de moda facilmente. Além de peças especiais, são modelos que combinam com todos os estilos e ocasiões.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img src="showcase/images/home/girl2.jpg" class="girl img-responsive" alt="" />
                                    <!-- <img src="showcase/images/home/pricing.png"  class="pricing" alt="" /> -->
                                </div>
                            </div>
                            
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1>Ateliê - <span>Maria Modas</span></h1>
                                    <h2>Você com tudo</h2>
                                    <p>Em qualquer estação, existem peças que são insubstituíveis e atemporais para usar onde quiser. São peças que, com seu estilo único e charmoso, conquistaram o guarda-roupa feminino e que não sairão de moda facilmente. Além de peças especiais, são modelos que combinam com todos os estilos e ocasiões.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img src="showcase/images/home/girl3.jpg" class="girl img-responsive" alt="" />
                                    <!-- <img src="showcase/images/home/pricing.png" class="pricing" alt="" /> -->
                                </div>
                            </div>
                            
                        </div>
                        
                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </section><!--/slider-->
@endsection

@section('container')
    @parent

    <div class="floating-wpp"></div>

    <div data-bind="if: $data.detailProduct.show">
        <div class="col-sm-12" data-bind="with: $data.detailProduct">
            <div class="product-details"><!--product-details-->
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3 class="green name-product">
                                <!--ko text: $data.product().name --><!--/ko-->
                            </h3>

                            <!-- Zoom image -->
                            <zoom-image params="show: $data.zoomImage.show, src: $data.zoomImage.urlImage, onClose: $data.zoomImage.onClose"></zoom-image>

                            <script type="text/html" id="template-view-images">
                                <div class="container-image" data-bind="click: $data.setZoom">
                                    <div class="icon-zoom">
                                        <img title="Zoom na imagem" src="{{ asset('showcase/images/zoom-in.png') }}">
                                    </div>
                                    <img class="zoom-in box-shadow" data-bind="attr: { src: $data.urlImage }">
                                </div>
                            </script>

                            <div class="view-product">
                                <slick-items params="elements: $data.product().urlImageList, templateID: 'template-view-images', conf: $data.confSlick"></slick-items>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="product-information">
                                <like-heart params="title: $data.titleLikeHeart, like: $data.product().like, onChange: function(isLike){ $parent.onLikeHeart(isLike, $data.product()) }"></like-heart>
                                <hr>
                                <p><b>Código Produto:</b> <!--ko text: $data.product().id --><!-- /ko--></p>
                                <p><b>Quantidade:</b> <!--ko text: $data.product().stock --><!-- /ko--></p>
                                <p><b>Disponibilidade:</b> No estoque</p>
                                <p><b>Categoria:</b> <!--ko text: $data.product().category().name --><!--/ko--></p>
                                <p><b>Marca:</b> <!--ko text: $data.product().brand().name --><!--/ko--></p>
                                <p><b>Descriçao:</b> <pre> <!--ko text: $data.product().description --><!--/ko--> </pre></p>
                                <a target="_blank" href="https://www.facebook.com/Maria.Modas2010/">
                                    <span><img src="/showcase/images/contact/facebook.png" /> facebook</span>
                                </a>
                            </div><!--/product-information-->
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <a class="btn btn-primary pull-right" data-bind="click: $parent.onBackPage">
                                <i class="fa fa-arrow-left mr-5"></i>
                                VOLTAR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-bind="visible: !$data.detailProduct.show()">
        <div class="col-sm-3 mb-5">
            <sidebar-filter params="categories: $data.categories, brands: $data.brands, dataFilter: $data.dataFilter, onFilter: $data.filterProducts"></sidebar-filter>
        </div>
        <div class="col-sm-9">
            <main-items params="products: $data.productsMainItems, onSeeDetails: $data.onSeeDetails, onChangeLikeProduct: $data.onLikeHeart"></main-items>

            <div class="row">
                <div class="col-sm-12" data-bind="visible: paginateMainItems.total() > 0">
                    <paginate-bootpag params="total: paginateMainItems.total, page: paginateMainItems.page, onChange: paginateMainItems.onChange"></paginate-bootpag>
                </div>
                <div class="col-sm-12" data-bind="visible: paginateMainItems.total() == 0">
                    <h5 class="text-center"> Nenhum produto encontrado </h5>
                </div>
            </div>

            <script type="text/html" id="template-view-images-recommended">
                <div class="recommended_items pointer" data-bind="click: $data.onSeeDetails">
                    <img class="box-shadow" data-bind="attr: { src: $data.urlImage }">
                </div>
            </script>

            <div class="row">
                <h2 class="title text-center">ITENS RECOMENDADOS</h2>
                <slick-items params="elements: $data.productsRecommendedItems, templateID: 'template-view-images-recommended'"></slick-items>
            </div>
        </div>
    </div>

@endsection


@section('script-footer')
    @parent

    <!--leaflet -->
    <link rel="stylesheet" href="{{ asset('showcase/js/imgViewer2-master/leaflet/leaflet.css') }}" />
    <script src="{{ asset('showcase/js/imgViewer2-master/leaflet/leaflet.js') }}" type="text/javascript"></script>

    <!--ui -->
    <script src="{{ asset('js/jquery/ui/jquery-ui.min.js') }}" type="text/javascript"></script>

    <!-- imgViewer2 -->
    <link href="{{ asset('showcase/js/imgViewer2-master/imgViewer2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('showcase/js/imgViewer2-master/imgViewer2.min.js') }}" type="text/javascript"></script>

    <!-- whatsapp -->
    <link href="{{ asset('showcase/js/floating-wpp/floating-wpp.min.css') }}" rel="stylesheet">
    <script src="{{ asset('showcase/js/floating-wpp/floating-wpp.min.js') }}"></script>

    <script src="{{ asset('range-slider/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('js/paginate/jquery.bootpag.min.js') }}"></script>

    <script src="{{ asset('js/libraries/format.js') }}"></script>
    <script src="{{ asset('js/slick-1.8.1/slick/slick.js') }}"></script>

    <script src="{{ asset('showcase/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('showcase/js/main.js') }}"></script>

    <script src="{{ asset('showcase/js/components/home.js') }}"></script>
    <script src="{{ asset('showcase/js/home.js') }}"></script>
@endsection