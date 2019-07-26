<!-- 搜索 -->
<div class="head-fixed">
    <div class="head-ser">
        <a href="/tmpl/member/address_list.html?select=true" class="header-location">
            <i class="icon"></i><span id="user_address_area" class="">
                       </span></a>
        <a href="/tmpl/search.html" class="header-inps">
            <span class="search-input" id="keyword">请输入关键词</span>
            <i class="icon" id='scan'></i>
        </a>
    </div>
</div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=A83cd06b54e826075981aa381d52b943"></script>
<script type="text/javascript">
    $(function () {
        $('#scan').click(function(){
            return false;
        });
        var location = getCookie('location');
        if (!location || location == '' || location == null) {
            // 百度地图API功能
            var geolocation = new BMap.Geolocation();
            var geoc = new BMap.Geocoder();

            geolocation.getCurrentPosition(function (r) {
                if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                    var mk = new BMap.Marker(r.point);
                    addCookie('location', r.point.lat + ',' + r.point.lng);
                    if(typeof load_shop_list === "function"){
                        load_shop_list();
                    }
                    geoc.getLocation(r.point, function (rs) {
                        var address = rs.addressComponents;
                        addCookie('location_area', address.district + " " + address.street + " " + address.streetNumber);
                        addCookie('location_city_id', "");
                        addCookie('location_city', address.city);
                        $("#user_address_area").html(getCookie('location_area'));
                        $(".head-ser").css("padding-left", $(".header-location").width());
                        //window.addressStr = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
                        //console.info(window.addressStr);
                        //alert(window.addressStr);
                        //alert('您的位置：' + r.point.lng + ',' + r.point.lat + ',' + window.addressStr);
                        //addCookie('goods_cart', goods_info);
                    });

                }
                else {
                    alert('获取定位失败' + this.getStatus());

                }
            }, {enableHighAccuracy: true})

            //关于状态码
            //BMAP_STATUS_SUCCESS	检索成功。对应数值“0”。
            //BMAP_STATUS_CITY_LIST	城市列表。对应数值“1”。
            //BMAP_STATUS_UNKNOWN_LOCATION	位置结果未知。对应数值“2”。
            //BMAP_STATUS_UNKNOWN_ROUTE	导航结果未知。对应数值“3”。
            //BMAP_STATUS_INVALID_KEY	非法密钥。对应数值“4”。
            //BMAP_STATUS_INVALID_REQUEST	非法请求。对应数值“5”。
            //BMAP_STATUS_PERMISSION_DENIED	没有权限。对应数值“6”。(自 1.1 新增)
            //BMAP_STATUS_SERVICE_UNAVAILABLE	服务不可用。对应数值“7”。(自 1.1 新增)
            //BMAP_STATUS_TIMEOUT	超时。对应数值“8”。(自 1.1 新增)
        }
        else {
            if(typeof load_shop_list === "function"){
                load_shop_list();
            }
            var location_area = getCookie('location_area');
            if (!location_area || location_area == '' || location_area == null) {
                var geoc = new BMap.Geocoder();
                var point = new BMap.Point(location.split(',')[1], location.split(',')[0]);
                geoc.getLocation(point, function (rs) {
                        var address = rs.addressComponents;
                        addCookie('location_area', address.street + "" + address.streetNumber);
                        addCookie('location_city_id', "");
                        addCookie('location_city', address.city);

                        $("#user_address_area").html(getCookie('location_area'));
                        $(".head-ser").css("padding-left", $(".header-location").width());

                    }
                );
            }
            else {
                $("#user_address_area").html(getCookie('location_area'));
                $(".head-ser").css("padding-left", $(".header-location").width());

            }
        }

        var location_href = window.location.href;
        var attrHref = "/tmpl/member/address_list.html?select=true" + "&url=" + location_href;
        $(".header-location").attr("href", attrHref);
    })

</script>