(function() {
    if (!_hero) {
        throw "Library Herp is not defined, the system will not work properly";
    }

    var Routes = function() {
        var self = this;

        self.baseUrl = $('meta[name="base-url"]').attr("content");
        self.urls = [];

        (function() {
            _hero
                .ajax()
                .url(self.baseUrl + "/routes")
                .verb("GET").async(false)
                .then(function(data) {
                    if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                        self.urls = data.response;
                    }
                })
                .execute();
        })();

        self.getParams = function(url){
            var regex = /(?<=\{)[0-9A-Za-z]+(?=\}|\?})/g;
            var result = [];

            while ((find = regex.exec(url)) !== null){
                // This is necessary to avoid infinite loops with zero-width matches
                if (find.index === regex.lastIndex) {
                    regex.lastIndex++;
                }
                result.push(find[0]);
            }

            return result;
        };

        self.makeUrl = function(url, parameters) {
            url = url.replace(new RegExp('\\?', 'g'), '');

            if (Array.isArray(parameters) && parameters.length) {
                self.getParams(url).forEach(function(param, index){
                    var value = parameters[index] ? parameters[index] : 'null';
                    url = url.replace('{'+ param +'}', value);
                });
            } else {
                for (var prop in parameters) {
                    var value = parameters[prop] ? parameters[prop] : 'null';
                    url = url.replace(new RegExp('{'+ prop +'}', 'g'), value);
                }
            }

            return url;
        };

        self.getObjectByNameRoute = function(name){
            return self.urls.filter(function(url) {
                return url.name == name;
            })[0];
        };

        self.find = function(name, parameters) {
            var locale = self.getObjectByNameRoute(name);

            if (!locale) {
                throw (name + " route not found into list!");
            }

            return self.makeUrl(locale.url, parameters);
        };

        self.redirect = function(name, parameters) {
            window.location = self.find(name, parameters);
        };

        self.baseUrlJoin = function(joinWith) {
            if (["/", "\\"].indexOf(joinWith[0]) == -1) {
                return self.baseUrl + "/" + joinWith;
            }

            return self.baseUrl + joinWith;
        };

        self.info = function(name){
            return self.getObjectByNameRoute(name);
        };

        self.headers = function(){
            return {
                Accept: "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "X-Requested-With": "XMLHttpRequest"
            };
        };

        return {
            find: self.find,
            baseUrl: self.baseUrl,
            baseUrlJoin: self.baseUrlJoin,
            redirect: self.redirect,
            info: self.info,
            headers: self.headers
        };
    };

    _hero.routes = new Routes;
})();
