<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" data-href="<?php echo \Yii::$app->urlManager->createUrl(['admin-role/save-user'])?>">
    <div class="easyui-layout"  style="height:556px;" fit="true" border="true">

        <div region="west"  title="" style="width:220px;" border="true">
            <table id="admin-role" class="easyui-datagrid" title="" border="false" fit="true"
                   data-options="fit:true, fitColumns:true,pagination:false,
        url:'<?= yii::$app->urlManager->createUrl(['admin-role/get-role-user-list','role_id'=>$models->id])?>',method:'get'">
                <thead>
                <tr>
                    <th data-options="field:'name',width:100, align:'center'">管理员姓名</th>
                    <th data-options="field:'opt',width:100,align:'center', formatter: formatRemoveAdmin"" >操作</th>
                </tr>
                </thead>
            </table>
        </div>

        <div region="center" border="true">
            <!-- 数据装载容器 -->
            <table id="all-user" class="easyui-datagrid" title="" border="false"
                   data-options="fit:true, fitColumns:true,pagination:true,rownumbers:true,checkOnSelect:true,singleSelect:true,
        url:'<?= yii::$app->urlManager->createUrl(['admin-role/get-role-user-list'])?>',method:'get'" toolbar="#all-user-toolbar">
                <thead>
                <tr>
                    <th data-options="field:'name',width:100, align:'center'">姓名</th>
                    <th data-options="field:'role_name',width:100, align:'center'">现在(组)</th>
                    <th data-options="field:'opt',width:100,align:'center', formatter: formatAddAdmin"">操作</th>
                </tr>
                </thead>
            </table>

            <div id="all-user-toolbar" class="datagrid-toolbar">
                <span>名字：<input id="name" class="easyui-textbox" style="width:180px;"></span>
                <span>
                    <a id="mysearch_btn" href="#" class="easyui-linkbutton" plain="true" data-options="iconCls:'icon-search'" onclick="doSearch();">查询</a>
                </span>
            </div>
        </div>

        <div region="south" border="false" style="overflow:hidden;background-color:#E0ECFF;">
            <div id="dlg-buttons" style="background-color:#E0ECFF; padding-top:8px; padding-bottom:0px; float:right;">
                <!--<a href="#" id="save-btn" class="easyui-linkbutton" iconCls="icon-save" onclick="saveFrom()">保存</a>-->
                <a href="#" class="easyui-linkbutton panel-tool-close" iconCls="icon-no" onclick="javascript:parent.$('#openWindow').window('close',true);">取消</a>
            </div>
        </div>

    </div>
</form>
</body>

<script type="text/javascript">

    /* 格式化管理员列 */
    function formatRemoveAdmin(val,row,index){

        var removeBtn = '<a href="javascript:void(0);" onclick="removeAdmin('+index+')" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
            '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">移除</span>' +
            '<span class="l-btn-icon icon-remove">&nbsp;</span></span>' +
            '</a>';
        var optionsStr = "{removeBtn}";

        optionsStr = optionsStr.replace('{removeBtn}', removeBtn);

        return optionsStr;
    }

    /* 格式化备选管理员列 */
    function formatAddAdmin(val,row,index){
        if(row.id==1){
            return;
        }
        var editBtn = '<a href="javascript:void(0);" onclick="addAdmin('+index+')" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
            '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">加为管理员</span>' +
            '<span class="l-btn-icon icon-add">&nbsp;</span></span>' +
            '</a>';
        var optionsStr = "{editBtn}";

        optionsStr = optionsStr.replace('{editBtn}', editBtn);

        return optionsStr;
    }

    function doSearch(){
        $('#all-user').datagrid('reload',{
            name:$('#name').textbox('getValue')
        });
    }

    //移出管理
    function removeAdmin(index){
        $('#admin-role').datagrid('selectRow', index);
        var row = $('#admin-role').datagrid('getSelected');
        setAdminUserRole(row.id,0);
    }

    //加为管理
    function addAdmin(index){
        $('#all-user').datagrid('selectRow', index);
        var row = $('#all-user').datagrid('getSelected');
        setAdminUserRole(row.id,<?= $models->id?>);
    }

    function setAdminUserRole(uid,role_id){
        var url = '<?= yii::$app->urlManager->createUrl(['admin-role/save-role-user'])?>';
        $.ajax({
            url:url,
            data:{uid:uid,role_id:role_id},
            type:'POST',
            dateType:'JSON',
            success:function(data){
                data = eval('('+data+')');
                if(data.status == 1){
                    $('#admin-role').datagrid('reload');
                    $('#all-user').datagrid('reload');
                }else{
                    $.messager.alert('提示',data.info,'error');
                }
            }
        });
    }
</script>

