ko.bindingHandlers.enterkey = {
    init: function (element, valueAccessor, allBindings, viewModel) {
        var callback = valueAccessor();
        $(element).keypress(function (event) {
            var keyCode = event.which ? event.which : event.keyCode;
            if (keyCode === 13) {
                callback.call(viewModel);
                return false;
            }
            return true;
        });
    }
};
ko.bindingHandlers.fadeVisible = {
    init: function (element, valueAccessor) {
        var value = valueAccessor();
        $(element).toggle(ko.unwrap(value));
    },
    update: function (element, valueAccessor) {
        var value = valueAccessor();
        ko.unwrap(value) ? $(element).fadeIn() : $(element).fadeOut();
    }
};

ko.bindingHandlers.fullCalendar = {
    update: function (element, viewModelAccessor) {
        var viewModel = ko.utils.unwrapObservable(viewModelAccessor());
        $(element).fullCalendar("destroy");
        element.innerHTML = "";
        $(element).fullCalendar({
            events: ko.utils.unwrapObservable(viewModel.events),
            header: viewModel.header,
            editable: viewModel.editable
        });
        $(element).fullCalendar(
            "gotoDate",
            ko.utils.unwrapObservable(viewModel.viewDate)
        );
    }
};

ko.fullCalendar = {
    viewModel: function (configuration) {
        this.events = configuration.events;
        this.header = configuration.header;
        this.editable = configuration.editable;
        this.viewDate = configuration.viewDate || ko.observable(new Date());
    }
};

ko.bindingHandlers.select2 = {
    init: function (el, valueAccessor, allBindingsAccessor, viewModel) {

        ko.utils.domNodeDisposal.addDisposeCallback(el, function () {
            $(el).select2('destroy');
        });

        var allBindings = allBindingsAccessor(),
        select2 = ko.utils.unwrapObservable(allBindings.select2);
        if ("data" in select2);
        select2.data = ko.utils.unwrapObservable(select2.data);

        $(el).select2(select2);
    },
    
    update: function (el, valueAccessor, allBindingsAccessor, viewModel) {
        var allBindings = allBindingsAccessor();

        if (!allBindings.value){
            return;
        }

        if ("value" in allBindings) {
            if ((allBindings.select2.multiple || el.multiple) && allBindings.value().constructor != Array) {
                $(el).val(allBindings.value().split(',')).trigger('change');
            }
            else {
                $(el).val(allBindings.value()).trigger('change');
            }
        } else if ("selectedOptions" in allBindings) {
            var converted = [];
            var textAccessor = function (value) { return value; };
            if ("optionsText" in allBindings) {

                textAccessor = function (value) {
                    var valueAccessor = function (item) { return item; }
                    if ("optionsValue" in allBindings) {
                        valueAccessor = function (item) { return item[allBindings.optionsValue]; }
                    }
                    var items = $.grep(allBindings.options(), function (e) { return valueAccessor(e) == value });
                    if (items.length == 0 || items.length > 1) {
                        return "UNKNOWN";
                    }
                    return items[0][allBindings.optionsText];
                }
            }
            $.each(allBindings.selectedOptions(), function (key, value) {
                converted.push({ id: value, text: textAccessor(value) });
            });
            $(el).select2("data", converted);
        }
        $(el).trigger("change");
    }
};

ko.SelectItem = function(id, text) {
    this.id = id;
    this.text = text;
};

ko.SelectItem.prototype.toString = function() {
    return this.text;
};
