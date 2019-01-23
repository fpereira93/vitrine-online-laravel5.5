App = App || {};
App.Institute = App.Institute || {};

App.Institute.Constants = {
    STEPS: { AddingSectors: 4 }
};

App.Institute.ViewModel = function () {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);
    self.show = ko.observable();
    self.isWaitingRequest = ko.observable(false);
    self.editing = ko.observable(false);

    self.institutes = ko.observableArray([]);

    self.sector = ko.observable();
    self.sectors = ko.observableArray([]);
    self.showSectors = ko.observableArray();

    self.showDetails = function (instituteId) {
        var institute = self.institutes().find(function (i) {
            return i.InstituteId() == instituteId;
        });
        self.sector(new App.Institute.Sector({ InstituteId: instituteId }));
        if (!institute) return;
        self.show(institute);
        self.step(Constants.STEPS.ShowingDetails);
    };

    self.showListing = function () {
        self.show(false);
        self.step(Constants.STEPS.Listing);
    };

    self.addNew = function (institute) {
        self.show(new App.Institute.Institute());
        self.step(Constants.STEPS.AddingNew);
    };

    var onInsert = function (data) {
        if (data.status == 1) {
            self.institutes.push(new App.Institute.Institute(data.response));
            self.showListing();
        } else {
            _hero.toastr.error(data.message);
        }
    };

    var onUpdate = function (data) {
        self.editing(false);
        if (data.status == 1) {
            _hero.toastr.success("Instituto atualizado");
            self.showListing();
        } else {
            _hero.toastr.error(data.message);
            self.editing(false);
            self.loadData(self.loadSectors);
        }
    };

    self.save = function (institute) {
        var id = _hero.loading.show('Salvando instituto...');
        var payload = ko.toJS(institute);
        delete payload.sector;
        delete payload.Sectors;
        delete payload.isWaitingRequest;
        delete payload.saveSector;
        var url = self.editing()
            ? _hero.routes.find("api.institutes.update") + payload.InstituteId
            : _hero.routes.find("api.institutes.create");
        var verb = self.editing() ? "PUT" : "POST";
        var thenAction = self.editing() ? onUpdate : onInsert;
        _hero
            .ajax()
            .url(url)
            .payload(payload)
            .verb(verb)
            .then(thenAction)
            .always(function () {
                _hero.loading.hide(id);
            })
            .execute();
    };

    self.remove = function (institute) {
        self.isWaitingRequest(true);
        var payload = ko.toJS(institute);
        _hero
            .ajax()
            .url(
            _hero.routes.find("api.institutes.destroy") + institute.InstituteId
            )
            .verb("DELETE")
            .then(function (data) {
                if (data.status == 1) {
                    self.institutes.remove(institute);
                    _hero.toastr.success("Instituto apagado com sucesso");
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .always(function (data) {
                self.isWaitingRequest(false);
            })
            .fail(function (data) {
                _hero.toastr.error((data || {}).message || "Erro ao salvar");
            })
            .execute();
    };

    self.edit = function () {
        self.editing(!self.editing());
    };

    self.addSectors = function (institute) {
        institute.sector(new App.Institute.Sector());
        self.show(institute);
        self.step(App.Institute.Constants.STEPS.AddingSectors);
    };

    self.saveSector = function (sector) {
        var institute = self.institutes().find(function (inst) {
            return inst.InstituteId() == sector.InstituteId();
        });
        _hero
            .ajax()
            .url(_hero.routes.find("api.sectors.create"))
            .verb("POST")
            .payload(ko.toJS(sector))
            .then(function (data) {
                if (data.status == 1) {
                    self.sector().SectorId(data.response.sector.SectorId);
                    self.sectors.push(self.sector());
                    institute.Sectors.push(self.sector());
                    self.sector(
                        new App.Institute.Sector({
                            InstituteId: data.response.sector.InstituteId
                        })
                    );
                    _hero.toastr.success("Salvo com sucesso");
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error((data || {}).message || "Erro ao salvar");
            })
            .execute();
    };

    self.removeSector = function (sectorId) {
        var sector = self.sectors().find(function (sector) {
            return sector.SectorId() == sectorId;
        });
        if (!sector) return;
        self.isWaitingRequest(true);
        _hero
            .ajax()
            .url(_hero.routes.find("api.sectors.destroy") + sector.SectorId())
            .verb("DELETE")
            .then(function (data) {
                if (data.status == 1) {
                    self.sectors.remove(sector);
                    self.show().Sectors.remove(sector);
                } else {
                    toastr.error("Falha ao apagar setor: " + data.message);
                }
            })
            .always(function () {
                self.isWaitingRequest(false);
            })
            .execute();
    };

    self.loadSectors = function (instituteData) {
        if (!instituteData.response.length) return;
        _hero
            .ajax()
            .url(_hero.routes.find("api.sectors.index"))
            .then(function (data) {
                if (data.status == 1) {
                    self.sectors(
                        data.response.map(function (item) {
                            return new App.Institute.Sector(item);
                        })
                    );
                    var institutes = [];
                    self.institutes().forEach(function (item) {
                        item.Sectors(
                            self.sectors().filter(function (sector) {
                                return sector.InstituteId() == item.InstituteId();
                            })
                        );
                        institutes.push(item);
                    });
                    self.institutes(institutes);
                }
            })
            .fail(function (data) {
                _hero.toastr.error("Falha ao carregar setores");
            })
            .execute();
    };

    self.loadData = function (next) {
        var id = _hero.loading.show('Carregando lista de institutos');
        _hero
            .ajax()
            .url(_hero.routes.find("api.institutes.index"))
            .then(function (data) {
                if (data.status == 1) {
                    self.institutes(
                        data.response.map(function (institute) {
                            return new App.Institute.Institute(institute);
                        })
                    );
                }
                next(data);
            })
            .fail(function (data) {
                _hero.toastr.error("Falha ao carregar institutos");
            })
            .always(function (data) {
                _hero.loading.hide(id);
            })
            .execute();
    };

    self.loadData(self.loadSectors);
    self.configDataTable = {
        dataTable: ko.observable(),
        columns: [
            {
                name: 'InstituteId',
                sWidth: '10%',
                text: 'Código'
            },
            {
                name: 'Name',
                sWidth: '50%',
                text: 'Descrição'
            }, {
                text: 'Ação',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function (institute) { return true; },
                    click: function (item, event, institute) {
                        self.showDetails(institute.InstituteId);
                        self.editing(true);
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (institute) { return true; },
                    click: function (item, event, institute) {
                        self.remove(institute);
                        //self.configDataTable.dataTable().ajax.reload();
                    }
                }
            }
        ],
        url: _hero.routes.find("api.institutes.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'InstituteId',
                    value: null,
                    type: 'equal'
                },
                {
                    column: 'Name',
                    value: null,
                    type: 'like'
                }
            ];
        }
    }
};

App.Institute.Institute = function (institute) {
    var self = this;
    var _construct = function (institute) {
        institute = institute || {};
        self.InstituteId = ko.observable(institute.InstituteId || 0);
        self.Name = ko.observable(institute.Name || "");
        self.Sectors = ko.observableArray(institute.Sectors || []);
        self.sector = ko.observable(false);
    };
    _construct(institute);

    self.isWaitingRequest = ko.observable(false);
};

App.Institute.Sector = function (sector) {
    var self = this;
    sector = sector || {};
    self.SectorId = ko.observable(sector.SectorId);
    self.InstituteId = ko.observable(sector.InstituteId);
    self.Name = ko.observable(sector.Name);
};
