App = App || {};
App.Checklist = App.Checklist || {};
App.Checklist.Showing_Residues = 0;
App.Checklist.Showing_Residue_Details = 1;
App.Checklist.Showing_Goals = 2;

App.FindValue = function(array, id) {
    id = ko.utils.unwrapObservable(id);
    array = ko.utils.unwrapObservable(array);
    return (ko.utils.arrayFirst(array, function(item) {
        return item.id == id;
    }) || {text: ''}).text;
};

App.Checklist.ViewModel = function () {
    var self = this;
    
    //Flow
    self.step = ko.observable(Constants.STEPS.Listing);
    self.substep = ko.observable(null);
    self.editing = ko.observable();
    self.goal = ko.observable();
    self.showingDetails = ko.observable();
    self.selectedResidue = ko.observable();
    self.showingLawDetails = ko.observable(false);
    
    //Autocompletes
    self.users = ko.observableArray([]);
    self.institutes = ko.observableArray([]);
    self.sectors = ko.observableArray([]);
    self.residues = ko.observableArray([]);
    self.derivations = ko.observableArray([]);

    self.goals = [
        {id: 1, text: 'REDUZIR' },
        {id: 2, text: 'RECICLAR' },
        {id: 3, text: 'REPENSAR' },
        {id: 4, text: 'REUTILIZAR' }
    ];

    self.deadlineType = [
        {id: 1, text: 'DIA(S)'},
        {id: 2, text: 'SEMANA(S)'},
        {id: 3, text: 'MES(ES)'}
    ];

    self.quantityTypes = [
        {id: 1, text: 'LITRO(S)' },
        {id: 2, text: 'QUILO(S)' }
    ];

    self.breadcrumb = ko.pureComputed(function() {
        var result = [];
        if(self.step() != Constants.STEPS.ShowingDetails && self.step() != Constants.STEPS.AddingNew) {
            return [];
        } else {
            result.push(self.step() == Constants.STEPS.ShowingDetails ? 'Adding New' : 'Editing');
        }
        
        result.push('Residues');
        if(self.substep() == App.Checklist.Showing_Residues) {
            return result;
        }

        if (self.substep() == App.Checklist.Showing_Residue_Details) {
            result.push(self.selectedResidue().type);
            return result;
        }

        return ['Checklist'];

    });

    self.residuesAvaiable = ko.pureComputed(function() {
        if (!self.editing()) {
            return self.residues();
        }
        var added = self.editing().residues();
        return ko.utils.arrayFilter(self.residues(), function(item) {
            return ko.utils.arrayFilter(added, function(addedItem) {
                return addedItem.type() == item.id;
            }).length == 0;
        });
    });
    
    var autocompletesToLoad = [
        {url: _hero.routes.find('api.users.autocomplete'), observable: self.users },
        {url: _hero.routes.find('api.institutes.autocomplete'), observable: self.institutes },
        {url: _hero.routes.find('api.residues.autocomplete'), observable: self.residues }
    ];

    self.loadInitialData = function(toLoad) {
        var current = toLoad.shift();
        if (!current) return;
        self.loadInitialData(toLoad);
        _hero.ajax()
        .url(current.url)
        .then(function(data) {
            current.observable(data.response.map(function(item){ return new ko.SelectItem(item.id, item.name) }));
        })
        .execute();
    };
    self.loadInitialData(autocompletesToLoad);

    self.editing.subscribe(function(newValue) {
        if (newValue && newValue.institute()) {
            newValue.institute.subscribe(function(instituteValue) {
                self.InstituteAutoCompleteLoad(instituteValue);
            });
            self.InstituteAutoCompleteLoad(newValue.institute());
        }
    })

    self.InstituteAutoCompleteLoad = function(instituteValue) {
        if (!instituteValue) return;
        self.loadInitialData([{url: _hero.routes.find('api.institutes.sectors.autocomplete', [instituteValue]), observable: self.sectors}]);
    };

    self.showListing = function () {
        self.step(Constants.STEPS.Listing);
        self.substep(null);
    };

    self.addChecklist = function () {
        self.editing(new App.Checklist.Checklist({ Date: moment().format('YYYY-MM-DD') }));
        self.step(Constants.STEPS.AddingNew);
        self.substep(App.Checklist.Showing_Residues);
    };

    self.hasErrors = function(checklist) {
        checklist = ko.toJS(checklist);

        if (!checklist.date) return 'Preencha a data!';
        if (!checklist.institute) return 'Preencha o instituto';
        if (!checklist.sector) return 'Preencha o setor';
        if (!checklist.contact) return 'Selecione um contato';
        if (!checklist.multiplier) return 'Selecione um multiplicador';
        if (!checklist.auditor) return 'Selecione um auditor';
        if (!checklist.residues.length) return 'Adicione ao menos um resíduo';

        return false;
    };

    self.saveChecklist = function (checklist) {
        var loadingId = _hero.loading.show('Salvando checklist...');

        var payload = new App.Checklist.JsonModel(ko.toJS(checklist));

        var route = !payload.checklistId
            ? 'api.checklist.create'
            : 'api.checklist.update';

        _hero.ajax().route(route, [payload.checklistId]).payload(payload)
        .then(function(data){
            if (data.status == 1) {
                _hero.toastr.success(data.message);
                self.step(Constants.STEPS.Listing);
            } else {
                _hero.toastr.error(data.message);
            }
        })
        .fail(function() {
            _hero.toastr.error('Falha ao salvar o checklist');
        })
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };

    self.show = function (checklist) {
        self.step(Constants.STEPS.ShowingDetails);
        self.editing(checklist);
    };

    self.showRequisites = function (checklist) {
        self.step(App.Checklist.Constants.STEPS.ShowingLaws);
        self.editing(checklist);
    };

    self.showLawDetails = function (law) {
        if (self.showingLawDetails() == law) {
            self.showingLawDetails(false);
        } else {
            self.showingLawDetails(law);
        }
    };

    self.delete = function(checklist) {
        self.checklists.remove(self);
    };

    self.toggleResidues = function(residue) {
        if (self.showingDetails() == residue) {
            viewModel.showingDetails(false);
        }
        else {
            viewModel.showingDetails(residue);
        }
    };

    self.addResidue = function(id) {
        var residue = ko.utils.arrayFirst(self.residues(), function(item){
            return item.id == id;
        });

        if (!residue) { return; }

        var exists =  ko.utils.arrayFirst(self.editing().residues(), function(item){
            return item.type() == id;
        });

        if (exists) { return; }

        self.editing().residues.push(new App.Checklist.Residue( { ResidueTypeId: residue.id } ));

        self.selectedResidue(null);
    };

    self.editResidue = function(data) {
        self.selectedResidue(data);
        self.substep(App.Checklist.Showing_Residue_Details);
    };

    self.showResidues = function() {
        self.substep(App.Checklist.Showing_Residues);
        self.selectedResidue(null);
    };

    self.getResidueName = function(id) {
        id = ko.utils.unwrapObservable(id);

        var residue = self.residues().find(function(residue) {
            return residue.id == id;
        });

        return residue ? residue.text : null;
    };

    self.getDerivationName = function(id) {
        
    };

    self.loadDerivations = function(id) {
        var config = {url: _hero.routes.find('api.residues.derivations.autocomplete', [id]), observable: self.derivations};
        self.loadInitialData([config]);
    };

    self.showGoals = function() {
        self.substep(App.Checklist.Showing_Goals);
        self.goal(new App.Checklist.Goal());
        self.loadDerivations(self.selectedResidue().type());
    };

    self.selectGoal = function(goal) {
        self.substep(App.Checklist.Showing_Goals);
        self.goal(goal);
        self.loadDerivations(self.selectedResidue().type());
    };

    self.backGoal = function() {
        self.substep(App.Checklist.Showing_Residue_Details);
    };

    self.saveGoal = function(goal) {
        var goals = self.selectedResidue().goals;
        var exists = goals.indexOf(goal) > -1;
        if (!exists){
            goals.push(goal);
        }
    };

    self.delete = function(checklist) {
        _hero.ajax().verb('DELETE').url(_hero.routes.find('api.checklist.delete', [checklist.ChecklistId])).execute();
    };

    self.configDataTable = {
        dataTable : ko.observable(),
        columns: [
            { name: 'ChecklistId', sWidth: '10%', text: '#'},
            { name: 'Sector', sWidth: '14%', text: 'Setor' },
            { name: 'Institute', sWidth: '14%', text: 'Instituto' },
            { name: 'Multiplier', sWidth: '14%', text: 'Multiplicador' },
            { name: 'Auditor', sWidth: '14%', text: 'Auditor' },
            { name: 'Contact', sWidth: '14%', text: 'Contato' },
            { name: 'Data', sWidth: '14%', text: 'Date' },
            { name: 'Action', sWidth: '14%', isAction: true, className: 'center',
                edit: { canRender: function(checklist) {return true;}, click: function(item, event, checklist) {

                    _hero.ajax().url(_hero.routes.find('api.checklist.details', [checklist.ChecklistId])).then(
                    function(data) {
                        self.editing(new App.Checklist.Checklist(data.response));
                        self.step(Constants.STEPS.AddingNew);
                        self.substep(App.Checklist.Showing_Residues);
                    }).execute();

                 } },
                delete: { canRender: function(checklist) {return true;}, click: function(item, event, checklist) { 
                    self.delete(checklist);
                } }
            }
        ],
        url: _hero.routes.find('api.checklist.paginate'),
        filters: function(data) { }
    };  
};

