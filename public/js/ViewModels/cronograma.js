App = App || {};
App.Cronograma = App.Cronograma || {};
var Convert = {};

Convert.FromResponse = {};
Convert.FromResponse.ToEvent = function(responseArray) {
    var events = responseArray.map(function(event) {
        return new App.Cronograma.Event(event);
    });
    return events.sort(function(eventA, eventB) {
        beginA = moment(eventA.BeginDate());
        beginB = moment(eventB.BeginDate());
        if (!beginA.isValid && !beginB.isValid) return 0;
        if (!beginA.isValid) return -1;
        if (!beginB.isValid) return 1;
        return beginA.isBefore(beginB) ? -1 : 1;
    });
};
Convert.FromMoment = {};
Convert.FromMoment.ToTimeObject = function(mObject) {
    return {
        hours: mObject.hours(),
        minutes: mObject.minutes(),
        seconds: mObject.seconds()
    };
};

Convert.FromEvent = {};
Convert.FromEvent.ToJson = function(event) {
    event = ko.toJS(event);
    event.BeginDate = moment(event.BeginDate);
    if (!event.allDay && event.BeginHour) {
        event.BeginDate.add(
            Convert.FromMoment.ToTimeObject(
                moment(event.BeginHour, "HH:mm")
            )
        );
    }
    event.BeginDate = event.BeginDate.format("YYYY-MM-DD HH:mm:ss");

    if (event.EndDate && !event.allDay) {
        event.EndDate = moment(event.EndDate);
        if (event.EndHour) {
            event.EndDate.add(
                Convert.FromMoment.ToTimeObject(
                    moment(event.EndHour, "HH:mm")
                )
            );
        }
        event.EndDate = event.EndDate.format("YYYY-MM-DD HH:mm:ss");
    }

    delete event.Month;
    delete event.Day;
    delete event.BeginHour;
    delete event.EndHour;
    delete event.Month;
    delete event.SubDescription;

    return event;
};

Convert.FromEvent.ToFullCalendarEvent = function(event) {
    return {
        id: event.EventId(),
        title: event.Name(),
        start: moment(event.BeginDate(), "YYYY-MM-DD HH:mm:ss").format(
            "YYYY-MM-DDTHH:mm:ss"
        ),
        allDay: event.allDay()
    };
};

App.Cronograma.ViewModel = function() {
    var self = this;
    STEPS = {
        ShowingCalendar: 0,
        ShowingNextEvents: 1,
        AddingNew: 2,
        ShowingFilter:3
    };
    
    self.nextEvents = ko.observableArray([]);
    self.events = ko.observableArray([]);
    self.newEvent = ko.observable(false);
    self.step = ko.observable(STEPS.ShowingCalendar);
    self.filter = ko.observable();

    self.calendarViewModel = ko.observable(
        new ko.fullCalendar.viewModel({
            events: ko.observable([]),
            header: {
                left: "prev,next today",
                center: "title",
                right: "month,agendaWeek,agendaDay"
            },
            editable: true
        })
    );
    self.showNextEvents = function() {
        self.step(STEPS.ShowingNextEvents);
    };

    self.showCalendar = function() {
        self.step(STEPS.ShowingCalendar);
    };

    self.showEventInput = function() {
        self.step(STEPS.AddingNew);
        self.newEvent(new App.Cronograma.Event({}));
    };

    self.showFilters = function() {
        self.step(STEPS.ShowingFilter);
        self.filter(new App.Cronograma.Filter);
    }

    self.save = function() {
        var id = _hero.loading.show('Salvando evento');
        var payload = Convert.FromEvent.ToJson(self.newEvent());
        _hero
            .ajax()
            .url(_hero.routes.find("api.events.create"))
            .verb("POST")
            .payload(payload)
            .then(function(data) {
                if (data.status == 1) {
                    _hero.toastr.success(data.message);
                    self.events.push(data.response);
                    self.loadNextEvents();
                } else {
                    _hero.toastr.error(data.message);
                }
                self.step(STEPS.ShowingNextEvents);
            })
            .fail(function(data) {
                _hero.toastr.error(data.message);
            })
            .always(function() {
                _hero.loading.hide(id);
            })
            .execute();
    };

    self.loadNextEvents = function(description) {
        var id = _hero.loading.show(description);
        _hero
            .ajax()
            .url(_hero.routes.find("api.events.next"))
            .then(function(data) {
                if (data.status != 1) return;
                self.nextEvents(Convert.FromResponse.ToEvent(data.response));
            })
            .always(function() {
                _hero.loading.hide(id);
            })
            .execute();
    };

    self.loadEvents = function(description) {
        var now = moment();
        var year = now.format("Y");
        var id = _hero.loading.show(description);
        _hero
            .ajax()
            .url(_hero.routes.find("api.events.index", [year]))
            .then(function(data) {
                if (data.status != 1) return;
                self.events(Convert.FromResponse.ToEvent(data.response));
                var events = self.events().map(function(event) {
                    return Convert.FromEvent.ToFullCalendarEvent(event);
                });
                self.calendarViewModel().events(events);
            })
            .always(function() {
                _hero.loading.hide(id);
            })
            .execute();
    };

    var dependenciesToLoad = [
        {
            description: "Carregando eventos do ano atual",
            load: self.loadEvents
        },
        {
            description: "Carregando próximos eventos",
            load: self.loadNextEvents
        }
    ];

    self.loadData = function() {
        dependenciesToLoad.forEach(function(dep) {
            dep.load(dep.description);
        });
    };
    self.loadData();
};

