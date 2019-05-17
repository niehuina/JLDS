<?php

/**
 * @author xialei <xialeistudio@gmail.com>
 */
class K3Cloud_Sync
{
    private static $k3cloud_instance;
    private static $cookie_jar;
    const REQ_GET = 1;
    const REQ_POST = 2;
    const CLOUD_URL = "http://123.183.136.65:8081/K3Cloud/";

    /**
     * 单例模式
     * @return map
     */
    public static function instance()
    {
        if (!self::$k3cloud_instance instanceof self) {
            self::$k3cloud_instance = new self;
        }
        return self::$k3cloud_instance;
    }

    public function login( )
    {

        //登陆参数
        $data = array(
            'acctID' => '5a17ec59dd4503',//帐套Id 正式
            'username' => 'admin',//用户名
            'password' => 'ygf118',//密码
            'lcid' => 2052//语言标识
        );
        $cookie_jar = tempnam(DATA_PATH . '/k3cloud', 'CloudSession');
        $post_content = $this->create_postdata($data);

        $result = $this->invoke_login(self::CLOUD_URL, $data, $cookie_jar);
        self::$cookie_jar = $cookie_jar;
        return $result;

//        $resp = $this-> async($cloudUrl,$data,true,self::REQ_POST,$cookie_jar,true);
//
      //  $array = json_decode($result, true);

        //$formdata = "{\"FormId\":\"STK_Inventory\",\"TopRowCount\":0,\"Limit\":10,\"StartRow\":0,\"FilterString\":\"FMaterialId.FNumber='HG_TEST'\",\"OrderString\":\"FID ASC\",\"FieldKeys\":\"FID,FSupplierId,FMaterialId,FMaterialId.FNumber,FMaterialName\"}";
//        $wullist = array(
//            'data' => "{\"FormId\":\"STK_Inventory\",\"TopRowCount\":0,\"Limit\":10,\"StartRow\":0,\"FilterString\":\"FBaseQty > 0\",\"OrderString\":\"FID ASC\",\"FieldKeys\":\"FID,FMaterialName,FMaterialId.FNumber,FBaseQty\"}"
//        );

//        $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"XSDD000001\",\"Id\":\"\"}";
//
//        $vdate = array(
//             'FormId' =>'SAL_SaleOrder',
//            'data' => $viewdata
//
//        );
//        $wuliresult = $this->invoke_view(self::CLOUD_URL, $vdate, $cookie_jar);

       // $lisresult = json_decode($this->view_salesOrder("XSDD000001",$cookie_jar), true);
     //    $lisresult = json_decode( $this->invoke_save_saleorder($cookie_jar), true );

        //查看出库单
         //$lisresult = json_decode($this->view_sale_outstock("XSCKD000001",$cookie_jar), true);
         //保存出库单

        //应收单
        //$lisresult = json_decode( $this->save_reveivable($cookie_jar), true );

//        return $lisresult;
    }

    function test(){

        $result = $this->login();
        if(!empty($result['Message'])){
            return $result['Message'];
        }
       // $lisresult = json_decode( $this->view_data("AR_REFUNDBILL","SKTKD00000001" ,self::$cookie_jar), true );
     $lisresult = json_decode( $this->save_refund_bill(self::$cookie_jar), true );

        //销售订单  SAL_SaleOrder
//        $lisresulta = json_decode( $this->submit_data("SAL_SaleOrder","XSDD000005" ,self::$cookie_jar), true );
//        $lisresult = json_decode( $this->audit_data("SAL_SaleOrder","XSDD000005" ,self::$cookie_jar), true );
        return $lisresult;

    }
    //销售订单
    function invoke_save_saleorder($orders)
    {
        $result = $this->login();
        if(!empty($result['Message'])){
            return $result['Message'];
        }

        foreach($orders as $key=>$order) {
            //BillType
            $FBillTypeID = "XSDD01_SYS";
            //SaleDeptId 销售部门： BM000022
            $FSaleDeptId = "BM000022";

            //销售店铺的组织机构id "102.4"
            $FSaleOrgId = $order['ShopOrgId'];
            //订单日期
            $FDate = date("Y-m-d");
            $userId = "02010001";
            //FCustId 散客 fnumber:02010001
            $FCustId = $userId;
            //FReceiveId 散客 fnumber:02010001
            $FReceiveId = $userId;
            //FSalerId 销售员 "0038"
            $FSalerId = $order['SalerId'];
            //SettleId 散客 02010001
            $FSettleId = $userId;

            $FISINIT = "false";
            $FIsMobile = "false";

            $saleOderContent = array(
                "Creator" => "",
                "NeedUpDateFields" => array(),
                "NeedReturnFields" => array(),
                "IsDeleteEntry" => "true",
                "SubSystemId" => "",
                "IsVerifyBaseDataField" => "false",
                "IsEntryBatchFill" => "true",
            );
            $saleOderContent["Model"] = array(
                "FID" => "0",
                "FBillTypeID" => array(
                    "FNumber" => $FBillTypeID,
                ),
                "FBillNo" => "",
                "FDate" => $FDate,
                "FSaleOrgId" => array(
                    "FNumber" => $FSaleOrgId,
                ),
                "FCustId" => array(
                    "FNumber" => $FCustId,
                ),
                "FReceiveId" => array(
                    "FNumber" => $FReceiveId,
                ),
                "FHeadDeliveryWay" => array(
                    "FNumber" => "",
                ),
                "FHEADLOCID" => array(
                    "FNUMBER" => "",
                ),
                "FSaleDeptId" => array(
                    "FNumber" => $FSaleDeptId,
                ),
                "FCorrespondOrgId" => array(
                    "FNumber" => "",
                ),
                "FSaleGroupId" => array(
                    "FNumber" => "",
                ),
                "FSalerId" => array(
                    "FNumber" => $FSalerId,
                ),
                "FReceiveAddress" => "",
                "FSettleId" => array(
                    "FNumber" => $FSettleId,
                ),
                "FReceiveContact" => array(
                    "FName" => "",
                ),
                "FChargeId" => array(
                    "FNumber" => "",
                ),
                "FNetOrderBillNo" => "",
                "FNetOrderBillId" => "0",
                "FOppID" => "0",
                "FSalePhaseID" => array(
                    "FNumber" => "",
                ),
                "FISINIT" => $FISINIT,
                "FNote" => "",
                "FIsMobile" => $FIsMobile,
            );

            //FSaleOrderFinance
            //币种  PRE001:人民币
            $FSettleCurrId = "PRE001";
            //FIsIncludedTax 是否包含税 true
            $FIsIncludedTax = "true";
            //FIsPriceExcludeTax 价格是否包含税 true
            $FIsPriceExcludeTax = "true";
            //FExchangeTypeId   固定汇率 HLTX01_SYS
            $FExchangeTypeId = "HLTX01_SYS";
            $FExchangeRate = "1";

            $saleOderContent["Model"]["FSaleOrderFinance"] = array(
                "FSettleCurrId" => array(
                    "FNumber" => $FSettleCurrId,
                ),
                "FRecConditionId" => array(
                    "FNumber" => "",
                ),
                "FIsIncludedTax" => $FIsIncludedTax,
                "FSettleModeId" => array(
                    "FNumber" => "",
                ),
                "FIsPriceExcludeTax" => $FIsPriceExcludeTax,
                "FPriceListId" => array(
                    "FNumber" => "",
                ),
                "FDiscountListId" => array(
                    "FNumber" => "",
                ),
                "FExchangeTypeId" => array(
                    "FNumber" => $FExchangeTypeId,
                ),
                "FExchangeRate" => $FExchangeRate,
                "FMarginLevel" => "0",
                "FMargin" => "0",
            );
            $FSaleOrderClause = array();
            $FSaleOrderClause[] = array(
                "FEntryID" => "0",
                "FClauseId" => array(
                    "FNumber" => "",
                ),
                "FClauseDesc" => ""
            );
            $saleOderContent["Model"]["FSaleOrderClause"] = $FSaleOrderClause;

            $amount = 0;
            $FSaleOrderEntry = array();
            foreach($order['Goods'] as $key=>$goods) {
                //FSaleOrderEntry
                //FMaterialId ，物料number
                $FMaterialId = $goods['materialId'];//"0601060049";
                //FUnitID 物料单位
                $FUnitID = "ping";
                //FQty 数量
                $FQty = $goods['order_goods_num'];
                //FTaxPrice 带税金额 8.8
                $FTaxPrice = $goods['goods_price'];
                //FEntryTaxRate 税率
                $FEntryTaxRate = "17";
                $FDiscountRate = "0";

                $amount += $FQty * $FTaxPrice;

                //日期
                $FDeliveryDate = date("Y-m-d");
                $FPlanDate = date("Y-m-d");
                $FSaleOrderEntry[] = array(
                    "FEntryID" => "0",
                    "FReturnType" => "",
                    "FRowType" => "Standard",
                    "FMapId" => array(
                        "FNumber" => "",
                    ),
                    "FMaterialId" => array(
                        "FNumber" => $FMaterialId
                    ),
                    "FAuxPropId" => array(
                        "FNumber" => ""
                    ),
                    "FParentMatId" => array(
                        "FNumber" => "",
                    ),
                    "FUnitID" => array(
                        "FNumber" => $FUnitID
                    ),
                    "FInventoryQty" => "0",
                    "FCurrentInventory" => "0",
                    "FAwaitQty" => "0",
                    "FAvailableQty" => "0",
                    "FQty" => $FQty,
                    "FOldQty" => $FQty,
                    "FPrice" => "0",
                    "FTaxPrice" => $FTaxPrice,
                    "FIsFree" => "false",
                    "FTaxCombination" => array(
                        "FNumber" => "",
                    ),
                    "FEntryTaxRate" => $FEntryTaxRate,
                    "FProduceDate" => "",
                    "FExpPeriod" => "0",
                    "FExpUnit" => "",
                    "FExpiryDate" => "",
                    "FLot" => array(
                        "FNumber" => "",
                    ),
                    "FDiscountRate" => $FDiscountRate,
                    "FDeliveryDate" => $FDeliveryDate,
                    "FStockOrgId" => array(
                        "FNumber" => "",
                    ),
                    "FSettleOrgIds" => array(
                        "FNumber" => "",
                    ),
                    "FSupplyOrgId" => array(
                        "FNumber" => "",
                    ),
                    "FOwnerTypeId" => "",
                    "FOwnerId" => array(
                        "FNumber" => "",
                    ),
                    "FEntryNote" => "",
                    "FReserveType" => "",
                    "FPriority" => "0",
                    "FMtoNo" => "",
                    "FPromotionMatchType" => "",
                    "FNetOrderEntryId" => "0",
                    "FPriceBaseQty" => $FQty,
                    "FSetPriceUnitID" => array(
                        "FNumber" => "ping"
                    ),
                    "FStockUnitID" => array(
                        "FNumber" => ""
                    ),
                    "FStockQty" => $FQty,
                    "FStockBaseQty" => $FQty,
                    "FServiceContext" => "",
                    "FOUTLMTUNIT" => "",
                    "FOutLmtUnitID" => array(
                        "FNumber" => ""
                    ),
                    "FOrderEntryPlan" => array(
                        0 => array(
                            "FDetailID" => "0",
                            "FDetailLocId" => array(
                                "FNUMBER" => ""
                            ),
                            "FDetailLocAddress" => "",
                            "FPlanDate" => $FPlanDate,
                            "FTransportLeadTime" => "0",
                            "FPlanQty" => $FQty
                        )
                    ),
                    "FTaxDetailSubEntity" => array(
                        0 => array(
                            "FDetailID" => "0",
                            "FTaxRate" => "0",
                            "FSellerWithholding" => "false",
                            "FBuyerWithholding" => "false"
                        )
                    ),
                );
            }
            $saleOderContent["Model"]["FSaleOrderEntry"] = $FSaleOrderEntry;

            $FNeedRecAdvance = "false";
            $FRecAdvanceRate = "100";

            //FRecAdvanceAmount 税价合计 总金额 3*8.8=26.4
            $FRecAdvanceAmount = strval($amount);

            $FSaleOrderPlan = array();
            $FSaleOrderPlan[] = array(
                "FEntryID" => "0",
                "FNeedRecAdvance" => $FNeedRecAdvance,
                "FReceiveType" => array(
                    "FNumber" => "",
                ),
                "FRecAdvanceRate" => $FRecAdvanceRate,
                "FRecAdvanceAmount" => $FRecAdvanceAmount,
                "FMustDate" => "",
                "FRelBillNo" => "",
                "FRecAmount" => "0",
                "FControlSend" => "",
                "FReMark" => "",
                "FPlanMaterialId" => array(
                    "FNumber" => "",
                ),
                "FMaterialSeq" => "0",
                "FOrderEntryId" => "0",
                "FSaleOrderPlanEntry" => array(
                    0 => array(
                        "FDETAILID" => "0",
                        "FPESettleOrgId" => array(
                            "FNumber" => "",
                        ),
                    ),
                ),
            );
            $saleOderContent["Model"]["FSaleOrderPlan"] = $FSaleOrderPlan;

            $FSalOrderTrace = array();
            $FSalOrderTrace[] = array(
                "FEntryID" => "0",
                "FLogComId" => array(
                    "FCode" => "",
                ),
                "FCarryBillNo" => "",
                "FSalOrderTraceDetail" => array(
                    0 => array(
                        "FDetailID" => "0",
                        "FTraceTime" => "",
                        "FTraceDetail" => ""
                    )
                )
            );
            $saleOderContent["Model"]["FSalOrderTrace"] = $FSalOrderTrace;

            $data = json_encode($saleOderContent);

            $vdata = array(
                'FormId' => 'SAL_SaleOrder',
                'Data' => $data
            );

            $saleorderresult = $this->invoke_save(self::CLOUD_URL, $vdata, self::$cookie_jar);
        }


        return $saleorderresult;
    }


