(function() {
    if (!_hero) {
        throw {err: "Hero Library is not defined!"}
    }
    // CONSTANTS
    var confirmationElement = $('.confirmation');
    var confirmationHeader = $('#confirmHeader');
    var confirmationBody = $('#confirmBody');
    var confirmationFooter = $('#confirmFooter');
    var HIDE_EVENT = 'confirmation.hide';
    var SHOW_EVENT = 'confirmation.show';

    //EVENTS
    confirmationElement.on(SHOW_EVENT, function() {
        confirmationElement.fadeIn(200);
    })
    confirmationElement.on(HIDE_EVENT, function() {
        confirmationElement.fadeOut(200);
    });
    
    //FUNCTIONS
    _hero.confirmation = function (config) {
        var self = this;
        self.buttons = config.buttons || [];
        self.header = config.title || 'Confirma?';
        self.body = config.body || 'Confirma?';

        confirmationHeader.empty();
        confirmationHeader.html(self.header);

        confirmationBody.empty();
        confirmationBody.html(self.body);

        confirmationFooter.empty();
        self.buttons.forEach(function(button) {
            confirmationFooter.append(button);
        });

        confirmationElement.trigger(SHOW_EVENT);
    };
    //FACTORIES
    _hero.confirmation.buttonMaker = function(template) {
        return function(text, configs) {
            var newObj = template.clone();
            if(configs.style) {
                newObj.style = configs.style;
            }
            if (configs.classes) {
				configs.classes.forEach(function(classe) {
                	newObj.addClass(classe);
				});
            }
            if (configs.click) {
                newObj.on('click', function() {
                    if (configs.closeOnClick) {
                        confirmationElement.trigger(HIDE_EVENT);
                    }
                    configs.click();
                });
            }
            newObj.html(text);
            return newObj;
        }
    };

    _hero.confirmation.defaultButtonMaker = _hero.confirmation.buttonMaker($('<button class="btn"></button>'));

    _hero.confirmation.defaultConfirmationButtons = function (config) {
        var buttonFactory = config.buttonFactory || _hero.confirmation.defaultButtonMaker;
        config.closeOnClick = config.closeOnClick != undefined ? config.closeOnClick : true;
        var buttonAccept = buttonFactory('Confirmar', {
            click: config.onAccept,
            classes: ['btn-success-outline', 'mg-r-10'],
            closeOnClick: config.closeOnClick
        });
        var buttonCancel = buttonFactory('Cancelar', {
            click: config.onAbort,
            classes: ['btn-danger-outline'],
            closeOnClick: config.closeOnClick
        });
        return [buttonAccept, buttonCancel];
    };
})();