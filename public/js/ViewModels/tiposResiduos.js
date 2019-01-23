App = App || {};
App.TiposResiduos = App.TiposResiduos || {};

App.TiposResiduos.showAjaxError = function(data){
    _hero.toastr.error(data.message);
}

App.TiposResiduos.ViewModel = function() {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable(false);
    self.showing = ko.observable();
    self.showingDetails = ko.observable(false);
    self.addingLink = ko.observable(false);
    self.addingFile = ko.observable(false);

    self.Law = ko.observable('');
    self.Link = ko.observable('');

    self.infoFileSelected = null;

    self.addNew = function() {
        self.step(Constants.STEPS.AddingNew);

        self.showing(
            new App.TiposResiduos.ResidueType({
                ResidueTypeId: 0,
                Name: '',
                LawObservations: '',
            })
         );
    };

    self.enabledButtonDocuments = ko.computed(function(){
        return self.editing() || self.step() == Constants.STEPS.AddingNew;
    }, self);

    self.validateInputDocuments = function(){
        if (!self.Law().trim())
            return false;

        if (self.addingFile() && !self.infoFileSelected)
            return false;

        if (self.addingLink() && !self.Link().trim())
            return false;

        return true;
    };

    self.clearDataInput = function(){
        self.Law('');
        self.Link('');
        self.infoFileSelected = null;
    };

    self.cancelAddingDocument = function() {
        self.addingFile(false);
        self.addingLink(false);
    };

    self.addLink = function() {
        self.clearDataInput();
        self.addingLink(true);
        self.addingFile(false);
    };

    self.saveLink = function(link) {
        if (!self.validateInputDocuments()){
            return;
        }

        self.showing().Links().push(
            new App.TiposResiduos.Link({
                ResidueTypeDocumentsId: 0,
                Description: self.Law(),
                Link: self.Link()
            })
        );

        self.cancelAddingDocument();
    };

    self.addFile = function() {
        self.clearDataInput();
        self.addingFile(true);
        self.addingLink(false);
    };

    self.saveFile = function(){
        if (!self.validateInputDocuments()){
            return;
        }

        self.showing().Files().push(
            new App.TiposResiduos.File({
                FileId: 0,
                MimeType: self.infoFileSelected.type,
                Name: '', //generate on service
                Description: self.Law(),
                OriginalName: self.infoFileSelected.name,
                File: self.infoFileSelected.base64
            })
        );

        self.cancelAddingDocument();
    };

    self.handleFileSelected = function(handle, evt){
        self.infoFileSelected = _hero.file.infor(evt.target.files[0]);

        _hero.file.toBase64(evt.target.files[0], function(base64){
            self.infoFileSelected.base64 = base64;
        });
    };

    self.showResidue = function(residue) {
        self.showing(residue);
        self.step(Constants.STEPS.ShowingDetails);

        self.setDocumentsResidue(residue);
    };

    self.showList = function() {
        self.step(Constants.STEPS.Listing);
        self.editing(false);
        self.showing(false);
        self.showingDetails(false);
        self.addingLink(false);
        self.addingFile(false);
    };

    self.edit = function() {
        self.editing(!self.editing());
    };

    self.saveDocuments = function(residue){
        var storeDataDocuments = ko.toJS({
            ResidueTypeId: residue.ResidueTypeId,
            files: residue.Files(),
            links: residue.Links()
        });

        var loading = _hero.loading.show('Salvando Documentos...');

        return _hero.ajax()
            .url(_hero.routes.find("api.residues.storeDataDocuments"))
            .verb('POST')
            .payload(storeDataDocuments)
            .always(function() {
                _hero.loading.hide(loading);
            })
        .fail(App.TiposResiduos.showAjaxError);
    };

    self.getOptionsSaveResiduos = function(id) {
        if (self.editing()) {
            return {
                url: _hero.routes.find("api.residues.update", [ id ]),
                method: "PUT"
            };
        }

        return {
            url: _hero.routes.find("api.residues.create"),
            method: "POST"
        };
    }

    self.saveResidue = function(residue) {
        var id = _hero.loading.show('Salvando resíduo...');
        var options = self.getOptionsSaveResiduos( residue.ResidueTypeId() );

        var payload = {
            Name: self.showing().Name,
            LawObservations: self.showing().LawObservations
        };

        _hero.ajax().url(options.url)
        .verb(options.method)
        .payload(payload)
        .then(function(data){
            if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                residue.ResidueTypeId(data.response.ResidueTypeId);

                self.saveDocuments(residue).then(function(data){
                    if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                        self.editing(true);
                        _hero.toastr.success("Resíduo salvo com sucesso!");
                        self.setDocumentsResidue(residue);
                    } else {
                        App.TiposResiduos.showAjaxError(data);
                    }
                }).execute();

            } else {
                App.TiposResiduos.showAjaxError(data);
            }
        })
        .always(function() {
            _hero.loading.hide(id);
        })
        .fail(App.TiposResiduos.showAjaxError)
        .execute();
    };

    self.deleteResidueType = function(ResidueTypeId, onSuccess){
        var loading = _hero.loading.show('Deletando Registro...');

        _hero.ajax()
            .url(_hero.routes.find("api.residues.destroy", [ ResidueTypeId ]))
            .verb('DELETE')
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                    onSuccess(data);
                } else {
                    App.TiposResiduos.showAjaxError(data);
                }
            })
            .always(function() {
                _hero.loading.hide(loading);
            })
            .fail(App.TiposResiduos.showAjaxError)
            .execute();
    };

    self.setDocumentsResidue = function(residue){
        var loading = _hero.loading.show('Buscado Documentos...');

        _hero.ajax()
            .url(_hero.routes.find("api.residues.documents", { id: residue.ResidueTypeId() }))
            .verb('GET')
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){

                    residue.Files(data.response.files.map(function(document){
                        return new App.TiposResiduos.File(document);
                    }));

                    residue.Links(data.response.links.map(function(link){
                        return new App.TiposResiduos.Link(link);
                    }));
                }
            })
        .always(function() {
            _hero.loading.hide(loading);
        })
        .fail(App.TiposResiduos.showAjaxError)
        .execute();
    };

    self.configDataTable = {
        dataTable: ko.observable(),
        pageLength: 50,
        columns: [
            {
                name : 'ResidueTypeId' ,
                sWidth: '10%',
                text: 'Código'
            },
            {
                name : 'Name',
                sWidth: '20%',
                text: 'Nome'
            },
            {
                name : 'LawObservations',
                sWidth: '50%',
                text: 'Observação'
            },
            {
                text: 'Ação',
                sWidth: '30%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function(residueType){
                        return true;
                    },
                    click: function(item, event, residueType){
                        self.showResidue(new App.TiposResiduos.ResidueType(residueType));
                        self.editing(true);
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro ?',
                    canRender: function(residueType){
                        return true;
                    },
                    click: function(item, event, residueType){
                        self.deleteResidueType(residueType.ResidueTypeId, function(data){
                            self.configDataTable.dataTable().ajax.reload();
                        });
                    }
                }
            }
        ],

        url: _hero.routes.find("api.residues.paginate"),

        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'ResidueTypeId',
                    value: null, //colocar depois algum filtro
                    type: 'equal'
                },
                {
                    column: 'Name',
                    value: null, //colocar depois algum filtro
                    type: 'like'
                },
                {
                    column: 'LawObservations',
                    value: null, //colocar depois algum filtro
                    type: 'like'
                }
            ];
        }
    };

};

