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

tlanapi.players.add = function(formdata) {

}
