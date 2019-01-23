ko.components.register("button-back", {
  viewModel: function(params) {
    var self = this;
    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
  },
  template:
    '<button class="btn btn-flat btn-warning ripple" data-bind="click: click, enable: enable">\
                <i class="fa fa-arrow-circle-left"></i>\
               </button>'
});

ko.components.register("button-table-delete", {
  viewModel: function(params) {
    var self = this;
    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
  },
  template:
    '<button class="btn btn-flat btn-danger btn-sm ripple" data-bind="click: click, enable: enable" style="width:30px">\
                <i class="fa fa-trash"></i>\
               </button>'
});

ko.components.register("button-table-view", {
  viewModel: function(params) {
    var self = this;
    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
  },
  template:
    '<button class="btn btn-flat btn-info btn-sm ripple" data-bind="click: click, enable: enable" style="width:30px">\
                <i class="fa fa-eye"></i>\
               </button>'
});

ko.components.register("custom-button", {
  viewModel: function(params) {
    var self = this;

    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
    self.css = params.css ? params.css : 'btn btn-success';
    self.text = params.text ? params.text : '';
  },
  template: '<button type="button" data-bind="click: click, css: css, enable: enable"> <!--ko text: text--><!--/ko--> </button>'
});

ko.components.register("button-add-new", {
  viewModel: function(params) {
    var self = this;
    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
  },
  template:
    '<button class="btn btn-flat btn-primary ripple" data-bind="click: click, enable: enable">\
                <i class="fa fa-plus"></i>\
               </button>'
});

ko.components.register("button-edit", {
  viewModel: function(params) {
    var self = this;
    self.click = params.click;
    self.enable = params.enable ? params.enable : true;
  },
  template:
    '<button class="btn btn-flat btn-info ripple" data-bind="click: click, enable: enable">\
                <i class="fa fa-pencil"></i>\
               </button>'
});

ko.components.register("form-input-text", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
    self.maxlength = params.maxlength === "undefined" ? 1000 : params.maxlength;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<input type="text" class="form-control" data-bind="value:value, attr: { placeholder: placeholder, maxlength: maxlength }, disable: disable">',
    "</div>"
  ].join("")
});

ko.components.register("form-input-date", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<input type="date" class="form-control" data-bind="value:value, attr: { placeholder: placeholder }, disable: disable">',
    "</div>"
  ].join("")
});

ko.components.register("form-input-number", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<input type="number" class="form-control" data-bind="value:value, attr: { placeholder: placeholder }, disable: disable">',
    "</div>"
  ].join("")
});

ko.components.register("form-input-textarea", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
    self.rows = params.rows === "undefined" ? 40 : params.rows;
    self.maxlength = params.maxlength === "undefined" ? 1000 : params.maxlength;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<textarea type="text" class="form-control" data-bind="value:value, attr: { placeholder: placeholder, rows: rows, maxlength: maxlength }, disable: disable"></textarea>',
    "</div>"
  ].join("")
});

ko.components.register("form-input-select", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.options = params.options;
    self.optionsValue = params.optionsValue;
    self.optionsText = params.optionsText;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<select class="form-control" data-bind="value:value, optionsCaption: placeholder, disable: disable, options: options, optionsValue: optionsValue, optionsText:optionsText"></select>',
    "</div>"
  ].join("")
});

ko.components.register("form-input-password", {
  viewModel: function(params) {
    var self = this;
    self.value = params.value;
    self.placeholder = params.placeholder || "";
    self.label = params.label;
    self.css = "form-group " + (params.css || "col-xs-12 col-md-6");
    self.disable = params.disable === "undefined" ? false : params.disable;
    self.isSmall = params.small;
  },
  template: [
    '<div data-bind="css: css">',
    '<label data-bind="text:label"></label>',
    '<input type="password" class="form-control" data-bind="value:value, attr: { placeholder: placeholder }, disable: disable, css: isSmall ? \'input-sm\' : \'\'">',
    "</div>"
  ].join("")
});

