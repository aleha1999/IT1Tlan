var game_row_proto;
var gameEditing;
var gameData;

$(document).ready(function(){
    game_row_proto = $("#game_row_proto").clone();
    $(game_row_proto).removeAttr("id");
    $("#game_row_proto").remove();
    $("#save_game").click(addgame);
    $("#add_game_form").modal({
        onCloseEnd:function(){
            gameEditing = undefined;
            $("#gamename").val("");
        }
    });
    $("#add_game_form").submit(function(e){
        e.preventDefault();
        addgame();
    });
    populate_games_list();
});

function populate_games_list() {
    tlanapi.games.get(function(d){
        if(d === false) {
            msg_error("Kunne ikke hente spilliste");
            return;
        }
        gameData = d;
        $("#games_table tr:not(.head)").remove();
        d.forEach(function(game){
            var row = $(game_row_proto).clone();
            $(row).find(".name").html(game.Name);
            $(row).attr("game",game.GameID)
            $("#games_table").append(row);
        });
        $(".actions .delete").click(function(){
            tlanapi.games.delete($(this).closest("tr").attr("game"),function(r){
                if(r) {
                    msg_success("Spillet ble slettet");
                    populate_games_list();
                } else {
                    msg_error("Spillet kunne ikke slettes");
                }
            });
        });

        $(".actions .edit").click(function(){
            gameEditing = $(this).closest("tr").attr("game");
            var gamename;
            for(var i = 0; i<gameData.length; i++) {
                if(gameData[i].GameID == gameEditing) {
                    gamename = gameData[i].Name;
                    break;
                }
            }
            $("#gamename").val(gamename);
            M.updateTextFields();
            $("#game_add_modal").modal('open');
        });
    });
}

function addgame() {
    if(gameEditing != undefined) {
        tlanapi.games.update(gameEditing,$("#gamename").val(),function(s){
            if(s) {
                msg_success("Spillet ble redigert");
                $("#gamename").val("");
                $("#game_add_modal").modal('close');
                gameEditing = undefined;
                populate_games_list();
            } else {
                msg_error("Spillet kunne ikke redigeres");
            }
        });
    } else {
        tlanapi.games.add($("#gamename").val(),function(d){
            if(d) {
                msg_success("Spillet ble lagt til");
                $("#gamename").val("");
                $("#game_add_modal").modal('close');
                populate_games_list();
            } else {
                msg_error("Spillet kunne ikke legges til");
            }
        });
    }
}
