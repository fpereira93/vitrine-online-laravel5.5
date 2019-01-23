var emailRegex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

function ViewModel() {
    var self = this;
    self.email = ko.observable('');
    self.password = ko.observable('');
    self.message = ko.observable('');
    self.Pages = { Login: 1, ForgotPassword: 2 };
    self.page = ko.observable(self.Pages.Login);
    self.isWaitingRequest = ko.observable(false);

    self.invalids = ko.computed(function () {
        var invalid = [];

        if (!emailRegex.test(self.email())) {
            invalid.push({ id: 'email', value: true });
        }

        if (self.password().length < 6) {
            invalid.push({ id: 'password', value: true });
        }

        return invalid;
    })

    self.checkIsInvalid = function(fieldName) {
        return self.invalids().find(function(item){
            return item.id == fieldName;
        });
    }

    self.isValid = ko.computed(function () {
        return !self.invalids().length
    });

    self.enter = function () {

        var payload = {
            email: self.email(),
            password: self.password()
        }

        self.isWaitingRequest(true);

        _hero.ajax()
        .url(_hero.routes.find('admin.login.user'))
        .payload(payload)
        .verb("POST")
        .then(function(data) {
            if (data.status == _hero.STATUS_RESPONSE.SUCCESS){
                _hero.routes.redirect('admin.base.index');
            } else {
                _hero.toastr['error'](data.message);
            }
        })
        .fail(function(data) {
            _hero.toastr['error']('Falha ao fazer o login, cheque o usuário e a senha');
        })
        .always(function(data) {
            self.isWaitingRequest(false);
        }).execute();
    }

    self.forgotPassword = function () {
        self.page(self.Pages.ForgotPassword);
    }

    self.showLogin = function () {
        self.page(self.Pages.Login);
    }
}
var viewModel = new ViewModel();
ko.applyBindings(viewModel);
