$(document).ready(function () {
    ////////////////////////
    $(".list-group-item").click(function () {
        $('#dialog').html(' ');
        var nkr = $(".navbar-nav li:first img").attr("src")
        var fr_id = $(this).attr("id");
        var fr_nkr = $(this).find("img").attr("src")
        var ur_id = $(".navbar-nav li:first").attr("id");
        var title = $(this).last().text();
        $.get('/site/message/' + fr_id, {}, function (data) {
            obj = jQuery.parseJSON(data)
            $('<div class="col-md-8 col-xs-8">\
                <h3 class="panel-title"><span class="glyphicon glyphicon-comment" id="' + fr_id + '"></span> Conversation  ' + title + '</h3>\
            </div> \
            <div class="col-xs-12 col-md-12">\
                <div class="panel panel-default">\
                <div class="panel-body msg_container_base" style="" id="box">').appendTo("#dialog")
            $.each(obj, function (key, value) {
                if (value['from'] == ur_id) {
                    $('<div class="row msg_container base_sent" id="' + value.message_id + '">\
                        <div class="">\
                            <div class="messages msg_sent">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00"style="float: left;">' + value.send_date + '</time>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + nkr + '" class=" img-responsive ">\
                        </div>\
                    </div>').appendTo("#box");
                }
                else {
                    $('<div class="row msg_container base_receive" id="' + value.message_id + '">\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + fr_nkr + '"  class=" img-responsive">\
                        </div>\
                        <div class="">\
                            <div class="messages msg_receive">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00" style="float: right;margin: -7px;">' + value.send_date + '</time>\
                                                </div>\
                                            </div>').appendTo("#box");
                }

            });
            $('</div>\
                <div class="panel-footer">\
                <div style="width: 100%" class="input-group">\
                <input style="width:100%" autofocus="autofocus" id="btn-input" type="text" class="form-control input-sm chat_input"\
            placeholder="Write your message here..."/>\
               </div> </div>').appendTo(".panel-default")

            $("#box").animate({scrollTop: $(document).height()}, "fast");


        })
        //$.getScript( "/js/test.js", function() {
        //})

    })
    /////////////////////
    function msg() {
        $("#box").animate({scrollTop: $(document).height()}, "fast");

        var nkr = $(".navbar-nav li:first img").attr("src")
        var fr_nkr = $('.base_receive').find("img").attr("src")
        var ur_id = $(".navbar-nav li:first").attr("id");
        var msg = $("#btn-input").val().trim();
        var fr_id = $(".glyphicon").attr('id')
        var lastmsg = $(".msg_container:last").attr("id")

        if (ur_id && fr_id && msg) {
            $.post("/site/addmessage", {usid: ur_id, frid: fr_id, msg: msg, lastmsg: lastmsg})
                .done(function (data) {
                    $("#btn-input").val('')
                    obj = jQuery.parseJSON(data)

                    $.each(obj, function (key, value) {

                        if (value['from'] == ur_id) {
                            $('<div class="row msg_container base_sent" id="' + value.message_id + '">\
                        <div class="">\
                            <div class="messages msg_sent">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00">' + value.send_date + '</time>\
                            </div>\
                        </div>\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + nkr + '" class=" img-responsive ">\
                        </div>\
                    </div>').appendTo("#box");
                        }
                        else {
                            $('<div class="row msg_container base_receive id="' + value.message_id + '">\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + fr_nkr + '"  class=" img-responsive ">\
                        </div>\
                        <div class="">\
                            <div class="messages msg_receive">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00">' + value.send_date + '</time>\
                            </div>\
                        </div>\
                    </div>').appendTo("#box");
                        }

                    });
                });
        }
    }

    $(document).on("keyup", "#btn-input", function (e) {

        if (e.which === 13) {
            msg()
        }

    })
/////////////////////////////////////
    function checkmsg() {
        var cnkr = $(".navbar-nav li:first img").attr("src")
        var cfr_nkr = $('.base_receive').find("img").attr("src")
        var cur_id = $(".navbar-nav li:first").attr("id");
        var cfr_id = $(".glyphicon").attr('id')
        var clastmsg = $(".msg_container:last").attr("id")
        $("#box").animate({scrollTop: $(document).height()}, "fast");

        if (cur_id && cfr_id) {
            $.post("/site/chekmsg", {usid: cur_id, frid: cfr_id, lastmsg: clastmsg})
                .done(function (data) {
                    obj = jQuery.parseJSON(data)

                    $.each(obj, function (key, value) {
                        if (value['from'] == cur_id) {
                            $('<div class="row msg_container base_sent" id="' + value.message_id + '">\
                        <div class="">\
                            <div class="messages msg_sent">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00">' + value.send_date + '</time>\
                            </div>\
                        </div>\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + cnkr + '" class=" img-responsive ">\
                        </div>\
                    </div>').appendTo("#box");
                        }
                        else {
                            $('<div class="row msg_container base_receive" id="' + value.message_id + '">\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + cfr_nkr + '"  class=" img-responsive ">\
                        </div>\
                        <div class="">\
                            <div class="messages msg_receive">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00">' + value.send_date + '</time>\
                            </div>\
                        </div>\
                    </div>').appendTo("#box");
                            var audioElement = document.createElement('audio');
                            audioElement.setAttribute('src', '/audio/1.mp3');
                            audioElement.setAttribute('autoplay', 'autoplay');
                            $.get();
                            audioElement.addEventListener("load", function () {
                                audioElement.play();
                            }, true);
                        }

                    });
                });
        }
    }



    function newmsg() {

        console.log('1')
        var ur_id = $(".navbar-nav li:first").attr("id");
        if (ur_id) {
         $(".list-group-item").each(function () {
         var frid = $(this).attr("id");
             var i=0
             $("#" + frid + " .msgcount").html('<span style="position: absolute;left: 71px;font-size: 80%;top: 3px;" class="label label-danger"></span>');

             $.post("/site/newmsglist")
            .done(function (data) {
                obj = jQuery.parseJSON(data)
                $.each(obj, function (key, value) {

                    if(value.from==frid ){
                    i++
                    console.log(frid);
                    console.log(value.from);
                    if (i > 0) {

                        $("#" + frid + " .msgcount").html('<span style="position: absolute;left: 71px;font-size: 80%;top: 3px;" class="label label-danger">' + i + '</span>');
                    }
                    else {
                        $("#" + frid + " .msgcount").html('<span style="position: absolute;left: 71px;font-size: 80%;top: 3px;" class="label label-danger"></span>');

                    }
                        return;

                }

                })


            })
        })

         }
    }

    setInterval(newmsg, 2000);
    setInterval(checkmsg, 2000);
    ////////////////
    var nkr = $(".navbar-nav li:first img").attr("src")
    var fr_id = $('.list-group-item:first').attr("id");
    var fr_nkr = $('.list-group-item:first img').attr("src")
    var ur_id = $(".navbar-nav li:first").attr("id");
    var title = $('.list-group-item:first').last().text();
    $.get('/site/message/' + fr_id, {}, function (data) {
        obj = jQuery.parseJSON(data)
        $('<div class="col-md-8 col-xs-8">\
                <h3 class="panel-title"><span class="glyphicon glyphicon-comment" id="' + fr_id + '"></span> Conversation  ' + title + '</h3>\
            </div> \
            <div class="col-xs-12 col-md-12">\
                <div class="panel panel-default">\
                <div class="panel-body msg_container_base" style="" id="box">').appendTo("#dialog")
        $.each(obj, function (key, value) {
            if (value['from'] == ur_id) {
                $('<div class="row msg_container base_sent" id="' + value.message_id + '">\
                        <div class="">\
                            <div class="messages msg_sent">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00"style="float: left;">' + value.send_date + '</time>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + nkr + '" class=" img-responsive ">\
                        </div>\
                    </div>').appendTo("#box");
            }
            else {
                $('<div class="row msg_container base_receive" id="' + value.message_id + '">\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + fr_nkr + '"  class=" img-responsive">\
                        </div>\
                        <div class="">\
                            <div class="messages msg_receive">\
                                <p>' + value.message + '</p>\
                                <time datetime="2009-11-13T20:00" style="float: right;margin: -7px;">' + value.send_date + '</time>\
                                                </div>\
                                            </div>').appendTo("#box");
            }

        });
        $('</div>\
                <div class="panel-footer">\
                <div style="width: 100%" class="input-group">\
                <input style="width:100%" autofocus="autofocus" id="btn-input" type="text" class="form-control input-sm chat_input"\
            placeholder="Write your message here..."/>\
               </div> </div>').appendTo(".panel-default")

        $("#box").animate({scrollTop: $(document).height()}, "fast");


    })
//////////////////
})


