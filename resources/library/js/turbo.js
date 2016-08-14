function Promise() {
    this._thens = [];
}

Promise.prototype.then = function (success, failure) {
    this._completed = false;
    this._thens.push({onsuccess: success, onfailure: failure});
};

Promise.prototype.isCompleted = function () {
    return (this._completed === true);
};

Promise.prototype.complete = function (success, args) {
    this._completed = true;
    var callback, i = 0;
    while (callback = this._thens[i++]) {
        success && callback.onsuccess && callback.onsuccess(args);
        !success && callback.onfailure && callback.onfailure(args);
    }
};

var Turbo = (function () {
    var turbo = { __namespace: true };

    var internal = {
        isFormBusy: 0
    };

    turbo.api = {};
    turbo.form = {};
    turbo.ext = {};

    //turbo.api.BaseUrl = "http://michaelmouawad.byethost11.com/api.php";
    turbo.api.BaseUrl = "http://demo/api.php";

    turbo.api.get = function (entityName, filter) {
        var promise = new Promise();
        var url = turbo.api.BaseUrl + "/" + entityName + "?";

        if (typeof filter === "object") {
            for (var f in filter) {
                if (filter.hasOwnProperty(f)) {
                    url += (f + "=" + filter[f] + "&");
                }
            }
        }

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function (response) {
                promise.complete(true, response);
            },
            error: function(xhr, textStatus, error){
                console.log(error);
                promise.complete(false);
            }
        });

        return promise;
    };

    turbo.api.post = function (entityName, data) {
        var promise = new Promise();
        var url = turbo.api.BaseUrl + "/" + entityName;

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            success: function (response) {
                promise.complete(true, response);
            },
            error: function(xhr, textStatus, error){
                console.log(error);
                promise.complete(false);
            }
        });

        return promise;
    };

    turbo.api.delete = function (entityName, id) {
        var promise = new Promise();
        var url = turbo.api.BaseUrl + "/" + entityName;

        $.ajax({
            url: url,
            type: "DELETE",
            headers: { Id: id },
            dataType: "json",
            success: function (response) {
                promise.complete(true, response);
            },
            error: function(xhr, textStatus, error){
                console.log(error);
                promise.complete(false);
            }
        });

        return promise;
    };

    turbo.form.setFormBusy = function(busy) {
        var initialState = internal.isFormBusy;

        internal.isFormBusy += busy ? 1 : -1;
        if (internal < 0) internal = 0;

        if (typeof internal.busyOverlay === "undefined") {
            $("#turbo_busy_overlay").remove();
            var overlay = $("<div/>", { id: "turbo_busy_overlay", class: "turbo-overlay-busy" });
            $("body").append(overlay);
            internal.busyOverlay = $(overlay);
        }

        if (internal.isFormBusy === 0 && initialState > 0) {
            internal.busyOverlay.fadeOut(200);
        } else if (internal.isFormBusy > 0 && initialState === 0) {
            internal.busyOverlay.fadeIn(200);
        }
    };

    return turbo;
})();