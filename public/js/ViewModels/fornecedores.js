App = App || {};
App.Provider = App.Provider || {};

App.Provider.showAjaxError = function(data){
    _hero.toastr.error(data.message);
}

App.Provider.ViewModel = function () {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);

    //TODO: remover essa parada e por dentro do sistema.
    self.states = ko.observableArray(['AC','AL','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','TO']);

    self.selectedProvider = ko.observable(null);
    self.selectedResidue = ko.observable('');
    self.acceptedResiduesOptions = ko.observableArray([]);
    self.autocompleteSelectedResidue = ko.observable('');
    self.focused = ko.observable(false);

    self.residueTypeSelected = ko.observable();
    self.dataAutocompleteOriginal = ko.observableArray();

    self.findByIdResidue = function(id){
        return self.dataAutocompleteOriginal().filter(function(item){
            return item.id == id;
        })[0];
    };

    self.confAutocomplete = {
        ajax: {
            url: _hero.routes.find('api.residues.autocomplete'),
            dataType: 'json',
            headers: _hero.routes.headers(),
            delay: 250, // wait 250 milliseconds before triggering the request
            processResults: function (data) {
                self.dataAutocompleteOriginal(data.response);

                var results = data.response.map(function(residue){
                    return {
                        "id": residue.id,
                        "text": residue.name
                    };
                });

                return {
                    results: results
                };
            }
        }
    };

    self.addResidueType = function(){
        var residueSearch = self.findByIdResidue(self.residueTypeSelected());

        if (!residueSearch){
            return;
        }

        self.selectedProvider().AcceptedResidues.push(new App.Provider.Residue({
            ResidueTypeId: residueSearch.id,
            Name: residueSearch.name
        }, false));

        self.residueTypeSelected(null);
    };

    self.infoFileSelected = null;
    self.descriptionFileUpload = ko.observable();

    self.handleFileSelected = function(handle, evt){
        self.infoFileSelected = _hero.file.infor(evt.target.files[0]);

        _hero.file.toBase64(evt.target.files[0], function(base64){
            self.infoFileSelected.base64 = base64;
        });
    };

    self.saveFile = function(){

        var doc = new App.Provider.FileDocument({
            FileId: 0,
            MimeType: self.infoFileSelected.type,
            Name: '', //generate on service
            Description: self.descriptionFileUpload(),
            OriginalName: self.infoFileSelected.name,
            File: self.infoFileSelected.base64
        }, false);

        self.selectedProvider().Docs.push(doc);
    }

    self.addProvider = function () {
        self.step(Constants.STEPS.AddingNew);
        self.selectedProvider(new App.Provider.Provider);
    }

    self.showList = function () {
        self.step(Constants.STEPS.Listing);
        self.selectedProvider(null);
    };

    self.saveProvider = function(provider) {
        var payload = JSON.parse(ko.toJSON(provider));
        var loadingId = _hero.loading.show('Salvando Fornecedor');

        var ajax = _hero.ajax()
            .payload(payload)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    _hero.toastr.success(data.message);
                    self.updateDataProvider(data.response.ProviderId);
                } else {
                    App.Provider.showAjaxError(data);
                }
            })
            .fail(App.Provider.showAjaxError)
            .always(function() {
                _hero.loading.hide(loadingId);
            })

            if (provider.ProviderId()){
                ajax.route('api.providers.update', [ provider.ProviderId() ]);
            } else {
                ajax.route('api.providers.create');
            }

        ajax.execute();
    };

    self.searchDetailProvider = function(providerId, next){
        _hero.loading.show();

        var ajax = _hero.ajax()
        .route('api.providers.get', [ providerId ])
        .then(function(data){
            if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                next(data.response);
            } else {
                App.Provider.showAjaxError(data);
            }
        })
        .fail(App.Provider.showAjaxError)
        .always(function() {
            _hero.loading.hide();
        }).execute();
    };

    self.updateDataProvider = function(providerId){
        self.searchDetailProvider(providerId, function(response){
            var providerObject = new App.Provider.Provider(response.provider);

            providerObject.AcceptedResidues(response.residues.map(function(residue){
                return new App.Provider.Residue(residue, true);
            }));

            providerObject.Docs(response.documents.map(function(_document){
                return new App.Provider.FileDocument(_document, true);
            }));

            self.selectedProvider(providerObject);
            self.step(Constants.STEPS.Editing);
        });
    };

    self.editProvider = function(provider){
        self.updateDataProvider(provider.ProviderId);
    };

    self.deleteProvider = function(provider) {
        var loadingId = _hero.loading.show(`Apagando fornecedor ${provider.SocialName}`);

        _hero.ajax()
        .route('api.providers.delete', [ provider.ProviderId ])
        .then(function(response){
            if (response.status == _hero.STATUS_RESPONSE.SUCCESS){
                _hero.toastr.success(response.message);
                self.configDataTable.dataTable().ajax.reload();
            } else {
                App.Provider.showAjaxError(response);
            }
        })
        .fail(App.Provider.showAjaxError)
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };

    self.configDataTable = {
        dataTable: ko.observable(),
        columns: [
            {
                name: 'ProviderId',
                sWidth: '10%',
                text: 'Código'
            },
            {
                name: 'SocialName',
                sWidth: '30%',
                text: 'Razão Social'
            },
            {
                name: 'FantasyName',
                sWidth: '30%',
                text: 'Nome Fantasia'
            },
            {
                name: 'CNPJ',
                sWidth: '10%',
                text: 'CNPJ'
            },
            {
                text: 'Ação',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function (provider) { return true; },
                    click: function (item, event, provider) {
                        self.editProvider(provider);
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (provider) { return true; },
                    click: function (item, event, provider) {
                        self.deleteProvider(provider);
                    }
                }
            }
        ],
        url: _hero.routes.find("api.providers.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'ProviderId',
                    value: null,
                    type: 'equal'
                },
                {
                    column: 'SocialName',
                    value: null,
                    type: 'like'
                },
                {
                    column: 'FantasyName',
                    value: null,
                    type: 'like'
                },
                {
                    column: 'CNPJ',
                    value: null,
                    type: 'equal'
                }
            ];
        }
    };
};

