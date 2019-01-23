App = App || {};
App.Profile = App.Profile || {};


App.Profile.ViewModel = function() {
    var self = this;

      self.id = ko.observable();
      self.name = ko.observable();
      self.email = ko.observable();
      self.picture = ko.observable();
      self.editing = ko.observable("");
      self.changingPassword = ko.observable(false);
      self.oldPassword = ko.observable("");
      self.password = ko.observable("");
      self.cPassword = ko.observable("");
      self.hasChangedPicture = false;
      self.changingPicture = ko.observable(false);
      self.previousPicture = ko.observable(null);

    self.changePassword = function() {
        self.changingPassword(true);
    };

    self.savePassword = function() {
        if (!self.password() || !self.cPassword() || !self.oldPassword()) {
            _hero.toastr.error("Preencha todos os campos!");
            return;
        }
        if (self.password() != self.cPassword()) {
            _hero.toastr.error("As novas senhas não conferem");
            return;
        }
        if (self.password().length < 6) {
            _hero.toastr.error("Preencha a nova senha com ao menos 6 caracters");
            return;
        }
        self.changingPassword(false);
    };

    self.cancelPasswordEdit = function() {
        self.changingPassword(false);
        self.password("");
        self.oldPassword("");
        self.cPassword("");
    };

    self.edit = function(field) {
        self.editing(field);
        $("#" + field).focus();
        $("#" + field).focusout(function() {
            self.editing(false);
        });
    };

    self.save = function() {
        if (self.changingPassword()) {
            _hero.toastr.warning('Termine de alterar sua senha antes de prosseguir');
            return;
        }

        if (self.picture()){
            var inforAvatar = _hero.file.infor(self.fileOriginalAvatar);

            var avatar = {
                OriginalName: inforAvatar.name,
                File: self.picture(),
                Size: inforAvatar.size,
                MimeType: inforAvatar.type,
            };

        } else {
            var avatar = null;
        }

        console.log(avatar);

        var payload = {
            name: self.name(),
            email: self.email(),
            avatar: avatar,
            oldPassword: self.oldPassword(),
            password: self.password(),
            cPassword: self.cPassword(),
            updatePassword: self.password() ? 1 : 0
        };

        var id = _hero.loading.show("Aguarde...");
        
        _hero.ajax()
            .url(_hero.routes.find('api.users.update', [self.id()]))
            .verb('PUT')
            .payload(payload)
            .then(function(data){
                if (data.status == _hero.STATUS_RESPONSE.ERROR){
                    _hero.toastr.error(data.message);
                } else {
                    _hero.toastr.success('Perfil Atualizado!')
                    vModelBase.updateInfoUser();
                }
            })
            .always(function(data) {
                _hero.loading.hide(id);
            })
            .fail(function(data){
                _hero.toastr.error(data.message);
            })
            .execute();
    };

    self.showPicture = function(evt) {
        var files = window.event.srcElement.files;

        if (files){
            _hero.file.putImage(document.getElementById('pictureImg'), files[0]);
            _hero.file.toBase64(files[0], function(base64){
                self.changingPicture(true);
                self.stopCrop();
                self.picture(base64);
                self.startCrop();
            });
            self.fileOriginalAvatar = files[0];
        }
    };

    self.finishCrop = function() {
        var base64 = self.cropperInstance.getCroppedCanvas().toDataURL();
        self.picture(base64);
        self.changingPicture(false);
        self.previousPicture(base64);
        self.stopCrop();
    };

    self.stopCrop = function() {
        if (self.cropperInstance) {
            self.cropperInstance.destroy();
            self.cropperInstance = null;
        }
    };

    self.cancelCrop = function() {
        self.changingPicture(false);
        self.stopCrop();
        document.getElementById('pictureImg').src = self.previousPicture();
    };

    self.startCrop = function() {
        var image = document.getElementById('pictureImg');
        self.cropperInstance = new Cropper(image, {
            aspectRatio: 1,
            dragMode: 'crop',
            crop: function(event) { },
            ready: function () {
                self.cropperInstance.crop();
            }
        });
    };

    self.loadData = function() {
        var id = _hero.loading.show('Aguarde...');
        _hero.ajax()
            .url(_hero.routes.find('api.users.detail-user'))
            .verb('GET')
            .then(function(data) {

                self.id(data.response.id);
                self.name(data.response.name);
                self.email(data.response.email);

                if (data.response.urlAvatar) {
                    document.getElementById('pictureImg').src = data.response.urlAvatar;
                    self.previousPicture(data.response.urlAvatar);
                } else {
                    document.getElementById('pictureImg').src = _hero.routes.baseUrlJoin('/img/user.png');
                    self.previousPicture(_hero.routes.baseUrlJoin('/img/user.png'));
                }
            })
            .always(function() {
                _hero.loading.hide(id);
            })
            .execute();
    };

    self.loadData();
};
