/*(function($) {
    ylapp.button("#nav-left", "btn-act",
    function() {});
    ylapp.button("#nav-right", "btn-act",
    function() {});

    ylapp.ready(function() {
        
        $.scrollbox($("#ScrollContent")).on("releaseToReload",
        function() { //After Release or call reload function,we reset the bounce
            //$("#ScrollContent").trigger("reload", this);
            $(".bounce_status").html('<i class="fa fa-spinner fa-spin fa-2x"></i>加载中');
        }).on("onReloading",
        function(a) { //if onreloading status, drag will trigger this event
            $(".bounce_status").html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        }).on("dragToReload",
        function() { //drag over 30% of bounce height,will trigger this event
            alert(11111);
        }).on("draging",
        function(status) { //on draging, this event will be triggered.
            alert(status);
        }).on("release",
        function() { //on draging, this event will be triggered.
            alert(3333);
        }).on("scrollbottom",
        function() { //on scroll bottom,this event will be triggered.you should get data from server
            $("#ScrollContent").trigger("more", this);
        }).reload();
    })

})($);*/
