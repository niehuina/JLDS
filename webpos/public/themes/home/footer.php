<?php if(!is_ajax()){ ?>


    <script src="<?php echo theme_url().'/js/jquery-1.7.1.js';?>"></script>
    <script src="<?php echo theme_url().'/js/jquery.qrcode.min.js';?>"></script>
    <script src="<?php echo theme_url().'/swiper/js/swiper.min.js';?>"></script>
    <script src="<?php echo base_url().'/misc/AdminLTE-2.3.11/plugins/jQueryUI/jquery-ui.min.js'; ?>"></script>
    <script src="<?php echo base_url().'/misc/moment.js'; ?> "></script>
    <script src="<?php echo base_url().'/misc/AdminLTE-2.3.11/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
    <script src="<?php echo theme_url().'/js/jquery.jqprint-0.3.js';?>"></script>

    
    <script src="<?php echo  base_url().'/misc/jquery.cookie.js'; ?>"></script>
    <script src="<?php echo base_url().'/misc/notify.js';?>"></script>
    <script src="<?php echo  base_url().'/misc/jquery.form.js'; ?>"></script>
    <script src="<?php echo  base_url().'/misc/sweetalert/dist/sweetalert.dev.js'; ?>"></script>
    <script src="<?php echo base_url().'/misc/toastr.min.js';?>"></script>
    <script src="<?php echo base_url().'/misc/comm.js';?>"></script>

<?php } ?>
<script src="<?php echo theme_url().'/js/style.js';?>"></script>
<script src="<?php echo  base_url().'/misc/vue.js'; ?>"></script>
<script src="<?php echo  theme_url().'/vue/home.js'; ?>"></script>
<script src="<?php echo  theme_url().'/vue/search.js'; ?>"></script>


<script>

    loginout();
    function loginout(){
        $('#loginout').click(function(){
            var obj = this;
            swal({
                    title: "确认退出?",

                    showCancelButton: true,
                    confirmButtonColor: "#38cecb",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText:"取消",
                    color:"#fff"
                } ,

                function(isConfirm){
                    if (isConfirm) {
                        swal.setDefaults({ confirmButtonText: '关闭' });
                        var url = $(obj).attr('rel');
                        var storage=window.localStorage;
                        storage.a=1;
                        storage.setItem("c",3);
                        console.log(storage);
                        storage.clear();
                        console.log(storage);
                        window.location.href = url;

                    } else {

                    }
                });

            return false;
        });


    }
    var swiper1 =new Swiper('.swiper-container1', {
        slidesPerView:6,
        paginationClickable: true,
        spaceBetween: 14,
        freeMode: true,
        initialSlide :0,
        observer:true,/*修改swiper自己或子元素时，自动初始化*/
        observeParents:true/*修改swiper的父元素时，自动初始化swiper*/
    });
    var swiper5 = new Swiper('.swiper-container5', {
        scrollbar: '.swiper-scrollbar5',
        direction: 'vertical',
        slidesPerView: 'auto',
        slidesPerView: 8,
        mousewheelControl: true,
        freeMode: true,
        initialSlide :0,
        observer:true,
        observeParents:true
    });

    var Swiper2 = new Swiper('.swiper-container2', {
        scrollbar: '.swiper-scrollbar2',
        direction: 'vertical',
        slidesPerView: 'auto',
        slidesPerView: 3,
        mousewheelControl: true,
        freeMode: true,
        initialSlide :0,
        observer:true,
        observeParents:true
    });

</script>

</body>

</html>
