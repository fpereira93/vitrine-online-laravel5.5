ko.bindingHandlers.allowBindings = {
    init: function(elem, valueAccessor) {
        // Let bindings proceed as normal *only if* my value is false
        var shouldAllowBindings = ko.unwrap(valueAccessor());

        return { controlsDescendantBindings: !shouldAllowBindings };
    }
};

var ViewModelBase = function () {
    var self = this;

};

ko.applyBindings(new ViewModelBase(), document.getElementById("body"));