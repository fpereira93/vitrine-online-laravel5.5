App.Home.CONTAINER = {
    MAIN_ITEMS: 1,
    RECOMMENDED_ITEMS: 2
};

App.Home.Detail = function(){
    var self = this;

    self.show = ko.observable(false);
    self.product = ko.observable(); // instance from 'App.Home.Product'

    self.currentUrlImage = ko.observable();
    self.titleLikeHeart = ko.observable("AMEI A PEÇA");
    self.containerZoomId = ko.observable(uniqueId());

    self.confSlick = {
        slidesToShow: 1,
        slidesToScroll: 1
    };

    self.zoomImage = new (function(){
        var self = this;

        self.show = ko.observable(false);
        self.urlImage = ko.observable();

        self.onClose = function(){
            whenBackHistory.back();
        };
    });

    self.product.subscribe(function(product){
        product.urlImageList().forEach(function(image){

            image.setZoom = function(){
                self.zoomImage.urlImage(image.urlImage());
                self.zoomImage.show(true);

                whenBackHistory.set('zoom', {}, function(){
                    self.zoomImage.show(false);
                });
            };
        });
    });
};

App.Home.ViewModel = function(){
    var self = this;

    self.categories = ko.observableArray();
    self.brands = ko.observableArray();
    self.productsMainItems = ko.observableArray();
    self.productsRecommendedItems = ko.observableArray();

    self.dataFilter = ko.observable();

    // begin: detail

    self.detailProduct = new App.Home.Detail;

    self.showProductDetail = function(product){
        self.detailProduct.product(product);
        self.detailProduct.show(true);
    };

    self.hideProductDetail = function(){
        self.detailProduct.show(false);
    };

    self.onBackPage = function(){
        whenBackHistory.back();
    };

    // end: detail

    self.paginateMainItems = {
        page: ko.observable(1),
        take: ko.observable(6),
        total: ko.observable(0),
        onChange: function(currentPage){
            var _filter = self.concatPaginate(self.dataFilter());

            self.searchMainItems(_filter);
        }
    };

    self.concatPaginate = function(data){
        var _clone = JSON.parse(JSON.stringify(data));

        _clone.paginate = {
            take: self.paginateMainItems.take(),
            page: self.paginateMainItems.page() - 1
        };

        return _clone;
    };

    self.executeAjax = function(request, route, onFinished, noLoading){

        if (!noLoading){
            var loadingId = _hero.loading.show();
        }

        var ajax = _hero.ajax().route(route)
        .then(function(data){
            if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                onFinished && onFinished(data.response);
            }
        })
        .fail(function (data) {
            console.log('Error on search data');
            console.log(data);
        })
        .always(function() {
            if (!noLoading){
                _hero.loading.hide(loadingId);
            }
        });

        if (request){
            ajax.payload(request);
        }

        ajax.execute();
    };

    self.parseCategory = function(category){
        var _category = new App.Home.Category();

        _category.id(category.idCategory);
        _category.name(category.name);
        _category.description(category.description);

        return _category;
    };

    self.parseBrand = function(brand){
        var _brand = new App.Home.Brand();

        _brand.id(brand.idBrand);
        _brand.name(brand.name);
        _brand.description(brand.description);

        return _brand;
    };

    self.executeAjax(null, 'api.category.all', function(response){
        response.forEach(function(category){
            _category = self.parseCategory(category);
            self.categories.push(_category);
        });
    });

    self.executeAjax(null, 'api.brand.all', function(response){
        response.forEach(function(brand){
            var _brand = self.parseBrand(brand);
            self.brands.push(_brand);
        });
    });

    self.parseProduct = function(product){
        var _product = new App.Home.Product();

        _product.id(product.idProduct);
        _product.name(product.name);
        _product.description(product.description);
        _product.price(product.price);
        _product.stock(product.stock);
        _product.urlImage(product.urlMainImage);
        _product.like(product.liked);

        _product.category(self.parseCategory(product.category));
        _product.brand(self.parseBrand(product.brand));

        if (product.allUrlImage){
            _product.urlImageList(product.allUrlImage.map(function(url){
                return {
                    urlImage: ko.observable(url)
                }
            }));
        }

        return _product;
    };

    self.searchMainItems = function(filter, onFinished){
        filter.container = App.Home.CONTAINER.MAIN_ITEMS;

        self.executeAjax(filter, 'api.product.searchProducts', function(response){
            self.productsMainItems.removeAll();

            response.items.forEach(function(product){
                self.productsMainItems.push(self.parseProduct(product));
            });

            if (onFinished){
                onFinished(response);
            }
        });
    };

    self.onSeeDetails = function(product){
        whenBackHistory.set('detail', {}, function(){
            self.detailProduct.show(false);
        });

        self.showProductDetail(product);
    };

    self.searchRecommendedItems = function(filter){
        filter.container = App.Home.CONTAINER.RECOMMENDED_ITEMS;

        self.executeAjax(filter, 'api.product.searchProducts', function(response){
            self.productsRecommendedItems(response.map(function(product){

                var product = self.parseProduct(product);
                product.onSeeDetails = self.onSeeDetails;

                return product;
            }));
        });
    };

    self.filterProducts = function(){
        var filterMainItems = self.concatPaginate(self.dataFilter());

        self.paginateMainItems.page(1);

        self.searchMainItems(filterMainItems, function(response){
            var division = response.count / self.paginateMainItems.take();
            var total = (division % 1) > 0 ? parseInt(division) + 1 : parseInt(division);
            self.paginateMainItems.total(total);
        });

        self.searchRecommendedItems(self.dataFilter());
    };

    self.onLikeHeart = function(isLike, product){
        self.executeAjax({
            isLike: isLike ? 1 : 0,
            productId: product.id()
        }, 'api.product.likeHeartProduct', null, true);
    };

    ko.applyBindingsToDescendants(_hero.loading, document.getElementById('custom-loading'));
};

ko.applyBindingsToDescendants(new App.Home.ViewModel(), document.getElementById("container-view"));