var row_proto;
var team_row_proto;
var games;
var competitions;
var teams;
var editingCompTeamPart;
var editing;

$(document).ready(function(){
    $("#add_comp_modal").modal({
        onCloseEnd: on_form_close
    });
    $("#comp_form_save").click(saveComp);
    $("#add_comp_form").submit(function(e){
        e.preventDefault();
        saveComp();
    });
    populate_games_list();
    row_proto = $("#comp_row_proto").clone();
    $(row_proto).removeAttr("id");
    $("#comp_row_proto").remove();
    team_row_proto = $("#participating_team_row_proto").clone();
    $(team_row_proto).removeAttr("id");
    $("#participating_team_row_proto").remove();
    populate_comp_list();
    tlanapi.teams.get(function(d){
        teams = d;
        var nd = {};
        teams.forEach(function(team){
            nd[team.Name] = null;
        });
        $("#teamselect").autocomplete({
            data:nd
        });
    });

    $("#add_team_form").submit(function(e){
        e.preventDefault();
        var teamid = 0;
        for(var i = 0; i<teams.length; i++) {
            if($("#teamselect").val() == teams[i].Name) {
                teamid = teams[i].TeamID;
                break;
            }
        }
        tlanapi.competitions.addParticipatingTeam(editingCompTeamPart,teamid,function(r){
            if(r === true) {
                msg_success("Laget ble lagt til");
                populate_teams_table(editingCompTeamPart);
            } else {
                msg_error("Laget kunne ikke legges til");
            }
        });
    });
});

function on_form_close() {
    $("#add_comp_modal input").val("");
    editing = undefined;
    M.updateTextFields();
}

function populate_games_list() {
    tlanapi.games.get(function(r){
        $("#addcompgame").html("");
        r.forEach(function(game){
            var opt = $("<option></option>").val(game.GameID).html(game.Name);
            $("#addcompgame").append(opt);
        });
        $("#addcompgame").formSelect();
    });
}

function saveComp() {
    var formData = new FormData(document.getElementById("add_comp_form"));
    formData.append("datetime", ((new Date(formData.get("date")+" "+formData.get("time"))).getTime())/1000);
    if(editing != undefined)
        formData.append("compid",editing);
    if(editing == undefined)
        tlanapi.competitions.add(formData,function(r){
            if(r === true) {
                msg_success("Konkurransen ble lagt til");
                $("#add_comp_modal").modal('close');
                populate_comp_list();
            } else {
                msg_error("Noe gikk galt");
            }
        });
    else
        tlanapi.competitions.update(formData,function(r){
            if(r === true) {
                msg_success("Konkurransen ble redigert");
                $("#add_comp_modal").modal('close');
                populate_comp_list();
            } else {
                msg_error("Noe gikk galt");
            }
        });
}

function populate_comp_list() {
    tlanapi.competitions.get(function(d){
        competitions = d;
        $("#comps_table tr:not(.head)").remove();
        d.forEach(function(comp){
            console.log(comp);
            var row = $(row_proto).clone();
            $(row).find(".title").html(comp.Title);
            $(row).find(".game").html(comp.GameName);
            var d = new Date(comp.Date*1000);
            $(row).find(".date").html(d.toLocaleDateString()+" "+d.toLocaleTimeString());
            $(row).attr("comp",comp.CompID);
            $("#comps_table").append(row);
        });
        $("#comps_table .actions .delete").click(function(){
            console.log($(this).closest("tr").attr("comp"));
            tlanapi.competitions.delete($(this).closest("tr").attr("comp"),function(d){
                if(d === true) {
                    msg_success("Konkurransen ble slettet");
                    populate_comp_list();
                } else {
                    msg_error("Konkurransen kunne ikke slettes");
                }
            });
        });
        $(".actions .manage").click(function(){
            editingCompTeamPart = $(this).closest("tr").attr("comp");
            populate_teams_table($(this).closest("tr").attr("comp"));
            $("#comp_team_manage_modal").modal('open');
        });
        $(".actions .edit").click(function(){
            var compid = $(this).closest("tr").attr("comp");
            editing = compid;
            var compinfo;
            for(var i = 0; i<competitions.length; i++) {
                if(compid == competitions[i].CompID) {
                    compinfo = competitions[i];
                    break;
                }
            }
            console.log(compinfo);
            $("#addcomptitle").val(compinfo.Title);
            $("#addcompgame").val(compinfo.Game);
            var d = new Date(compinfo.Date*1000);
            $("#date").datepicker("setDate",d);
            var tstring = "";
            tstring += d.getHours();
            tstring += ":";
            tstring += d.getMinutes();
            M.Timepicker.getInstance(document.getElementById("time")).time = tstring;
            $("#time").timepicker();
            M.updateTextFields();
            $("#addcompgame").formSelect();
            $("#add_comp_modal").modal('open');
        });
    });
}

function populate_teams_table(compid) {
    tlanapi.competitions.getParticipatingTeams(compid,function(d){
        $("#participating_teams tr:not(.head)").remove();
        d.forEach(function(team){
            var row = $(team_row_proto).clone();
            $(row).attr("team",team.Team)
            $(row).find(".name").html(team.Name);
            $(row).find(".points").html(team.Points)
            $("#participating_teams").append(row);
        });
        $(".actions .addpoint").click(function(){
            $.post("/api/competitions/addpoint.php",{comp:editingCompTeamPart,team:$(this).closest("tr").attr("Team")},function(d) {
                populate_teams_table(editingCompTeamPart);
            });
        });

        $(".actions .subpoint").click(function(){
            $.post("/api/competitions/removepoint.php",{comp:editingCompTeamPart,team:$(this).closest("tr").attr("Team")},function(d) {
                populate_teams_table(editingCompTeamPart);
            });
        });
    });
}
