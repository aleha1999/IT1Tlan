var teams = {};
var teams_row_proto;
var team_player_proto;
var players;
var countries;
var editingTeam = undefined;
var curTeamSetup;
var config = {
    lang: {
        team_add_success: "Laget ble lagt til",
        team_add_failure: "Laget kunne ikke legges til",
        team_delete_success: "Laget ble slettet",
        team_delete_failure: "Laget kunne ikke slettes. Sjekk at ikke fremmede verdier avhenger av laget og prøv igjen",
        team_edit_success: "Laget ble redigert",
        team_edit_failure: "Laget kunne ikke redigeres",
        player_doesnotexist: "Spilleren eksisterer ikke",
        team_player_added: "Spilleren ble lagt til",
        team_player_adderr: "Spilleren kunne ikke legges til"
    }
}

$(document).ready(function(){
    //Initialize form
    var add_team_modal = document.getElementById("add_team_modal");
    M.Modal.init(add_team_modal,{
        onCloseEnd: on_modal_close
    });
    var team_setup_modal = document.getElementById("team_setup_modal");
    M.Modal.init(team_setup_modal);
    //Remove and store team row proto
    teams_row_proto = $("#team_row_proto").clone();
    $(teams_row_proto).removeAttr("id");
    $("#team_row_proto").remove();
    //Populate the teams table
    updateTeams();

    tlanapi.players.get(function(d){
        players = d;
        populatePlayerSelect();
    });

    tlanapi.countries.getCountriesInOrder(function(c){
        countries = c;
        populateCountries();
    });

    $("#saveteam").click(submitForm);
    $("#team_setup_form").submit(addPlayer);
    team_player_proto = $("#team_player_proto").clone();
    $(team_player_proto).removeAttr("id");
    $("#team_player_proto").remove();
});

function on_modal_close() {
    $("#add_team_form input").val("");
}

function updateTeams() {
    $("#teamstable tr").fadeOut(function(){
        tlanapi.teams.get(function(d){
        teams = d;
        tlanapi.players.get(function(){
            populateTeamsTable();
        });
    });
    });
}

function populateTeamsTable() {
    $("#teamstable tr:not(.head)").remove();
    teams.forEach(function(team){
        var row = $(teams_row_proto).clone();
        $(row).find(".name").html(team.Name);
        if(team.Logo != null)
            $(row).find("img").attr("src","/img/logos/"+team.Logo);
        else
            $(row).find("img").hide();
        $(row).find(".nationality i").remove();
        $(row).find(".nationality").append($("<i></i>").addClass("flag-icon").addClass("flag-icon-"+team.Nationality.toLowerCase()).attr("title",tlanapi.countries.getName(team.Nationality)));
        $(row).find(".captain").html(
            tlanapi.players.getName(team.Captain)
        );
        $(row).attr("team",team.TeamID)
        $("#teamstable").append(row);
    });
    bindEventsToTeamRows();
    $("#teamstable tr").fadeIn();
}

function bindEventsToTeamRows() {
    $("#teamstable .actions .delete").click(function(){
        var team = $(this).closest("tr").attr("team");
        tlanapi.teams.delete(team,function(s){
            if(s) {
                msg_success(config.lang.team_delete_success);
                updateTeams();
            } else
                msg_error(config.lang.team_delete_failure);
        });
    });

    $("#teamstable .actions .edit").click(function(){
        var team = getTeam(this);
        editTeam(team);
    });

    $("#teamstable .actions .editteam").click(function(){
        editTeamSetup(getTeam(this));
    });

    function getTeam(_this) {
        return $(_this).closest("tr").attr("team");
    }
}

function populatePlayerSelect() {
    var data = {};
    players.forEach(function(player){
        data[player.Email] = null;
    });
    $("#capselect").autocomplete({
        data:data
    });
    $("#pselect").autocomplete({
        data:data
    });
}

function populateCountries() {
    var cData = {};
    countries.forEach(function(country){
        cData[country.Name] = "/flags/1x1/"+country.ID+".svg"
    });
    $("#natselect").autocomplete({
        data:cData
    });
}

function submitForm() {
    var d = new FormData(document.getElementById("add_team_form"));
    d.set("nationality",tlanapi.countries.getID(d.get("nationality")));
    d.set("captain",tlanapi.players.getID(d.get("captain")));
    if(editingTeam != undefined) {
        d.append("teamid",editingTeam);
        tlanapi.teams.edit(d,function(s){
            if(s) {
                msg_success(config.lang.team_edit_success);
                $("#add_team_modal").modal('close');
                updateTeams();
            } else {
                msg_error(config.lang.team_edit_failure);
            }
        });
    } else {
        tlanapi.teams.add(d,function(r){
            if(r == true) {
            $("#add_team_modal").modal('close');
            updateTeams();
            msg_success(config.lang.team_add_success);
        } else {
            msg_error(config.lang.team_add_failure);
        }
        });
    }
}

function addPlayer(e) {
    e.preventDefault();
    var pEmail = $("#pselect").val();
    var pID;
    for(var i = 0; i<players.length; i++) {
        if(players[i].Email == pEmail) {
            pID = players[i].PlayerID;
            break;
        }
    }
    if(pID == undefined) {
        msg_error(config.lang.player_doesnotexist);
        return;
    }

    tlanapi.teams.addplayer(pID,curTeamSetup,function(r){
        if(r == true) {
            msg_success(config.lang.team_player_added);
            update_team_players();
        } else {
            msg_error(config.lang.team_player_adderr);
        }
    });
}

function editTeam(team) {
    editingTeam = team;
    var teaminfo;
    for(var i = 0; i<teams.length; i++) {
        if(teams[i].TeamID == team) {
            teaminfo = teams[i];
            break;
        }
    }
    function setval(name,val) {
        $("#add_team_form input[name='"+name+"']").val(val);
    }
    setval("name",teaminfo.Name);
    setval("captain",tlanapi.players.getEmail(teaminfo.Captain));
    setval("nationality",tlanapi.countries.getName(teaminfo.Nationality));
    M.updateTextFields();
    $("#add_team_modal").modal('open');
}

function update_team_players() {
    tlanapi.teams.getPlayers(curTeamSetup,function(d){
        if(d === false) {
            msg_error("Kunne ikke hente spillerdata");
            return;
        } else {
            $("#team_player_table tr:not(.head)").remove();
            d.forEach(function(pl){
                var row = $(team_player_proto).clone();
                console.log(row);
                var playerinfo = tlanapi.players.getInfo(pl.Player);
                $(row).find(".name").html(playerinfo.Firstname+" "+playerinfo.Surname);
                $(row).find(".email").html(playerinfo.Email);
                $(row).attr("player",pl.Player);
                $("#team_player_table").append(row);
            });

            $("#team_player_table .actions .delete").click(function(){
                var player = $(this).closest("tr").attr("player");
                tlanapi.teams.removeplayer(player,curTeamSetup,function(d) {
                    if(d) {
                        msg_success("Spilleren ble fjernet fra laget");
                        update_team_players();
                    } else {
                        msg_error("Spilleren kunne ikke fjernes fra laget");
                    }
                })
            });
        }
    });
}

function editTeamSetup(team) {
    curTeamSetup = team;
    update_team_players();
    $("#team_setup_modal").modal('open');
}
