$(document).ready(init);

function init() {
    M.AutoInit();
    //$("select").formSelect();
}

function msg_error(msg) {
    M.toast({html:msg,classes:"red"});
}

function msg_success(msg) {
    M.toast({html:msg,classes:"green"});
}

function msg_info(msg) {
    M.toast({html:msg});
}
