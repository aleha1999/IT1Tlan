var tlanapi = {};
tlanapi.countries = {};
tlanapi.countries.cache = undefined;
tlanapi.players = {};
tlanapi.players.cache = undefined;
tlanapi.teams = {};
tlanapi.games = {};

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
        if(c.toLowerCase() == val.Name.toLowerCase())
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
        if(d.success === true){
            tlanapi.players.cache = d.data;
            callback(d.data);
        } else
             callback(false);
    });
}

tlanapi.players.getID = function(email) {
    for(var i = 0; i < tlanapi.players.cache.length; i++) {
        if(tlanapi.players.cache[i].Email == email)
            return tlanapi.players.cache[i].PlayerID;
    }
    return false;
}

tlanapi.players.delete = function(player,callback) {
    $.get("/api/players/delete.php",{player:player},function(d){
        if(d.success == true)
            callback(true);
        else
            callback(false);
    });
}

tlanapi.players.edit = function(d,c){
    $.ajax({
        url:"/api/players/edit.php",
        data:d,
        type:"POST",
        processData:false,
        contentType:false,
        success:function(data) {
            if(data.success)
                c(true);
            else
                c(false);
        }
    });
}

tlanapi.players.getName = function(id) {
    for(var i = 0; i<tlanapi.players.cache.length; i++) {
        if(tlanapi.players.cache[i].PlayerID == id)
            return tlanapi.players.cache[i].Firstname+" "+tlanapi.players.cache[i].Surname;
    }
    return false;
}

tlanapi.players.getInfo = function(id) {
    for(var i = 0; i<tlanapi.players.cache.length; i++) {
        if(tlanapi.players.cache[i].PlayerID == id)
            return tlanapi.players.cache[i];
    }
    return false;
}

tlanapi.players.getEmail = function(id) {
    return tlanapi.players.getInfo(id).Email;
}

tlanapi.teams.add = function(d,c) {
    $.ajax({
        url:"/api/teams/add.php",
        data:d,
        type:"POST",
        processData:false,
        contentType:false,
        success:function(data) {
            if(data.success)
                c(true);
            else
                c(false);
        }
    });
}

tlanapi.teams.get = function(callback) {
    $.get("/api/teams/get.php",function(d) {
        callback(d.data);
    });
}

tlanapi.teams.edit = function(d,c){
    $.ajax({
        url:"/api/teams/edit.php",
        data:d,
        type:"POST",
        processData:false,
        contentType:false,
        success:function(data) {
            if(data.success)
                c(true);
            else
                c(false);
        }
    });
}

tlanapi.teams.delete = function(teamid,c) {
    $.post("/api/teams/delete.php",{teamid:teamid},function(d){
        c(d.success);
    });
}

tlanapi.teams.addplayer = function(playerid,team,c) {
    $.post("/api/teams/addplayer.php",{player:playerid,team:team},function(r){
        c(r.success);
    });
}

tlanapi.teams.removeplayer = function(playerid,team,c) {
    $.post("/api/teams/removeplayer.php",{player:playerid,team:team},function(r){
        c(r.success);
    })
}

tlanapi.teams.getPlayers = function(teamID,c) {
    $.get("/api/teams/getplayers.php",{team:teamID},function(d){
        if(d.success)
            c(d.data);
        else
            c(false);
    });
}

tlanapi.games.get = function(c) {
    $.get("/api/games/get.php",function(data){
        if(data.success)
            c(data.data);
        else
            c(false);
    });
}

tlanapi.games.add = function(name,c){
    $.post("/api/games/add.php",{name:name},function(d){
        c(d.success);
    });
}

tlanapi.games.delete = function(game,c) {
    $.post("/api/games/delete.php",{game:game},function(d){
        c(d.success);
    });
}

tlanapi.games.update = function(game,gamename,c){
    $.post("/api/games/update.php",{game:game,name:gamename},function(d){
        c(d.success);
    });
}
