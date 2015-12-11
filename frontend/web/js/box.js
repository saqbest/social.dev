$(document).ready(function () {
    $(".avatar_change").click(function () {
        $('#ura').modal('show')
    })

    ///////////////////

    $(".avatari").click(function () {
        $(".avatari").removeClass("selected");
        $(this).addClass("selected");
    })

    $("#change").click(function () {
        var selected = $(".selected").attr("id")
        $.get('/site/savepic/' + selected, {}, function (data) {
            location.reload();

        })
    })
    ////////////////////////
    $(".list-group-item").click(function () {
        $('#dialog').html(' ');
        var nkr = $("#avatar").attr("src")
        var fr_id = $(this).attr("id");
        var fr_nkr = $(this).find("img").attr("src")
        var ur_id = $(".navbar-nav li:first").attr("id");
        var title = $(this).last().text();
        $.get('/site/message/' + fr_id, {}, function (data) {
            obj = jQuery.parseJSON(data)
            $('<div class="container">\
                <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px;">\
                <div class="col-xs-12 col-md-12">\
                <div class="panel panel-default">\
                <div class="panel-heading top-bar">\
                <div class="col-md-8 col-xs-8">\
                <h3 class="panel-title"><span class="glyphicon glyphicon-comment" id="' + fr_id + '"></span> Chat - ' + title + '</h3>\
            </div>\
            <div class="col-md-4 col-xs-4" style="text-align: right;">\
                <a href="#"><span id="minim_chat_window"\
            class="glyphicon glyphicon-minus icon_minim"></span></a>\
            <a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_1"></span></a>\
            </div>\
            </div>\
            <div class="panel-body msg_container_base" style="height: 300px" id="box">').appendTo("#dialog")
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
                    $('<div class="row msg_container base_receive" id="' + value.message_id + '">\
                        <div class="col-md-2 col-xs-2 avatar">\
                            <img src="' + fr_nkr + '"  class=" img-responsive">\
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
            $('</div>\
                <div class="panel-footer">\
                <div style="width: 100%" class="input-group">\
                <input style="width:100%" autofocus="autofocus" id="btn-input" type="text" class="form-control input-sm chat_input"\
            placeholder="Write your message here..."/>\
              </div></div></div></div></div> </div>').appendTo(".panel-default")

            $("#box").animate({scrollTop: $(document).height()}, "fast");


        })
        //$.getScript( "/js/test.js", function() {
        //})

    })
    /////////////////////
    function msg() {
        $("#box").animate({scrollTop: $(document).height()}, "fast");

        var nkr = $("#avatar").attr("src")
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
        var cnkr = $("#avatar").attr("src")
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



    setInterval(checkmsg, 2000);
    //////////////////////
    $.post("/site/editor")
        .done(function (data) {
            obj = jQuery.parseJSON(data)
            $.each(obj, function (key, value) {

                $("#timeline").prepend("<li class='work'>\
            <input class='radio' id='"+value.post_id+"' name='works' type='radio' checked>\
            <div class='relative'>\
                <label for='"+value.post_id+"'>"+value.post_title+"</label>\
                <span class='date'>"+value.posted_time+"</span>\
                <span class='circle'></span>\
            </div>\
            <div class='content'>\
                "+value.post+" \
            </div>\
        </li>");
            })
        })

    $(document).on("click", ".post_status", function (){
        var title=$('#timeline-post_title').val().trim();

        var editor='content_textarea';
        var content =tinyMCE.activeEditor.getContent();
        var last_post = $(".radio:first").attr("id")


        if(content && title ){
            $.post("/site/editor", {title: title, content: content,last_post:last_post})
                .done(function (data) {
                    obj = jQuery.parseJSON(data)
                    $.each(obj, function (key, value) {

                        $("#timeline").prepend("<li class='work'>\
            <input class='radio' id='"+value.post_id+"' name='works' type='radio' checked>\
            <div class='relative'>\
                <label for='"+value.post_id+"'>"+value.post_title+"</label>\
                <span class='date'>"+value.posted_time+"</span>\
                <span class='circle'></span>\
            </div>\
            <div class='content'>\
                "+value.post+" \
            </div>\
        </li>");
                    })
                })
        }

        $('#timeline-post_title').val("");
        tinyMCE.activeEditor.setContent('');

    })
    ///////////////////
})


