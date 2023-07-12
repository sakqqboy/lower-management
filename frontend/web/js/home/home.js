$("#admin-icon").click(function() {
    $("#admin-menu").toggle();
});
$("#close-admin-menu").click(function() {
    $("#admin-menu").hide();
});
$("#close-noti-message").click(function() {
    $(".message-noti-box").hide();
});
$(document).mouseup(function(e) {
    var admin = $("#admin-menu");
    if (!admin.is(e.target) &&
        admin.has(e.target).length === 0) {
        admin.hide();
    }
    var monthCalendar = $(".month-calendar-box");
    if (!monthCalendar.is(e.target) &&
        monthCalendar.has(e.target).length === 0) {
        monthCalendar.hide();
    }
    var clientSelectBox = $(".client-select-box");
    if (!clientSelectBox.is(e.target) &&
        clientSelectBox.has(e.target).length === 0) {
        clientSelectBox.hide();
    }
    var notiBox = $(".message-noti-box");
    if (!notiBox.is(e.target) &&
        notiBox.has(e.target).length === 0) {
        notiBox.hide();
    }

    var menuChat = $(".chat-context-menu");
    if (!menuChat.is(e.target) &&
        menuChat.has(e.target).length === 0) {
        menuChat.hide();
    }
    var menuChat = $(".chat-context-menu-other");
    if (!menuChat.is(e.target) &&
        menuChat.has(e.target).length === 0) {
        menuChat.hide();
    }
    var menuChat = $(".modal-schedule");
    if (!menuChat.is(e.target) &&
        menuChat.has(e.target).length === 0 || menuChat.is(e.target)) {
        menuChat.hide();
    }
});