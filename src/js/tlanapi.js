var tlanapi = {};
tlanapi.countries = {};
tlanapi.players = {};

tlanapi.countries.getCountriesInOrder = function(callback) {
    $.get("/api/countries/getAllInOrder.php",function(data){
        if(data.success)
            callback(data.data);
        else
            callback(false);
    });
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