App.Checklist.Checklist = function(checklist) {
    var self = this;

    checklist = checklist || {}
    checklist.residues = checklist.residues || [];

    self.id = checklist.ChecklistId;
    self.sector = ko.observable(checklist.SectorId || 1);
    self.institute = ko.observable(1);
    self.date = ko.observable(moment(checklist.Date || '1900-01-01').format('YYYY-MM-DD'));
    self.multiplier = ko.observable(checklist.Multiplier);
    self.contact = ko.observable(checklist.Contact);
    self.auditor = ko.observable(checklist.Auditor);
    self.residues = ko.observableArray(checklist.residues.map(function(r) { return new App.Checklist.Residue(r) }));  
    self.selectedResidue = ko.observable();
    self.selectedResidueText = ko.observable();
    self.editingResidue = ko.observable();
    self.SectorName = checklist.SectorName;
    self.InstituteName = checklist.InstituteName;

    self.removeResidue = function(residue){
        self.residues.remove(residue);
    };
};

App.Checklist.Residue = function(residue) {
    var self = this;

    residue = residue || {};
    residue.goals = residue.goals || [];

    self.id = residue.ChecklistResidueId;
    self.type = ko.observable(residue.ResidueTypeId);
    self.segregation = ko.observable(residue.Segregation);
    self.quantity = ko.observable(residue.QuantityFound);
    self.quantityType = ko.observable(residue.QuantityType);
    self.identificationAndStorage = ko.observable(residue.Identification);
    self.treatment = ko.observable(residue.Treatment);
    self.transport = ko.observable(residue.Transport);
    self.law = ko.observable(residue.Law);
    self.lawDescription = ko.observable(residue.Description);
    self.goals = ko.observableArray(residue.goals.map(function(g) { return new App.Checklist.Goal(g); }) );

    self.removeGoal = function(goal) {
        self.goals.remove(goal);
    };
};

