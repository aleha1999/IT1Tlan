var tlanapi = {};

tlanapi.getCountries = function() {

}

tlanapi.getCountriesInOrder = function(callback) {
    $.get("/api/countries/getAllInOrder.php",function(data){
        if(data.success)
            callback(data.data);
        else
            callback(false);
    });
}
