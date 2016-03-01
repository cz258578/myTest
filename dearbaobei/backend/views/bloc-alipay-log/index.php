<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/css/default.css">

<div class="easyui-layout" fit="true">

    <div region="north" border="false" style="padding:10px;height:48px; padding-top:16px; padding-bottom:0px; overflow:hidden;">

        <!--工具按钮和筛选栏目开始-->
        <div class="easyui-layout" fit="true" border="false">

            <!--工具栏开始-->
            <div id="main_body_tool" region="west" style="width:188px; padding-right:18px;" border="false">
                <!--<a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="add()">新增集团</a>-->
            </div>
            <!--工具栏结束-->

                <!--筛选条件开始-->
                <div id="main_body_search" region="center" border="false" style="padding-left:26px;">筛选条件：

                    <input id="name" value="" style="cursor:pointer;" class="easyui-textbox" data-options="prompt:'输入集团名称'">

                    <a id="mysearch_btn" href="#" class="easyui-linkbutton" plain="true"
                       data-options="iconCls:'icon-search'" style="margin-left:8px;" onclick="doSearch();return false;">查询</a>
                </div>
                <!--筛选条件结束-->

        </div>
        <!--工具按钮和筛选栏目结束-->

    </div>

    <div region="center" border="false" id="main_body_datagrid" style="padding:8px; padding-bottom:0px;">

        <!--数据表开始-->
        <table id="tt" class="easyui-datagrid"
               data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['bloc-alipay-log/infojson'])?>',
               fitColumns:true,fit:true,pagination:true,onLoadSuccess: dataLoadSuccess,
			   singleSelect:true,pageList:<?= yii::$app->params['pageRow']?>,pageSize:<?= yii::$app->params['pageSize']?>,
			   rownumbers:true,checkOnSelect:true">
            <thead>
            <tr>
                <th data-options="field:'bloc_name',width:60">集团名称</th>
                <th data-options="field:'teacher_name',width:60">操作人</th>
                <th data-options="field:'order_number',width:60">充值订单号</th>
                <th data-options="field:'money',width:60">充值金额</th>
                <th data-options="field:'type',width:60">充值方式</th>
                <th data-options="field:'status_name',width:60">支付状态</th>
                <th data-options="field:'account_log_id',width:60">账户流水ID</th>
                <th data-options="field:'create_time_name',width:60">创建时间</th>
            </tr>
            </thead>
        </table>
        <!--数据表结束-->

    </div>

</div>

<script type="text/javascript">
    /**
     * 表格加载完成
     * @param data
     */
    function dataLoadSuccess(data){
        if(data.total == 0){ //没有记录
            $(this).datagrid('appendRow', { bloc_name: '<div style="text-align:center;color:red"><?= yii::$app->params['noRecordeTips']?></div>' })
                .datagrid('mergeCells', { index: 0, field: 'bloc_name', colspan: 8 })

            $(this).closest('div.datagrid-wrap').find('div.datagrid-pager').hide();
        }
    }

    /**
     * 添加按钮函数
     * @param value
     * @param row
     * @param index
     * @returns {*}
     */
    function rowformater(value,row,index)
    {
        var rs = '';
        rs += '<a onclick="edit('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
            '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">编辑</span>' +
            '<span class="l-btn-icon icon-edit">&nbsp;</span></span>' +
            '</a>';

        return rs;
    }


    function edit(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.editUrl;
        title = '编辑集团';
        callfun = 'callback';
        openTopWindow(url,title,488,298,callfun);
    }

    function callback(){
        $('#tt').datagrid('reload',{
            name: $('#name').textbox("getValue")
        });
    }

    function doSearch(){
        callback();
    }

</script>