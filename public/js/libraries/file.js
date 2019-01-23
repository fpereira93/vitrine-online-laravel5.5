;(function () {

    if (!_hero) {
        throw "Library is not defined, the system will not work properly";
    };

    var FileManipulation = function(){
        var self = this;

        self.infor = function(file){
            return {
                name: escape(file.name),
                size: file.size,
                type: file.type
            };
        };

        self.toBase64 = function(file, next){
            var reader = new FileReader();

            reader.onload = function(readerEvt) {
                var binaryString = readerEvt.target.result;
                next(btoa(binaryString));
            };

            reader.readAsBinaryString(file)
        };

        self.putImage = function(element, fileImage, onFinished){
            try {
                var infor = self.infor(fileImage);

                self.toBase64(fileImage, function(base64){
                    var src = 'data:' + infor.type + ';base64,' + base64;
                    element.src = src;

                    if (onFinished){
                        onFinished();
                    }
                });
            } catch(err) {
                console.error(err.message);
            }
        };

        return {
            infor: self.infor,
            toBase64: self.toBase64,
            putImage: self.putImage
        };
    };

    _hero.file = new FileManipulation;
})();
