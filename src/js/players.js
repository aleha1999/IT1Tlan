var countries;
var player_proto;
var add_player_modal;
var playerdata;
var editingPlayer = undefined;

var config = {
    lang: {
        player_added_message: "Spilleren ble lagt til",
        player_add_fail_message: "Noe gikk galt. Sjekk skjemaet og prøv på nytt",
        country_dne: "Landet oppgitt eksisterer ikke",
        get_players_error: "Kunne ikke hente spillere fra serveren",
        delete_player_warning: "Er du sikker på at du vil slette spilleren?",
        delete_player_success: "Spilleren ble slettet",
        delete_player_error: "Spilleren kunne ikke slettes",
        player_edit_success: "Spilleren ble redigert",
        player_edit_failure: "Spilleren kunne ikke redigeres"
    },
    table_pop_f_delay: 70
}

$(document).ready(function(){
    add_player_modal = document.getElementById("add_player_modal");
    add_player_modal = M.Modal.init(add_player_modal,{
        onCloseEnd:on_form_close
    });
    tlanapi.countries.getCountriesInOrder(function(d){
        if(d != false){
            countries = d;
            populateCountries();
            updatePlayers();
        }
    });
    player_proto = $("#player_proto").clone();
    player_proto.removeAttr("id");
    $("#player_proto").remove();
    $("#add_player_save").click(submitForm);
});

function populateCountries() {
    var autoCompleteData = {};
    countries.forEach(function(val){
        autoCompleteData[val.Name] = "/flags/1x1/"+val.ID.toLowerCase()+".svg";
    });
    $("#nat").autocomplete({
        data:autoCompleteData
    });
}

function on_form_close() {
    $("#add_player_form").find("input").val("");
    editingPlayer = undefined;
}

function submitForm() {
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
            M.toast({html:config.lang.country_dne,classes:"red"});
        }
    }
    //Submit the form data
    if(editingPlayer == undefined) {
        tlanapi.players.add(d,function(success){
            if(success) {
                M.toast({html:config.lang.player_added_message,classes:"green"});
                add_player_modal.close();
                updatePlayers();
            } else
                M.toast({html:config.lang.player_add_fail_message,classes:"red"});
        });
    } else {
        d.append("player",editingPlayer);
        tlanapi.players.edit(d,function(r){
            if(r == true) {
                editingPlayer = undefined;
                $("#add_player_modal").modal('close');
                updatePlayers();
                msg_success(config.lang.player_edit_success);
            } else {
                msg_error(config.lang.player_edit_failure);
            }
        });
    }
}

function updatePlayers(populate) {
    if(populate == undefined)
        populate = true;
    tlanapi.players.get(function(d){
        if(d === false) {
            msg_error(config.lang.get_players_error);
            return;
        }
        playerdata = d;
        if(populate)
            populatePlayerTable();
    });
}

function getCountryName(code) {
    var c = null;
    countries.forEach(function(country){
        if(country.ID == code)
            c = country.Name;
    });
    return c;
}

function populatePlayerTable() {
    if($("#playertable tr:not(.head)").length == 0)
        pop();
    else
    $("#playertable").find("tr:not(.head)").fadeOut(function(){
        $("#playertable").find("tr:not(.head)").remove();
        pop();
    });

    function pop() {
        playerdata.forEach(function(player,i) {
            var row = player_proto.clone();
            $(row).find(".firstname").html(player.Firstname);
            $(row).find(".surname").html(player.Surname);
            $(row).find(".email").html(player.Email);
            $(row).find(".tag").html(player.Tag);
            var flag = $(row).find(".origin i");
            $(flag).addClass("flag-icon-"+player.Nationality.toLowerCase());
            $(flag).attr("title",getCountryName(player.Nationality));
            $(row).attr("player",player.PlayerID);
            var r = $("#playertable").append(row);
            $(row).hide();
            setTimeout(function(){
                $(row).fadeIn();
            },config.table_pop_f_delay * i);
        });
        bindEventsToPlayerRows();
    }
}

function bindEventsToPlayerRows() {
    $("tr .actions .inf").click(function(){
        showInfoFor($(this).closest("tr").attr("player"));
    });

    $("tr .actions .del").click(function(){
        if(confirm(config.lang.delete_player_warning)) {
            var player = $(this).closest("tr").attr("player");
            tlanapi.players.delete(player,function(r){
                if(r == true) {
                    msg_success(config.lang.delete_player_success);
                    updatePlayers(false);
                    $("tr[player="+player+"]").fadeOut();
                } else {
                    msg_error(config.delete_player_error);
                }
            });
        }
    });

    $("tr .actions .edit").click(function(){
        var player = $(this).closest("tr").attr("player");
        editInfoFor(player);
    });
}

function showInfoFor(player) {
    var playerinfo;
    playerdata.forEach(function(p){
        console.log(player  );
        if(playerinfo != undefined) return;
        if(p.PlayerID == player)
            playerinfo = p;
    });
    function sel(s) {
        return $("#player_info_modal "+s);
    }
    sel(".fname").html(playerinfo.Firstname);
    sel(".lname").html(playerinfo.Surname);
    sel(".tag").html(playerinfo.Tag);
    sel(".address").html(playerinfo.Address);
    sel(".phone").html(playerinfo.Phonenumber);
    sel(".mail").html(playerinfo.email);
    sel(".postnumber").html(playerinfo.Postnr);
    sel(".rating").html(playerinfo.Rating);
    sel(".nat").html("").append("<i class='flag-icon flag-icon-"+playerinfo.Nationality.toLowerCase()+"'></i>").append("<span> "+tlanapi.countries.getName(playerinfo.Nationality)+"</span>");
    $("#player_info_modal").modal("open");
}

function setFormValue(name,val) {
    $("#add_player_form *[name='"+name+"']").val(val);
}

function editInfoFor(player) {
    editingPlayer = player;
    var playerinfo;
    for(var i = 0; i<playerdata.length;i++) {
        if(playerdata[i].PlayerID == player) {
            playerinfo = playerdata[i];
            break;
        }
    }
    setFormValue("firstname",playerinfo.Firstname);
    setFormValue("surname",playerinfo.Surname);
    setFormValue("tag", playerinfo.Tag);
    setFormValue("nationality", tlanapi.countries.getName(playerinfo.Nationality));
    setFormValue("address", playerinfo.Address);
    setFormValue("postnumber",playerinfo.Postnr);
    setFormValue("phone", playerinfo.Phonenumber);
    setFormValue("email", playerinfo.Email);
    setFormValue("rating",playerinfo.Rating);
    M.updateTextFields();
    $("#add_player_modal").modal('open');
}
