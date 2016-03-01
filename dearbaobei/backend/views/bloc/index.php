<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/css/default.css">

<div class="easyui-layout" fit="true">

    <div region="north" border="false" style="padding:10px;height:48px; padding-top:16px; padding-bottom:0px; overflow:hidden;">

        <!--工具按钮和筛选栏目开始-->
        <div class="easyui-layout" fit="true" border="false">

                <!--筛选条件开始-->
                <div id="main_body_search" region="center" border="false" style="padding-left:0px;">用户名称：<input id="name" value="" style="cursor:pointer;width:100px;" class="easyui-textbox" data-options="prompt:'输入客户名称'">

                    &nbsp;省市区：
                    <input id="province_id" name="province_id" style="width:100px;" value="" class="easyui-combobox" data-options="">
                    <input id="city_id" name="city_id" value="" style="width:100px;" class="easyui-combobox" data-options="">
                    <input id="area_id" name="area_id" value="" style="width:100px;" class="easyui-combobox" data-options="">

                    &nbsp;代理商：<input id="agent_id" value="" style="cursor:pointer;width:80px;" class="easyui-textbox" data-options="prompt:'代理商ID'">

                    &nbsp;客户专员：<input id="admin_user_id" value="" style="cursor:pointer;width:80px;" class="easyui-textbox" data-options="prompt:'客户专员ID'">

                    &nbsp;注册时间：<input id="create_time_start" name="bespeak_enter_time" style="width:100px;" value="" class="easyui-validatebox" data-options="">
                    至
                    <input id="create_time_end" name="bespeak_enter_time" style="width:100px;" value="" class="easyui-validatebox" data-options="">

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
               data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['bloc/infojson'])?>',fitColumns:true,fit:true,pagination:true,
               onLoadSuccess: dataLoadSuccess,
			   singleSelect:true,pageList:<?= yii::$app->params['pageRow']?>,pageSize:<?= yii::$app->params['pageSize']?>,
			   rownumbers:true,checkOnSelect:true">
            <thead>
            <tr>
                <th data-options="field:'name',width:80">用户名称</th>
                <th data-options="field:'addr_name',width:120">详细地址</th>
                <th data-options="field:'school_limit_num',width:40">学校限额</th>
                <th data-options="field:'contacts',width:40">联系人</th>
                <th data-options="field:'sex_name',width:40">性别</th>
                <th data-options="field:'contact_phone',width:40">手机号</th>
                <th data-options="field:'agent_id',width:40">代理商ID</th>
                <th data-options="field:'admin_user_name',width:40">客户专员</th>
                <th data-options="field:'create_time_name',width:80">注册时间</th>
                <th data-options="field:'status_name',width:40">状态</th>
                <th data-options="field:'edit',formatter: rowformater">操作</th>
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
            $(this).datagrid('appendRow', { name: '<div style="text-align:center;color:red"><?= yii::$app->params['noRecordeTips']?></div>' })
                .datagrid('mergeCells', { index: 0, field: 'name', colspan: 11 })

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
        title = '编辑用户资料';
        callfun = 'callback';
        openTopWindow(url,title,576,348,callfun);
    }

    function callback(){
        $('#tt').datagrid('reload',{
            name: $('#name').textbox("getValue"),
            province_id:$("#province_id").combobox("getValue"),
            city_id:$("#city_id").combobox("getValue"),
            area_id:$("#area_id").combobox("getValue"),
            agent_id:$("#agent_id").textbox("getValue"),
            admin_user_id:$("#admin_user_id").textbox("getValue"),
            create_time_start:$("#create_time_start").datebox("getValue"),
            create_time_end:$("#create_time_end").datebox("getValue")
        });
    }

    function doSearch(){
        callback();
    }

    $(function(){
        var province_id = $('#province_id').combobox({
            url:'<?= \Yii::$app->urlManager->createUrl(['province/get-all-json']);?>',
            editable:false,
            valueField:'province_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){

            },
            onSelect:function(record){
                //刷新数据，重新读取，并清空当前输入的值
                $('#city_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>?province_id=' + record.province_id);
                $('#area_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>?city_id=' + $('#city_id').combobox('getValue'));
            }
        });
        var city_id = $('#city_id').combobox({
            url:'<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>'+'?province_id=',
            editable:false,
            valueField:'city_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            },
            onSelect:function(record){
                //刷新数据，重新读取，并清空当前输入的值
                $('#area_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>?city_id=' + record.city_id);
            }
        });
        var area_id = $('#area_id').combobox({
            url:'<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>'+'?city_id=',
            editable:false,
            valueField:'area_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            }
        })

        //  清空按钮
        var buttons = $.extend([], $.fn.datebox.defaults.buttons);
        buttons.splice(1, 0, {
            text: '清空',
            handler: function(target){
                $('#'+$(target).attr('id')).datebox("setValue","").datebox('hidePanel');
            }
        });
        //出生日期
        $('#create_time_start').datebox({
            buttons: buttons,
            editable:false
        });
        //预报到日期
        $('#create_time_end').datebox({
            buttons: buttons,
            editable:false
        })
    })


</script>