App.Checklist.Goal = function(goal) {
    var self = this;

    goal = goal || {};
    goal.actions = goal.actions || [];

    self.id = ko.observable(goal.ChecklistResidueGoalId);
    self.derivation = ko.observable(goal.ResidueDerivationId); // esse campo nao esta na migration
    self.goal = ko.observable(goal.Goal);
    self.objective = ko.observable(goal.Objective);
    self.actions = ko.observableArray(goal.actions.map(function(a) { return new App.Checklist.GoalAction(a); }));

    self.addAction = function(add) {
        if (!add) {
            return;
        }

        self.actions.push(new App.Checklist.GoalAction);
    };

    self.removeAction = function(action) {
        self.actions.remove(action);
    };
};

App.Checklist.GoalAction = function(action) {
    var self = this;

    action = action || {};

    self.id = ko.observable(action.ChecklistResidueGoalActionId);
    self.action = ko.observable(action.Action);
    self.place = ko.observable(action.Place);
    self.responsible = ko.observable(action.Responsible);
    self.deadline = ko.observable(action.Deadline);
    self.deadlineType = ko.observable(action.DeadlineType);
};

App.Checklist.JsonModel = function(checklist) {
    var self = this;
    self.auditorId = checklist.auditor;
    self.contactId = checklist.contact;
    self.date = checklist.date;
    self.instituteId = checklist.institute;
    self.multiplierId = checklist.multiplier;
    self.sectorId = checklist.sector;
    self.residues = checklist.residues;
    self.checklistId = checklist.id;
};