var Menu = function(title, icon, template, namespace) {
    var self = this;

    self.title = ko.observable(title);
    self.icon = ko.observable(icon);
    self.template = ko.observable(template || null);
    self.namespace = ko.observable(namespace || null);
    self.sons = ko.observableArray([]);
    self.fileDependencies = []; //no computed

    self.onClick = function() {
        //event call into template
    };

    self.addSubmenu = function(submenuObject) {
        self.sons.push(submenuObject);
        return self;
    };

    self.addDependencies = function(pathName, optionalAttrs, readOnlyOnce) {
        self.fileDependencies.push({
            pathName: pathName,
            attr: optionalAttrs,
            readOnlyOnce: readOnlyOnce
        });

        return self;
    };

    self.setEvent = function(event) {
        self.onClick = event;
        return self;
    };
};

var LoadPage = (function() {
    var instance;

    var InstanceLoadPage = function() {
        var self = this;

        self.menus = ko.observableArray([]);
        self.readCss = [];
        self.readScripts = [];
        self.elementId = "#template";
        self.events = null;

        self.dataInformation = {
            menus: {
                current: null
            }
        };

        self.callback = function(func) {
            if (func) func();
        };

        self.addScriptRead = function(script){
            if (self.readScripts.indexOf(script) == -1){
                self.readScripts.push(script);
            }
        };

        self.getScriptSync = function(files, next) {
            var script = files.shift();

            if (!script) {
                self.callback(next);
            } else if (script.readOnlyOnce && self.readScripts.indexOf(script) > -1){
                self.getScriptSync(files, next);
            } else {
                self.addScriptRead(script);

                $.get(script.pathName, function() {
                    self.getScriptSync(files, next);
                });
            }
        };

        self.getMenuByNamespace = function(namespaceArray, menus) {
            var _menu = (menus ? menus : self.menus())
                .filter(function(menu) {
                    return menu.namespace() == namespaceArray[0];
                })
                .shift();

            namespaceArray.shift();

            if (namespaceArray.length == 0 || !_menu) {
                return _menu;
            }

            return self.getMenuByNamespace(namespaceArray, _menu.sons());
        };

        self.clearCssRead = function(){
            self.readCss.length = 0;
        };

        self.removeAllCssExternal = function() {
            self.readCss.forEach(function(css) {
                $("#" + css.id).remove();
            });

            self.clearCssRead();
        };

        self.loadCssExternal = function(pathName, attr) {
            var id = new Date().getTime().toString(36);

            attr = attr || {};
            attr.rel = "stylesheet";
            attr.type = "text/css";
            attr.href = pathName;
            attr.id = id;

            $("<link/>", attr).appendTo("head");

            return { pathName: pathName, id: id };
        };

        self.loadCss = function(arrayPathCss) {
            self.removeAllCssExternal();

            arrayPathCss.forEach(function(css) {
                self.readCss.push(self.loadCssExternal(css.pathName, css.attr));
            });
        };

        self.filesSeparate = function(files) {
            return {
                css: files.filter(function(file) {
                    return file.pathName.indexOf(".css") > -1;
                }),
                javascript: files.filter(function(file) {
                    return file.pathName.indexOf(".js") > -1;
                })
            };
        };

        self.applyBindings = function(menu, paramsModel) {
            if (menu.namespace()) {
                try {
                    var model = new App[(menu.namespace())].ViewModel(
                        paramsModel
                    );
                } catch (error) {
                    if (Constants.LogError) {
                        console.error(
                            ":( Error generated on instance ViewModel "
                        );
                        console.error(error);
                    }
                }
            }
            ko.applyBindings(
                model || {},
                document.getElementById(self.elementId.replace("#", ""))
            );
        };

        self.loadHtmlTemplate = function(menu, next) {
            $(self.elementId).load(
                "templates/" + menu.template() + ".html",
                function() {
                    var element = $(self.elementId);
                    ko.cleanNode(element[0]);
                    self.callback(next);
                }
            );
        };

        self.loadFileDependencies = function(menu, next) {
            var filesSeparate = self.filesSeparate(menu.fileDependencies);
            self.loadCss(filesSeparate.css);
            self.getScriptSync(filesSeparate.javascript, next);
        };

        self.load = function(menu, paramsModel, next) {
            self.callback(self.events.beforePageLoads);

            self.loadHtmlTemplate(menu, function() {
                self.loadFileDependencies(menu, function() {
                    self.applyBindings(menu, paramsModel);
                    self.callback(next);
                    self.callback(self.events.afterPageLoads);
                });
            });

            self.dataInformation.menus.current = menu;
        };

        return {
            menus: function() {
                return self.menus;
            },
            load: function(menu, paramsModel, next) {
                self.load(menu, paramsModel, next);
            },
            redirect: function(namespace, paramsModel, next) {
                self.load(
                    self.getMenuByNamespace(namespace.split(".")),
                    paramsModel,
                    next
                );
            },
            addDefaultdependency: function(pathName, optionalAttrs, readOnlyOnce){
                var file = self.filesSeparate([{
                    pathName: pathName,
                    attr: optionalAttrs,
                    readOnlyOnce: readOnlyOnce
                }]);

                self.loadCss(file.css);
                self.getScriptSync(file.javascript);
                self.clearCssRead();

                return this;
            },
            setEvents: function(events) {
                self.events = events;
            }
        };
    };

    return {
        getInstance: function() {
            if (!instance) {
                instance = new InstanceLoadPage();
            }
            return instance;
        }
    };
})();
