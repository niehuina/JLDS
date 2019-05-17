
$(function(){
    var arr = $('#qxz').val();
    if(arr){
        $('a').each(function(){
            var url = $(this).data('url');
            console.log(url);

            if(url)
            {
                if(arr.indexOf(url) == -1)
                {
                    $(this).parent().hide();

                }
            }

        });
    }
});
