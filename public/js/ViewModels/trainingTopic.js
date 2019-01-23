App = App || {};
App.TrainingTopic = App.TrainingTopic || {};

App.TrainingTopic.ViewModel = function() {
    var self = this;
    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable(null);
    self.url = {
        base: 'api.training.topics.',
        create: function() { return _hero.routes.find(`${this.base}create`)},
        update: function(id) { return _hero.routes.find(`${this.base}update`, [id])},
        delete: function(id) { return _hero.routes.find(`${this.base}delete`, [id])},
        index: function() { return _hero.routes.find(`${this.base}index`)},
        paginate: function(){return _hero.routes.find(`${this.base}paginate`)}
    };
    self.addNew = function() {
        self.editing(new App.TrainingTopic.Topic());
        self.step(Constants.STEPS.AddingNew);
    };
    self.saveTopic = function(topic) {
        var loadingId = _hero.loading.show('Salvando dados do tópico');
        var payload = ko.toJS(topic);
        _hero.ajax()
        .url(payload.TrainingTopicId ? self.url.update(payload.TrainingTopicId) : self.url.create())
        .verb(payload.TrainingTopicId ? 'PUT' : 'POST')
        .payload(payload)
        .then(function(data) {
            if( data.status == 1) {
                self.showListing();
                self.editing(null);
                _hero.toastr.success(data.message);
            } else {
                _hero.toastr.error(data.message);
            }
        })
        .fail(function(data) {
        })
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };
    self.deleteTopic = function(id) {
        var loadingId = _hero.loading.show('Apagando informações do tópico');
        _hero.ajax()
        .url(self.url.delete(id))
        .verb('DELETE')
        .then(function(data) {
            _hero.toastr.success(data.message);
        })
        .fail(function(data) {
            _hero.toastr.error(data.message);
        })
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };
    self.editTopic = function(topic) {
        self.editing(new App.TrainingTopic.Topic(topic));
        self.step(Constants.Editing);
    };
    self.showListing = function() {
        self.step(Constants.STEPS.Listing);
    };
    self.configDataTable = {
        dataTable : ko.observable(),
        columns: [
            {
                name: 'TrainingTopicId',
                sWidth: '20%',
                text: 'Código'
            }, {
                name: 'Description',
                sWidth: '60%',
                text: 'Descrição'
            }, {
                name: 'Action',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function(topic) {return true;},
                    click: function(item, event, topic) {
                        self.editTopic(topic);
                    }
                },
                delete: {
                    canRender: function(topic) {return true;},
                    click: function(item, event, topic) {
                        self.deleteTopic(topic);
                    }
                }
            }
        ],
        url: self.url.paginate(),
        filters: function(data) {
            data.filters = [
                {
                    column: 'TrainingTopicId',
                    value: null,
                    type: 'equal'
                },
                {
                    column: 'Description',
                    value: null,
                    type: 'like'
                },
            ];
        }
    };
};

App.TrainingTopic.Topic = function(topic) {
    var self = this;
    topic = topic || {};
    self.TrainingTopicId = ko.observable(topic.TrainingTopicId);
    self.Description = ko.observable(topic.Description);
};