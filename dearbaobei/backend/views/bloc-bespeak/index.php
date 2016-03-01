<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/css/default.css">

<div class="easyui-layout" fit="true">

    <div region="north" border="false" style="padding:10px;height:48px; padding-top:16px; padding-bottom:0px; overflow:hidden;">

        <!--工具按钮和筛选栏目开始-->
        <div class="easyui-layout" fit="true" border="false">

            <!--工具栏开始-->
            <div id="main_body_tool" region="west" style="width:148px; padding-right:18px;" border="false">
                <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="add()">创建预注册用户</a>
            </div>
            <!--工具栏结束-->

                <!--筛选条件开始-->
                <div id="main_body_search" region="center" border="false" style="padding-left:26px;">用户名称：

                    <input id="username" value="" style="cursor:pointer; width:130px;" class="easyui-textbox" data-options="prompt:'输入用户名称'">
					&nbsp;注册时间：
                    <input id="create_time_start" style="cursor:pointer; width:120px;" name="bespeak_enter_time" value="" class="easyui-validatebox" data-options="">
                    至
                    <input id="create_time_end" style="cursor:pointer; width:120px;" name="bespeak_enter_time" value="" class="easyui-validatebox" data-options="">

                    &nbsp;状态：
                    <select id="status" name="status" value="" class="easyui-combobox" data-options="panelHeight:'auto',editable:false">
                        <option value="0">全部状态</option>
                        <?php
                            foreach(yii::$app->params['blocBespeakStatus'] as $k=>$v){
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                        ?>
                    </select>

                    <a id="mysearch_btn" href="#" class="easyui-linkbutton" plain="true" data-options="iconCls:'icon-search'" style="margin-left:8px;" onclick="doSearch();return false;">查询</a>
                </div>
                <!--筛选条件结束-->

        </div>
        <!--工具按钮和筛选栏目结束-->

    </div>

    <div region="center" border="false" id="main_body_datagrid" style="padding:8px; padding-bottom:0px;">

        <!--数据表开始-->
        <table id="tt" class="easyui-datagrid"
               data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/infojson'])?>',
               fitColumns:true,pagination:true,onClickRow: setBespeakMemo,onLoadSuccess: dataLoadSuccess,
			   singleSelect:true,pageList:<?= yii::$app->params['pageRow']?>,pageSize:15,rownumbers:true,checkOnSelect:true">
            <thead>
            <tr>
                <th data-options="field:'bloc_name',width:60">用户名称</th>
                <th data-options="field:'address',width:120">详细地址</th>
                <th data-options="field:'contacts',width:30">联系人</th>
                <th data-options="field:'contact_phone',width:40">联系方式（登录名）</th>
                <th data-options="field:'create_time_name',width:40">注册时间</th>
                <th data-options="field:'next_visit_time_name',width:40">回访时间</th>
                <th data-options="field:'status_name',width:20">状态</th>
                <th data-options="field:'edit',width:130,formatter: rowformater">操作</th>
            </tr>
            </thead>
        </table>
        <!--数据表结束-->
        <table cellpadding="0" cellspacing="0" style="width:100%; margin: auto; border: 0px solid #dedede; margin-top:10px;">
            <tr>
                <td valign="top" width="300" id="bespeakInfo">
                    <p>客户专员：</p>
                    <p>微信：</p>
                    <p>QQ：</p>
                    <p>Email：</p>
                    <p>获取途径：</p>
                    <p>意向类型：</p>
                    <p>备注：</p>
                </td>
                <td valign="top">
                    <table id="tb" class="easyui-datagrid"  data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/get-bespeak-memo'])?>',
                    pagination:true,fitColumns:true,onLoadSuccess: dataLoadSuccessTb,
                    pageList:[5,10,20],pageSize:5,rownumbers:true,singleSelect:true">
                        <thead>
                        <th data-options="field:'description',width:120">跟进内容</th>
                        <th data-options="field:'create_time_name',width:40">跟进时间</th>
                        <th data-options="field:'admin_name',width:40">跟进人</th>
                        </thead>
                    </table>
                    <form style="margin-top:10px;" id="ff" method="post" data-href="<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/save-bespeak-memo'])?>">
                        <input id="desc" name="desc" value="" class="easyui-textbox" required="true" data-options="novalidate:true,multiline:true,prompt:'在此填写跟进内容'" style="width:658px;height:96px;">
                        <p>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-save" onclick="saveFrom()">跟进</a>
                            <a href="#" class="easyui-linkbutton panel-tool-close" iconCls="icon-cancel" onclick="clearFrom()">清空</a>
                        </p>
                    </form>
                </td>
            </tr>
      </table>
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

    function dataLoadSuccessTb(data){
        if(data.total == 0){ //没有记录
            $(this).datagrid('appendRow', { description: '<div style="text-align:center;color:red"><?= yii::$app->params['noRecordeTips']?></div>' })
                .datagrid('mergeCells', { index: 0, field: 'description', colspan: 3 })

            //$(this).closest('div.datagrid-wrap').find('div.datagrid-pager').hide();
        }
    }

    /**
     * 行点击事件
     * @param rowIndex
     * @param rowData
     */
    var setBespeakMemoLocked,setBespeakMemoId; //定义 数据锁，避免重复加载
    function setBespeakMemo(rowIndex,rowData){
        if(setBespeakMemoId == rowData.id) return;
        setBespeakMemoLocked = rowIndex;
        setBespeakMemoId = rowData.id;

        var html = '';
        html += '<p>客户专员：'+rowData.admin_user_name+'</p>';
        html += '<p>微信：'+rowData.weixin+'</p>';
        html += '<p>QQ：'+rowData.qq+'</p>';
        html += '<p>Email：'+rowData.email+'</p>';
        html += '<p>获取途径：'+rowData.access_to_name+'</p>';
        html += '<p>意向类型：'+rowData.intention_type_name+'</p>';
        html += '<p>备注：'+rowData.note+'</p>';
        $("#bespeakInfo").html(html);
        $("#tb").datagrid('reload',rowData.memoUrl);
    }

    function saveFrom(){
        if(setBespeakMemoLocked == undefined){
            $.messager.alert('提示','请选择一条数据','error')
            return false;
        }
        $("#tt").datagrid('selectRow',setBespeakMemoLocked);
        var row = $('#tt').datagrid('getSelected');
        if(! row || setBespeakMemoId != row.id) {
            $.messager.alert('提示','请选择一条数据','error')
            return false;
        };
        var url = $("#ff").attr('data-href');
        $('#ff').form('submit',{
            url:url,
            onSubmit:function(params){
                params.id = row.id;
                params.sinKeyMemo = row.sinKeyMemo;
                return $(this).form('enableValidation').form('validate');
            },
            success:function(result){
                var result = eval('('+result+')');
                if (result.status==1){
                    clearFrom();
                    $("#tb").datagrid('reload',row.memoUrl);
                }else {
                    $.messager.alert('提示',result.info,'error')
                }
            }
        });
    }

    function clearFrom(){
        $('#ff').form('clear');
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

        if(row.status==3){
            rs += '<a onclick="changeStatus('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
                '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">审批</span>' +
                '<span class="l-btn-icon icon-filter">&nbsp;</span></span>' +
                '</a>'
        }
        return rs;
    }

    function changeStatus(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.changeUrl;
        title = '审批预注册用户';
        callfun = 'callback';
        openTopWindow(url,title,338,268,callfun);
    }

    function add(){
        url = '<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/add'])?>';
        title = '创建预注册用户';
        callfun = 'callback';
        openTopWindow(url,title,708,498,callfun);
    }

    function edit(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.editUrl;
        title = '编辑预注册用户';
        callfun = 'callback';
        openTopWindow(url,title,638,498,callfun);
    }

    function callback(){
        $('#tt').datagrid('reload',{
            bloc_name: $('#username').textbox("getValue"),
            create_time_start:$("#create_time_start").datebox("getValue"),
            create_time_end:$("#create_time_end").datebox("getValue"),
            status:$("#status").combobox("getValue")
        });
    }

    function doSearch(){
        callback();
    }

    $(function(){
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