$(function ()
{
    var a = getCookie("key");
    if (!a)
    {
        login();
        return;
    }
    $.ajax({
        type: "get",  url:ApiUrl+"/index.php?ctl=Buyer_User&met=certificationForWap&typ=json", data: {k:a,u:getCookie('id'),user_id:getCookie('id')}, dataType: "json", success: function (result)
        {
            if (result.status == 200)
            {
                $('#user_name').html(result.data.user_name);
                if(result.data.user_id>0) {
                    $('#user_realname').val(result.data.user_realname);
                    $('#type').val(result.data.user_identity_type);
                    $('#idcardno').val(result.data.user_identity_card);
                    $('#startDate').val(result.data.user_identity_start_time);
                    $('#endDate').val(result.data.user_identity_end_time);
                    $('#face_logo').attr('src', result.data.user_identity_face_logo);
                    $('#font_logo').attr('src', result.data.user_identity_font_logo);
                    $('#face_logo_url').val(result.data.user_identity_face_logo);
                    $('#font_logo_url').val(result.data.user_identity_font_logo);
                }
                if(result.data.user_identity_statu == 2){
                    $("#submit_btn").addClass("disabled");
                }
            }
        }
    });

    $.sValid.init({
        rules: {idcardno: "required",startDate: "required",endDate: "required"},
        messages: {idcardno: "证件号码必填！",startDate: "有效起始日必填",endDate: "有效结束日必填"},
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
    $("#submit_btn").click(function ()
    {
        if($(this).hasClass("disabled")) return;
        if ($.sValid())
        {
            //身份证照片字段没有加进来
            var realname=$('#user_realname').val();
            var idcardno=$('#idcardno').val();
            var startDate=$('#startDate').val();
            var endDate=$('#endDate').val();
            var type=$('#type').val();
            var font_logo=$('#font_logo_url').val();
            var face_logo=$('#face_logo_url').val();
            var user_id=getCookie('id');
            var k=getCookie('key');

            var myDate = new Date();
            var s_date=new Date(startDate);
            var e_date=new Date(endDate);
            if(s_date<0 || e_date<myDate ||s_date >myDate){
                errorTipsShow("<p>请填写正确的证件有效期</p>");
                return false;
            }
            if(type == 1)
            {
                //验证身份证是否正确
                if(!checkCard(idcardno)){
                    errorTipsShow("<p>身份证号码格式错误</p>");
                    return false;
                }
            }
            var url = ApiUrl+'/index.php?ctl=Buyer_User&met=isExistIdentity&typ=json';
            $.post(url, {k: k, u: getCookie('id'),user_id:user_id,idcardno:idcardno,type:type}, function(a){
                if (a.status == 200 && a.data.isExist==false){
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?ctl=Buyer_User&met=editCertificationForWap&typ=json",
                        data: {k:k,u:getCookie('id'),user_id:user_id, realname:realname ,idcardno:idcardno,startDate:startDate,endDate:endDate,type:type,font_logo:font_logo,face_logo:face_logo},

                        dataType: "json",
                        success: function (a)
                        {
                            if (a)
                            {
                                location.href = "javascript:history.go(-1)";
                            }
                            else
                            {
                                location.href = WapSiteUrl
                            }
                        }
                    })
                }
                else{
                    errorTipsShow("<p>身份证号码已存在</p>");
                    flag=false;
                    return;
                }
            });
        }
    });

    $('#btnFace').click(function () {
        $('#face').click();
    });
    $('#btnFont').click(function () {
        $('#font').click();
    });

});

$('input[name="upfile"]').ajaxUploadImage({
    url: ApiUrl + "/index.php?ctl=Upload&action=uploadImage",
    data: {key:getCookie('id')},
    start: function (e) {
    },
    success: function (e, a) {
        checkLogin(a.login);
        if (a.state != 'SUCCESS') {
            e.parent().siblings(".upload-loading").remove();
            $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
            return false
        }
        if(e.attr('id')=="face"){
            $('#face_logo').attr('src',a.url);
            $('#face_logo_url').val(a.url);
        }
        else if(e.attr('id')=="font"){
            $('#font_logo').attr('src',a.url);
            $('#font_logo_url').val(a.url);
        }
    }
});

var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
    21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",
    33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",
    42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",
    51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",
    63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"
};
checkCard = function(obj)
{
    //var card = document.getElementById('card_no').value;
    //是否为空
    // if(card === '')
    // {
    //  return false;
    //}
    //校验长度，类型
    if(isCardNo(obj) === false)
    {
        return false;
    }
    //检查省份
    if(checkProvince(obj) === false)
    {
        return false;
    }
    //校验生日
    if(checkBirthday(obj) === false)
    {
        return false;
    }
    //检验位的检测
    if(checkParity(obj) === false)
    {
        return false;
    }
    return true;
};
//检查号码是否符合规范，包括长度，类型
isCardNo = function(obj)
{
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
    var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;
    if(reg.test(obj) === false)
    {
        return false;
    }
    return true;
};
//取身份证前两位,校验省份
checkProvince = function(obj)
{
    var province = obj.substr(0,2);
    if(vcity[province] == undefined)
    {
        return false;
    }
    return true;
};
//检查生日是否正确
checkBirthday = function(obj)
{
    var len = obj.length;
    //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
    if(len == '15')
    {
        var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/;
        var arr_data = obj.match(re_fifteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date('19'+year+'/'+month+'/'+day);
        return verifyBirthday('19'+year,month,day,birthday);
    }
    //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
    if(len == '18')
    {
        var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/;
        var arr_data = obj.match(re_eighteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date(year+'/'+month+'/'+day);
        return verifyBirthday(year,month,day,birthday);
    }
    return false;
};
//校验日期
verifyBirthday = function(year,month,day,birthday)
{
    var now = new Date();
    var now_year = now.getFullYear();
    //年月日是否合理
    if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)
    {
        //判断年份的范围（3岁到100岁之间)
        var time = now_year - year;
        if(time >= 0 && time <= 130)
        {
            return true;
        }
        return false;
    }
    return false;
};
//校验位的检测
checkParity = function(obj)
{
    //15位转18位
    obj = changeFivteenToEighteen(obj);
    var len = obj.length;
    if(len == '18')
    {
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        var cardTemp = 0, i, valnum;
        for(i = 0; i < 17; i ++)
        {
            cardTemp += obj.substr(i, 1) * arrInt[i];
        }
        valnum = arrCh[cardTemp % 11];
        if (valnum == obj.substr(17, 1))
        {
            return true;
        }
        return false;
    }
    return false;
};
//15位转18位身份证号
changeFivteenToEighteen = function(obj)
{
    if(obj.length == '15')
    {
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        var cardTemp = 0, i;
        obj = obj.substr(0, 6) + '19' + obj.substr(6, obj.length - 6);
        for(i = 0; i < 17; i ++)
        {
            cardTemp += obj.substr(i, 1) * arrInt[i];
        }
        obj += arrCh[cardTemp % 11];
        return obj;
    }
    return obj;
};