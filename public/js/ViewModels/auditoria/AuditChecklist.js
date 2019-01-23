App.Auditoria.AuditChecklist = function(audit) {
    var self = this;
    self.auditStep = ko.observable(App.Auditoria.Constants.STEPS.Auditing);
    self.auditSubStep = ko.observable(App.Auditoria.Constants.STEPS.ShowingResidues);
    self.selectedResidue = ko.observable(false);
    self.selectedGoal = ko.observable(false);
    self.selectedAction = ko.observable(false);
    self.noEdit = ko.observable(false);

    audit = audit || {};

    self.ChecklistId = ko.observable(audit.ChecklistId);
    self.Sector = ko.observable(audit.SectorName);
    self.Institute = ko.observable(audit.InstituteName);
    self.Multiplier = ko.observable(audit.Multiplier);
    self.MultiplierName = ko.observable(audit.MultiplierName);
    self.Contact = ko.observable(audit.Contact);
    self.ContactName = ko.observable(audit.ContactName);
    self.Date = ko.observable(audit.Date);
    self.Auditor = ko.observable(audit.Auditor);
    self.AuditorName = ko.observable(audit.AuditorName);
    self.IsAudited = ko.observable(audit.IsAudited);

    var mappedResidues = audit.residues.map(function(residue) {
        return new App.Auditoria.Residue(residue);
    });
    
    self.Residues = ko.observableArray(mappedResidues);
    self.original = ko.toJS(self);

    self.showAuditing = function() {
        self.auditStep(App.Auditoria.Constants.STEPS.Auditing);
        self.auditSubStep(App.Auditoria.Constants.STEPS.ShowingResidues);
    };

    self.showGoalsForResidue = function(residue) {
        self.auditSubStep(App.Auditoria.Constants.STEPS.ShowingGoals);
        self.selectedResidue(residue);
    };

    self.showActionsForGoal = function(goal) {
        self.selectedGoal(goal);
        self.auditSubStep(App.Auditoria.Constants.STEPS.ShowingActions);
    };

    self.showActionDetails = function(action) {
        self.selectedAction(action);
    };

    self.hideActionDetails = function() {
        self.selectedAction(false);
    }

    self.showPartialResults = function() {
        self.auditStep(App.Auditoria.Constants.STEPS.PartialResults);
        App.Auditoria.MakeGraphs(prepareData());
    };

    function prepareData() {
        return {
            residues: self.residues().map(function(r) {
                return [r.type(), r.quantity()];
            }),
            auditedResidues: self.residues().map(function(r) {
                return [r.type(), parseFloat(r.quantityAudited()) || 0];
            }),
            perGoal: preparePerGoalData()
        };
    };
    function preparePerGoalData() {
        var goalData = [];
        App.Auditoria.Constants.GoalStatuses.forEach(function(st) {
            goalData.push({
                name: st,
                data: [0]
            });
        });
        self.residues().forEach(function(r) {
            r.goals().forEach(function(g) {
                goalData[App.Auditoria.Constants.GoalStatuses.indexOf(g.goalStatus())].data[0]++;
            });
        });
        return goalData;
    }
};