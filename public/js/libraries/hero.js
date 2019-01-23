var _hero = (function() {
    var self = this;

    self.Ajax = {};
    self.Ajax.ExpiredToken = -1;

    self.STATUS_RESPONSE = {
        ERROR: 0,
        SUCCESS: 1,
        UNAUTHENTICATED: 401,
        FORBIDDEN: 403
    };

    self.Ajax.AjaxModel = function() {
        var self = this;

        var tokenName;
        var methods = ["POST", "PUT", "GET", "DELETE"];

        var options = {
            method: "GET",
            payload: {},
            then: [],
            fail: [],
            always: [],
            async: true
        };

        var Statuses_ExpiredToken = -1;

        self.async = function(async){
            options.async = async;
            return self;
        };

        self.url = function(url) {
            options.url = url;
            return self;
        };

        self.payload = function(payload) {
            options.payload = payload;
            return self;
        };

        self.verb = function(method) {
            if (!methods.find(m => m == method)) {
                throw `Method ${method} not found. Choose one of ${methods.join(",")}`;
            }
            options.method = method;
            return self;
        };

        self.then = function(callback) {
            options.then.push(callback);
            return self;
        };

        self.clearThen = function() {
            options.then = [];
            return self;
        };

        self.fail = function(callback) {
            options.fail.push(callback);
            return self;
        };

        self.clearFail = function() {
            options.fails = [];
            return self;
        };

        self.always = function(callback) {
            options.always.push(callback);
            return self;
        };

        self.clearAlways = function() {
            options.always = [];
            return self;
        };

        self.route = function(route, parameters) {
            var info = _hero.routes.info(route);

            if (info){
                self.verb(info.verb);
                self.url(_hero.routes.find(route, parameters || {}));
            }

            return self;
        }

        function onDone(data) {
            if (data.status == Statuses_ExpiredToken) {
                // chama o login
            } else {
                executeFunctionArray(options.then, data);
            }
        }

        function onAlways(data) {
            executeFunctionArray(options.always, data);
        }

        function onFail(data) {
            // logar erro em algum lugar.
            executeFunctionArray(options.fail, data);
        }

        function executeFunctionArray(functions, payload) {
            var fn = functions.shift();
            if (fn) {
                fn(payload);
                executeFunctionArray(functions, payload);
            }
        }

        self.execute = function() {
            var configs = {
                url: options.url,
                method: options.method,
                data: options.payload,
                async: options.async,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "X-Requested-With": "XMLHttpRequest"
                }
            };

            $.ajax(configs)
            .done(function(data) {
                onDone(data);
            })
            .fail(function(data) {
                onFail(data);
            })
            .always(function(data) {
                onAlways(data);
            });

            return self;
        };
    };

    self.Ajax.create = function() {
        return new self.Ajax.AjaxModel();
    };

    return {
        ajax: self.Ajax.create,
        STATUS_RESPONSE: self.STATUS_RESPONSE
    };
})();

(function() {
    if (!toastr) {
        throw "Library toastr is not defined, the system will not work properly";
    }

    _hero.toastr = toastr;

    var eventError = toastr.error;
    var eventWarning = toastr.warning;

    /**
     * [formatMessage 'customiza o retorno da mensagem, pode ser um array de erros ou message']
     * @param  {mixed} message
     * @return {string}
     */
    var formatMessage = function(message){
        if (!message){
            return 'Unknown server error';
        }

        // e erro tipo string
        if (typeof message === 'string' || message instanceof String) {
            return message;
        }

        // e erro tipo array de string
        return Object.keys(message).reduce(function(custom, property){
            return custom + message[property] + "<br>";
        }, "");
    };

    // override
    _hero.toastr.error = function(message){
        eventError(formatMessage(message));
    };

    // override
    _hero.toastr.warning = function(message){
        eventWarning(formatMessage(message));
    };

    _hero.toastr.options.closeButton = true;
    _hero.toastr.options.progressBar = true;
    _hero.toastr.options.timeOut = 10000;
    _hero.toastr.options.positionClass = "toast-bottom-right",

    (_hero.toastr.reset = function() {
        _hero.toastr.options.onShown = undefined;
        _hero.toastr.options.onHidden = undefined;
        _hero.toastr.options.onclick = undefined;
        _hero.toastr.options.onCloseClick = undefined;
    });

    _hero.toastr.onShow = function(callback) {
        _hero.toastr.options.onShown = callback;
        return _hero.toastr;
    };

    _hero.toastr.onHidden = function(callback) {
        _hero.toastr.options.onHidden = callback;
        return _hero.toastr;
    };

    _hero.toastr.onClick = function(callback) {
        _hero.toastr.options.onclick = callback;
        return _hero.toastr;
    };

    _hero.toastr.onCloseClick = function(callback) {
        _hero.toastr.options.onCloseClick = callback;
        return _hero.toastr;
    };
})();
