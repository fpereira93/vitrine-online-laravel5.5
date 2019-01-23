;(function () {

    if (!_hero) {
        throw "Library is not defined, the system will not work properly";
    };

    var Format = function(){
        var self = this;

        self.numberToReal = function(number){
            var number = number.toFixed(2).split('.');
            number[0] = "R$ " + number[0].split(/(?=(?:...)*$)/).join('.');
            return number.join(',');
        };

        return {
            numberToReal: self.numberToReal
        };
    };

    _hero.format = new Format;
})();
