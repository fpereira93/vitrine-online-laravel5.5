ko.components.register('app-header', {
    viewModel: function (params) {
        var self = this;
        self.notifications = params.notifications || ko.observableArray([]);

        self.user = params.userData || {};

        self.onClickLogout = params.onClickLogout || function(){};
    },
    template: '<header class="main-header">\
    <a href="javascript: void(0);" class="logo">\
        <span class="logo-mini"><i class="fa fa-cogs"></i></span>\
        <span class="logo-lg"><i class="fa fa-cogs"></i> <b>Ateliê</b></span>\
    </a>\
    <nav class="navbar navbar-static-top">\
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">\
            <span class="sr-only">Toggle navigation</span>\
            <span class="icon-bar"></span>\
            <span class="icon-bar"></span>\
            <span class="icon-bar"></span>\
        </a>\
        <div class="navbar-custom-menu">\
            <ul class="nav navbar-nav">\
                <li class="dropdown notifications-menu">\
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">\
                        <i class="fa fa-bell-o"></i>\
                        <!-- ko if: notifications().length -->\
                        <span class="label label-warning" data-bind="text:notifications().length"></span>\
                        <!-- /ko -->\
                    </a>\
                    <ul class="dropdown-menu">\
                        <li class="header">Você tem <span data-bind="text:notifications().length"></span> notificações novas</li>\
                        <li>\
                            <ul class="menu" data-bind="foreach:notifications">\
                                <li>\
                                    <a href="#" class="white-space-normal">\
                                        <i class="fa fa-bell text-aqua"></i><span data-bind="text:text"></span>\
                                    </a>\
                                </li>\
                            </ul>\
                        </li>\
                        <!-- ko if: notifications().length -->\
                        <li class="footer"><a href="#">Ver todas</a></li>\
                        <!-- /ko -->\
                    </ul>\
                </li>\
                <li class="dropdown user user-menu">\
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">\
                      <img class="user-image" data-bind="attr: { src: $data.user.avatar }">\
                      <span class="hidden-xs" data-bind="text: $data.user.name"></span> \
                    </a>\
                    <ul class="dropdown-menu">\
                        <li class="user-header">\
                            <img class="img-circle" data-bind="attr: { src: $data.user.avatar }">\
                            <p>\
                                <!--ko text: $data.user.name--><!--/ko--> </br>\
                                <!--ko text: $data.user.email--><!--/ko-->\
                            </p>\
                        </li>\
                        <li class="user-footer">\
                            <div class="pull-right">\
                                <a href="javascript: void(0)"  data-bind=" click: $data.onClickLogout " class="btn btn-default btn-flat ripple">Sair</a>\
                            </div>\
                        </li>\
                    </ul>\
                </li>\
            </ul>\
        </div>\
    </nav>\
</header>'
});
