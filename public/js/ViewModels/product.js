App = App || {};

App.Product = App.Product || {};

App.Product.autocomplete = function(conf){
    var self = this;

    self.value = ko.observable();
    self.source = function(searchTerm, callback) {

        var _custom = function(server){
            callback(server.status ? server.response.map(conf.customResponse) : []);
        };

        $.ajax({
            dataType: "json",
            headers: _hero.routes.headers(),
            url: conf.url,
            data: { query: searchTerm },
        }).done(_custom);
    }
};

App.Product.Container = function(container){
    var self = this;

    self.id = ko.observable(container.idContainer);
    self.name = ko.observable(container.name);
    self.checked = ko.observable(false);
};

App.Product.Image = function(urlImageInitial, fileId, showAttachImage){
    var self = this;

    self.fileId = fileId;
    self.urlImageInitial = urlImageInitial;
    self.pictureCropper = ko.observable();
    self.fileSelected = ko.observable();
    self.checked = ko.observable(false);
    self.showAttachImage = ko.observable(showAttachImage);
};

App.Product.ViewModel = function () {
    var self = this;

    self.completeCategory = new App.Product.autocomplete({
        url: _hero.routes.find("api.category.autocomplete"),
        customResponse: function(data){
            return {
                label: data.name,
                input: data.name,
                value: data.idCategory
            };
        }
    });

    self.completeBrand = new App.Product.autocomplete({
        url: _hero.routes.find("api.brand.autocomplete"),
        customResponse: function(data){
            return {
                label: data.name,
                input: data.name,
                value: data.idBrand
            };
        }
    });

    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable();
    self.showingDetails = ko.observable();
    self.product = ko.observable();
    self.containers = ko.observableArray();
    self.images = ko.observableArray();
    self.imagesDeleted = [];
    self.lastImageSelected = null;

    self.clearData = function(){
        self.completeBrand.value(null);
        self.completeCategory.value(null);

        self.containers().forEach(function(container){
            container.checked(false);
        });

        self.images.removeAll();
        self.imagesDeleted = [];
        self.lastImageSelected = null;
    };

    self.new = function(){
        self.getContainers(function(){
            self.clearData();
            self.step(Constants.STEPS.AddingNew);
            self.product(new App.Product.ProductModel());
        });
    };

    self.setContainers = function(ids){
        self.containers().forEach(function(container){
            var index = ids.indexOf(container.id());
            container.checked(index > -1);
        });
    };

    self.addMostImage = function(){
        var image = new App.Product.Image(null, null, true);

        if (!self.images().length){
            self.onCheckImagem(image);
        }

        self.images.push(image);
    };

    self.removeImage = function(image){
        if (image.fileId){
            self.imagesDeleted.push(image.fileId);
        }

        self.images.remove(image);

        if (image.checked() == 1){
            self.lastImageSelected = null;

            if (self.images().length){
                self.onCheckImagem(self.images()[0]);
            }
        }
    };

    self.onCheckImagem = function(image){
        if (self.lastImageSelected){
            self.lastImageSelected.checked(0);
        }

        image.checked(1);
        self.lastImageSelected = image;

        return true;
    };

    self.edit = function(product){
        self.clearData();

        self.getContainers(function(){

            self.getDetailProduct(product.idProduct(), function(product){
                self.product(new App.Product.ProductModel(product));

                self.completeCategory.value({
                    label: product.category.name,
                    input: product.category.name,
                    value: product.category.idCategory
                });

                self.completeBrand.value({
                    label: product.brand.name,
                    input: product.brand.name,
                    value: product.brand.idBrand
                });

                self.step(Constants.STEPS.Editing);

                product.images.forEach(function(image){
                    var _image = new App.Product.Image(image.url, image.fileId, false);

                    self.images.push(_image);

                    if (image.checked){
                        self.onCheckImagem(_image);
                    }
                });

                self.setContainers(product.containers.map(function(container){
                    return container.idContainer;
                }));
            });

        });
    };

    self.getContainers = function(onFinish){
        if (self.containers().length) {
            onFinish();
            return;
        }

        _hero.ajax()
            .route('api.product.containers')
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    data.response.forEach(function(container){
                        self.containers.push(new App.Product.Container(container));
                    });
                    onFinish();
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            }).execute();
    };

    self.getDetailProduct = function(id, callback){
        var loading = _hero.loading.show('Detalhes do produto');

        _hero.ajax()
            .route('api.product.detail', { id: id })
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    callback(data.response);
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            })
            .always(function (data) {
                _hero.loading.hide(loading);
            }).execute();
    };

    self.delete = function(product){
        var loading = _hero.loading.show('Deletando produto');

        _hero.ajax()
            .route('api.product.delete', { id: product.idProduct })
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    self.configDataTable.dataTable().ajax.reload();
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            })
            .always(function (data) {
                _hero.loading.hide(loading);
            }).execute();
    };

    self.back = function(){
        self.step(Constants.STEPS.Listing);
    };

    self.request = function(){

        var images = self.images().filter(function(image){
            return image.fileSelected();
        }).map(function(image){

            var info = _hero.file.infor(image.fileSelected());

            return {
                checked: image.checked() ? 1 : 0,
                OriginalName: info.name,
                File: image.pictureCropper(),
                Size: info.size,
                MimeType: info.type,
            };
        });

        var category = self.completeCategory.value();
        var brand = self.completeBrand.value();

        return {
            idProduct: self.product().idProduct(),
            name: self.product().name(),
            description: self.product().description(),
            stock: parseInt(self.product().stock()),
            price: parseFloat(self.product().price()),
            category: category ? category.value : null,
            brand: brand ? brand.value : null,
            images: images,
            imagesDeleted: self.imagesDeleted,
            containers: self.containers().filter(function(container){
                return container.checked();
            }).map(function(container){
                return container.id();
            }),
            mainImage: self.lastImageSelected
        };
    };

    self.removeImageNotSelected = function(){
        self.images().forEach(function(image){
            if (!image.fileId && !image.fileSelected()){
                self.removeImage(image);
            }
        });
    };

    self.save = function(){
        self.removeImageNotSelected();

        var request = self.request();
        var loading = _hero.loading.show('Salvando produto');

        if (self.step() == Constants.STEPS.Editing){
            var route = 'api.product.update';
        } else {
            var route = 'api.product.create';
        }

        _hero.ajax()
            .route(route, [ self.product().idProduct() ])
            .payload(request)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    _hero.toastr.success(data.message);
                    self.back();
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            })
            .always(function (data) {
                _hero.loading.hide(loading);
            }).execute();
    };

    self.configDataTable = {
        dataTable: ko.observable(),
        columns: [
            {
                name: 'idProduct',
                sWidth: '10%',
                text: 'Código'
            },
            {
                name: 'name',
                sWidth: '50%',
                text: 'Nome'
            },
            {
                name: 'likes',
                sWidth: '20%',
                text: 'Curtidas',
            },
            {
                text: 'Ação',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function (product) {
                        return true;
                    },
                    click: function (item, event, product) {
                        self.edit(new App.Product.ProductModel(product));
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (product) {
                        return true;
                    },
                    click: function (item, event, product) {
                        self.delete(product);
                    }
                }
            }
        ],
        url: _hero.routes.find("api.product.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'idProduct',
                    value: null,
                    type: 'equal'
                },
                {
                    column: 'name',
                    value: null,
                    type: 'like'
                }
            ];
        }
    };
};

App.Product.ProductModel = function (product) {
    var self = this;

    product = product || {};

    self.idProduct = ko.observable(product.idProduct || 0);
    self.name = ko.observable(product.name || '');
    self.description = ko.observable(product.description || '');
    self.stock = ko.observable(product.stock || 0);
    self.price = ko.observable(product.price || 0);
}