App.Auditoria.Goal = function(goal) {
    var self = this;
    
    goal = goal || {};
    self.derivation = ko.observable(goal.Derivation);
    self.goal = ko.observable(goal.Goal);
    self.objective = ko.observable(goal.Objective);
    self.actions = ko.observableArray(goal.actions.map(function(item) { return new App.Auditoria.GoalAction(item); }) || []);
    self.isCanceled = ko.observable(goal.isCanceled);
    self.original = ko.toJS(self);

    self.goalStatus = ko.pureComputed(function() {
        if (self.isCanceled()) return App.Auditoria.Constants.GoalStatuses[3];
        if (self.auditedActions().length == 0) {
            return App.Auditoria.Constants.GoalStatuses[2];
        }
        if (self.auditedActions().length == self.actions().length) {
            return App.Auditoria.Constants.GoalStatuses[0];
        }
        return App.Auditoria.Constants.GoalStatuses[1];
    });

    self.auditedActions = ko.pureComputed(function() {
        return self.actions().filter(function(it) {
            return it.isAudited();
        });
    });

    self.isAudited = ko.pureComputed(function() {
        return self.auditedActions().length == self.actions().length;
    });

    self.progress = ko.pureComputed(function() {
        return (self.auditedActions().length / self.actions().length) * 100;
    });
};