<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/css/default.css">

<div class="easyui-layout" fit="true">

    <div region="north" border="false" style="padding:10px;height:48px; padding-top:16px; padding-bottom:0px; overflow:hidden;">

        <!--工具按钮和筛选栏目开始-->
        <div class="easyui-layout" fit="true" border="false">

            <!--工具栏开始-->
            <div id="main_body_tool" region="west" style="width:188px; padding-right:18px;" border="false">
                <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="add()">新增系统配置</a>
            </div>
            <!--工具栏结束-->

                <!--筛选条件开始-->
                <div id="main_body_search" region="center" border="false" style="padding-left:26px;">筛选条件：

                    <input id="name" value="" style="cursor:pointer;" class="easyui-textbox" data-options="prompt:'输入字段名/描述'">
                    分组：
                    <select id="group" value="" style="width:146px;" class="easyui-combobox" data-options="panelHeight:'auto'">
                        <option value="0">请选择</option>
                        <?php
                            foreach(yii::$app->params['configGroup'] as $k=>$v){
                        ?>
                                <option value="<?= $k?>"><?= $v?></option>
                        <?php
                            }
                        ?>
                    </select>

                    类型：
                    <select id="type" value="" style="width:146px;" class="easyui-combobox" data-options="panelHeight:'auto'">
                        <option value="0">请选择</option>
                        <option value="array">array</option>
                        <option value="string">string</option>
                    </select>

                    状态：
                    <select id="status" value="" style="width:146px;" class="easyui-combobox" data-options="panelHeight:'auto'">
                        <option value="-1">请选择</option>
                        <option value="1">正常</option>
                        <option value="0">禁用</option>
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
               data-options="url:'<?php echo \Yii::$app->urlManager->createUrl(['system-config/infojson'])?>',fitColumns:true,fit:true,pagination:true,
			   singleSelect:true,pageList:[15,20,25,30,40,50,100],pageSize:25,rownumbers:true,checkOnSelect:true">
            <thead>
            <tr>
                <th data-options="field:'name',width:60">字段名</th>
                <th data-options="field:'describe',width:60">描述</th>
                <th data-options="field:'group_name',width:60">分组</th>
                <th data-options="field:'type',width:20">类型</th>
                <th data-options="field:'value_name',width:140">值</th>
                <th data-options="field:'rank',width:20">排序</th>
                <th data-options="field:'create_time_name',width:60">创建时间</th>
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
        rs = '<a onclick="edit('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
            '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">编辑</span>' +
            '<span class="l-btn-icon icon-edit">&nbsp;</span></span>' +
            '</a>';
        if(row.status==1){
            rs += '<a onclick="changeStatus('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
                '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">禁用</span>' +
                '<span class="l-btn-icon icon-stop">&nbsp;</span></span>' +
                '</a>'
        }else{
            rs += '<a onclick="changeStatus('+index+');" class="easyui-linkbutton" style="cursor:pointer; color:#000000;">' +
                '<span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">启用</span>' +
                '<span class="l-btn-icon icon-start">&nbsp;</span></span>' +
                '</a>'
        }
        return rs;
    }

    function changeStatus(index){
        /* alert(id+' '+status); return false;*/
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        $.ajax({
            url:row.changeUrl,
            dataType: 'JSON',
            success:function(data){
                if(data.status==1){
                    callback();
                }else{
                    $.messager.alert('提示',data.info);
                }
            }
        })
    }

    function add(){
        url = '<?php echo \Yii::$app->urlManager->createUrl(['system-config/add'])?>';
        title = '新增系统配置';
        callfun = 'callback';
        openTopWindow(url,title,508,468,callfun);
    }

    function edit(index){
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        url = row.editUrl;
        title = '编辑系统配置';
        callfun = 'callback';
        openTopWindow(url,title,508,468,callfun);
    }

    function callback(){
        doSearch();
    }

    function doSearch(){
        $('#tt').datagrid('reload',{
            name: $('#name').textbox("getValue"),
            type: $('#type').combobox("getValue"),
            status: $('#status').combobox("getValue"),
            group: $('#group').combobox("getValue")
        });
    }

    $(function(){
        $('#type').combobox({
            editable:false
        })
        $('#status').combobox({
            editable:false
        })
        $('#group').combobox({
            editable:false
        })
    })

</script>