    //查看销售订单
    function view_salesOrder($number,$cookie_jar ){

      //  $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"XSDD000001\",\"Id\":\"\"}";
        $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"". $number . "\",\"Id\":\"\"}";
        $vdate = array(
            'FormId' =>'SAL_SaleOrder',
            'data' => $viewdata

        );
        return $this->invoke_view(self::CLOUD_URL, $vdate, $cookie_jar);
    }

    //查看销售出库单 XSCKD000001
    function view_sale_outstock($number,$cookie_jar){
        $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"". $number . "\",\"Id\":\"\"}";
        $vdate = array(
            'FormId' =>'SAL_OUTSTOCK',
            'data' => $viewdata

        );
        return $this->invoke_view(self::CLOUD_URL, $vdate, $cookie_jar);

    }

    //收款单 AR_RECEIVEBILL ，
    function view_data($formid,$number,$cookie_jar){

        $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"". $number . "\",\"Id\":\"\"}";
        $vdate = array(
            'FormId' => $formid,
            'data' => $viewdata);
        return $this->invoke_view(self::CLOUD_URL, $vdate, $cookie_jar);
    }
    function submit_data($formid,$number,$cookie_jar){
        $viewdata = "{\"Numbers\":[\"". $number . "\"]}";
        $vdate = array(
            'FormId' => $formid,
            'data' => $viewdata);

        $invokeurl = self::CLOUD_URL  .'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.Submit.common.kdsvc';
        return  $this-> async($invokeurl,$vdate,true,self::REQ_POST,$cookie_jar,false);

}
    function audit_data($formid,$number,$cookie_jar){
        $viewdata = "{\"Numbers\":[\"". $number . "\"]}";
        $vdate = array(
            'FormId' => $formid,
            'data' => $viewdata);
        return $this->invoke_audit(self::CLOUD_URL, $vdate, $cookie_jar);
    }