ko.components.register("sortable-table-header", {
  viewModel: function(params) {
    var self = this;
    self.sort = params.sort;
    self.order = params.order;
    self.click = params.click;
    self.name = params.name;
    self.label = params.label;

    self.style = ko.computed(function() {
      return self.name == self.sort() ? { fontWeight: "bold" } : {};
    });
    self.caret = ko.computed(function() {
      if (self.sort() != self.name) return "";
      return "glyphicons " + self.order() == "ASC"
        ? "glyphicons-sort-by-attributes"
        : "glyphicons-sort-by-attributes-alt";
    });
  },
  template:
    '<a data-bind="text: label, click:click, style: style" style="user-select: none, cursor:pointer"></a>' +
    '<i data-bind="css:caret"></i>'
});
/**
 * [ Na propriedade 'columns' podemos passar componentes customizados, segue o exemplo ]
    {
        text: 'Ação',
        sWidth: '30%',
        className: 'center'
        components: [
            {
                canRender: function(data){
                    return true;
                },
                template: '<button-table-delete data-bind="click: clicar"></button-table-delete>',
                modelView: {

                    clicar: function(){
                        alert('externo');
                    }

                }
            }
        ]
    }
 */
ko.components.register("data-table", {
    viewModel: function(params) {
        var self = this;

        self.getData = function(event){
            var row = $(event.target).parents('tr');
            return self.dataTable.row(row.hasClass('child') ? row.prev() : row).data();
        };

        self.fnRowCallback = function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            $(nRow).children().each(function(indexChildren, children){
                if ($(children).hasClass('custom-column')){

                    // find column with data
                    var columnDatatable = params.conf.columns[indexChildren];

                    // each child into columns with components
                    $(children).children().each(function(indexC, componentDom){
                        var modelView = columnDatatable.components[$(componentDom).attr('index')].modelView;

                        if (modelView.onClick) {
                            var _click = modelView.onClick;

                            //override method click if action
                            modelView.onClick = function(item, event){
                                _click(item, event, self.getData(event));
                            };
                        }

                        ko.applyBindings(modelView, componentDom);
                    });
                }
            });
        };

        self.renderHtml = function(column){
            return function (data, type, row){
                return column.components.reduce(function(before, current, indice){

                    if (!current.canRender(row)){
                        return before;
                    }

                    var templateDom = $(current.template);
                    templateDom.attr('index', indice);

                    return before + templateDom.prop('outerHTML');
                }, ' ');
            };
        };

        self.makeDefaultComponentsForAction = function(column){
            return [
                {
                    canRender: column.delete.canRender,
                    template: '<button-table-delete data-bind="click: onClick" class="pr-5"></button-table-delete>',
                    modelView: {
                        onClick: function(item, event, object){
                            _hero.confirmation({
                                title: 'Confirmar operação',
                                body: column.delete.messageOnDelete,
                                buttons: _hero.confirmation.defaultConfirmationButtons({
                                    onAccept: function() {
                                        column.delete.click(item, event, object);
                                    },
                                    onAbort: function() { }
                                })
                            });
                        }
                    }
                },
                {
                    canRender: column.edit.canRender,
                    template: '<button-table-view data-bind="click: onClick"></button-table-view>',
                    modelView: {
                        onClick: column.edit.click
                    }
                },
            ];
        };

        self.makeColumns = function(){
            return params.conf.columns.map(function(column){
                column.data = column.name;

                if (column.isAction){

                    column.className = "center custom-column";
                    column.orderable = false;
                    column.components = self.makeDefaultComponentsForAction(column);
                    column.render = self.renderHtml(column);
                } else if (column.components){

                    column.className += " custom-column";
                    column.render = self.renderHtml(column);
                }

                return column;
            });
        };

        self.id = ko.observable(new Date().getTime().toString(36));

        self.conf = $.extend({
            processing: true,
            serverSide: true,
            searching: false,
            lengthMenu: [ 20, 25, 50, 100, 200 ],
            fnServerParams: params.conf.filters || function(){},
            language: {
                url: _hero.routes.baseUrlJoin('datatable/Translate/Portuguese-Brasil.json')
            },
            ajax: {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "X-Requested-With": "XMLHttpRequest"
                },
                type: 'POST',
                url: params.conf.url,
                dataFilter: function(data){
                    var dataJson = jQuery.parseJSON(data);

                    if (dataJson.status == _hero.STATUS_RESPONSE.ERROR){
                        return {
                            data: [],
                            draw: 0,
                            recordsFiltered: 0,
                            recordsTotal: 0
                        };
                    }
                    return JSON.stringify(dataJson.response);
                }
            },
            columns: self.makeColumns(),
            fnRowCallback: self.fnRowCallback
        }, params.conf);

        self.afterRender = function(){
            self.dataTable = $('#' + self.id()).DataTable(self.conf);

            if (params.conf.dataTable){
                params.conf.dataTable(self.dataTable); // observable
            }
        };
    },
    template: [
        '<!-- ko template: { afterRender: $data.afterRender }-->',
            '<table data-bind="attr : { id: $data.id }" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">',
                '<thead>',
                    '<tr data-bind="foreach: conf.columns">',
                        '<th>',
                            '<!--ko text: $data.text --><!--/ko-->',
                        '</th>',
                    '</tr>',
                '</thead>',
            '</table>',
        '<!-- /ko -->'
    ].join('')
});

