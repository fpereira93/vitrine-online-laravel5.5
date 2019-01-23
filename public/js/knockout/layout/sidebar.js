ko.components.register('app-sidebar', {
    viewModel : function(params) {
        var self = this;
        self.userData = params.userData || {};
        self.menus = params.menus;
    },
    template : '<aside class="main-sidebar">\
        <section class="sidebar">\
            <div class="user-panel">\
                <div class="pull-left image">\
                    <img class="img-circle" alt="User Image" data-bind="attr: {src: userData.avatar}"/>\
                </div>\
                <div class="pull-left info">\
                    <p data-bind="text:userData.name"></p>\
                </div>\
            </div>\
            <ul class="sidebar-menu" data-widget="tree" data-bind="foreach: $data.menus">\
                <li class="treeview">\
                    <a href="#">\
                        <i data-bind="css: \'fa fa-\' + $data.icon()"></i>\
                        <span data-bind="text: $data.title"></span>\
                        <span class="pull-right-container">\
                            <i class="fa fa-angle-left pull-right"></i>\
                        </span>\
                    </a>\
                    <ul class="treeview-menu" data-bind="foreach: $data.sons">\
                        <li><a href="#"  data-bind="click: $data.onClick">\
                        <i data-bind="css: \'fa fa-\' + $data.icon()"></i><span data-bind="text: $data.title"></span></a></li>\
                    </ul>\
                </li>\
            </ul>\
        </section>\
    </aside>'
});
