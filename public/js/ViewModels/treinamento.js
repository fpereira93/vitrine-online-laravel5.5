App = App || {};
App.Training = App.Training || {};

App.Training.ViewModel = function () {
    var self = this;

    self.step = ko.observable(Constants.STEPS.Listing);
    self.editing = ko.observable(null);
    self.url = {
        base: 'api.trainings.',
        index: function () { return _hero.routes.find(`${this.base}index`) },
        get: function(id) { return _hero.routes.find(`${this.base}get`, [id])},
        create: function () { return _hero.routes.find(`${this.base}create`) },
        update: function (id) { return _hero.routes.find(`${this.base}update`, [id]) },
        delete: function (id) { return _hero.routes.find(`${this.base}delete`, [id]) },
        paginate: function () { return _hero.routes.find(`${this.base}paginate`) },
        topics: function(id) { return _hero.routes.find(`${this.base}topics`, [id])},
        summonedUsers: function(id) {return _hero.routes.find(`${this.base}summoneds`, [id])},
        addUser: function(id) { return _hero.routes.find(`${this.base}addUser`, [id])},
        removeUser: function(id) { return _hero.routes.find(`${this.base}removeUser`, [id])},
    };
    self.trainers = ko.observableArray([]);
    self.selectedStates = ko.observable();
    self.summonedUsers = ko.observableArray([]);
    self.Topic = ko.observable('');
    self.User = ko.observable({});

    self.usersToSummon = ko.computed(function() {
        return self.trainers().filter(function(user) {
            return !ko.utils.arrayFirst(self.summonedUsers(), function(sm) {
                return sm.id == user.id;
            });
        });
    });

    var dataToLoad = [
        {route: _hero.routes.find('api.users.autocomplete'), observable: self.trainers, message: 'Buscando dados de palestrantes'}
    ];

    self.loadData = function(dataToLoad) {
        dataToLoad.forEach(function(data) {
            var loadingId = _hero.loading.show(data.message);
            _hero.ajax()
                .url(data.route)
                .then(function(response) {
                    data.observable(response.response.map(function(item) {return new ko.SelectItem(item.id, item.name)}));
                })
                .always(function() {
                    _hero.loading.hide(loadingId);
                })
                .execute();
        });
    }; 
    self.loadData(dataToLoad);

    self.insertTopic = function() {
        self.editing().Topics.push(new App.Training.Topic({Description: self.Topic()}));
        self.Topic('');
    };

    self.addNew = function () {
        self.step(Constants.STEPS.AddingNew);
        self.editing(new App.Training.Training);
    };

    self.editTraining = function(trainingId) {
        var loadingId = _hero.loading.show('Buscando informações do treinamento');
        _hero.ajax()
            .url(self.url.get(trainingId))
            .then(function(data) {
                if (data.status == 1) {
                    self.editing(new App.Training.Training(data.response));
                    self.step(Constants.STEPS.Editing);
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function(data) {
                _hero.toastr.error(data.message);
            })
            .always(function() {
                _hero.loading.hide(trainingId);
            })
            .execute();
    };

    self.showListing = function () {
        self.step(Constants.STEPS.Listing);
        self.editing(null);
    };

    self.saveTraining = function (training) {
        var payload = ko.toJS(training);
        delete payload.Trainer;
        delete payload.Topics;
        delete payload.renderizableTopics;
        var trainingTopicsPayload = ko.toJS(training.Topics());
        var loadingId = _hero.loading.show('Salvando dados do treinamento');
        _hero.ajax()
            .url(payload.TrainingId ? self.url.update(payload.TrainingId) : self.url.create())
            .verb(payload.TrainingId ? "PUT" : "POST")
            .payload(payload)
            .then(function (data) {
                if (data.status == 1) {
                    self.saveTopics(trainingTopicsPayload, data.response.TrainingId, function(data) {
                        self.step(Constants.STEPS.Listing);
                        _hero.toastr.success(data.message);
                    });
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            })
            .always(function () {
                _hero.loading.hide(loadingId);
            })
            .execute();
    };

    self.saveTopics = function(topics,id, onSuccess) {
        if (topics.length == 0 ) {
            return;
            onSuccess({message: 'Salvo com sucesso'});
        }
        topics = {topics};
        var loadingId = _hero.loading.show('Salvando tópicos');
        _hero.ajax()
        .url(self.url.topics(id))
        .verb("POST")
        .payload(topics)
        .then(onSuccess)
        .fail(function(data) {
            _hero.toastr.error(data.message);
        })
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };

    self.deleteTraining = function (id) {
        var loadingId = _hero.loading.show('Validando exclusão do treinamento');
        _hero.ajax()
            .url(self.url.delete(id))
            .verb('DELETE')
            .then(function(data) {
                if (data.status == 1) {
                    _hero.toastr.success(data.message || 'Treinamento apagado');
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function(data) {
                _hero.toastr.error(data.message);
            })
            .always(function() {
                _hero.loading.hide(loadingId);
            })
            .execute();
    };

    self.removeTopic = function(topic) {
        if (topic.TrainingTopicId == null) {
            self.editing().Topics.remove(topic);
        } else {
            topic.Deleted(true);
        }
        _hero.toastr.success('Tópico removido com sucesso');
    };

    self.summonUp = function() {
        var training = self.editing();
        var loadingId = _hero.loading.show('Buscando usuários inscritos');
        self.summonedUsers([]);
        self.step(App.Training.Constants.STEPS.SummonUp);
        _hero.ajax()
        .url(self.url.summonedUsers(training.TrainingId))
        .then(function(data) {
            if (data.status == 1) {
                self.summonedUsers(data.response);
            } else {
                _hero.toastr.error(data.message);
            }
        })
        .fail(function(data) {
            _hero.toastr.error(data.message || "Falha ao buscar usuários inscritos");
        })
        .always(function() {
            _hero.loading.hide(loadingId);
        })
        .execute();
    };

    self.addUser = function() {
        if (self.User()) {
            var loadingId = _hero.loading.show('Inserindo novo usuário');
            var training = self.editing();
            _hero.ajax()
            .url(self.url.addUser(training.TrainingId))
            .payload({
                id: self.User()
            })
            .verb('POST')
            .then(function(data) {
                if (data.status == 1) {
                    self.summonedUsers.push(data.response);
                    self.User(null);
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function(data) {
                _hero.toastr.error(data.message || "Falha ao inscrever usuário");
            })
            .always(function() {
                _hero.loading.hide(loadingId);
            })
            .execute();
        }
    };

    self.removeUser = function(user) {
        if (user && user.id) {
            var loadingId = _hero.loading.show('Removendo usuário');
            var training = self.editing();
            _hero.ajax()
            .url(self.url.removeUser(training.TrainingId))
            .payload({
                id: user.id
            })
            .verb('DELETE')
            .then(function(data) {
                if (data.status == 1) {
                    self.summonedUsers.remove(user);
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function(data) {
                _hero.toastr.error(data.message || "Falha ao remove usuário");
            })
            .always(function() {
                _hero.loading.hide(loadingId);
            })
            .execute();
        }
    };

    self.configDataTable = {
        dataTable : ko.observable(),
        columns: [
            {
                name: 'Place',
                sWidth: '20%',
                text: 'Local',
            }, {
                name: 'Theme',
                sWidth: '20%',
                text: 'Tema'
            }, {
                name: 'BeginDate',
                sWidth: '20%',
                text: 'Data'
            }, {
                name: 'Action',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function(training) {return true;},
                    click: function(item, event, training) {
                        self.editTraining(training.TrainingId);
                    }
                },
                delete: {
                    canRender: function(training) {return true;},
                    click: function(item, event, training) {
                        self.deleteTraining(training.TrainingId);
                    }
                }
            }
        ],
        url: self.url.paginate(),
        filters: function(data) {

        }
    };    
};

App.Training.Training = function (training) {
    var self = this;
    function _construct(training) {
        training = training || {};
        self.TrainingId = training.TrainingId || null;
        self.Place = ko.observable(training.Place || '');
        self.Theme = ko.observable(training.Theme || '');
        self.BeginDate = ko.observable(moment(training.BeginDate, Constants.LaravelDateFormat).format(Constants.InputDateFormat) || null);
        self.EndDate = ko.observable(moment(training.EndDate, Constants.LaravelDateFormat).format(Constants.InputDateFormat) || null);
        self.Trainer = ko.observable(training.trainer || {});
        self.TrainerId = ko.observable(training.TrainerId || -1);
        self.Status = ko.observable(training.Status || '');
        if (training.topics) {
            self.Topics = ko.observableArray(training.topics.map(function(topic) { return new App.Training.Topic(topic)}));
        } else {
            self.Topics = ko.observableArray([]);
        }
        self.Summoneds = ko.observableArray(training.Summoneds || []);
        self.renderizableTopics = ko.computed(function(){
            return self.Topics().filter(function(item) {
                return !item.Deleted();
            })
        })
    }
    _construct(training);
};

App.Training.Topic = function(topic) {
    var self = this;
    self.TrainingTopicId = ko.observable(topic.TrainingTopicId || null);
    self.Description = ko.observable(topic.Description || '');
    self.Deleted = ko.observable(false);
}

App.Training.Constants = {
    STEPS: { SummonUp: 4 }
}
