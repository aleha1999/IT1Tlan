var game_row_proto;

$(document).ready(function(){
    game_row_proto = $("#game_row_proto").clone();
    $(game_row_proto).removeAttr("id");
    $("#game_row_proto").remove();
    $("#save_game").click(addgame);
    populate_games_list();
});

function populate_games_list() {
    tlanapi.games.get(function(d){
        if(d == false) {
            msg_error("Kunne ikke hente spilliste");
            return;
        }
        $("#games_table tr:not(.head)").remove();
        d.forEach(function(game){
            var row = $(game_row_proto).clone();
            $(row).find(".name").html(game.Name);
            $(row).attr("game",game.GameID)
            $("#games_table").append(row);
        });
    });

    $(".actions .delete").click(function(){
        tlanapi.games.delete($(this).closest("tr").attr("game"),function(r){
            
        });
    });
}

function addgame() {
    tlanapi.games.add($("#gamename").val(),function(d){
        if(d) {
            msg_success("Spillet ble lagt til");
            $("#gamename").val("");
            $("#game_add_modal").modal('close');
        } else {
            msg_error("Spillet kunne ikke legges til");
        }
    });
}
