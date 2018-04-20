var countries;
var player_proto;

$(document).ready(function(){
    var add_player_modal = document.getElementById("add_player_modal");
    M.Modal.init(add_player_modal,{
        onCloseEnd:on_form_close
    });
    tlanapi.countries.getCountriesInOrder(function(d){
        if(d != false){
            countries = d;
            populateCountries();
        }
    });
    player_proto = $("#player_proto").clone();
    player_proto.removeAttr("id");
    $("#player_proto").remove();
    $("#add_player_save").click(submitForm);
    $("#add_player_form").submit(function(e){
        e.preventDefault();
        var d = new FormData(document.getElementById("add_player_form"));
        //Convert name of country to code.
        var c = d.get('nationality');
        if(c != "") {
            var foundcountry = false;
            countries.forEach(function(country) {
                if(country.Name == c) {
                    foundcountry = true;
                    d.set("nationality",country.ID);
                }
            });
            if(!foundcountry) {
                M.toast({html:"Landet oppgitt er ikke et land",classes:"red"});
            }
        }
        //Submit the form data

    });
});

function populateCountries() {
    var autoCompleteData = {};
    countries.forEach(function(val){
        autoCompleteData[val.Name] = null;
    });
    $("#nat").autocomplete({
        data:autoCompleteData
    });
}

function on_form_close() {
    $("#add_player_form").find("input").val("");
}

function submitForm() {
    $("#add_player_form").submit();
}
