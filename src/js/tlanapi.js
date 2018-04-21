var tlanapi = {};
tlanapi.countries = {};
tlanapi.countries.cache = undefined;
tlanapi.players = {};

tlanapi.countries.getCountriesInOrder = function(callback) {
    $.get("/api/countries/getAllInOrder.php",function(data){
        if(data.success) {
            tlanapi.countries.cache = data.data;
            callback(data.data);
        } else
            callback(false);
    });
}

tlanapi.countries.getName = function(c) {
    var cname = undefined;
    tlanapi.countries.cache.forEach(function(val) {
        if(cname != undefined) return;
        if(c.toLowerCase() == val.ID.toLowerCase())
            cname = val.Name;
    });
    return cname;
}

tlanapi.countries.getID = function(c) {
    var cid = undefined;
    tlanapi.countries.cache.forEach(function(val) {
        if(cid != undefined) return;
        if(c.toLowerCase() == val.name.toLowerCase())
            cid = val.ID;
    });
    return cid;
}

tlanapi.players.add = function(formdata,callback) {
    $.ajax({
        url:"/api/players/add.php",
        data:formdata,
        type:"POST",
        processData:false,
        contentType:false,
        success:function(data) {
            if(data.success)
                callback(true);
            else
                callback(false);
        }
    });
}

tlanapi.players.get = function(callback) {
    $.get("/api/players/get.php",function(d){
        if(d.success === true)
            callback(d.data);
        else
             callback(false);
    });
}
