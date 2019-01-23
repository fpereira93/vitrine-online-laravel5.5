App = App || {};
App.Auditoria = App.Auditoria || {};

//VIEWMODEL
App.Auditoria.ViewModel = function() {
    var self = this;
    self.step = ko.observable(Constants.STEPS.Listing);
    self.show = ko.observable(false);
    self.list = ko.observableArray([]);
    self.filter = ko.observable();

    self.showListing = function () {
        self.step(Constants.STEPS.Listing);
    };

    self.showDetails = function (checklist) {
        checklist.noEdit(true);
        self.show(checklist);
        self.step(Constants.STEPS.AddingNew);
    };

    self.audit = function (checklist) {
        checklist.noEdit(false);
        self.show(checklist);
        self.step(Constants.STEPS.AddingNew);
    };

    var openChecklistSuccessHandler = function(data) {
        self.show(new App.Auditoria.AuditChecklist(data.response));
        self.step(Constants.STEPS.AddingNew);
    }

    var openChecklist = function(item, event, auditoria) {
        var messageId = _hero.loading.show('Carregando dados do checklist...');
        _hero.ajax().url(_hero.routes.find('api.auditoria.details', [auditoria.ChecklistId]))
        .then(openChecklistSuccessHandler)
        .always(function() { _hero.loading.hide(messageId); })
        .execute();
    };

    self.configDataTable = {
        dataTable : ko.observable(),
        columns: [
            { name: 'ChecklistId', sWidth: '10%', text: '#'},
            { name: 'Sector', sWidth: '10%', text: 'Setor' },
            { name: 'Institute', sWidth: '10%', text: 'Instituto' },
            { name: 'Multiplier', sWidth: '10%', text: 'Multiplicador' },
            { name: 'Auditor', sWidth: '10%', text: 'Auditor' },
            { name: 'Contact', sWidth: '10%', text: 'Contato' },
            { name: 'Data', sWidth: '10%', text: 'Date' },
            { name: 'IsAudited', sWidth: '10%', text: 'Auditado?' },
            { name: 'Action', sWidth: '10%', isAction: true, className: 'center',
                edit: { canRender: function(auditoria) {return true;}, click: openChecklist },
                delete: { canRender: function(auditoria) {return false;}, click: function(item, event, auditoria) { } }
            }
        ],
        url: _hero.routes.find('api.auditoria.paginate'),
        filters: function(data) { }
    };

};

//CONSTANTS
App.Auditoria.Constants = {
    STEPS: {
        Auditing: 0,
        PartialResults: 1,
        ShowingResidues: 2,
        ShowingGoals: 3,
        ShowingActions: 4
    },
    GoalStatuses: [ 'Meta atingida', 'Meta iniciada', 'Meta não iniciada', 'Meta cancelada' ]
}


//FUNCTIONS
App.Auditoria.MakeGraphs = function(data) {
    // Highcharts.chart('perResidue', {
    //     chart: { type: 'column' },
    //     title: { text: 'Tipos de resíduos'} ,
    //     plotOptions: {
    //         bar: {
    //             allowPointSelect: true,
    //             cursor: 'pointer',
    //             dataLabels: { enabled: true, format: '{point.name}' }
    //         }
    //     },
    //     series: [{
    //         type: 'column',
    //         name: 'Resíduos auditados',
    //         data: data.auditedResidues
    //     },{
    //         type: 'column',
    //         name: 'Quantidade de Resíduos',
    //         data: data.residues
    //     }]
    // });

    // Highcharts.chart('perGoal', {
    //     chart: { type: 'column' },
    //     title: { text: 'Metas'} ,
    //     plotOptions: {
    //         bar: {
    //             allowPointSelect: true,
    //             cursor: 'pointer',
    //             dataLabels: { enabled: true, format: '{point.name}' }
    //         }
    //     },
    //     series: data.perGoal
    // });
};