    //收款单 保存
    function save_RECEIVEBILL($number,$order){

        //FBillTypeID 销售收款单 -> SKDLX01_SYS
        //FCONTACTUNITTYPE  值 -> BD_Customer
        // CONTACTUNIT 联系人单位  -> 02010001 散客
        //FPAYUNITTYPE -> BD_Customer
        //PAYUNIT -> 02010001 散客
        //FCURRENCYID 币种 -> PRE001
        //FPAYORGID 组织 ->
        //FSETTLEORGID 结算组织
        // FSETTLERATE -> 1
        //FSALEORGID 销售组织 ->
        //FSALEDEPTID 销售部门 -> 源谷丰阳光大厦店 BM000022
        //FSALEERID 销售人 -> 0038  张璐璐
        //DOCUMENTSTATUS -> C
        //FBUSINESSTYPE -> 1
        //FEXCHANGERATE -> 2
        //CancelStatus -> A
        //FSETTLECUR 币种
        //RECTOTALAMOUNTFOR  FRECAMOUNTFOR_E  SETTLERECAMOUNT  RECTOTALAMOUNT WRITTENOFFAMOUNTFOR REALRECAMOUNTFOR FREALRECAMOUNT 一样值 8.8× 3 = 26.4
        //FRECAMOUNT_E 金额

        // FRECEIVEBILLENTRY  -》   FSETTLETYPEID ->JSFS04_SYS 电汇  | FPURPOSEID -> SFKYT01_SYS 销售收款
        // FACCOUNTID 银行账户  ->  603013010000006950 秦皇岛银行建设大街支行
        // 源单类型:FSRCBILLTYPEID   源单编号:FSRCBILLNO

        //FALLAMOUNTFOR 总价：可能不需要填写

        // FRECEIVEBILLSRCENTRY |  源单类型:FSRCBILLTYPEID   源单编号:FSRCBILLNO | 计划收款金额 FPLANRECAMOUNT | 本次收款金额 FREALRECAMOUNT | 应收金额 FAFTTAXTOTALAMOUNT | 源单币别 FSRCCURRENCYID

        // "FEXCHANGERATE":"0" 不存在

        $sdata ="{\"Creator\":\"\",\"NeedUpDateFields\":[],\"NeedReturnFields\":[],\"IsDeleteEntry\":\"True\",\"SubSystemId\":\"\",\"IsVerifyBaseDataField\":\"false\",\"IsEntryBatchFill\":\"True\",
                \"Model\":{\"FID\":\"0\",\"FBillTypeID\":{\"FNumber\":\"SKDLX01_SYS\"},\"FBillNo\":\"\",\"FDATE\":\"2017-12-08\",\"FCONTACTUNITTYPE\":\"BD_Customer\",\"FCONTACTUNIT\":{\"FNumber\":\"02010001\"},\"FPAYUNITTYPE\":\"BD_Customer\",
               \"FALLAMOUNTFOR\":\"26.4\", \"FPAYUNIT\":{\"FNumber\":\"02010001\"},\"FCURRENCYID\":{\"FNumber\":\"PRE001\"},\"FPAYORGID\":{\"FNumber\":\"102.4\"},\"FSETTLEORGID\":{\"FNumber\":\"102.4\"},\"FSETTLERATE\":\"1\",
                \"FSALEORGID\":{\"FNumber\":\"102.4\"},\"FSALEDEPTID\":{\"FNumber\":\"BM000022\"},\"FSALEGROUPID\":{\"FNumber\":\"\"},\"FSALEERID\":{\"FNumber\":\"0038\"},\"FDOCUMENTSTATUS\":\"\",
                \"FDepartment\":{\"FNumber\":\"\"},\"FBUSINESSTYPE\":\"1\",\"FISINIT\":\"false\",\"FCancelStatus\":\"A\",\"FCustomerID\":{\"FNumber\":\"02010001\"},
                \"FSETTLECUR\":{\"FNumber\":\"PRE001\"},\"FISB2C\":\"false\",\"FWBSETTLENO\":\"\",\"FIsWriteOff\":\"false\",\"FMatchMethodID\":\"0\",  \"FALLAMOUNTFOR\":\"26.4\",


                \"FRECEIVEBILLENTRY\":[{\"FEntryID\":\"0\",\"FSETTLETYPEID\":{\"FNumber\":\"JSFS04_SYS\"},\"FPURPOSEID\":{\"FNumber\":\"SFKYT01_SYS\"},\"FRECEIVEITEMTYPE\":\"\",\"FRECEIVEITEM\":\"\",\"FSaleOrderID\":\"0\",
                \"FRECTOTALAMOUNTFOR\":\"26.4\",\"FRECAMOUNTFOR_E\":\"26.4\",\"FSETTLEDISTAMOUNTFOR\":\"0\",\"FHANDLINGCHARGEFOR\":\"0\",\"FOVERUNDERAMOUNTFOR\":\"0\",
                \"FACCOUNTID\":{\"FNumber\":\"603013010000006950\"},\"FOPPOSITEBANKACCOUNT\":\"\",\"FOPPOSITECCOUNTNAME\":\"\",\"FINNERACCOUNTID\":{\"FNumber\":\"\"},\"FCashAccount\":{\"FNumber\":\"\"},\"FSETTLENO\":\"\",
                \"FOPPOSITEBANKNAME\":\"\",\"FCOMMENT\":\"\",\"FRECAMOUNT_E\":\"26.4\",\"FPOSTDATE\":\"2017-12-08\",
                \"FMATERIALID\":{\"FNumber\":\"\"},\"FSALEORDERNO\":\"\",\"FMATERIALSEQ\":\"0\",\"FORDERENTRYID\":\"0\",\"FASSSALESORDER\":[{\"FDetailID\":\"0\"}]}],

                \"FRECEIVEBILLSRCENTRY\":[{\"FEntryID\":\"0\",\"FORDERBILLNO\":\"\",\"FSRCMATERIALID\":{\"FNumber\":\"\"},\"FSRCMATERIALSEQ\":\"0\",\"FSRCORDERENTRYID\":\"0\",\"FSRCBILLTYPEID\":\"AR_receivable\", \"FSRCBILLNO\":\"AR00000001\",
               \"FPLANRECAMOUNT\":\"26.4\", \"FREALRECAMOUNT\":\"26.4\",\"FAFTTAXTOTALAMOUNT\":\"26.4\" ,\"FEXPIRY\":\"2017-12-08\" ,  \"FSRCCURRENCYID\":{\"FNumber\":\"PRE001\"}
                }],

                \"FBILLRECEIVABLEENTRY\":[{\"FEntryID\":\"0\",\"FInnerAccountID_B\":{\"FNumber\":\"\"},\"FBILLID\":{\"FNumber\":\"\"},\"FUSEDAMOUNTFOR\":\"0\",\"FBILLPARAMOUNT\":\"0\",
                \"FPARLEFTAMOUNTSTD\":\"0\",\"FUSEDAMOUNTSTD\":\"0\",\"FTempOrgId\":{\"FNumber\":\"\"}}],

                \"FBILLSKDRECENTRY\":[{\"FEntryID\":\"0\",\"FInnerActId\":{\"FNumber\":\"\"},
                \"FReceivebleBillId\":{\"FNumber\":\"\"},\"FPayPurse\":{\"FNumber\":\"\"},\"FReturnAmount\":\"0\",\"FReturnAmountStd\":\"0\",\"FKDBPARBILLNO\":\"\",\"FParAmount\":\"0\",\"FPARAMOUNTSTD\":\"0\",\"FBCONTACTUNITTYPE\":\"\",
                \"FBCONTACTUNIT\":{\"FNumber\":\"\"}}]
                                }}";


        $vdata = array(
            'FormId' =>'AR_RECEIVEBILL',
            'data' => $sdata

        );
        $results = $this->invoke_save(self::CLOUD_URL,$vdata, $cookie_jar);

        return $results;
    }

    //保存销售出库单
    function save_sal_outstock($order)
    {
        $result = $this->login();
        if(!empty($result['Message'])){
            return $result['Message'];
        }

        //FBillTypeID 销售  标准销售出库单 XSCKD01_SYS
        //FSaleOrgId 销售组织机构 102.4
        //FSaleDeptID 销售部门 BM000022 "源谷丰阳光大厦店
        //FCustomerID 客户id 02010001 散客
        //FDate 日期
        //FStockOrgId 库存组织机构
        //FReceiverID 接收人id
        //SettleID  02010001 散客
        //FPayerID 02010001 散客
        //FSettleOrgID 结算组织
        //FSettleCurrID 结算币别
        //FStockID    CK001 "阳光大厦店商品库

        //FExchangeTypeID 汇率 HLTX01_SYS |  FExchangeRate 1
        //FEntity FRowType --stand  |  FMaterialID 物料编码 | FUnitID 单位 | FRealQty 真实数量 |FTaxPrice 含税单价 | FOwnerTypeID -> BD_OwnerOrg | FOwnerID 组织机构 | StockStatusID 库存状态 KCZT01_SYS（可以）
        //     | FSalUnitID 销售单位 |FSALUNITQTY 销售数量 | FSALBASEQTY 销售数量 | FPRICEBASEQTY 数量 | ESettleCustomerId 源谷丰第四分公司（阳光大厦店）030006

        //FSrcType 原单类型 SAL_SaleOrder， FSrcBillNo 源单编号
        $saleOderContent = array(
            "Creator" => "",
            "NeedUpDateFields" => array(),
            "NeedReturnFields" => array(),
            "IsDeleteEntry" => "true",
            "SubSystemId" => "",
            "IsVerifyBaseDataField" => "false",
            "IsEntryBatchFill" => "true",
        );

        //BillType
        $FBillTypeID = "XSCKD01_SYS";
        //SaleDeptId 销售部门： BM000022
        $FSaleDeptId = "BM000022";

        //销售店铺的组织机构id "102.4"
        $FSaleOrgId = "102.4";//$order['ShopOrgId'];
        //订单日期
        $FDate = date("Y-m-d");
        $userId = "02010001";
        //FCustId 散客 fnumber:02010001
        $FCustId = $userId;
        //FReceiveId 散客 fnumber:02010001
        $FReceiveId = $userId;
        //FSettleId 散客 02010001
        $FSettleId = $userId;
        //FPayerID 散客 02010001
        $FPayerID = $userId;

        $FOwnerTypeIdHead = "BD_OwnerOrg";
        $FOwnerIdHead = $FSaleOrgId; //"102.4"

        $saleOderContent["Model"] = array(
            "FID" => "0",
            "FBillTypeID" => array(
                "FNumber" => $FBillTypeID,
            ),
            "FBillNo" => "",
            "FDate" => $FDate,
            "FSaleOrgId" => array(
                "FNumber" => $FSaleOrgId,
            ),
            "FSaleDeptId" => array(
                "FNumber" => $FSaleDeptId,
            ),
            "FCustomerID" => array(
                "FNumber" => $FCustId,
            ),
            "FHeadLocationId" => array(
                "FNUMBER" => "",
            ),
            "FCarrierID" => array(
                "FNumber" => "",
            ),
            "FCorrespondOrgId" => array(
                "FNumber" => "",
            ),
            "FCarriageNO" => "",
            "FSalesGroupID" => array(
                "FNumber" => "",
            ),
            "FSalesManID" => array(
                "FNumber" => "",
            ),
            "FStockOrgId" => array(
                "FNumber" => $FSaleOrgId,
            ),
            "FDeliveryDeptID" => array(
                "FNumber" => "",
            ),
            "FStockerGroupID" => array(
                "FNumber" => "",
            ),
            "FStockerID" => array(
                "FNumber" => "",
            ),
            "FNote" => "",
            "FReceiverID" => array(
                "FNumber" => $FReceiveId,
            ),
            "FReceiveAddress" => "",
            "FSettleID" => array(
                "FNumber" => $FSettleId,
            ),
            "FReceiverContactID" => array(
                "FName" => "",
            ),
            "FPayerID" => array(
                "FNumber" => $FPayerID,
            ),
            "FOwnerTypeIdHead" => $FOwnerTypeIdHead,
            "FOwnerIdHead" => array(
                "FNumber" => $FOwnerIdHead
            ),
            "FScanBox" => "",
            "FCDateOffsetUnit" => "",
            "FCDateOffsetValue" => "0",
            "FPlanRecAddress" => "",
            "FIsTotalServiceOrCost" => "false",
        );

        //FSaleOrderFinance
        //币种  PRE001:人民币
        $FSettleCurrId = "PRE001";
        //FIsIncludedTax 是否包含税 true
        $FIsIncludedTax = "true";
        //FIsPriceExcludeTax 价格是否包含税 true
        $FIsPriceExcludeTax = "true";
        //FExchangeTypeId   固定汇率 HLTX01_SYS
        $FExchangeTypeId = "HLTX01_SYS";
        $FExchangeRate = "1";

        $saleOderContent["Model"]["SubHeadEntity"] = array(
            "FSettleCurrID" => array(
                "FNumber" => $FSettleCurrId,
            ),
            "FSettleOrgID" => array(
                "FNumber" => $FSaleOrgId,
            ),
            "FSettleTypeID" => array(
                "FNumber" => "",
            ),
            "FReceiptConditionID" => array(
                "FNumber" => "",
            ),
            "FPriceListId" => array(
                "FNumber" => "",
            ),
            "FDiscountListId" => array(
                "FNumber" => "",
            ),
            "FIsIncludedTax" => $FIsIncludedTax,
            "FLocalCurrID" => array(
                "FNumber" => "",
            ),
            "FExchangeTypeID" => array(
                "FNumber" => $FExchangeTypeId,
            ),
            "FExchangeRate" => $FExchangeRate,
            "FIsPriceExcludeTax" => $FIsPriceExcludeTax,
        );

        //源单类型
        $FSrcType = "SAL_SaleOrder";
        //源单编号
        $FSRCBillNo = "XSDD000008"; //$order['erp_xsdd_number'];
        $FEntity = array();
//        foreach($order['Goods'] as $key=>$goods) {
            //FMaterialId,物料number:"0601060049"
            $FMaterialId = "0601060049";//$goods['materialId'];
            //FUnitID 物料单位
            $FUnitID = "ping";
            //FQty 数量 3
            $FQty = "3";//$goods['order_goods_num'];
            //FTaxPrice 带税金额 8.8
            $FTaxPrice = "8.8";//$goods['goods_price'];

            $FStockID = "CK001";
            $FStockStatusID = "KCZT01_SYS";
            $FESettleCustomerId = "030006";
            $FEntity[] = array(
                "FENTRYID" => "0",
                "FRowType" => "Standard",
                "FCustMatID" => array(
                    "FNumber" => ""
                ),
                "FMaterialID" => array(
                    "FNumber" => $FMaterialId
                ),
                "FSrcType" => $FSrcType,
                "FSrcBillNo" => $FSRCBillNo,
                "FAuxPropId" => array(
                    "FNumber" => ""
                ),
                "FUnitID" => array(
                    "FNumber" => $FUnitID
                ),
                "FInventoryQty" => "0",
                "FParentMatId" => array(
                    "FNumber" => ""
                ),
                "FRealQty" => $FQty,
                "FDisPriceQty" => "0",
                "FPrice" => "0",
                "FTaxPrice" => $FTaxPrice,
                "FIsFree" => "false",
                "FBomID" => array(
                    "FNumber" => ""
                ),
                "FProduceDate" => "",
                "FOwnerTypeID" => $FOwnerTypeIdHead,
                "FOwnerID" => array(
                    "FNumber" => $FOwnerIdHead,
                ),
                "FLot" => array(
                    "FNumber" => ""
                ),
                "FExpiryDate" => "",
                "FTaxCombination" => array(
                    "FNumber" => ""
                ),
                "FEntryTaxRate" => "0",
                "FAuxUnitQty" => "0",
                "FExtAuxUnitId" => array(
                    "FNumber" => ""
                ),
                "FExtAuxUnitQty" => "0",
                "FStockID" => array(
                    "FNumber" => $FStockID
                ),
                "FStockLocID" => array(
                    "FNumber" => ""
                ),
                "FStockStatusID" => array(
                    "FNumber" => $FStockStatusID
                ),
                "FQualifyType" => "",
                "FMtoNo" => "",
                "FEntrynote" => "",
                "FDiscountRate" => "0",
                "FActQty" => "0",
                "FSalUnitID" => array(
                    "FNumber" => $FUnitID
                ),
                "FSALUNITQTY" => $FQty,
                "FSALBASEQTY" => $FQty,
                "FPRICEBASEQTY" => $FQty,
                "FProjectNo" => "",
                "FOUTCONTROL" => "false",
                "FRepairQty" => "0",
                "FIsCreateProDoc" => "",
                "FEOwnerSupplierId" => array(
                    "FNumber" => ""
                ),
                "FIsOverLegalOrg" => "false",
                "FESettleCustomerId" => array(
                    "FNumber" => $FESettleCustomerId
                ),
                "FPriceListEntry" => array(
                    "FNumber" => ""
                ),
                "FARNOTJOINQTY" => "0",
                "FQmEntryID" => "0",
                "FConvertEntryID" => "0",
                "FSOEntryId" => "0",
                "FBeforeDisPriceQty" => "0",
                "FTaxDetailSubEntity" => array(
                    array(
                        "FDetailID" => "0",
                        "FTaxRate" => "0"
                    )
                ),
                "FSerialSubEntity" => array(
                    array(
                        "FDetailID" => "0",
                        "FSerialNo" => "",
                        "FSerialNote" => ""
                    )
                ),
            );
//        }
        $saleOderContent["Model"]["FEntity"] = $FEntity;

        $FOutStockTrace = array();
        $FOutStockTrace[] = array(
            "FEntryID" => "0",
            "FLogComId" => array(
                "FCode" => "",
            ),
            "FCarryBillNo" => "",
            "FSalOrderTraceDetail" => array(
                0 => array(
                    "FDetailID" => "0",
                    "FTraceTime" => "",
                    "FTraceDetail" => ""
                )
            )
        );
        $saleOderContent["Model"]["FOutStockTrace"] = $FOutStockTrace;

        $sdata = json_encode($saleOderContent);
        $vdata = array(
            'FormId' =>'SAL_OUTSTOCK',
            'data' => $sdata

        );
        $saleorderresult = $this->invoke_save(self::CLOUD_URL,$vdata, self::$cookie_jar);

        return $saleorderresult;
    }

    //查看应收单  AR00000001
    function view_receivable($number,$cookie_jar){

        $viewdata = "{\"CreateOrgId\":\"0\",\"Number\":\"". $number . "\",\"Id\":\"\"}";
        $vdate = array(
            'FormId' =>'AR_receivable',
            'data' => $viewdata

        );
        return $this->invoke_view(self::CLOUD_URL, $vdate, $cookie_jar);
    }

    //保存应收单
    function save_reveivable($number,$order)
    {
        $result = $this->login();
        if(!empty($result['Message'])){
            return $result['Message'];
        }

        //FBillTypeID  标准应收单 YSD01_SYS
        //FDATE 日期 FENDDATE_H
        //FCUSTOMERID   02010001 散客
        //FCURRENCYID 当前币种 PRE001 人民币
        //FISPRICEEXCLUDETAX true
        // FSETTLEORGID 结算组织
        //FPAYORGID  组织 102.4
        //FSALEORGID 销售组织
        //FSALEDEPTID 销售哦部门
        //FSALEERID 销售员  0038
        //FCancelStatus A
        //FBUSINESSTYPE BZ
        //SetAccountType
        // FEntityDetail 详情    FMATERIALID 物料编码 FMaterialDesc 物料描述  | FPRICEUNITID 单位 |FPriceQty 数量 | FTaxPrice 含税单价 | FPrice 价格不用填
        //     | FEntryTaxRate 税率  | FNoTaxAmountFor_D 不含税总价 | FTAXAMOUNTFOR_D 税总价  |FALLAMOUNTFOR_D 含税总价
        // | FStockUnitId 库存单位 | FStockQty 数量 | FStockBaseQty 数量 | FSalUnitId 销售单位 | FSalQty 销售数量 | FSalBaseQty 数量 | PriceBaseDen 数量（不知道做什么的）| FSalBaseNum 数量 | FStockBaseNum 数量
       //     | FORDERENTRYID 订单号 出库单？ | FSOURCEBILLTYPEID 数据源类型  标准销售出库单  | FTaxAmount_T 税总价 （不知道是否要填）

        //FEntityPlan  FPAYAMOUNTFOR  整个订单总价

        //FMAINBOOKSTDCURRID 本位币  PRE001
        //  FEXCHANGETYPE 汇率类型 固定汇率
        //FExchangeRate 汇率1

        //entity 里面
        //关联单号 出库  FSOURCETYPE -> SAL_OUTSTOCK  | FSourceBillNo 出库单号 -> XSCKD000001
        //关联单号 退货  FSOURCETYPE -> SAL_RETURNSTOCK  | FSourceBillNo 退货单号 -> XSTHD000001

        $sdata  = "{\"Creator\":\"\",\"NeedUpDateFields\":[],\"NeedReturnFields\":[],\"IsDeleteEntry\":\"True\",\"SubSystemId\":\"\",
        \"IsVerifyBaseDataField\":\"false\",\"IsEntryBatchFill\":\"True\",
        \"Model\":{\"FID\":\"0\",\"FBillTypeID\":{\"FNumber\":\"YSD01_SYS\"},\"FBillNo\":\"\",\"FDATE\":\"2017-12-06\",\"FENDDATE_H\":\"2017-12-06\",\"FISINIT\":\"false\", 
        \"FCUSTOMERID\":{\"FNumber\":\"02010001\"},\"FCURRENCYID\":{\"FNumber\":\"PRE001\"},\"FPayConditon\":{\"FNumber\":\"\"},\"FACCOUNTSYSTEM\":{\"FNumber\":\"\"},
        \"FISPRICEEXCLUDETAX\":\"true\",\"FSETTLEORGID\":{\"FNumber\":\"102.4\"},\"FPAYORGID\":{\"FNumber\":\"102.4\"},\"FSALEORGID\":{\"FNumber\":\"102.4\"},\"FISTAX\":\"true\",
        \"FSALEDEPTID\":{\"FNumber\":\"BM000022\"},\"FSALEGROUPID\":{\"FNumber\":\"\"},\"FSALEERID\":{\"FNumber\":\"0038\"},\"FCancelStatus\":\"A\",\"FBUSINESSTYPE\":\"BZ\",
        \"FMatchMethodID\":\"0\",\"FAR_Remark\":\"\",\"FSetAccountType\":\"\",\"FISHookMatch\":\"false\",\"FISINVOICEARLIER\":\"false\",
        
        \"FsubHeadSuppiler\":{\"FORDERID\":{\"FNumber\":\"\"},\"FTRANSFERID\":{\"FNumber\":\"\"},\"FChargeId\":{\"FNumber\":\"\"}},
        
        \"FsubHeadFinc\":{\"FACCNTTIMEJUDGETIME\":\"1900-01-01\",\"FSettleTypeID\":{\"FNumber\":\"\"},\"FMAINBOOKSTDCURRID\":{\"FNumber\":\"PRE001\"},\"FEXCHANGETYPE\":{\"FNumber\":\"HLTX01_SYS\"},
        \"FExchangeRate\":\"1\",\"FTaxAmountFor\":\"0\",\"FNoTaxAmountFor\":\"0\"},
        
        \"FEntityDetail\":[{\"FEntryID\":\"0\",\"FMATERIALID\":{\"FNumber\":\"0601060049\"},\"FMaterialDesc\":\"安记白胡椒粉25g\",
        \"FCOSTID\":{\"FNumber\":\"\"},\"FASSETID\":{\"FNumber\":\"\"},\"FPRICEUNITID\":{\"FNumber\":\"ping\"},\"FPriceQty\":\"3\",\"FTaxPrice\":\"8.8\",\"FPrice\":\"0\",
        \"FTaxCombination\":{\"FNumber\":\"\"},\"FEntryTaxRate\":\"17\",\"FEntryDiscountRate\":\"0\",\"FNoTaxAmountFor_D\":\"0\",\"FDISCOUNTAMOUNTFOR\":\"0\",\"FTAXAMOUNTFOR_D\":\"0\",
        \"FCOSTDEPARTMENTID\":{\"FNumber\":\"\"},\"FAUXPROPID\":{},\"FALLAMOUNTFOR_D\":\"26.4\",\"FBUYIVQTY\":\"0\",\"FIVALLAMOUNTFOR\":\"0\",\"FBaseDeliveryMaxQty\":\"0\",\"FBaseStockOutJoinQty\":\"0\",
        
        \"FSOURCETYPE\":\"SAL_OUTSTOCK\",\"FSourceBillNo\":\"XSCKD000001\",  \"FORDERNUMBER\":\"XSCKD000001\",   
        
        
        
        \"FDeliveryControl\":\"false\",\"FLot\":{\"FNumber\":\"\"},\"FSTOCKORGID\":{\"FNumber\":\"\"},\"FPROCESSID\":{\"FNumber\":\"\"},\"FMONUMBER\":\"\",\"FMOENTRYSEQ\":\"0\",\"FOPNO\":\"\",\"FSEQNUMBER\":\"\",
        \"FFPRODEPARTMENTID\":{\"FNumber\":\"\"},\"FOPERNUMBER\":\"0\",\"FStockUnitId\":{\"FNumber\":\"ping\"},\"FSRCROWID\":\"0\",\"FStockQty\":\"3\",\"FIsFree\":\"false\",\"FStockBaseQty\":\"3\",
        \"FSalUnitId\":{\"FNumber\":\"ping\"},\"FSalQty\":\"3\",\"FSalBaseQty\":\"3\",\"FPriceBaseDen\":\"3\",\"FSalBaseNum\":\"3\",\"FStockBaseNum\":\"3\",\"FORDERENTRYID\":\"100001\",
        
        \"FSOURCEBILLTYPEID\":{\"FNumber\":\"XSCKD01_SYS\"},\"FORDERENTRYSEQ\":\"0\",\"FRECEIVEAMOUNT\":\"0\",\"FBUYIVINIQTY\":\"0\",\"FBUYIVINIBASICQTY\":\"0\",\"FIVINIALLAMOUNTFOR\":\"0\",
        \"FTaxDetailSubEntity\":[{\"FDetailID\":\"0\",\"FTaxRateId\":{\"FNumber\":\"\"},\"FTaxRate\":\"0\",\"FTaxAmount_T\":\"0\",\"FCostPercent\":\"0\",\"FCostAmount\":\"0\",\"FVAT\":\"false\",
        \"FSellerWithholding\":\"false\",\"FBuyerWithholding\":\"false\"}]}],
        
        \"FEntityPlan\":[{\"FEntryID\":\"0\",\"FENDDATE\":\"2017-12-06\",\"FPAYAMOUNTFOR\":\" 26.4\",\"FPAYRATE\":\"0\",\"FORDERBILLNO\":\"\",
        \"FSALEORDERID_S\":\"0\",\"FRECEIVABLEENTRYID\":\"0\",\"FMATERIALSEQ\":\"0\",\"FMATERIALID_S\":{\"FNumber\":\"\"}}],\"FARCOSTENTRY\":[{\"FEntryID\":\"0\"}]}}";

        $dataContent = array(
            "Creator" => "",
            "NeedUpDateFields" => array(),
            "NeedReturnFields" => array(),
            "IsDeleteEntry" => "True",
            "SubSystemId" => "",
            "IsVerifyBaseDataField" => "false",
            "IsEntryBatchFill" => "True",
        );

        //FBillTypeID  标准应收单 YSD01_SYS
        $FBillTypeID = "YSD01_SYS";
        //FDATE 日期 FENDDATE_H
        $FDATE = date("Y-m-s");
        //FCUSTOMERID   02010001 散客
        $FCUSTOMERID = "02010001";
        //FCURRENCYID 当前币种 PRE001 人民币
        $FCURRENCYID = "PRE001";
        //FISPRICEEXCLUDETAX true
        $FISPRICEEXCLUDETAX = "true";
        // FSETTLEORGID 结算组织
        $FSETTLEORGID = "102.4";
        //FPAYORGID  组织 102.4
        $FPAYORGID = $FSETTLEORGID;
        //FSALEORGID 销售组织
        $FSALEORGID = $FSETTLEORGID;
        $FISTAX = "true";
        //FSALEDEPTID 销售哦部门
        $FSALEDEPTID = "BM000022";
        //FSALEERID 销售员  0038
        $FSALEERID = "0038";
        //FCancelStatus A
        $FCancelStatus = "A";
        //FBUSINESSTYPE BZ
        $FBUSINESSTYPE = "BZ";
        //FMAINBOOKSTDCURRID 本位币  PRE001
        $FMAINBOOKSTDCURRID = "PRE001";
        //FEXCHANGETYPE 汇率类型 固定汇率
        $FEXCHANGETYPE = "HLTX01_SYS";
        //FExchangeRate 汇率1
        $FExchangeRate = "1";

        $dataContent["Model"] = array(
            "FID" => "0",
            "FBillTypeID" => array(
                "FNumber" => $FBillTypeID
            ),
            "FBillNo" => "",
            "FDATE" => $FDATE,
            "FENDDATE_H" => $FDATE,
            "FISINIT" => "false",
            "FCUSTOMERID" => array(
                "FNumber" => $FCUSTOMERID
            ),
            "FCURRENCYID" => array(
                "FNumber" => $FCURRENCYID
            ),
            "FPayConditon" => array(
                "FNumber" => ""
            ),
            "FACCOUNTSYSTEM" => array(
                "FNumber" => ""
            ),
            "FISPRICEEXCLUDETAX" => $FISPRICEEXCLUDETAX,
            "FSETTLEORGID" => array(
                "FNumber" => $FSETTLEORGID
            ),
            "FPAYORGID" => array(
                "FNumber" => $FPAYORGID
            ),
            "FSALEORGID" => array(
                "FNumber" => $FSALEORGID
            ),
            "FISTAX" => $FISTAX,
            "FSALEDEPTID" => array(
                "FNumber" => $FSALEDEPTID
            ),
            "FSALEGROUPID" => array(
                "FNumber" => ""
            ),
            "FSALEERID" => array(
                "FNumber" => $FSALEERID
            ),
            "FCancelStatus" => $FCancelStatus,
            "FBUSINESSTYPE" => $FBUSINESSTYPE,
            "FMatchMethodID" => "0",
            "FAR_Remark" => "",
            "FSetAccountType" => "",
            "FISHookMatch" => "false",
            "FISINVOICEARLIER" => "false",
            "FsubHeadSuppiler" => array(
                "FORDERID" => array(
                    "FNumber" => ""
                ),
                "FTRANSFERID" => array(
                    "FNumber" => ""
                ),
                "FChargeId" => array(
                    "FNumber" => ""
                )
            ),
            "FsubHeadFinc" => array(
                "FACCNTTIMEJUDGETIME" => "1900-01-01",
                "FSettleTypeID" => array(
                    "FNumber" => ""
                ),
                "FMAINBOOKSTDCURRID" => array(
                    "FNumber" => $FMAINBOOKSTDCURRID
                ),
                "FEXCHANGETYPE" => array(
                    "FNumber" => $FEXCHANGETYPE
                ),
                "FExchangeRate" => $FExchangeRate,
                "FTaxAmountFor" => "0",
                "FNoTaxAmountFor" => "0"
            ),
        );

        $totalAmount = 0;
        $FEntityDetail = array();

        //FMATERIALID 物料编码
        $FMATERIALID = "0601060049";
        //FMaterialDesc 物料描述
        $FMaterialDesc = "安记白胡椒粉25g";
        //FPRICEUNITID 单位 | FStockUnitId 库存单位 | FSalUnitId 销售单位 | FStockUnitId 库存单位
        $FPRICEUNITID = "ping";
        //FPriceQty 数量 | FStockQty 数量 | FStockBaseQty 数量 | FSalQty 销售数量 | FSalBaseQty 数量 | FSalBaseNum 数量 | FStockBaseNum 数量
        $FQty = "3";
        //FTaxPrice 含税单价
        $FTaxPrice = "8.8";
        //FEntryTaxRate 税率
        $FEntryTaxRate = "17";
        //FALLAMOUNTFOR_D 含税总价
        $FALLAMOUNTFOR_D = $FTaxPrice * $FQty;
        $totalAmount += $FALLAMOUNTFOR_D;
        //FORDERENTRYID 订单号 出库单
        $FORDERENTRYID = "100001";
        //FSOURCEBILLTYPEID 数据源类型  标准销售出库单
        $FSOURCEBILLTYPEID = "XSCKD01_SYS";

        $FEntityDetail[] =  array(
            "FEntryID" => "0",
            "FMATERIALID" => array(
                "FNumber" => $FMATERIALID
            ),
            "FMaterialDesc" => $FMaterialDesc,
            "FCOSTID" => array(
                "FNumber" => ""
            ),
            "FASSETID" => array(
                "FNumber" => ""
            ),
            "FPRICEUNITID" => array(
                "FNumber" => $FPRICEUNITID
            ),
            "FPriceQty" => $FQty,
            "FTaxPrice" => $FTaxPrice,
            "FPrice" => "0",
            "FTaxCombination" => array(
                "FNumber" => ""
            ),
            "FEntryTaxRate" => $FEntryTaxRate,
            "FEntryDiscountRate" => "0",
            "FNoTaxAmountFor_D" => "0",
            "FDISCOUNTAMOUNTFOR" => "0",
            "FTAXAMOUNTFOR_D" => "0",
            "FCOSTDEPARTMENTID" => array(
                "FNumber" => ""
            ),
            "FAUXPROPID" => array(
                "FNumber" => ""
            ),
            "FALLAMOUNTFOR_D" => $FALLAMOUNTFOR_D,
            "FBUYIVQTY" => "0",
            "FIVALLAMOUNTFOR" => "0",
            "FBaseDeliveryMaxQty" => "0",
            "FBaseStockOutJoinQty" => "0",
            "FDeliveryControl" => "false",
            "FLot" => array(
                "FNumber" => ""
            ),
            "FSTOCKORGID" => array(
                "FNumber" => ""
            ),
            "FPROCESSID" => array(
                "FNumber" => ""
            ),
            "FMONUMBER" => "",
            "FMOENTRYSEQ" => "0",
            "FOPNO" => "",
            "FSEQNUMBER" => "",
            "FFPRODEPARTMENTID" => array(
                "FNumber" => ""
            ),
            "FOPERNUMBER" => "0",
            "FStockUnitId" => array(
                "FNumber" => $FPRICEUNITID
            ),
            "FSRCROWID" => "0",
            "FStockQty" => $FQty,
            "FIsFree" => "false",
            "FStockBaseQty" => $FQty,
            "FSalUnitId" => array(
                "FNumber" => $FPRICEUNITID
            ),
            "FSalQty" => $FQty,
            "FSalBaseQty" => $FQty,
            "FPriceBaseDen" => $FQty,
            "FSalBaseNum" => $FQty,
            "FStockBaseNum" => $FQty,
            "FORDERENTRYID" => $FORDERENTRYID,
            "FSOURCEBILLTYPEID" => array(
                "FNumber" => $FSOURCEBILLTYPEID
            ),
            "FORDERENTRYSEQ" => "0",
            "FRECEIVEAMOUNT" => "0",
            "FBUYIVINIQTY" => "0",
            "FBUYIVINIBASICQTY" => "0",
            "FIVINIALLAMOUNTFOR" => "0",
            "FTaxDetailSubEntity" => [
                array(
                    "FDetailID" => "0",
                    "FTaxRateId" => array(
                        "FNumber" => ""
                    ),
                    "FTaxRate" => "0",
                    "FTaxAmount_T" => "0",
                    "FCostPercent" => "0",
                    "FCostAmount" => "0",
                    "FVAT" => "false",
                    "FSellerWithholding" => "false",
                    "FBuyerWithholding" => "false"
                )
            ],
        );
        $dataContent["Model"]["FEntityDetail"] = $FEntityDetail;

        //FEntityPlan  FPAYAMOUNTFOR  整个订单总价
        $FEntityPlan = array();
        $FEntityPlan[] =  array(
            "FEntryID" => "0",
            "FENDDATE" => $FDATE,
            "FPAYAMOUNTFOR" => $totalAmount,
            "FPAYRATE" => "0",
            "FORDERBILLNO" => "",
            "FSALEORDERID_S" => "0",
            "FRECEIVABLEENTRYID" => "0",
            "FMATERIALSEQ" => "0",
            "FMATERIALID_S" => array(
                "FNumber" => ""
            )
        );
        $dataContent["Model"]["FEntityPlan"] = $FEntityPlan;

        $FARCOSTENTRY = array();
        $FARCOSTENTRY[] = array(
            "FEntryID" => "0"
        );
        $dataContent["Model"]["FARCOSTENTRY"] = $FARCOSTENTRY;

        //$sdata = json_encode($dataContent);
        $vdata = array(
            'FormId' =>'AR_receivable',
            'data' => $sdata

        );
        $saleorderresult = $this->invoke_save(self::CLOUD_URL,$vdata, self::$cookie_jar);

        return $saleorderresult;

    }

    //保存销售退货单
    function save_return_stock($order)
    {
        $result = $this->login();
        if(!empty($result['Message'])){
            return $result['Message'];
        }

        //FBillTypeID ->  XSTHD01_SYS 标准销售退货单
        //Fdate -< 当前日期
        // FSaleOrgId  销售组织 -> 102.4
        //FSaledeptid 销售部门  ->  BM000022 源谷丰阳光大厦店
        // RetcustId -> 02010001 散客
        // FTransferBizType ->OverOrgSal 跨组织销售
        //FSalesManId 销售员 -> 0038 张璐璐
        //FStockOrgId 库存组织 -> 102.4
        //FReceiveCustId -> 02010001 散客
        //FSettleCustId -> 02010001 散客
        //FPayCustId -> 02010001 散客
        //FOwnerTypeIdHead -> BD_OwnerOrg
        //FOwnerIdHead 值是组织机构编码-> 102.4
        //FSettleOrgId   值是组织机构编码-> 102.4
        //FLocalCurrId  币种 ->PRE001
        //FExchangeTypeId  ->  固定汇率 HLTX01_SYS
        //FExchangeRate -> 1
        //FSettleCurrId 结算货币 -> PRE001
        // FOwnerTypeId -> BD_OwnerOrg
        //FOwnerId  值是组织机构编码-> 102.4

        //FEntity :  FMaterialId 物料编码  | FUnitID 物料单位 | FRealQty 实际数量 | FTaxPrice 含税总价 | FTaxRate 税率 | FReturnType -> THLX01_SYS 退货
        //  | FStockId 仓库 -> 阳光大厦店商品库 CK001 | FStockstatusId ->  KCZT01_SYS 可用 | FISCONSUMESUM 0 | SalUnitID 销售单位 -> | FSalUnitQty 销售单位数量
        // FSalBaseQty 销售数量 |  FPriceBaseQty
        //  FESettleCustomerId  结算客户 -> 030006 源谷丰第四分公司（阳光大厦店）
        // FSOEntryId 销售订单id

        //  FExpiryDate 设置空  ： 没有启用保质期管理，不允许录入生产日期和有效期至
        $sdata = "{\"Creator\":\"\",\"NeedUpDateFields\":[],\"NeedReturnFields\":[],\"IsDeleteEntry\":\"True\",\"SubSystemId\":\"\",\"IsVerifyBaseDataField\":\"false\",\"IsEntryBatchFill\":\"True\",
        \"Model\":{\"FID\":\"0\",\"FBillTypeID\":{\"FNumber\":\"XSTHD01_SYS\"},\"FBillNo\":\"\",\"FDate\":\"2017-12-07\",\"FSaleOrgId\":{\"FNumber\":\"102.4\"},\"FSaledeptid\":{\"FNumber\":\"\"},
        \"FRetcustId\":{\"FNumber\":\"02010001\"},\"FReturnReason\":{\"FNumber\":\"\"},\"FHeadLocId\":{\"FNUMBER\":\"\"},\"FSaleGroupId\":{\"FNumber\":\"\"},\"FTransferBizType\":{\"FNumber\":\"OverOrgSal\"},
        \"FCorrespondOrgId\":{\"FNumber\":\"\"},\"FSalesManId\":{\"FNumber\":\"0038\"},\"FStockOrgId\":{\"FNumber\":\"102.4\"},\"FStockDeptId\":{\"FNumber\":\"\"},\"FStockerGroupId\":{\"FNumber\":\"\"},\"FStockerId\":{\"FNumber\":\"\"},
        \"FHeadNote\":\"\",\"FReceiveCustId\":{\"FNumber\":\"02010001\"},\"FReceiveAddress\":\"\",\"FSettleCustId\":{\"FNumber\":\"02010001\"},\"FReceiveCusContact\":{\"FName\":\"\"},
        \"FPayCustId\":{\"FNumber\":\"02010001\"},\"FOwnerTypeIdHead\":\"BD_OwnerOrg\",\"FOwnerIdHead\":{\"FNumber\":\"102.4\"},\"FScanBox\":\"\",\"FCDateOffsetUnit\":\"\",\"FCDateOffsetValue\":\"0\",
        \"FDOCUMENTSTATUS\":\"C\",    \"FAPPROVERID_Id\":\"100113\",  \"FAPPROVEDATE\":\"2017-12-07\" ,

        \"SubHeadEntity\":{\"FSettleCurrId\":{\"FNumber\":\"PRE001\"},\"FSettleOrgId\":{\"FNumber\":\"102.4\"},\"FSettleTypeId\":{\"FNumber\":\"\"},\"FChageCondition\":{\"FNumber\":\"\"},
        \"FPriceListId\":{\"FNumber\":\"\"},\"FDiscountListId\":{\"FNumber\":\"\"},\"FLocalCurrId\":{\"FNumber\":\"PRE001\"},\"FExchangeTypeId\":{\"FNumber\":\"HLTX01_SYS\"},\"FExchangeRate\":\"1\"},
        
        \"FEntity\":[{\"FENTRYID\":\"0\",\"FRowType\":\"\",\"FMapId\":{\"FNumber\":\"\"},\"FMaterialId\":{\"FNumber\":\"0601060057\"},\"FAuxpropId\":{},
        \"FUnitID\":{\"FNumber\":\"bao\"},\"FInventoryQty\":\"0\",\"FRealQty\":\"1\",\"FParentMatId\":{\"FNumber\":\"\"},\"FPrice\":\"0\",\"FTaxPrice\":\"8\",\"FIsFree\":\"false\",
        \"FTaxCombination\":{\"FNumber\":\"\"},\"FEntryTaxRate\":\"0\",\"FBOMId\":{\"FNumber\":\"\"},\"FReturnType\":{\"FNumber\":\"THLX01_SYS\"},
        \"FOwnerTypeId\":\"BD_OwnerOrg\",\"FOwnerId\":{\"FNumber\":\"102.4\"},\"FProduceDate\":\"1900 - 01 - 01\",\"FExpiryDate\":\"\",
        \"FStockId\":{\"FNumber\":\"CK001\"},\"FStocklocId\":{},\"FStockstatusId\":{\"FNumber\":\"KCZT01_SYS\"},\"FDeliveryDate\":\"2017-12-07\",\"FMtoNo\":\"\",\"FNote\":\"\",\"FDiscountRate\":\"0\",\"FAuxUnitQty\":\"0\",
        \"FExtAuxUnitId\":{\"FNumber\":\"\"},\"FExtAuxUnitQty\":\"0\",\"FSalCostPrice\":\"0\",\"FISCONSUMESUM\":\"0\",\"FLot\":{\"FNumber\":\"\"},\"FSalUnitID\":{\"FNumber\":\"bao\"},
        
        \"FORDERNO\":\"XSDD000008\" ,\"FSrcBillTypeID\":\"SAL_SaleOrder\" , \"FSrcBillNo\":\"XSDD000008\",    \"FSTOCKFLAG\":\"1\"  ,
        
        \"FSalUnitQty\":\"1\",\"FSalBaseQty\":\"1\",\"FPriceBaseQty\":\"1\",\"FProjectNo\":\"\",\"FQualifyType\":\"\",\"FEOwnerSupplierId\":{\"FNumber\":\"\"},
        \"FIsOverLegalOrg\":\"false\",\"FESettleCustomerId\":{\"FNumber\":\"030006\"},\"FSOEntryId\":\"\",\"FPriceListEntry\":{\"FNumber\":\"\"},\"FARNOTJOINQTY\":\"0\",\"FIsReturnCheck\":\"false\",\"FTaxDetailSubEntity\":[{\"FDetailID\":\"0\",\"FTaxRate\":\"17\"}],
        
        
        \"FSerialSubEntity\":[{\"FDetailID\":\"0\",\"FSerialNo\":\"\",\"FSerialNote\":\"\"}]}]}}";
        // entity    "FSRCBILLTYPE":"SAL_SaleOrder", ,  "FSTOCKFLAG":"1"  ,  "FSrcBillTypeID":{"FNumber":"XSDD01_SYS"} ,
        //         "FDOCUMENTSTATUS":"C", "FAPPROVERID_Id":"100113",  "FAPPROVEDATE":"2017-12-07"  ,

        $dataContent = array(
            "Creator" => "",
            "NeedUpDateFields" => array(),
            "NeedReturnFields" => array(),
            "IsDeleteEntry" => "True",
            "SubSystemId" => "",
            "IsVerifyBaseDataField" => "false",
            "IsEntryBatchFill" => "True",
        );

        //FBillTypeID ->  XSTHD01_SYS 标准销售退货单
        $FBillTypeID = "XSTHD01_SYS";
        //Fdate -< 当前日期
        $FDate = date("Y-m-d");
        // FSaleOrgId  销售组织 -> 102.4
        $FSaleOrgId = "102.4";
        //FSaledeptid 销售部门  ->  BM000022 源谷丰阳光大厦店
        $FSaledeptid = "BM000022";
        // RetcustId -> 02010001 散客
        $RetcustId = "02010001";
        // FTransferBizType ->OverOrgSal 跨组织销售
        $FTransferBizType = "OverOrgSal";
        //FSalesManId 销售员 -> 0038 张璐璐
        $FSalesManId = "0038";
        //FStockOrgId 库存组织 -> 102.4
        $FStockOrgId = "102.4";
        //FReceiveCustId -> 02010001 散客
        $FReceiveCustId = "02010001";
        //FSettleCustId -> 02010001 散客
        $FSettleCustId = "02010001";
        //FPayCustId -> 02010001 散客
        $FPayCustId = "02010001";
        //FOwnerTypeIdHead -> BD_OwnerOrg
        $FOwnerTypeIdHead = "BD_OwnerOrg";
        //FOwnerIdHead 值是组织机构编码-> 102.4
        $FOwnerIdHead = "102.4";
        //FSettleOrgId   值是组织机构编码-> 102.4
        $FSettleOrgId = "102.4";
        //FLocalCurrId  币种 ->PRE001
        $FLocalCurrId = "PRE001";
        //FExchangeTypeId  ->  固定汇率 HLTX01_SYS
        $FExchangeTypeId = "HLTX01_SYS";
        //FExchangeRate -> 1
        $FExchangeRate = "1";
        //FSettleCurrId 结算货币 -> PRE001
        $FSettleCurrId = "PRE001";

        $dataContent["Model"] = array(
            "FID" => "0",
            "FBillTypeID" => array(
                "FNumber" => $FBillTypeID
            ),
            "FBillNo" => "",
            "FDate" => $FDate,
            "FSaleOrgId" => array(
                "FNumber" => $FSaleOrgId
            ),
            "FSaledeptid" => array(
                "FNumber" => $FSaledeptid
            ),
            "FRetcustId" => array(
                "FNumber" => $RetcustId
            ),
            "FReturnReason" => array(
                "FNumber" => ""
            ),
            "FHeadLocId" => array(
                "FNUMBER" => ""
            ),
            "FSaleGroupId" => array(
                "FNumber" => ""
            ),
            "FTransferBizType" => array(
                "FNumber" => $FTransferBizType
            ),
            "FCorrespondOrgId" => array(
                "FNumber" => ""
            ),
            "FSalesManId" => array(
                "FNumber" => $FSalesManId
            ),
            "FStockOrgId" => array(
                "FNumber" => $FStockOrgId
            ),
            "FStockDeptId" => array(
                "FNumber" => ""
            ),
            "FStockerGroupId" => array(
                "FNumber" => ""
            ),
            "FStockerId" => array(
                "FNumber" => ""
            ),
            "FHeadNote" => "",
            "FReceiveCustId" => array(
                "FNumber" => $FReceiveCustId
            ),
            "FReceiveAddress" => "",
            "FSettleCustId" => array(
                "FNumber" => $FSettleCustId
            ),
            "FReceiveCusContact" => array(
                "FName" => ""
            ),
            "FPayCustId" => array(
                "FNumber" => $FPayCustId
            ),
            "FOwnerTypeIdHead" => $FOwnerTypeIdHead,
            "FOwnerIdHead" => array(
                "FNumber" => $FOwnerIdHead
            ),
            "FScanBox" => "",
            "FCDateOffsetUnit" => "",
            "FCDateOffsetValue" => "0",
            "SubHeadEntity" => array(
                "FSettleCurrId" => array(
                    "FNumber" => $FSettleCurrId
                ),
                "FSettleOrgId" => array(
                    "FNumber" => $FSettleOrgId
                ),
                "FSettleTypeId" => array(
                    "FNumber" => ""
                ),
                "FChageCondition" => array(
                    "FNumber" => ""
                ),
                "FPriceListId" => array(
                    "FNumber" => ""
                ),
                "FDiscountListId" => array(
                    "FNumber" => ""
                ),
                "FLocalCurrId" => array(
                    "FNumber" => $FLocalCurrId
                ),
                "FExchangeTypeId" => array(
                    "FNumber" => $FExchangeTypeId
                ),
                "FExchangeRate" => $FExchangeRate
            ),
        );

        $FEntity = array();
        //FEntity :
        //FMaterialId 物料编码 "0601060057"
        $FMaterialId = "0601060049";
        //FUnitID 物料单位 "bao"
        $FUnitID = "ping";
        //FRealQty 实际数量
        $FRealQty = "1";
        //FTaxPrice 含税总价
        $FTaxPrice = "8.8";
        //FTaxRate 税率
        $FTaxRate = "17";
        //FReturnType -> THLX01_SYS 退货
        $FReturnType = "THLX01_SYS";
        // FOwnerTypeId -> BD_OwnerOrg
        $FOwnerTypeId = "BD_OwnerOrg";
        //FOwnerId  值是组织机构编码-> 102.4
        $FOwnerId = "102.4";
        //FStockId 仓库 -> 阳光大厦店商品库 CK001
        $FStockId = "CK001";
        //FStockstatusId ->  KCZT01_SYS 可用
        $FStockstatusId = "KCZT01_SYS";
        //FISCONSUMESUM 0
        $FISCONSUMESUM = "0";
        //FSalUnitID 销售单位
        $FSalUnitID = "ping";
        //FSalUnitQty 销售单位数量 | FSalBaseQty 销售数量 |  FPriceBaseQty
        $FSalUnitQty = $FRealQty;
        $FSalBaseQty = $FRealQty;
        $FPriceBaseQty = $FRealQty;
        //FESettleCustomerId  结算客户 -> 030006 源谷丰第四分公司（阳光大厦店）
        $FESettleCustomerId = "030006";
        //FSOEntryId 销售订单id
        $FSOEntryId = "100006";
        //FExpiryDate 设置空  ： 没有启用保质期管理，不允许录入生产日期和有效期至
        $FExpiryDate = "";

        $FORDERNO = "XSDD000008";
        $FSrcBillTypeID = "SAL_SaleOrder";
        $FSrcBillNo = "XSDD000008";
        $FSTOCKFLAG = "1";

        $FEntity[] = array(
            "FENTRYID" => "0",
            "FRowType" => "",
            "FMapId" => array(
                "FNumber" => ""
            ),
            "FMaterialId" => array(
                "FNumber" => $FMaterialId
            ),
            "FAuxpropId" => array(
                "FNumber" => ""
            ),
            "FUnitID" => array(
                "FNumber" => $FUnitID
            ),
            "FInventoryQty" => "0",
            "FRealQty" => $FRealQty,
            "FParentMatId" => array(
                "FNumber" => ""
            ),
            "FPrice" => "0",
            "FTaxPrice" => $FTaxPrice,
            "FIsFree" => "false",
            "FTaxCombination" => array(
                "FNumber" => ""
            ),
            "FEntryTaxRate" => "0",
            "FBOMId" => array(
                "FNumber" => ""
            ),
            "FReturnType" => array(
                "FNumber" => $FReturnType
            ),
            "FOwnerTypeId" => $FOwnerTypeId,
            "FOwnerId" => array(
                "FNumber" => $FOwnerId
            ),
            "FProduceDate" => "1900 - 01 - 01",
            "FExpiryDate" => "",
            "FStockId" => array(
                "FNumber" => $FStockId
            ),
            "FStocklocId" => array(
                "FNumber" => ""
            ),
            "FStockstatusId" => array(
                "FNumber" => $FStockstatusId
            ),
            "FDeliveryDate" => $FDate,
            "FMtoNo" => "",
            "FNote" => "",
            "FDiscountRate" => "0",
            "FAuxUnitQty" => "0",
            "FExtAuxUnitId" => array(
                "FNumber" => ""
            ),
            "FExtAuxUnitQty" => "0",
            "FSalCostPrice" => "0",
            "FISCONSUMESUM" => $FISCONSUMESUM,
            "FLot" => array(
                "FNumber" => ""
            ),
            "FSalUnitID" => array(
                "FNumber" => $FSalUnitID
            ),
            "FORDERNO" => $FORDERNO,
            "FSrcBillTypeID" => $FSrcBillTypeID,
            "FSrcBillNo" => $FSrcBillNo,
            "FSTOCKFLAG" => $FSTOCKFLAG,
            "FSalUnitQty" => $FSalUnitQty,
            "FSalBaseQty" => $FSalBaseQty,
            "FPriceBaseQty" => $FPriceBaseQty,
            "FProjectNo" => "",
            "FQualifyType" => "",
            "FEOwnerSupplierId" => array(
                "FNumber" => ""
            ),
            "FIsOverLegalOrg" => "false",
            "FESettleCustomerId" => array(
                "FNumber" => $FESettleCustomerId
            ),
            "FSOEntryId" => $FSOEntryId,
            "FPriceListEntry" => array(
                "FNumber" => ""
            ),
            "FARNOTJOINQTY" => "0",
            "FIsReturnCheck" => "false",
            "FTaxDetailSubEntity" => array(
                0 => array(
                    "FDetailID" => "0",
                    "FTaxRate" => $FTaxRate
                )
            ),
            "FSerialSubEntity" => array(
                0 => array(
                    "FDetailID" => "0",
                    "FSerialNo" => "",
                    "FSerialNote" => ""
                )
            )
        );

        $dataContent["Model"]["FEntity"] = $FEntity;

       // $sdata = json_encode($dataContent);
        file_put_contents("/web/shop/shop/data/logs/my.log", $sdata,FILE_APPEND);
        $vdata = array(
            'FormId' =>'SAL_RETURNSTOCK',
            'data' => $sdata

        );
        $saleorderresult = $this->invoke_save(self::CLOUD_URL,$vdata, self::$cookie_jar);

        return $saleorderresult;

    }

    //收款退款单
    function save_refund_bill($order)
    {
        //FBillTypeID  -> SKTKDLX01_SYS 销售业务退款单
        //  FCONTACTUNITTYPE -> BD_Customer
        //FCONTACTUNIT -> 02010001 散客
        //FSETTLERATE -> 1
        // FDOCUMENTSTATUS -> D
        //FRECTUNITTYPE -> BD_Customer
        //FRECTUNIT -> 02010001 散客
        //FCURRENCYID  -> 币种
        //FSETTLEORGID 组织机构  -> 102.4
        // FSALEORGID   组织机构  -> 102.4
        //FSALEDEPTID 销售部门 ->  BM000022 源谷丰阳光大厦店
        //FSALEERID 销售员
        //FEXCHANGERATE -> 1
        // FCancelStatus -> B
        //FPAYORGID  组织机构  -> 102.4
        //FISSAMEORG -> true
        // FSETTLECUR 币种 -> PRE001
        //FSOURCESYSTEM -> 0

        // FREFUNDBILLENTRY | FSETTLETYPEID -> "电汇 JSFS04_SYS | FPURPOSEID -> SFKYT01_SYS 销售收款  | FREFUNDAMOUNTFOR ，FREFUNDAMOUNTFOR_E ,FREFUNDAMOUNT_E 退款金额
        // FACCOUNTID 退款账户 -> 603013010000006950 秦皇岛银行建设大街支行 | FRecType -> 0 | FPOSTDATE ->日期 | FISPOST -> true | FRuZhangType -> 1
        // FPayType -> A  | FNOTE -> 备注必填 "退款"
        //FREALREFUNDAMOUNTFOR 实退金额
        // REFUNDBILLPAYENTRY, BillRefundPayEntry , REFUNDBILLRECEIVENTRY 空array
        // FREFUNDBILLSRCENTRY 关联源单 FSOURCETYPE源单类型 -> AR_receivable | FSRCBILLNO 原单编号 收款单 | FPLANREFUNDAMOUNT 计划退款金额 | FPLANREFUNDAMOUNT 实退金额本位币  |FAFTTAXTOTALAMOUNT 应退金额:
        $sdata = "{\"Creator\":\"\",\"NeedUpDateFields\":[],\"NeedReturnFields\":[],\"IsDeleteEntry\":\"True\",\"SubSystemId\":\"\",\"IsVerifyBaseDataField\":\"false\",\"IsEntryBatchFill\":\"True\",
        \"Model\":{\"FID\":\"0\",\"FBillTypeID\":{\"FNumber\":\"SKTKDLX01_SYS\"},\"FBillNo\":\"\",\"FDATE\":\"2017-12-07\",\"FCONTACTUNITTYPE\":\"BD_Customer\",\"FCONTACTUNIT\":{\"FNumber\":\"02010001\"},
        \"FISINIT\":\"false\",\"FDepartment\":{\"FNumber\":\"\"},\"FSETTLERATE\":\"1\",\"FDOCUMENTSTATUS\":\"C\",\"FRECTUNITTYPE\":\"BD_Customer\",\"FRECTUNIT\":{\"FNumber\":\"02010001\"},\"FCURRENCYID\":{\"FNumber\":\"PRE001\"},
        \"FSETTLEORGID\":{\"FNumber\":\"102.4\"},\"FSALEORGID\":{\"FNumber\":\"102.4\"},\"FSALEDEPTID\":{\"FNumber\":\"BM000022\"},\"FSALEGROUPID\":{\"FNumber\":\"\"},\"FSALEERID\":{\"FNumber\":\"0038\"},\"FEXCHANGERATE\":\"1\",
        \"FCancelStatus\":\"B\",\"FPAYORGID\":{\"FNumber\":\"102.4\"},\"FISSAMEORG\":\"true\",\"FSETTLECUR\":{\"FNumber\":\"PRE001\"},\"FISB2C\":\"false\",\"FSOURCESYSTEM\":\"0\",\"FIsWriteOff\":\"false\",
        \"FMatchMethodID\":\"0\",
        
        \"FREFUNDTOTALAMOUNTFOR\":\"8\",\"FREFUNDTOTALAMOUNT\":\"8\",\"FREALREFUNDAMOUNT\":\"8\",\"FREALREFUNDAMOUNTFOR\":\"8\",\"FREFUNDAMOUNTFOR_H\":\"8\",\"FREFUNDAMOUNT_H\":\"8\",
       
        
        \"FREFUNDBILLENTRY\":[{\"FEntryID\":\"0\",\"FSETTLETYPEID\":{\"FNumber\":\"JSFS04_SYS\"},\"FPURPOSEID\":{\"FNumber\":\"SFKYT01_SYS\"},\"FREFUNDAMOUNTFOR\":\"8\",\"FREFUNDAMOUNTFOR_E\":\"8\",
        \"FREFUNDAMOUNT\":\"8\",\"FWRITTENOFFAMOUNTFOR_D\":\"8\", \"FREALREFUNDAMOUNTFOR\":\"8\" , \"FREALREFUNDAMOUNT\":\"8\"  ,  \"FREFUNDAMOUNTFOR_E\":\"8\"  , \"FREFUNDAMOUNT_E\":\"8\"  ,
        
        \"FHANDLINGCHARGEFOR\":\"0\",\"FACCOUNTID\":{\"FNumber\":\"603013010000006950\"},\"FINNERACCOUNTID\":{\"FNumber\":\"\"},\"FCashAccount\":{\"FNumber\":\"\"},\"FSETTLENO\":\"\",\"FNOTE\":\"退款\",\"FOpenAddressRec\":\"\",
        \"FRecType\":\"0\",\"FREFUNDAMOUNT_E\":\"8\",\"FPOSTDATE\":\"2017-12-07\",\"FISPOST\":\"true\",\"FSALEORDERNUMBER\":\"\",\"FMATERIALID\":{\"FNumber\":\"\"},\"FMATERIALSEQ\":\"0\",
        \"FORDERENTRYID\":\"0\",\"FRuZhangType\":\"1\",\"FEBMSG\":\"\",\"FPayType\":\"A\"
        
        
        }],
       
       \"FREFUNDBILLSRCENTRY\":[{\"FEntryID\":\"0\",\"FPAYPURPOSEID\":{\"FNumber\":\"\"},\"FSOURCETYPE\":\"AR_receivable\",\"FSRCBILLNO\":\"AR00000003\",\"FPLANREFUNDAMOUNT\":\"8\", 
       \"FAFTTAXTOTALAMOUNT\":\"8\",\"FREALREFUNDAMOUNT\":\"8\",
       }  ],
        \"FREFUNDBILLPAYENTRY\":[{\"FEntryID\":\"0\",\"FINNERACCOUNTID_B\":{\"FNumber\":\"\"},\"FBILLID\":{\"FNumber\":\"\"},\"FUSEDAMOUNTFOR\":\"0\",\"FUSEDAMOUNTSTD\":\"0\",\"FTempOrgId\":{\"FNumber\":\"\"}}],
        
        \"FBillRefundPayEntry\":[{\"FEntryID\":\"0\",\"FBillRecId\":{\"FNumber\":\"\"},
        \"FPayPurseId\":{\"FNumber\":\"\"},\"FReTurnAmount\":\"0\",\"FRETURNAMOUNTSTD\":\"0\",\"FKDBPARBILLNO\":\"\",\"FParAmount\":\"0\",\"FPARAMOUNTSTD\":\"0\",\"FInnerActId\":{\"FNumber\":\"\"},
        \"FBCONTACTUNITTYPE\":\"\",\"FBCONTACTUNIT\":{\"FNumber\":\"\"}}],
        
        \"FREFUNDBILLRECEIVENTRY\":[{\"FEntryID\":\"0\",\"FRECBILLID\":{\"FNumber\":\"\"},\"FRCONTACTUNITTYPE\":\"\",\"FRECCONTACTUNIT\":{\"FNumber\":\"\"},
        \"FBPBILLPARAMOUNT\":\"0\",\"FRETURNBILLAMOUNT\":\"0\",\"FBPBILLNUMBER\":\"\",\"FPAYPURSERECID\":{\"FNumber\":\"\"},\"FRETURNAMOUNTFOR\":\"0\",\"FBILLPARAMOUNTFOR\":\"0\",\"FINNERACCOUNTID_T\":{\"FNumber\":\"\"}}]
        
        }}";
