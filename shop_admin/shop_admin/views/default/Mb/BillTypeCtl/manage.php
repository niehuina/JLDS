<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
    <form method="post" id="shop_api-setting-form" name="settingForm" class="nice-validator n-yellow" novalidate="novalidate">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="type_no">账单编号</label>
                </dt>
                <dd class="opt">
                    <input id="type_no" name="type_no" value="" class="ui-input w400" type="text">
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="type_name">账单名称</label>
                </dt>
                <dd class="opt">
                    <input id="type_name" name="type_name" value="" class="ui-input w400" type="text">
                </dd>
            </dl>
        </div>
    </form>

<?php
include TPL_PATH . '/' . 'footer.php';
?>
<script>
    $(function() {
        api = frameElement.api, data = api.data, oper = data.oper, callback = data.callback; var type_id;

        if ( oper == 'edit' ) {
            //init
            var rowData = data.rowData;

            type_id = rowData.type_id;

            $('#type_no').val(rowData.type_no);
            $('#type_name').val(rowData.type_name);

        }

        api.button({
            id: "confirm", name: '确定', focus: !0, callback: function () {
                postData();
                return false;
            }
        }, {id: "cancel", name: '取消'});

        function postData() {

            var param = {
                type_no: $('#type_no').val(),
                type_name: $('#type_name').val(),
            };

            if ( oper == 'edit' ) {
                param.type_id = data.rowData.type_id;
            }

            Public.ajaxPost(SITE_URL + '?ctl=Mb_BillType&met=' + oper + 'BillType&typ=json', {
                param: param
            }, function (data) {
                if (data.status == 200) {
                    typeof callback == 'function' && callback(data.data, oper, window);
                    return true;
                } else {
                    Public.tips({type: 1, content: data.msg});
                }
            })
        }

    });
</script>

