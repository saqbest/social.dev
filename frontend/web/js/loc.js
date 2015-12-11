$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    $(".fr_req").attr("data-content", '' +'<div class="list-group" id="req_not" style="width: 245px;"></div>'+'')

    $(".fr_req").click(function(){
        $("#req_not").html('')
        $.post("/user/checkrequest")
            .done(function (data) {
                obj2 = jQuery.parseJSON(data)
                if(obj2){
                    $.each(obj2, function (key, value) {
                        $('<a href="#" class="list-group-item" blok_id='+value.user_id+' style="height:80px"><img class="img-thumbnail " src="/'+value.pic+'" alt="Chenge picture" style="cursor: pointer; \
                  width: 50px; height: 50px; display: inline; float: left; margin-right: 5px;">    <h5 class="list-group-item-heading">' + value.first_name + " " + value.last_name + '</h5>\
                   <p class="list-group-item-text but" style="margin-top: 6px;"><button data_req="'+value.user_id+'" type="button" class="btn btn-primary post_status add_fr">Accept</button> <button data_req="'+value.user_id+'" type="button" class="btn btn-danger post_status del"> Cancel</button>\
</p></a>').appendTo('#req_not')

                    })
                }
            })
    })
    $(".navbar-inverse li:nth-child(2)").append('<div id="msgcount"></div>');
    $(".navbar-inverse li:nth-child(3)").append('<div id="notcount"></div>');

    function newmsg() {

        $.post("/site/newmsg")
            .done(function (data) {

                if (data > 0) {

                    $("#msgcount").html('<span style="position: absolute;left: 71px;font-size: 80%;top: 3px;" class="label label-danger">' + data + '</span>');
                }
                else {
                    $("#msgcount").html('<span style="position: absolute;left: 71px;font-size: 80%;top: 3px;" class="label label-danger"></span>');

                }
            })

    }
    function newnot() {

        $.post("/site/newnot")
            .done(function (data) {

                if (data > 0) {

                    $("#notcount").html('<span style="position: absolute;left: 43px;font-size: 80%;top: 3px;" class="label label-danger">' + data + '</span>');
                }
                else {
                    $("#notcount").html('<span style="position: absolute;left: 43px;font-size: 80%;top: 3px;" class="label label-danger"></span>');

                }
            })

    }
    setInterval(newnot, 2000);
    setInterval(newmsg, 2000);
    $(document).on("click", ".add_fr", function () {
        var add_fr_id=$(this).attr('data_req');
        if(add_fr_id){
            $.post("/user/addfr", {add_fr_id: add_fr_id})
                .done(function (data) {
                    $('a[blok_id='+add_fr_id+']').remove()
                    location.reload();

                })
        }
    })
    $(document).on("click", ".del", function () {
        var del_fr_id=$(this).attr('data_req');
        console.log(del_fr_id)
        if(del_fr_id){
            $.post("/user/delrequest", {del_fr_id: del_fr_id})
                .done(function (data) {
                    $('a[blok_id='+del_fr_id+']').remove()
                    location.reload();

                })
        }
    })
})