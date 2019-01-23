var App = App || {};

App.Home = App.Home || {};

App.Home.Category = function(){
    var self = this;

    self.id = ko.observable();
    self.name = ko.observable();
    self.description = ko.observable();
    self.checked = ko.observable(false);
};

App.Home.Brand = function(){
    var self = this;

    self.id = ko.observable();
    self.name = ko.observable();
    self.description = ko.observable();
    self.checked = ko.observable(false);
};

App.Home.Product = function(){
    var self = this;

    self.id = ko.observable();
    self.name = ko.observable();
    self.description = ko.observable();
    self.price = ko.observable();
    self.stock = ko.observable();
    self.urlImage = ko.observable();
    self.like = ko.observable(false);

    self.category = ko.observable();
    self.brand = ko.observable();

    self.urlImageList = ko.observableArray();
};

/**
 * [loadImage set loading on load image]
 * 
 */

ko.bindingHandlers.loadImage = {
    init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {

        var data = allBindings.get('loadImage');
        var urlTargetImage = ko.isObservable(data.url) ? data.url() : data.url;

        var divLoading = (function(){
            var div = document.createElement('div');
            div.className = "loading-image";
            div.setAttribute('style', 'height: ' + element.height + 'px');
            return div;
        })();

        element.parentElement.append(divLoading);
        element.hidden = true;

        var imageLoad = new Image();
        imageLoad.src = urlTargetImage;

        imageLoad.onload = function(){
            element.src = urlTargetImage;
            divLoading.remove();

            if (data.onload){
                data.onload(imageLoad, element);
            }

            element.hidden = false;
        };
    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {   }
};

ko.components.register("zoom-image", {
    viewModel: function(params) {
        var self = this;

        self.id = ko.observable(uniqueId());
        self.show = params.show;
        self.src = params.src;

        self.close = function(){
            if (params.onClose){
                params.onClose();
            }

            self.show(false);
        };

        self.init = function(){   };

        can(self.init, self.id());
    },
    template: [
        '<fade-modal params="show: $data.show" class="fade-modal-zoom-image">',
            '<button type="button" class="close" aria-label="Close" data-bind="click: $parent.close">',
                '<span aria-hidden="true">&times;</span>',
            '</button>',
            '<div class="container-img-viewer-2">',
                '<img-viewer-2 params="src: $parent.src"></img-viewer-2>',
            '</div>',
        '</fade-modal>',
    ].join('')
});

ko.components.register("img-viewer-2", {
    viewModel: function(params) {
        var self = this;

        self.id = ko.observable(uniqueId());
        self.src = params.src;
        self.height = $(window).height();
        self.alreadyInitialized = false;

        self.conf = {
            zoomMax: 6,
            zoomable: true,
            dragable: true,
            onReady: function() {   }
        };

        self.reset = function(){
            if (!self.alreadyInitialized){
                return;
            }

            $('#' + self.id()).imgViewer2('destroy');
        };

        self.imgViewer2 = function(){
            timeout(function(){
                var conf = $.extend({}, self.conf, params.conf || {});
                $('#' + self.id()).imgViewer2(conf);
            });
        };

        self.resetViewer2 = function(){
            self.reset();
            self.imgViewer2();
        };

        self.init = function(){
            self.src.subscribe(function(newValue) {
                if (!self.src || !self.src()){
                    return;
                }

                self.resetViewer2();
                self.alreadyInitialized = true;
            });
        };

        can(self.init, self.id());
    },
    template: [
        '<img data-bind="attr: { id: $data.id, src: $data.src, height: $data.height }" />'
    ].join('')
});

ko.components.register("fade-modal", {
    viewModel: function(params) {
        var self = this;

        self.id = ko.observable(uniqueId());
        self.show = params.show;

        self.options = {
            backdrop: "static",
            show: false
        };

        self.testShowModal = function(){
            $('#' + self.id()).modal(self.show() ? 'show' : 'hide');
        };

        self.show.subscribe(function(newValue) {
            self.testShowModal();
        });

        self.init = function(){
            $('#' + self.id()).modal(self.options);

            self.testShowModal();
        };

        can(self.init, self.id());
    },
    template: [
        '<div data-bind="attr: { id: $data.id }, visible: $data.show" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">',
            '<!-- ko template: { nodes: $componentTemplateNodes } --><!-- /ko -->',
        '</div>'
    ].join('')
});

ko.components.register("slick-items", {
    viewModel: function(params) {
        var self = this;

        self.id = ko.observable(uniqueId());
        self.templateID = ko.observable(params.templateID);
        self.visible = ko.observable(false);
        self.elements = params.elements;

        self.prev = [
            '<a class="left recommended-item-control">',
                '<i class="fa fa-angle-left"></i>',
            '</a>'
        ].join('');

        self.next = [
            '<a class="right recommended-item-control">',
                '<i class="fa fa-angle-right"></i>',
            '</a>'
        ].join('');

        self.getConf = function(){
            var conf = {
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                prevArrow: self.prev,
                nextArrow: self.next,
                responsive: [
                    {
                        point: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            };

            return $.extend({}, conf, params.conf || {});
        };

        self.unslick = function(){
            try {
                $('#' + self.id()).slick('unslick');
                return true;
            } catch(error) {
                return false;
            }
        };

        self.setBindForeach = function(){
            self.unslick();

            var targetElement = document.getElementById(self.id());

            targetElement.innerHTML = "";

            for (var elementIndex = 0; elementIndex < self.elements().length; elementIndex++) {
                targetElement.innerHTML += [
                    '<div data-bind="with: $data.elements()['+ elementIndex +']">',
                        document.getElementById(self.templateID()).innerHTML ,
                    '</div>'
                ].join('');
            }

            $('#' + self.id()).slick(self.getConf());

            ko.applyBindingsToDescendants(self, document.getElementById(self.id()));
        };

        self.timeout = null;

        self.whenExecute = function(){
            self.visible(false);

            clearTimeout(self.timeout);

            self.timeout = timeout(function(){
                self.setBindForeach();
                self.visible(true);
            }, 500);
        };

        self.elements.subscribe(self.whenExecute);

        if (self.elements().length){
            self.whenExecute();
        }
    },
    template: [
        '<section data-bind="attr: { id: $data.id }"></section>'
    ].join('')
});

ko.components.register("main-items", {
    viewModel: function(params) {
        var self = this;

        self.onSeeDetails = params.onSeeDetails || function(product){};
        self.products = params.products; // observable array
        self.onChangeLikeProduct = params.onChangeLikeProduct; // observable

        self.onloadImage = function(imageLoad, element){
        };
    },
    template: [
        '<div class="row">',
            '<div class="col-md-12">',
                 '<div class="features_items"><!--features_items-->',
                    '<h2 class="title text-center">PRINCIPAIS ITENS</h2>',

                    '<!-- ko foreach: $data.products -->',

                        '<div class="col-xs-6 col-md-4">',
                            '<div class="product-image-wrapper box-shadow">',

                                '<div class="productinfo">',
                                    '<div class="single-products">',
                                        '<div class="text-center">',
                                            '<img data-bind="loadImage: { url: $data.urlImage, onload: $parent.onloadImage }, attr: { alt: $data.description }, click: $parent.onSeeDetails" />',
                                        '</div>',
                                    '</div>',
                                    '<div class="text-center">',
                                        '<p class="mt-5 name-product"><!--ko text: $data.name --><!--/ko--></p>',
                                    '</div>',
                                '</div>',

                                '<div class="choose">',
                                    '<like-heart params="onChange: function(isLike){ $parent.onChangeLikeProduct(isLike, $data) }, like: $data.like"></like-heart>',
                                    '<ul class="nav nav-pills nav-justified">',
                                        '<li><a data-bind="click: $parent.onSeeDetails"><i class="fa fa-asterisk"></i>DETALHES</a></li>',
                                    '</ul>',
                                '</div>',
                            '</div>',
                        '</div>',

                    '<!-- /ko -->',

                '</div>',
            '</div>',
        '</div>'

    ].join('')
});

ko.components.register("sidebar-filter", {
    viewModel: function(params) {
        var self = this;

        self.categories = params.categories || []; // observable array
        self.brands = params.brands || []; // observable array
        self.dataFilter = params.dataFilter || {}; // json

        self.id = ko.observable(uniqueId());

        self.formatItens = function(list){
            return list.filter(function(o){
                return o.checked();
            }).map(function(o){
                return o.id();
            });
        };

        self.getRangePrice = function(){
            var valuesPrice = $('#' + self.id()).val().split(',');

            if (!valuesPrice[0]){
                return null;
            }

            return {
                min: parseFloat(valuesPrice[0]),
                max: parseFloat(valuesPrice[1])
            };
        };

        self.getDataForFilter = function(){
            var data = {
                categories: self.formatItens(self.categories()),
                brands: self.formatItens(self.brands())
            };

            var rangePrice = self.getRangePrice();

            if (rangePrice){
                data.price = rangePrice;
            }

            return data;
        };

        self.onFilter = params.onFilter || function() {};

        self.setDataFilter = function(){
            self.dataFilter(self.getDataForFilter());
            return true;
        };

        self.beforeFilter = function(){
            self.onFilter(self.getDataForFilter());
        };

        timeout(function(){
            can(function(){
                $('#' + self.id()).slider({
                    min: 0,
                    max: 300,
                    orientation: 'horizontal',
                    value: 0,
                    range: true,
                    tooltip: 'always',
                    formatter: function formatter(val) {
                        if (Array.isArray(val)) {
                            return _hero.format.numberToReal(val[0]) + " : " + _hero.format.numberToReal(val[1]);
                        } else {
                            return _hero.format.numberToReal(val);
                        }
                    },

                }).change(self.setDataFilter);

                self.setDataFilter();
                self.beforeFilter();

            }, self.id());

        });
    },
    template: [
    '<div class="row">',
        '<div class="col-md-12">',
            '<div class="left-sidebar">',
                '<h2>Categoria</h2>',

                '<div class="panel-group category-products"><!--category-productsr-->',
                    '<div class="panel panel-default">',

                        '<!-- ko foreach: $data.categories -->',
                            '<h4 class="panel-title">',

                                '<div class="checkbox">',
                                    '<label>',
                                        '<input type="checkbox" data-bind="checked: $data.checked, click: $parent.setDataFilter">',
                                        '<span class="cr">',
                                            '<i class="cr-icon glyphicon glyphicon-ok"></i>',
                                        '</span>',
                                        '<span class="cr-label"><!--ko text: $data.name --><!--/ko--></span>',
                                    '</label>',
                                '</div>',
                            '</h4>',
                        '<!-- /ko -->',

                    '</div>',
                '</div><!--/category-products-->',

                '<div class="brands_products"><!--brands_products-->',
                    '<h2>Marcas</h2>',
                    '<div class="brands-name">',

                        '<!-- ko foreach: $data.brands -->',
                            '<div class="checkbox">',
                                '<label>',
                                    '<input type="checkbox" data-bind="checked: $data.checked, click: $parent.setDataFilter">',
                                    '<span class="cr">',
                                        '<i class="cr-icon glyphicon glyphicon-ok"></i>',
                                    '</span>',
                                    '<span class="cr-label"><!--ko text: $data.name --><!--/ko--></span>',
                                '</label>',
                            '</div>',
                        '<!-- /ko -->',

                    '</div>',
                '</div><!--/brands_products-->',

                '<div class="price-range" style="display: none;"><!--price-range-->',
                    '<h2>Preço</h2>',
                    '<input type="text" data-bind="attr: { id: $data.id }" />',
                '</div><!--/price-range-->',

                '<a class="btn btn-primary pull-right" data-bind="click: $data.beforeFilter">',
                    '<i class="fa fa-filter mr-5"></i>',
                    'VER VITRINE (FILTRAR)',
                '</a>',

            '</div>',
        '</div>',
    '</div>'
    ].join('')
});

ko.components.register("paginate-bootpag", {
    viewModel: function(params) {
        var self = this;

        self.id = ko.observable(uniqueId());
        self.onChange = params.onChange; // function
        self.total = params.total; // observable
        self.page = params.page; // observable

        self.total.subscribe(function(newValue){
            $('#' + self.id()).bootpag({ total : newValue });
        });

        self.page.subscribe(function(newValue){
            $('#' + self.id()).bootpag({ page : newValue });
        });

        can(function(){
            $('#' + self.id()).bootpag({
                total: self.total(), // total records
                page: self.page(), //current page
                maxVisible: 5,
                leaps: true,
                firstLastUse: true,
                first: '←',
                last: '→',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            }).on("page", function(event, num){
                self.page(num);
                self.onChange(num);
            });
        }, self.id());
    },
    template: [
        '<div class="pull-right" data-bind="attr: { id: $data.id }"></div>'
    ].join('')
});

ko.components.register("like-heart", {
    viewModel: function(params) {
        var self = this;

        self.like = params.like || ko.observable(false); // observable
        self.title = params.title || ko.observable('AMEI'); // observable

        self.change = function(){
            self.like(!self.like());

            if (params.onChange){
                params.onChange(self.like());
            }
        };
    },
    template: [
        '<div class="container-like-heart" title="Clique no coração para dizer que gostou">',
            '<div class="like-heart">',
                '<img data-bind="visible: $data.like(), click: $data.change" src="/showcase/images/like/like-yes.png" style="display: none">',
                '<img data-bind="visible: !$data.like(), click: $data.change" src="/showcase/images/like/like-no.png" style="display: none">',
                '<label data-bind="visible: $data.title(), css: { like: $data.like }, click: $data.change">',
                    '<!--ko text: $data.title --><!--/ko-->',
                '</label>',
            '</div>',
        '</div>'
    ].join('')
});