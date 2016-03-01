<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/css/default.css">

<div class="easyui-layout" fit="true">

    <div region="north" border="false" style="padding:10px;height:48px; padding-top:16px; padding-bottom:0px; overflow:hidden;">

        <!--工具按钮和筛选栏目开始-->
        <div class="easyui-layout" fit="true" border="false">

            <!--工具栏开始-->
            <div id="main_body_tool" region="west" style="width:188px; padding-right:18px;" border="false">
                <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="add()">新增角色</a>
            </div>
            <!--工具栏结束-->

                <!--筛选条件开始-->
                <div id="main_body_search" region="center" border="false" style="padding-left:26px;">筛选条件：

                    <input id="name" value="" style="cursor:pointer;" class="easyui-textbox" data-options="prompt:'输入名称'">

                    <a id="mysearch_btn" href="#" class="easyui-linkbutton" plain="true" data-options="iconCls:'icon-search'" style="margin-left:8px;" onclick="doSearch();return false;">查询</a>
                </div>
                <!--筛选条件结束-->

        </div>
        <!--工具按钮和筛选栏目结束-->

    </div>

    <div region="center" border="false" id="main_body_datagrid" style="padding:8px; padding-bottom:0px;">

        <!--数据表开始-->
        <table id="tt" class="easyui-datagrid"
               data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['admin-role/infojson'])?>',fitColumns:true,fit:true,pagination:true,
			   singleSelect:true,pageList:<?= yii::$app->params['pageRow']?>,pageSize:<?= yii::$app->params['pageSize']?>,rownumbers:true,checkOnSelect:true">
            <thead>
            <tr>
                <th data-options="field:'name',width:60">名称</th>

                <th data-options="field:'status_name',width:60">状态</th>

                <th data-options="field:'edit',width:130,formatter: rowformater">操作</th>
            </tr>
            </thead>
        </table>
        <!--数据表结束-->

    </div>

</div>

<script type="text/javascript">

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

        rs += '<a onclick="setRoleUser('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
            '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">成员管理</span>' +
            '<span class="l-btn-icon icon-stop">&nbsp;</span></span>' +
            '</a>'

        if(row.id!=1){
        rs += '<a onclick="setRole('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
                '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">权限管理</span>' +
                '<span class="l-btn-icon icon-stop">&nbsp;</span></span>' +
                '</a>'
        }
        return rs;
    }

    function setRole(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.setRoleUrl;
        title = '权限管理';
        callfun = 'callback';
        openTopWindow(url,title,798,598,callfun);
    }

    function setRoleUser(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.setRoleUserUrl;
        title = '成员管理';
        callfun = 'callback';
        openTopWindow(url,title,798,598,callfun);
    }

    function add(){
        url = '<?php echo \Yii::$app->urlManager->createUrl(['admin-role/add'])?>';
        title = '新增角色';
        callfun = 'callback';
        openTopWindow(url,title,508,298,callfun);
    }

    function edit(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.editUrl;
        title = '编辑角色';
        callfun = 'callback';
        openTopWindow(url,title,508,298,callfun);
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