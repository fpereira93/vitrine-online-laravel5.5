App = App || {};
App.Ticket = App.Ticket || {};

App.Ticket.showAjaxError = function(data){
    _hero.toastr.error(data.message);
}

App.Ticket.ViewModel = function () {
    var self = this;
    self.step = ko.observable();

    self.statusFilter = ko.observable();
    self.subjectFilter = ko.observable();
    self.show = ko.observable();
    self.step = ko.observable(Constants.STEPS.Listing);
    self.ticketView = ko.observable();

    self.add = function () {
        self.step(Constants.STEPS.AddingNew);
        self.show(new App.Ticket.TicketCreate);
    };

    self.filter = function(){
        self.configDataTable.dataTable().ajax.reload();
    };

    self.executeAjax = function(ticket, messageLoading, route){
        _hero.loading.show(messageLoading);

        var request = {
            Message: ticket.text()
        };

        _hero.ajax()
            .route(route, { id: ticket.TicketId() })
            .payload(request)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                    self.showDetails(ticket.TicketId()); //refresh
                    _hero.toastr.success(data.message);
                } else {
                    App.Ticket.showAjaxError(data);
                }
            }).always(function() {
                _hero.loading.hide();
            })
            .fail(App.Ticket.showAjaxError).execute();
    };

    self.confimation = function(message, onAccept){
        _hero.confirmation({
            title: 'Confirmar operação',
            body: message,
            buttons: _hero.confirmation.defaultConfirmationButtons({
                onAccept: onAccept,
                onAbort: function() { }
            })
        });
    };

    self.answerTicket = function(ticket){
        self.executeAjax(ticket, 'Salvando Resposta ...', 'api.ticket.answerTicket');
    };

    self.closeTicket = function(ticket){
        self.confimation('Deseja realmente fechar o Ticket ?', function(){
            self.executeAjax(ticket, 'Fechando Ticket ...', 'api.ticket.closeTicket');
        });
    };

    self.assumeTicket = function(ticket){
        self.confimation('Deseja realmente assumir o Ticket ?', function(){
            self.executeAjax(ticket, 'Assumindo Ticket ...', 'api.ticket.takeTicket');
        });
    };

    self.makeTicket = function (ticket) {
        _hero.loading.show('Salvando ticket...');

        var request = {
            TicketSubjectId: ticket.subject().TicketSubjectId,
            Message: ticket.message()
        };

        _hero.ajax()
            .route('api.ticket.create')
            .payload(request)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                    _hero.toastr.success(data.message);
                    self.configDataTable.dataTable().ajax.reload();
                } else {
                    App.Ticket.showAjaxError(data);
                }
            }).always(function() {
                _hero.loading.hide();
            })
            .fail(App.Ticket.showAjaxError).execute();
    };

    self.showListing = function () {
        self.step(Constants.STEPS.Listing);
        self.show(false);
    }

    self.showDetails = function (TicketId, permission) {
        self.step(Constants.STEPS.ShowingDetails);

        _hero.ajax().route('api.ticket.get', [ TicketId ]).then(function(data){
            if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                self.ticketView(new App.Ticket.TicketView(data.response));
                $('#overflow-messages').scrollTop(1000000);
            }
        }).execute();
    }

    self.configDataTable = {
        dataTable: ko.observable(),
        pageLength: 50,
        columns: [
            {
                name : 'TicketId' ,
                sWidth: '10%',
                text: 'Código'
            },
            {
                name : 'name',
                sWidth: '40%',
                text: 'Quem Abriu ?'
            },
            {
                name: 'SubjectDescription',
                sWidth: '30%',
                text: 'Assunto'
            },
            {
                text: 'Ação',
                sWidth: '20%',
                className: 'center',
                components: [
                    {
                        canRender: function(data){
                            return true;
                        },
                        template: '<button-table-view data-bind="click: onClick"></button-table-view>',
                        modelView: {
                            onClick: function(item, event, data){
                                self.showDetails(data.TicketId);
                            }
                        }
                    }
                ]
            }
        ],

        url: _hero.routes.find("api.ticket.paginate"),

        filters: function (aoData) {
            aoData.filters = [];

            if (self.subjectFilter().TicketSubjectId){
                aoData.filters.push({
                    column: 'TicketSubjectId',
                    value: self.subjectFilter().TicketSubjectId,
                    type: 'equal'
                });
            }

            if (self.statusFilter().StatusId){
                aoData.filters.push({
                    column: 'StatusId',
                    value: self.statusFilter().StatusId,
                    type: 'equal'
                });
            }
        }
    };

};

App.Ticket.TicketViewHistory = function(dataDb) {
    var self = this;

    self.HistoryId = ko.observable(dataDb.HistoryId);
    self.Description = ko.observable(dataDb.Description);
    self.UserAction = ko.observable(dataDb.user_action.id);
    self.UserNameAnswer = ko.observable(dataDb.user_action.name);
    self.CreatedAt = ko.observable(dataDb.user_action.created_at);
    self.UpdatedAt = ko.observable(dataDb.user_action.updated_at);
};

App.Ticket.TicketView = function(dataDb) {
    var self = this;

    self.text = ko.observable('');

    self.TicketId = ko.observable(dataDb.ticket.TicketId);
    self.OpendBy = ko.observable(dataDb.ticket.OpendBy);

    self.permission = {
        answer : ko.observable(dataDb.permission.answer),
        take : ko.observable(dataDb.permission.take),
        close : ko.observable(dataDb.permission.close)
    };

    self.history = ko.observableArray(dataDb.ticket.history.map(function(history){
        return new App.Ticket.TicketViewHistory(history);
    }));
};

App.Ticket.TicketCreate = function() {
    var self = this;

    self.subject = ko.observable('').extend({
        required: true
    });

    self.message = ko.observable('').extend({
        minLength: 10,
        maxLength: App.Ticket.Data.MessageLengthLimit,
        required: true
    });

    self.errors = ko.validation.group(self);

    self.messageIsOverLimit = ko.computed(function () {
        return App.Ticket.Data.MessageLengthLimit < self.message().length;
    });

    self.isValid = ko.computed(function () {
        return self.errors().length == 0;
    });
};

App.Ticket.Data = {};
App.Ticket.Data.NonFilteredOption = 0;
App.Ticket.Data.WaitingUserFeedbackOption = 4;
App.Ticket.Data.InitialStatus = 1;
App.Ticket.Data.MessageLengthLimit = 300;
App.Ticket.Data.StatusTypes = ko.observableArray();
App.Ticket.Data.SubjectsOptions = ko.observableArray();

(function(){

    _hero.ajax().route('api.ticket.subjects').then(function(data){
        if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
            App.Ticket.Data.SubjectsOptions([{
                TicketSubjectId: 0,
                Description: 'Selecione uma opção'
            }].concat(data.response));
        }
    }).execute();

    _hero.ajax().route('api.ticket.status').then(function(data){
        if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
            App.Ticket.Data.StatusTypes([{
                StatusId: 0,
                Description: 'Todos'
            }].concat(data.response));
        }
    }).execute();

})();