App.Cronograma.Event = function(event) {
    var self = this;
    self.EventId = ko.observable(event.EventId || 0);
    self.Name = ko.observable(event.Name);
    self.Description = ko.observable(event.Description);
    self.BeginDate = ko.observable(event.BeginDate);
    self.BeginHour = ko.observable();
    self.EndDate = ko.observable(event.EndDate);
    self.EndHour = ko.observable();
    self.allDay = ko.observable(event.allDay);
    self.Day = ko.computed(function() {
        var date = moment(self.BeginDate());
        if (!date.isValid) return "0";
        return date.format("DD");
    });
    self.Month = ko.computed(function() {
        var date = moment(self.BeginDate());
        if (!date.isValid) return "0";
        return App.Months[date.format("M") - 1];
    });
    self.SubDescription = ko.computed(function() {
        var text = [];
        var begin = moment(self.BeginDate());
        var end = moment(self.EndDate());
        if (self.allDay()) return "O dia todo";

        if (begin._isValid) {
            text[0] = "Em";
            text[1] = begin
                .format("DD/MM/YYYY HH:mm:ss")
                .replace("00:00:00", "");
        }

        if (end._isValid) {
            text[0] = "Início:";
            text[2] = "Até:";
            text[3] = end.format("DD/MM/YYYY HH:mm:ss").replace("00:00:00", "");
        }
        return text.join(" ");
    });
};

App.Cronograma.Filter = function(filter) {
    var self = this;
    filter = filter || {};
    self.BeginDate = ko.observable(filter.BeginDate);
    self.Description = ko.observable(filter.Description);
    self.Name = ko.observable(filter.Name);
    self.Sources = ko.observableArray(filter.Sources);
    self.Matches = function(events) {
        return events.filter(self.Match);
    };
    self.Match = function(event) {
        if (self.BeginDate() && event.BeginDate() != self.BeginDate()) return false;
        if (self.Description() && !event.Description().has(self.Description())) return false;
        if (self.Name() && !event.Name().has(self.Name())) return false;
        return true;
        //TODO:Sources
    };
}

App.Months = [
    "JAN",
    "FEV",
    "MAR",
    "ABR",
    "MAI",
    "JUN",
    "JUL",
    "AGO",
    "SET",
    "OUT",
    "NOV",
    "DEZ"
];

App.Cronograma.SourceOptions = [
    {id: 1, source: 'Manual'}
];
