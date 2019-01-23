App = App || {};
App.Users = App.Users || {};

App.Users.ViewModel = function () {
    var self = this;
    // self.list = ko.observableArray();
    self.step = ko.observable(Constants.STEPS.Listing);
    self.sortCriteria = ko.observable('name');
    self.sortOrder = ko.observable('ASC');
    self.filter = ko.observable('');
    self.isWaitingRequest = ko.observable(false);
    self.user = ko.observable(false);
    self.roles = ko.observableArray();

    self.getRolesCurrentUserForSave = function(){
        return self.roles().filter(function(role){
            return role.checked() == true;
        }).map(function(role){
            return role.name();
        });
    };

    self.createRequestForSave = function(){
        return {
            name: self.user().name(),
            email: self.user().email(),
            password: self.user().password(),
            cPassword: self.user().cPassword(),
            roleNames: self.getRolesCurrentUserForSave()
        };
    };

    self.saveUser = function () {
        var idloading = _hero.loading.show('Salvando usuário');

        var payload = self.createRequestForSave();

        if (self.step() == Constants.STEPS.Editing){
            var url = _hero.routes.find('api.users.update', { id: self.user().id() });
            var verb = 'PUT';
        } else {
            var url = _hero.routes.find('api.users.create');
            var verb = 'POST';
        }

        _hero.ajax()
            .url(url)
            .payload(payload)
            .verb(verb)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                    _hero.toastr.success(data.message);

                    self.user().roleNames(payload.roleNames);
                    self.goBack();

                    vModelBase.updateInfoUser();
                } else {
                    _hero.toastr.error(data.message);
                }
            })
            .fail(function (data) {
                _hero.toastr.error(data.message);
            })
            .always(function (data) {
                _hero.loading.hide(idloading);
            })
            .execute();
    };

    self.deleteUser = function(user) {
        var id = _hero.loading.show('Removendo usuário');
        _hero.ajax()
            .url(_hero.routes.find('api.users.destroy', [ user.id ]))
            .verb('DELETE')
            .then(function(data) {
                if (data.status == 1) {
                    toastr.success(data.message);
                    self.loadData();
                }
            })
            .always(function(data){
                _hero.loading.hide(id);
            })
            .fail(function(data) {
                toastr.error(data.message);
            })
            .execute();
    }

    self.checkRolesCurrentUserIfExists = function(){
        self.roles().forEach(function(role){
            var exist = self.user().roleNames().some(function(roleName){
                return roleName == role.name();
            })

            role.checked(exist);
        });
    };

    self.loadAllRoles = function(next){
        if (self.roles().length > 0){
            next.call();
            return;
        }

        var id = _hero.loading.show('Carregando permissões');
        _hero.ajax()
            .url(_hero.routes.find('api.permission.index'))
            .verb('GET')
            .then(function(data) {
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                    var roles = data.response.map(function(roleDb){
                        return new App.Users.Role(roleDb);
                    });

                    self.roles(roles);
                    next.call();
                }
            })
            .always(function(data){
                _hero.loading.hide(id);
            })
            .fail(function(data) {
                toastr.error(data.message);
            })
            .execute();
    };

    self.addNew = function () {
        self.user(new App.Users.User);
        self.step(Constants.STEPS.AddingNew);

        self.loadAllRoles(function(){
            self.checkRolesCurrentUserIfExists();
        });
    };

    self.editUser = function(user){
        self.user(user);
        self.step(Constants.STEPS.Editing);

        self.loadAllRoles(function(){
            self.checkRolesCurrentUserIfExists();
        });
    };

    self.cancel = function () {
        self.user(new App.Users.User);
        self.step(Constants.STEPS.Listing);
    }

    self.goBack = function () {
        self.step(Constants.STEPS.Listing);
    };

    self.configDataTable = {
        dataTable: ko.observable(),
        columns: [
            {
                name: 'id',
                sWidth: '10%',
                text: 'Código'
            },
            {
                name: 'name',
                sWidth: '20%',
                text: 'Nome'
            },
            {
                name: 'email',
                sWidth: '20%',
                text: 'E-mail'
            },
            {
                name: 'lastAccess',
                sWidth: '10%',
                text: 'Último acesso'
            },
            {
                text: 'Ação',
                sWidth: '20%',
                isAction: true,
                className: 'center',
                edit: {
                    canRender: function (user) { return true; },
                    click: function (item, event, user) {
                        self.editUser(new App.Users.User(user));
                    }
                },
                delete: {
                    messageOnDelete: 'Deseja realmente deletar o registro?',
                    canRender: function (user) { return true; },
                    click: function (item, event, user) {
                        self.deleteUser(user);
                        //self.configDataTable.dataTable().ajax.reload();
                    }
                }
            }
        ],
        url: _hero.routes.find("api.users.paginate"),
        filters: function (aoData) {
            aoData.filters = [
                {
                    column: 'id',
                    value: null,
                    type: 'equal'
                },
                {
                    column: 'name',
                    value: null,
                    type: 'like'
                }
            ];
        }
    }
};

App.Users.User = function (user) {
    var self = this;
    self.id = ko.observable();
    self.name = ko.observable();
    self.UserGroupId = ko.observable();
    self.email = ko.observable();
    self.password = ko.observable();
    self.cPassword = ko.observable();
    self.lastAccess = ko.observable();
    self.roleNames = ko.observable();

    self.formatedLastAccess = ko.computed(function () {
        if (!self.lastAccess()) {
            return 'Nunca acessou';
        }
        var mLastAccess = moment(self.lastAccess());
        if (mLastAccess.format('DD') == moment().format('DD')) {
            return 'Hoje às ' + mLastAccess.format('HH:mm');
        }
        if (mLastAccess.format('YYYY') == moment().format('YYYY')) {
            return mLastAccess.format('DD/MM HH:mm');
        }
        return mLastAccess.format('DD/MM/YYY HH:mm');
    });

    var _construct = function (user) {
        user = user || {};
        self.id(user.id || 0);
        self.name(user.name || '');
        self.UserGroupId(user.UserGroupId || 0);
        self.email(user.email || '');
        self.password(user.password || '');
        self.cPassword(user.cPassword || '');
        self.lastAccess(user.lastAccess || false);
        self.roleNames(user.roleNames || []);
        self.original = ko.toJS(self);
    };
    _construct(user);

    self.revert = function () {
        _construct(self.original);
    }
}

App.Users.Role = function (roleDb) {
    var self = this;

    self.id = ko.observable(roleDb.id || -1);
    self.name = ko.observable(roleDb.name || "");
    self.checked = ko.observable(roleDb.checked || false);
}
