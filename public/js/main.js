var groupBy = function(xs, key) {
    return xs.reduce(function(rv, x) {
        (rv[x[key]] = rv[x[key]] || []).push(x);
        return rv;
    }, {});
};

function ToShortDate(date) {
    var ano = date.getFullYear();
    var mes = PadLeft(date.getMonth(), 2, "0");
    var dia = PadLeft(date.getDay(), 2, "0");
    return dia + "/" + mes + "/" + ano;
}

function ToShortDateTime(date) {
    var short = ToShortDate(date);
    var hora = PadLeft(date.getHours(), 2, "0");
    var minuto = PadLeft(date.getMinutes(), 2, "0");
    var segundo = PadLeft(date.getSeconds(), 2, "0");
    return short + " " + hora + ":" + minuto + ":" + segundo;
}

function PadLeft(text, maxLength, character) {
    var str = text + "";
    var pad = "";
    for (var i = 0; i < maxLength; i++) {
        pad += character;
    }
    return pad.substring(0, pad.length - str.length) + str;
}
function Distinct(value, index, self) {
    return self.indexOf(value) === index;
}
var AdminLTEOptions = {
    sidebarExpandOnHover: true,
    enableBoxRefresh: true,
    enableBSToopltip: true
};

var Constants = Constants || {};
// Date constants
Constants.DateFormat = "DD/MM/YYYY";
Constants.MinDate = "01/01/1900";
Constants.LaravelDateFormat = "YYYY-MM-DD HH:mm:ss";
Constants.InputDateFormat = "YYYY-MM-DD";

// Flow Constants
Constants.STEPS = {
    Listing: 0,
    ShowingDetails: 1,
    AddingNew: 2,
    Editing: 3
};

Constants.YesNo = [{ value: true, text: "Sim" }, { value: false, text: "Não" }];

String.prototype.has = function(value) {
    return this.toUpperCase().indexOf(value.toUpperCase()) > -1;
};