ko.components.register("item-crop-image", {
    viewModel: function(params) {
        var self = this;

        self.randomID = function(){
            return 'id_' + (Math.floor((1 + Math.random()) * 0x10000)).toString();
        };

        self.pictureImgID = ko.observable(self.randomID());
        self.pictureID = ko.observable(self.randomID());

        self.changingPicture = ko.observable();
        self.previousPicture = ko.observable(null);
        self.fileSelectedTemp = null;

        if (ko.isObservable(params.showAttachImage)){
            self.showAttachImage = params.showAttachImage;
        } else {
            self.showAttachImage = ko.observable((typeof params.showAttachImage === "undefined") ? true : params.showAttachImage);
        }

        self.pictureCropper = params.pictureCropper; // observable
        self.fileSelected = params.fileSelected; // observable

        self.startCrop = function() {
            var image = document.getElementById(self.pictureImgID());

            self.cropperInstance = new Cropper(image, {
                dragMode: 'crop',
                crop: function(event) {
                },
                ready: function () {
                    self.cropperInstance.crop();
                }
            });
        };

        self.putOtherImage = function(){
            var picture = document.getElementById(self.pictureID());
            picture.value = null;
            picture.click();
        };

        self.setImage = function(data){
            document.getElementById(self.pictureImgID()).src = data;
            self.previousPicture(data);
        };

        self.finishCrop = function() {
            var base64 = self.cropperInstance.getCroppedCanvas().toDataURL();

            self.setImage(base64);
            self.pictureCropper(base64);
            self.changingPicture(false);
            self.stopCrop();
            self.fileSelected(self.fileSelectedTemp);
        };

        self.cancelCrop = function() {
            self.changingPicture(false);
            self.stopCrop();
            self.setImage(self.previousPicture());
        };

        self.stopCrop = function() {
            if (self.cropperInstance) {
                self.cropperInstance.destroy();
                self.cropperInstance = null;
            }
        };

        self.showPicture = function(evt) {
            var files = window.event.srcElement.files;

            if (files){
                _hero.file.putImage(document.getElementById(self.pictureImgID()), files[0], function(){

                    _hero.file.toBase64(files[0], function(base64){
                        self.changingPicture(true);
                        self.stopCrop();
                        self.startCrop();
                    });

                    self.fileSelectedTemp = files[0];
                });
            }
        };

        setTimeout(function(){
            var urlImageInitial = !params.urlImageInitial
                    ? _hero.routes.baseUrlJoin('img/no-image.png')
                    : params.urlImageInitial;

            self.setImage(urlImageInitial);
        });
    },
    template: [
        '<div>',
            '<img data-bind="attr: { id: pictureImgID }" style="max-width: 100%;"/>',

            '<input type="file" accept="image/*" style="display:none"',
                'data-bind="event: { change: showPicture }, attr: { id: pictureID }" />',

            '<label class="btn btn-xs btn-success mt-5" data-bind="click: putOtherImage,visible: !changingPicture() && showAttachImage() ">Anexar Imagem</label>',

            '<button type="button" class="btn btn-xs btn-success mr-5" data-bind="click: finishCrop, visible: changingPicture">Feito!</button>',
            '<button type="button" class="btn btn-xs btn-warning" data-bind="click: cancelCrop, visible: changingPicture">Cancelar</button>',
        '</div>'
    ].join('')

});
