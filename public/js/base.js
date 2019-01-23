var App;

function Constants() { }

// Date constants
Constants.DateFormat = "DD/MM/YYYY";
Constants.MinDate = "01/01/1900";

// Flow Constants
Constants.STEPS = {
    Listing: 0,
    ShowingDetails: 1,
    AddingNew: 2,
    Editing: 3
};

Constants.LogError = true;

var ViewModel = function () {
    var self = this;

    self.loadPage = ko.observable(LoadPage.getInstance()); //singleton
    self.loading = _hero.loading; // default loading
    self.ready = ko.observable(false);

    self.userData = {
        name: ko.observable(),
        email: ko.observable(),
        avatar: ko.observable()
    };

    self.updateInfoUser = function () {
        var id = self.loading.show('Carregando informações do usuário');
        _hero
            .ajax()
            .url(_hero.routes.find("api.users.detail-user"))
            .async(false)
            .then(function (data) {
                if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {

                    if (!data.response.urlAvatar) {
                        data.response.urlAvatar = _hero.routes.baseUrlJoin("/img/user.png");
                    }

                    self.userData.name(data.response.name);
                    self.userData.email(data.response.email);
                    self.userData.avatar(data.response.urlAvatar);
                }
            })
            .always(function () {
                self.loading.hide(id);
            })
            .execute();
    };

    self.updateInfoUser();
    self.beforeLoadPageVariables = {};
    self.loadPage().setEvents({
        beforePageLoads: function () {
            self.beforeLoadPageVariables.loadingId = self.loading.show('Carregando recursos...');
        },
        afterPageLoads: function () {
            self.loading.hide(self.beforeLoadPageVariables.loadingId);
        }
    });

    self
        .loadPage()
        .addDefaultdependency("js/main.js")
        .addDefaultdependency("js/knockout/knockout-validation-min.js");

    self.onClickMenu = function (menu) {
        self.loadPage().load(menu);
    };

    self.onClickLogout = function () {
        _hero.routes.redirect("admin.logout.user");
    };

    self.addDatatableDependencies = function (menu) {
        return menu
            .addDependencies("datatable/css/dataTables.bootstrap4.min.css", null, true)
            .addDependencies("datatable/Responsive/css/responsive.bootstrap4.min.css", null, true)
            .addDependencies("datatable/js/jquery.dataTables.min.js", null, true)
            .addDependencies("datatable/js/dataTables.bootstrap4.min.js", null, true)
            .addDependencies("datatable/Responsive/js/dataTables.responsive.min.js", null, true);
    };

    self.loadPage().menus().push(
        new Menu("CONFIGURAÇÕES", "cogs", null, "Configuracoes")
        .addSubmenu(
            self.addDatatableDependencies(new Menu( "Usuários do Sistema", "user", "user", "Users" ))
            .setEvent(self.onClickMenu)
            .addDependencies("js/ViewModels/users.js")
        )
        .addSubmenu(
            new Menu("Meu Perfil", "user", "profile", "Profile")
                .setEvent(self.onClickMenu)
                .addDependencies("js/ViewModels/profile.js")
                .addDependencies("js/cropper.min.js")
                .addDependencies("css/cropper.min.cs")
            )
    );

    self.loadPage().menus().push(
        new Menu("VITRINE", "cogs", null, "Vitrine")
        .addSubmenu(
            self.addDatatableDependencies(new Menu( "Categoria", "user", "category", "Category" ))
            .setEvent(self.onClickMenu)
            .addDependencies("js/ViewModels/category.js")
        )
        .addSubmenu(
            self.addDatatableDependencies(new Menu( "Marca", "user", "brand", "Brand" ))
            .setEvent(self.onClickMenu)
            .addDependencies("js/ViewModels/brand.js")
        )
        .addSubmenu(
            self.addDatatableDependencies(new Menu( "Produto", "user", "product", "Product" ))
            .setEvent(self.onClickMenu)

            .addDependencies("js/jquery/ui/jquery-ui.min.js")
            .addDependencies("js/jquery/ui/jquery-ui.theme.min.css")
            .addDependencies("js/jquery/ui/jquery-ui.structure.min.css")
            .addDependencies("js/knockout-jqAutocomplete.js")

            .addDependencies("js/cropper.min.js")
            .addDependencies("css/cropper.min.css")

            .addDependencies("css/custom-checkbox.css")

            .addDependencies("js/ViewModels/product.js")
        )
    );

};

var vModelBase = new ViewModel();

$(document).ready(function () {
    $(".sidebar-menu").tree();
});

ko.applyBindings(vModelBase, document.getElementById("wrapper"));
