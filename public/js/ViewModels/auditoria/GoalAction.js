App.Auditoria.GoalAction = function(action) {
    var self = this;
    action = action || {};
    self.isAudited = ko.observable(action.isAudited);
    self.action = ko.observable(action.Action);
    self.place = ko.observable(action.Place);
    self.responsible = ko.observable(action.Responsible);
    self.deadline = ko.observable(action.Deadline);
    self.deadlineType = ko.observable(action.DeadlineType);
    self.original = ko.toJS(self);
};