App.Provider.Provider = function(provider) {
    var self = this;
    provider = provider || {};

    self.ProviderId = ko.observable(provider.ProviderId);
    self.SocialName = ko.observable(provider.SocialName);
    self.FantasyName = ko.observable(provider.FantasyName);
    self.CNPJ = ko.observable(provider.CNPJ);
    self.IE = ko.observable(provider.IE);
    self.PhoneNumber = ko.observable(provider.PhoneNumber);
    self.Number = ko.observable(provider.Number);
    self.PhoneNumber2 = ko.observable(provider.PhoneNumber2);
    self.Mail = ko.observable(provider.Mail);
    self.WebSite = ko.observable(provider.WebSite);
    self.Street = ko.observable(provider.Street);
    self.District = ko.observable(provider.District);
    self.City = ko.observable(provider.City);
    self.State = ko.observable(provider.State);
    self.IncludeDate = ko.observable(provider.IncludeDate);

    self.deleteResidue = function(residue){
        if (!residue.alreadySaveData)
            self.AcceptedResidues.remove(residue);
        else
            residue.deleted(1);
    };

    self.deleteFile = function(doc){
        if (!doc.alreadySaveData)
            self.Docs.remove(doc);
        else
            doc.deleted(1);
    };

    self.AcceptedResidues = ko.observableArray(provider.AcceptedResidues || []);
    self.Docs = ko.observableArray(provider.Docs || []);
};

App.Provider.FileDocument = function(_document, alreadySaveData) {
    var self = this;

    _document = _document || {};

    self.alreadySaveData = alreadySaveData;
    self.FileId = ko.observable(_document.FileId);
    self.MimeType = ko.observable(_document.MimeType);
    self.Name = ko.observable(_document.Name || '');
    self.OriginalName = ko.observable(_document.OriginalName);
    self.File = ko.observable(_document.File || '');
    self.Description = ko.observable(_document.Description || '');

    self.deleted = ko.observable(0);
};

App.Provider.Residue = function(accepted, alreadySaveData) {
    var self = this;

    accepted = accepted || {};

    self.alreadySaveData = alreadySaveData;
    self.ResidueTypeId = ko.observable(accepted.ResidueTypeId || 0);
    self.Name = ko.observable(accepted.Name || '');
    self.LawObservations = ko.observable(accepted.LawObservations || '');

    self.deleted = ko.observable(0);
};
