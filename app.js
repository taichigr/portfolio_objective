$(function(){

    var $jsShowMsg = $('.js-show-msg'),
        msg = $jsShowMsg.text();

        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
        $jsShowMsg.slideToggle('slow');
    setTimeout(function(){
        $jsShowMsg.slideToggle('slow');
    },800);
}
    





    //$('.js-counter').change(function(){
    //var $jsShowMsg = $('.js-show-msg');
    //$jsShowMsg.slideToggle('slow');
    //setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 3000);
    //});

});