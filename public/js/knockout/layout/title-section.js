ko.components.register('title-section', {
    viewModel: function(params) {
        var self = this;
        self.title = params.title;
        self.subtitle = params.subtitle;
    },
    template: ['<section class="content-header">',
                '<h1><span data-bind="text: title"></span>',
                    ' <small data-bind="text: subtitle"></small>',
                '</h1>',
            '</section>'].join('')
});