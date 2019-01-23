App = App || {};

App.Brand = App.Brand || {};

App.Brand.ViewModel = function () {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable();
    self.showingDetails = ko.observable();
    self.brand = ko.observable();

    self.new = function(){
        self.step(Constants.STEPS.AddingNew);
        self.brand(new App.Brand.BrandModel);
    };

    self.edit = function(brand){
        self.brand(brand);
        self.step(Constants.STEPS.Editing);
    };

    self.delete = function(brand){
        var loading = _hero.loading.show('Deletando marca');

        _hero.ajax()
            .route('api.brand.delete', { id: brand.idBrand })
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
        return {
            idBrand: self.brand().idBrand(),
            name: self.brand().name(),
            description: self.brand().description()
        };
    };

    self.save = function(){
        var request = self.request();

        var loading = _hero.loading.show('Salvando marca');

        if (self.step() == Constants.STEPS.Editing){
            var route = 'api.brand.update';
        } else {
            var route = 'api.brand.create';
        }

        _hero.ajax()
            .route(route, [ self.brand().idBrand() ])
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
                name: 'idBrand',
                sWidth: '10%',
                text: 'Código'
            },
            {
                name: 'name',
                sWidth: '70%',
                text: 'Nome'
            },
            {
                text: 'Ação',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function (brand) {
                        return true;
                    },
                    click: function (item, event, brand) {
                        self.edit(new App.Brand.BrandModel(brand));
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (brand) {
                        return true;
                    },
                    click: function (item, event, brand) {
                        self.delete(brand);
                    }
                }
            }
        ],
        url: _hero.routes.find("api.brand.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'idBrand',
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

App.Brand.BrandModel = function (brand) {
    var self = this;

    brand = brand || {};

    self.idBrand = ko.observable(brand.idBrand || 0);
    self.name = ko.observable(brand.name || '');
    self.description = ko.observable(brand.description || '');
}