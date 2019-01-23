App = App || {};

App.Category = App.Category || {};

App.Category.ViewModel = function () {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable();
    self.showingDetails = ko.observable();
    self.category = ko.observable();

    self.new = function(){
        self.step(Constants.STEPS.AddingNew);
        self.category(new App.Category.CategoryModel);
    };

    self.edit = function(category){
        self.category(category);
        self.step(Constants.STEPS.Editing);
    };

    self.delete = function(category){
        var loading = _hero.loading.show('Deletando categoria');

        _hero.ajax()
            .route('api.category.delete', { id: category.idCategory })
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
            idCategory: self.category().idCategory(),
            name: self.category().name(),
            description: self.category().description()
        };
    };

    self.save = function(){
        var request = self.request();

        var loading = _hero.loading.show('Salvando categoria');

        if (self.step() == Constants.STEPS.Editing){
            var route = 'api.category.update';
        } else {
            var route = 'api.category.create';
        }

        _hero.ajax()
            .route(route, [ self.category().idCategory() ])
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
                name: 'idCategory',
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
                    canRender: function (category) {
                        return true;
                    },
                    click: function (item, event, category) {
                        self.edit(new App.Category.CategoryModel(category));
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (category) {
                        return true;
                    },
                    click: function (item, event, category) {
                        self.delete(category);
                    }
                }
            }
        ],
        url: _hero.routes.find("api.category.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'idCategory',
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

App.Category.CategoryModel = function (category) {
    var self = this;

    category = category || {};

    self.idCategory = ko.observable(category.idCategory || 0);
    self.name = ko.observable(category.name || '');
    self.description = ko.observable(category.description || '');
}