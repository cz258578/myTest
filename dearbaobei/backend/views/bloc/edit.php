<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:328px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['bloc/save','sinKey'=>$_GET['sinKey']])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:278px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
              <tr>
                <td colspan="2" align="right">用户名称：
                <input id="name" style="width:460px;" name="name" value="<?= $dataInfo['bloc_name']?>" class="easyui-textbox"></td>
              </tr>

                <tr>
                    <td colspan="2" align="right">详细地址：
                      <input id="province_id" style="width:90px;" name="province_id" value="" class="easyui-combobox" data-options="">
                      <input id="city_id" style="width:90px;" name="city_id" value="" class="easyui-combobox" data-options="">
                      <input id="area_id" style="width:90px;" name="area_id" value="" class="easyui-combobox" data-options="">
                  <input id="addr" name="addr" value="<?= $dataInfo['addr']?>" class="easyui-textbox"></td>
                </tr>



                <tr>
                    <td width="243" align="right">联系人：
                    <input id="contacts" name="contacts" value="<?= $dataInfo['contacts']?>" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                    <td align="right">性别：
                    <input id="sex" name="sex" value="" class="easyui-combobox" data-options="novalidate:true"></td>
                </tr>



                <tr>
                    <td align="right">联系手机：
                    <input id="contact_phone" name="contact_phone" value="<?= $dataInfo['contact_phone']?>" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                    <td align="right">QQ号：
                    <input id="qq" name="qq" value="<?= $dataInfo['qq']?>" class="easyui-textbox" ></td>
                </tr>

  

                <tr>
                    <td align="right">微信：
                    <input id="weixin" name="weixin" value="<?= $dataInfo['weixin']?>" class="easyui-textbox"></td>
                    <td align="right">Email：
                    <input id="email" name="email" value="<?= $dataInfo['email']?>" class="easyui-textbox"></td>
                </tr>



                <tr>
                    <td align="right">学校限额：
                    <input id="school_limit_num" name="school_limit_num" value="<?= $dataInfo['school_limit_num']?>" class="easyui-textbox"></td>
                    <td align="right">状态：
                    <input id="status" name="status" value="" class="easyui-combobox" data-options="panelHeight:'auto'"></td>
                </tr>

    

                <tr>
                    <td align="right">代理商ID：
                    <?= $dataInfo['agent_id']?></td>
                    <td align="right">客户专员：
                    <?= $dataInfo['admin_user_name']?></td>
                </tr>


            </table>

        </div>
        <input type="hidden" name="id" value="<?= $dataInfo['bloc_id']?>">
        <div region="center" border="false" style="overflow:hidden;background-color:#E0ECFF; ">
            <div id="dlg-buttons" style="background-color:#E0ECFF; padding-top:8px; padding-bottom:0px; float:right;">
                <a href="#" id="save-btn" class="easyui-linkbutton" iconCls="icon-save" onClick="saveFrom()">保存</a>
                <a href="#" class="easyui-linkbutton panel-tool-close" iconCls="icon-no" onClick="javascript:parent.$('#openWindow').window('close',true);">取消</a>
            </div>
        </div>

    </div>
</form>
</body>

<script type="text/javascript">
    function saveFrom(){
        if ($('#save-btn').hasClass('mylinkbtn-load')) {
            return false;
        }
        $('#save-btn').addClass('mylinkbtn-load');

        var url = $("#ff").attr('data-href');
        $('#ff').form('submit',{
            url:url,
            onSubmit:function(){
                var result = $(this).form('enableValidation').form('validate');
                if(!result){
                    $('#save-btn').removeClass('mylinkbtn-load');
                };
                return result;
            },
            success:function(result){
                var result = eval('('+result+')');
                if (result.status==1){
                    parent.window.closeWinIsReloadData=1;
                    parent.$('#openWindow').window('close');
                }else{
                    $('#save-btn').removeClass('mylinkbtn-load');
                    $.messager.alert('提示',result.info,'error')
                }
            }
        });
    }

    $(function(){
        var statusData = [
            <?php
                foreach(yii::$app->params['blocStatus'] as $k=>$v){
                    if($k==$dataInfo['status']){
                        echo ($k>0?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                    }else{
                        echo ($k>0?',':'').'{id:'.$k.',name:"'.$v.'"}';
                    }
                }
            ?>
        ];
        $('#status').combobox({
            editable:false,
            data : statusData,
            valueField:'id',
            textField:'name',
            onLoadSuccess:function(){

            }
        })

            var province_id = $('#province_id').combobox({
                url:'<?= \Yii::$app->urlManager->createUrl(['province/get-all-json']);?>',
                editable:false,
                valueField:'province_id',
                textField:'name',
                required: true,
                novalidate:true,
                onLoadSuccess:function(){
                    province_id.combobox('setValue',<?= $dataInfo['province_id']?>);
                },
                onSelect:function(record){
                    //刷新数据，重新读取，并清空当前输入的值
                    $('#city_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>?province_id=' + record.province_id);
                    $('#area_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>?city_id=' + $('#city_id').combobox('getValue'));
                }
            });
            var load_start_city_id = 1;
            var city_id = $('#city_id').combobox({
                url:'<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>'+'?province_id=<?= $dataInfo['province_id']?>',
                editable:false,
                valueField:'city_id',
                textField:'name',
                required: true,
                novalidate:true,
                onLoadSuccess:function(){
                    if(load_start_city_id == 1){
                        city_id.combobox('setValue',<?= $dataInfo['city_id']?>);
                        load_start_city_id++;
                    }

                },
                onSelect:function(record){
                    //刷新数据，重新读取，并清空当前输入的值
                    $('#area_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>?city_id=' + record.city_id);
                }
            });
            var load_start_area_id = 1;
            var area_id = $('#area_id').combobox({
                url:'<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>'+'?city_id=<?= $dataInfo['city_id']?>',
                editable:false,
                valueField:'area_id',
                textField:'name',
                required: true,
                novalidate:true,
                onLoadSuccess:function(){
                    if(load_start_area_id == 1){
                        area_id.combobox('setValue',<?= $dataInfo['area_id']?>);
                        load_start_area_id ++;
                    }
                }
            })

        //性别
        var sexData = [
            <?php
                foreach(yii::$app->params['sexType'] as $k=>$v){
                    if($k==$dataInfo['sex']){
                        echo ($k>0?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                    }else{
                        echo ($k>0?',':'').'{id:'.$k.',name:"'.$v.'"}';
                    }

                }
            ?>
        ];
        var sex = $('#sex').combobox({
            data:sexData,
            editable:false,
            valueField:'id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            }
        });

    })

</script>

