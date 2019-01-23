App = App || {};

App.ViewModel = function() {
    var self = this;
    self.currentPage = ko.observable(false);

    self.selectPage = function(page) {
        page = ko.utils.arrayFirst(App.Pages, function(p) {
            return p.id == page;
        }) || {};
        self.currentPage(page);
    };
};