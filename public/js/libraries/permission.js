;(function () {

    if (!_hero) {
        throw "Hero Library is not defined, the system will not work properly";
    };

    var Permission = function(){
        var self = this;

        self.roles = [];
        self.permissions = [];

        (function() {
            _hero
                .ajax()
                .url(_hero.routes.find("api.permission.rolesUser"))
                .verb("GET").async(false)
                .then(function(data) {
                    if (data.status == _hero.STATUS_RESPONSE.SUCCESS) {
                        self.roles = data.response.roles;
                        self.permissions = data.response.permissions;
                    }
                })
                .execute();
        })();

        self.arrayContainsArray = function(array1, array2, allTrue) {
            var callback = function(value){
                return array2.indexOf(value) > -1;
            };

            return (allTrue ? array1.every(callback) : array1.some(callback));
        }

        self.can = function(permission, allTrue){
            if (Array.isArray(permission)){
                return self.arrayContainsArray(permission, self.permissions, allTrue);
            }

            return self.permissions.indexOf(permission) > -1;
        };

        self.hasRole = function(role, allTrue){
            if (Array.isArray(role)){
                return self.arrayContainsArray(role, self.roles, allTrue);
            }

            return self.roles.indexOf(role) > -1;
        };

        return {
            can: self.can,
            hasRole: self.hasRole
        };
    };

    _hero.permission = new Permission;
})();