//   "FREFUNDBILLSRCENTRY":[{"FEntryID":"0","FPAYPURPOSEID":{"FNumber":""},"FSOURCETYPE":"AR_receivable","FSRCBILLNO":"AR00000003" }  ],

// FREFUNDBILLSRCENTRY 关联源单 FSOURCETYPE源单类型 -> AR_receivable | FSRCBILLNO 原单编号 收款单 | FPLANREFUNDAMOUNT 计划退款金额 | FPLANREFUNDAMOUNT 实退金额本位币  |FAFTTAXTOTALAMOUNT应退金额:
        //"FREFUNDBILLSRCENTRY":[{"FEntryID":"0","FPAYPURPOSEID":{"FNumber":""},"FSOURCETYPE":"AR_receivable","FSRCBILLNO":"AR00000003" ,"FPLANREFUNDAMOUNT":"8" ,"FPLANREFUNDAMOUNT":"8" ,"FAFTTAXTOTALAMOUNT":"8"  }  }  ],

        //FBillTypeID  -> SKTKDLX01_SYS 销售业务退款单
        $FBillTypeID = "SKTKDLX01_SYS";
        $FDATE = date("Y-m-d");
        //FCONTACTUNITTYPE -> BD_Customer
        $FCONTACTUNITTYPE = "BD_Customer";
        //FCONTACTUNIT -> 02010001 散客
        $FCONTACTUNIT = "02010001";
        //FSETTLERATE -> 1
        $FSETTLERATE = "1";
        // FDOCUMENTSTATUS -> D
        $FDOCUMENTSTATUS = "D";
        //FRECTUNITTYPE -> BD_Customer
        $FRECTUNITTYPE = "BD_Customer";
        //FRECTUNIT -> 02010001 散客
        $FRECTUNIT = "02010001";
        //FCURRENCYID  -> 币种
        $FCURRENCYID = "PRE001";
        //FSETTLEORGID 组织机构  -> 102.4
        $FSETTLEORGID = "102.4";
        // FSALEORGID   组织机构  -> 102.4
        $FSALEORGID = "102.4";
        //FSALEDEPTID 销售部门 ->  BM000022 源谷丰阳光大厦店
        $FSALEDEPTID = "BM000022";
        //FSALEERID 销售员
        $FSALEERID = "0038";
        //FEXCHANGERATE -> 1
        $FEXCHANGERATE = "1";
        // FCancelStatus -> B
        $FCancelStatus = "B";
        //FPAYORGID  组织机构  -> 102.4
        $FPAYORGID = "102.4";
        //FISSAMEORG -> true
        $FISSAMEORG = "true";
        // FSETTLECUR 币种 -> PRE001
        $FSETTLECUR = "PRE001";
        //FSOURCESYSTEM -> 0
        $FSOURCESYSTEM = "0";

        //FSETTLETYPEID -> "电汇 JSFS04_SYS
        $FSETTLETYPEID = "JSFS04_SYS";
        //FPURPOSEID -> SFKYT01_SYS 销售收款
        $FPURPOSEID = "SFKYT01_SYS";

        //FREFUNDAMOUNTFOR ，FREFUNDAMOUNTFOR_E ,FREFUNDAMOUNT_E 退款金额
        $FREFUNDAMOUNTFOR = "8";
        $FREFUNDAMOUNTFOR_E = "8";
        $FREFUNDAMOUNT_E = "8";
        //FREALREFUNDAMOUNTFOR 实退金额
        //FACCOUNTID 退款账户 -> 603013010000006950 秦皇岛银行建设大街支行
        $FACCOUNTID = "603013010000006950";
        //FNOTE -> 备注必填 "退款"
        $FNOTE = "退款";
        $FPOSTDATE = date("Y-m-d");
        $FISPOST = "true";
        $FRuZhangType = "1";
        //FPayType -> A "退款"
        $FPayType = "A";

        //应收单
        $FSOURCETYPE = "AR_receivable";
        $FSRCBILLNO = "AR00000003";

        $dataContent = array(
            "Creator" => "",
            "NeedUpDateFields" => array(),
            "NeedReturnFields" => array(),
            "IsDeleteEntry" => "True",
            "SubSystemId" => "",
            "IsVerifyBaseDataField" => "false",
            "IsEntryBatchFill" => "True",
            "Model" => array(
                "FID" => "0",
                "FBillTypeID" => array(
                    "FNumber" => $FBillTypeID
                ),
                "FBillNo" => "",
                "FDATE" => $FDATE,
                "FCONTACTUNITTYPE" => $FCONTACTUNITTYPE,
                "FCONTACTUNIT" => array(
                    "FNumber" => $FCONTACTUNIT
                ),
                "FISINIT" => "false",
                "FDepartment" => array(
                    "FNumber" => ""
                ),
                "FSETTLERATE" => $FSETTLERATE,
                "FDOCUMENTSTATUS" => $FDOCUMENTSTATUS,
                "FRECTUNITTYPE" => $FRECTUNITTYPE,
                "FRECTUNIT" => array(
                    "FNumber" => $FRECTUNIT
                ),
                "FCURRENCYID" => array(
                    "FNumber" => $FCURRENCYID
                ),
                "FSETTLEORGID" => array(
                    "FNumber" => $FSETTLEORGID
                ),
                "FSALEORGID" => array(
                    "FNumber" => $FSALEORGID
                ),
                "FSALEDEPTID" => array(
                    "FNumber" => $FSALEDEPTID
                ),
                "FSALEGROUPID" => array(
                    "FNumber" => ""
                ),
                "FSALEERID" => array(
                    "FNumber" => $FSALEERID
                ),
                "FEXCHANGERATE" => $FEXCHANGERATE,
                "FCancelStatus" => $FCancelStatus,
                "FPAYORGID" => array(
                    "FNumber" => $FPAYORGID
                ),
                "FISSAMEORG" => $FISSAMEORG,
                "FSETTLECUR" => array(
                    "FNumber" => $FSETTLECUR
                ),
                "FISB2C" => "false",
                "FSOURCESYSTEM" => $FSOURCESYSTEM,
                "FIsWriteOff" => "false",
                "FMatchMethodID" => "0",
                "FREFUNDBILLENTRY" => [
                    array(
                        "FEntryID" => "0",
                        "FSETTLETYPEID" => array(
                            "FNumber" => $$FSETTLETYPEID
                        ),
                        "FPURPOSEID" => array(
                            "FNumber" => $FPURPOSEID
                        ),
                        "FREFUNDAMOUNTFOR" => $FREFUNDAMOUNTFOR,
                        "FREFUNDAMOUNTFOR_E" => $FREFUNDAMOUNTFOR_E,
                        "FHANDLINGCHARGEFOR" => "0",
                        "FACCOUNTID" => array(
                            "FNumber" => $FACCOUNTID
                        ),
                        "FINNERACCOUNTID" => array(
                            "FNumber" => ""
                        ),
                        "FCashAccount" => array(
                            "FNumber" => ""
                        ),
                        "FSETTLENO" => "",
                        "FNOTE" => $FNOTE,
                        "FOpenAddressRec" => "",
                        "FRecType" => "0",
                        "FREFUNDAMOUNT_E" => $FREFUNDAMOUNT_E,
                        "FPOSTDATE" => $FPOSTDATE,
                        "FISPOST" => $FISPOST,
                        "FSALEORDERNUMBER" => "",
                        "FMATERIALID" => array(
                            "FNumber" => ""
                        ),
                        "FMATERIALSEQ" => "0",
                        "FORDERENTRYID" => "0",
                        "FRuZhangType" => $FRuZhangType,
                        "FEBMSG" => "",
                        "FPayType" => $FPayType
                    )
                ],
                "FREFUNDBILLSRCENTRY" => [
                    array(
                        "FEntryID" => "0",
                        "FPAYPURPOSEID" => array(
                            "FNumber" => ""
                        ),
                        "FSOURCETYPE" => $FSOURCETYPE,
                        "FSRCBILLNO" => $FSRCBILLNO
                    )
                ],
                "FREFUNDBILLPAYENTRY" => [
                    array(
                        "FEntryID" => "0",
                        "FINNERACCOUNTID_B" => array(
                            "FNumber" => ""
                        ),
                        "FBILLID" => array(
                            "FNumber" => ""
                        ),
                        "FUSEDAMOUNTFOR" => "0",
                        "FUSEDAMOUNTSTD" => "0",
                        "FTempOrgId" => array(
                            "FNumber" => ""
                        )
                    )
                ],
                "FBillRefundPayEntry" => [
                    array(
                        "FEntryID" => "0",
                        "FBillRecId" => array(
                            "FNumber" => ""
                        ),
                        "FPayPurseId" => array(
                            "FNumber" => ""
                        ),
                        "FReTurnAmount" => "0",
                        "FRETURNAMOUNTSTD" => "0",
                        "FKDBPARBILLNO" => "",
                        "FParAmount" => "0",
                        "FPARAMOUNTSTD" => "0",
                        "FInnerActId" => array(
                            "FNumber" => ""
                        ),
                        "FBCONTACTUNITTYPE" => "",
                        "FBCONTACTUNIT" => array(
                            "FNumber" => ""
                        )
                    )
                ],
                "FREFUNDBILLRECEIVENTRY" => [
                    array(
                        "FEntryID" => "0",
                        "FRECBILLID" => array(
                            "FNumber" => ""
                        ),
                        "FRCONTACTUNITTYPE" => "",
                        "FRECCONTACTUNIT" => array(
                            "FNumber" => ""
                        ),
                        "FBPBILLPARAMOUNT" => "0",
                        "FRETURNBILLAMOUNT" => "0",
                        "FBPBILLNUMBER" => "",
                        "FPAYPURSERECID" => array(
                            "FNumber" => ""
                        ),
                        "FRETURNAMOUNTFOR" => "0",
                        "FBILLPARAMOUNTFOR" => "0",
                        "FINNERACCOUNTID_T" => array(
                            "FNumber" => ""
                        )
                    )
                ]
            )
        );

    //    $sdata = json_encode($dataContent);
        $vdata = array(
            'FormId' =>'AR_REFUNDBILL',
            'data' => $sdata

        );
        $result = $this->invoke_save(self::CLOUD_URL,$vdata, self::$cookie_jar);

        return $result;
    }

    //登陆
    function invoke_login($cloudUrl,$post_content,$cookie_jar)
    {
        $loginurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.AuthService.ValidateUser.common.kdsvc';
        return  $this-> async($loginurl,$post_content,true,self::REQ_POST,$cookie_jar,true);
    }

    //保存
    function invoke_save($cloudUrl,$post_content,$cookie_jar)
    {
        $invokeurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.Save.common.kdsvc';
       // return invoke_post($invokeurl,$post_content,$cookie_jar,FALSE);
        return $this-> async($invokeurl,$post_content,true,self::REQ_POST,$cookie_jar,false);
    }

    function invoke_query($cloudUrl,$post_content,$cookie_jar){

        $invkoeurl  = $cloudUrl. 'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.ExecuteBillQuery.common.kdsvc';
        return  $this-> async($invkoeurl,$post_content,true,self::REQ_POST,$cookie_jar,false);
    }

    function invoke_view($cloudUrl,$post_content,$cookie_jar){

        $invokeurl  = $cloudUrl. 'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.View.common.kdsvc';
        return  $this-> async($invokeurl,$post_content,true,self::REQ_POST,$cookie_jar,false);
    }



    //审核
    function invoke_audit($cloudUrl,$post_content,$cookie_jar)
    {
        $invokeurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.Audit.common.kdsvc';
        return  $this-> async($invokeurl,$post_content,true,self::REQ_POST,$cookie_jar,false);
    }


    private function async($url, $params = array(), $encode = true, $method = self::REQ_GET,$cookie_jar,$isLogin)
    {
        file_put_contents("/web/shop/shop/data/logs/my.log", 'begin get data',FILE_APPEND);
        $ch = curl_init();
        if ($method == self::REQ_GET)
        {
            $url = $url . '?' . http_build_query($params);
            $url = $encode ? $url : urldecode($url);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        else
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_REFERER, 'k3 cloud referer');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($isLogin){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        }
        else{
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        }
        file_put_contents("/web/shop/shop/data/logs/my.log", $ch,FILE_APPEND);

        $resp = curl_exec($ch);
        file_put_contents("/web/shop/shop/data/logs/my.log", $resp,FILE_APPEND);

        curl_close($ch);
        return $resp;
    }

    //示例代码，不工作
    function invoke_post($url,$post_content,$cookie_jar,$isLogin)
    {
        $ch = curl_init($url);

        $this_header = array(
            'Content-Type: application/json',
            'Content-Length: '.strlen($post_content)
        );

         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($isLogin){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        }
        else{
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        }
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    //构造Web API请求格式
    function create_postdata($args) {
        $postdata = array(
            'format'=>1,
            'useragent'=>'ApiClient',
            'rid'=>$this-> create_guid(),
            'parameters'=>$args,
            'timestamp'=>date('Y-m-d'),
            'v'=>'1.0'
        );
        return json_encode($postdata);

    }

    //生成guid
    function create_guid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}