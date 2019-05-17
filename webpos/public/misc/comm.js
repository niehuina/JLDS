var Public = {};
Public.tips = function(options){
var defaults = {
    "type": 0,
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

var opts = $.extend({},defaults,options);

toastr.remove();

if (1 == parseInt(opts.type))
{
    toastr.error(opts.content, null, opts);
}
else if (2 == parseInt(opts.type))
{
    toastr.warning(opts.content, null, opts);
}
else if (3 == parseInt(opts.type))
{
    toastr.success(opts.content, null, opts);
}
else
{
    toastr.info(opts.content, null, opts);
}
}

Public.tips.info = function(msg)
{
Public.tips({type: 4, content: msg});
}

Public.tips.error = function(msg)
{
Public.tips({type: 1, content: msg});
}


Public.tips.success = function(msg)
{
Public.tips({type: 3, content: msg});
}


Public.tips.warning = function(msg)
{
Public.tips({type: 2, content: msg});
}