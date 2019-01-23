App.Auditoria.Residue = function(residue) {
    var self = this;

    residue = residue || {};
    self.ChecklistResidueId = residue.ChecklistResidueId;
    self.ResidueType = ko.observable(residue.ResidueType);
    self.Segregation = ko.observable(residue.Segregation);
    self.QuantityFound = ko.observable(residue.QuantityFound);
    self.QuantityAudited = ko.observable(residue.QuantityAudited);
    self.Identification = ko.observable(residue.Identification);
    self.Treatment = ko.observable(residue.Treatment);
    self.Transport = ko.observable(residue.Transport);
    self.Law = ko.observable(residue.Law);
    
    var mappedGoals = residue.goals.map(function(goal) {
        return new App.Auditoria.Goal(goal);
    });
    self.Goals = ko.observableArray(mappedGoals);
    self.original = ko.toJS(self);

    self.auditedGoals = ko.computed(function() {
        return self.Goals().filter(function(it) {
            return it.isAudited() || it.isCanceled();
        });
    });
    self.progress = ko.pureComputed(function(){
        return (self.auditedGoals().length / self.Goals().length) * 100;
    });
};