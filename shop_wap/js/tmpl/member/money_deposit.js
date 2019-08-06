$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        login();
        return;
    }

    // 充值类型选择
    $('input[name="inv_title_select"]').click(function(){
        var type = this.value;
        //账户充值
        if (type == 'money') {
            $('#moneyDiv').show();
            $('#deposit_btn').show();

            $('#cardDiv').hide();
            $('#deposit_card_btn').hide();
        }else if(type == "card") {//购物卡充值
            $('#cardDiv').show();
            $('#deposit_card_btn').show();

            $('#moneyDiv').hide();
            $('#deposit_btn').hide();
        }
    });

    $.sValid.init({
        rules: {deposit_amount: "required"},
        messages: {deposit_amount: "请输入充值金额！"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var i = "";
                $.map(e, function (a, e)
                {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            }
            else
            {
                errorTipsHide()
            }
        }
    });

    //账户充值
    $("#deposit_btn").click(function(){
        if ($.sValid()){
            if(checkAmount()){
                depositSubmit();
            }
        }
    });
});

function checkAmount()
{
    var deposit_amount = $("#deposit_amount").val();

    if(deposit_amount <= 0)
    {
        errorTipsShow("<p>充值金额不可小于0元</p>");
        return false;
    }
    else if(deposit_amount >= 10000000)
    {
        errorTipsShow("<p>充值金额不可大于10000000元</p>");
        return false;
    }
    return true;
}

function depositSubmit()
{
    var deposit_amount = $("#deposit_amount").val();
    var url = ApiUrl +'/index.php?ctl=Buyer_User&met=addDepositForWap&typ=json';
    var data = {k:getCookie('key'),u:getCookie('id'),deposit_amount:deposit_amount,user_id:getCookie('id'),user_account:getCookie('user_account')};

    $.post(url,data, function (data){
        if(data.status == 200)
        {
            window.location.href = WapSiteUrl + "/tmpl/member/money_deposit_pay.html?uorder=" + data.data.uorder;
        }
    })
    // window.location.href = WapSiteUrl + "/tmpl/member/money_deposit_pay.html?uorder=U20171220040735546";
}

function checkPassword()
{
    var card_code = $("#card_code").val();
    var card_password = $("#card_password").val();

    if(card_code && card_password)
    {
        $.post(ApiUrl +'/index.php?ctl=Shop&met=checkCardPassworForWap&typ=json',
            {k:getCookie('key'), u:getCookie('id'), card_code:card_code,card_password:card_password},
            function(data){
                if(data.status == 250)
                {
                    errorTipsShow("<p>" + data.msg + "</p>");
                    $("#deposit_card_btn").attr("disabled", "disabled");
                }
                else
                {
                    $("#deposit_card_btn").removeAttr("disabled");
                }
            }
        );
    }
    else
    {
        $("#deposit_card_btn").attr("disabled", "disabled");
    }
}
