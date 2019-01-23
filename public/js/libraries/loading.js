;(function () {

    if (!_hero) {
        throw "Hero Library is not defined, the system will not work properly";
    };

    var Loading = function(){
        var self = this;

        self.count = 0;
        self.display = ko.observable(false);
        self.arrayMessage = ko.observableArray();

        self.makeMessageWithId = function(message){
            return {
                id: new Date().getTime().toString(36),
                message: message
            }
        };

        self.show  = function(message){
            self.count++;
            self.display(true);

            if (message){
                var message = self.makeMessageWithId(message);
                self.arrayMessage.push(message);
                return message.id;
            }
        };

        self.hide = function(id){
            setTimeout(function() {
                self.arrayMessage(self.arrayMessage().filter(function(message){
                    return message.id !== id;
                }));
                self.count = self.count > 0 ? self.count - 1 : 0;
    
                if (self.count == 0){
                    self.display(false);
                    self.arrayMessage.removeAll();
                }
            }, 150);

        };

        return {
            arrayMessage: self.arrayMessage,
            display: self.display,
            show: self.show,
            hide: self.hide
        };
    };

    _hero.loading = new Loading;
})();