App.TiposResiduos.ResidueType = function(type) {
    var self = this;

    self.ResidueTypeId = ko.observable();
    self.Name = ko.observable();
    self.LawObservations = ko.observable();

    self.Files = ko.observableArray([]);
    self.Links = ko.observableArray([]);

    self.confirmationDelete = function(message, onAccept){
        _hero.confirmation({
            title: 'Confirmar operação',
            body: message,
            buttons: _hero.confirmation.defaultConfirmationButtons({
                onAccept: onAccept,
                onAbort: function() { }
            })
        });
    };

    self.deleteFile = function(file){
        self.confirmationDelete('Deseja realmente deletar o arquivo ?', function(){
            file.deleted(1);
        });
    };

    self.deleteLink = function(link){
        self.confirmationDelete('Deseja realmente deletar o link ?', function(){
            link.deleted(1);
        });
    };

    var _construct = function(type) {
        self.ResidueTypeId(type.ResidueTypeId || 0);
        self.Name(type.Name || "");
        self.Files(type.Files || []);
        self.LawObservations(type.LawObservations || "");
    };

    _construct(type || {});
};

App.TiposResiduos.File = function(file) {
    var self = this;

    file = file || {};

    self.FileId = ko.observable(file.FileId);
    self.MimeType = ko.observable(file.MimeType);
    self.Name = ko.observable(file.Name || '');
    self.OriginalName = ko.observable(file.OriginalName);
    self.File = ko.observable(file.File || '');
    self.Description = ko.observable(file.Description || '');

    self.deleted = ko.observable(0);
};

App.TiposResiduos.Link = function(link) {
    var self = this;

    self.ResidueTypeDocumentsId = ko.observable(link.ResidueTypeDocumentsId);
    self.Description = ko.observable(link.Description);
    self.Link = ko.observable(link.Link);

    self.deleted = ko.observable(0);
};
