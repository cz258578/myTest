<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:498px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/save'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:428px; ">
            <input type="hidden" name="id" value="<?= $models->id?>" class="easyui-validatebox">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
              <tr>
                <td width="17%" align="right">用户名称：</td>
                <td width="30%"><input style="width:180px;" id="bloc_name" name="bloc_name" value="<?= $models->bloc_name?>" class="easyui-textbox" required="true" data-options="novalidate:true"></td>

                    <td width="22%" align="right">密码（留空不改）：</td>
                <td colspan="2"><input id="password" name="password"  validtype="passW" type="password" class="easyui-textbox"
                               /></td>
              </tr>

              <tr>
                <td align="right">省市区：</td>
                <td colspan="3">
                    <input style="width:150px;" id="province_id" name="province_id" value="" class="easyui-combobox" data-options="">
                    <input style="width:150px;" id="city_id" name="city_id" value="" class="easyui-combobox" data-options="">
                    <input style="width:150px;" id="area_id" name="area_id" value="" class="easyui-combobox" data-options="">                </td>
              </tr>

                <tr>
                <td align="right">详细地址：</td>
                <td colspan="3"><input id="addr" name="addr" value="<?= $models->addr?>" class="easyui-textbox" style="width:508px;"
                                       required="true"  data-options="novalidate:true,prompt:'请输入详细地址'"></td>
              </tr>

                <tr>
                    <td align="right">联系人：</td>
                    <td><input id="contacts" name="contacts" class="easyui-textbox" value="<?= $models->contacts?>"  validtype="CHS"
                               required="true"  data-options="novalidate:true,prompt:'请输入联系人'"></td>
                    <td align="right">手机号码：</td>
                    <td width="31%"><input id="contact_phone" name="contact_phone" class="easyui-textbox" value="<?= $models->contact_phone?>"  validtype="mobile"
                               required="true"  data-options="novalidate:true,prompt:'请输入联系号码'"></td>
                </tr>

                <tr>
                    <td align="right">性别：</td>
                    <td><input id="sex" name="sex" class="easyui-combobox"  data-options="panelHeight:'auto'"></td>
                    <td align="right">获取途径：</td>
                    <td><input id="access_to" name="access_to" class="easyui-combobox"  data-options="panelHeight:'auto'">
                        <input name="admin_user_id" value="<?= yii::$app->user->identity->id?>" type="hidden">                    </td>
                </tr>

                <tr>
                    <td align="right">意向类型：</td>
                    <td><input id="intention_type" name="intention_type" class="easyui-combobox" data-options="panelHeight:'auto'"></td>
                    <td align="right">回访时间：</td>
                    <td><input id="next_visit_time" name="next_visit_time" value="<?= $models->next_visit_time>0?date('Y-m-d H:i',$models->next_visit_time):''?>"></td>
                </tr>

                <tr>
                    <td align="right">QQ号：</td>
                    <td><input id="qq" name="qq" value="<?= $models->qq?>" class="easyui-textbox" data-options="panelHeight:'auto'"></td>
                    <td align="right">微信：</td>
                    <td><input id="weixin" name="weixin" value="<?= $models->weixin?>" class="easyui-textbox"  data-options=""></td>
                </tr>

                <tr>
                    <td align="right">Email：</td>
                    <td><input id="email" name="email" value="<?= $models->email?>" class="easyui-textbox"  data-options=""></td>
                    <td align="right">代理商ID：</td>
                    <td><?= $models->agent_id?></td>
                </tr>

                <tr>
                    <td align="right">备注：</td>
                    <td colspan="3"><input id="note" style="width:508px; height: 66px;" name="note" value="<?= $models->note?>" class="easyui-textbox"  data-options="multiline:true"></td>
                </tr>
          </table>

        </div>

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
    $.extend($.fn.validatebox.defaults.rules, {
        CHS: {
            validator: function (value) {
                return /^[\u0391-\uFFE5]+$/.test(value);
            },
            message: '只能输入汉字'
        },
        mobile: {//value值为文本框中的值
            validator: function (value) {
                var reg = /^1[3|4|5|7|8|9]\d{9}$/;
                return reg.test(value);
            },
            message: '输入手机号码格式不准确.'
        },
        passW: {//value值为文本框中的值
            validator: function (value) {
                if(value=='') return true;
                if(value.length < 6) return false;
                else return true;
            },
            message: '密码长度最少6位.'
        }
    })

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
                if(result == undefined){
                    $('#save-btn').removeClass('mylinkbtn-load');
                    saveFrom(); //重新提交
                    return false;
                }
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

        var province_id = $('#province_id').combobox({
            url:'<?= \Yii::$app->urlManager->createUrl(['province/get-all-json']);?>',
            editable:false,
            valueField:'province_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
                province_id.combobox('setValue',<?= $models->province_id?>);
            },
            onSelect:function(record){
                //刷新数据，重新读取，并清空当前输入的值
                $('#city_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>?province_id=' + record.province_id);
                $('#area_id').combobox('clear').combobox('reload', '<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>?city_id=' + $('#city_id').combobox('getValue'));
            }
        });
        var load_start_city_id = 1;
        var city_id = $('#city_id').combobox({
            url:'<?= \Yii::$app->urlManager->createUrl(['city/get-all-json']);?>'+'?province_id=<?= $models->province_id?>',
            editable:false,
            valueField:'city_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
                if(load_start_city_id == 1){
                    city_id.combobox('setValue',<?= $models->city_id?>);
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
            url:'<?= \Yii::$app->urlManager->createUrl(['area/get-all-json']);?>'+'?city_id=<?= $models->city_id?>',
            editable:false,
            valueField:'area_id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
                if(load_start_area_id == 1){
                    area_id.combobox('setValue',<?= $models->area_id?>);
                    load_start_area_id ++;
                }
            }
        });

        //性别
        var sexData = [
            <?php
                foreach(yii::$app->params['sexType'] as $k=>$v){
                    if($k==$models->sex){
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

        //获取途径
        var accessData = [
            <?php
                foreach(yii::$app->params['accessWay'] as $k=>$v){
                    if($k==$models->access_to){
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                    }else{
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'"}';
                    }
                }
            ?>
        ];
        var access_to = $('#access_to').combobox({
            data:accessData,
            editable:false,
            valueField:'id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            }
        });

         //意向类型
         var intentionData = [
                <?php
                    foreach(yii::$app->params['blocBespeakIntentionType'] as $k=>$v){
                        if($k==$models->intention_type){
                            echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                        }else{
                            echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'"}';
                        }
                    }
                ?>
            ];
        var intention_type = $('#intention_type').combobox({
            data:intentionData,
            editable:false,
            valueField:'id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            }
        });

        //  清空按钮
        var buttons = $.extend([], $.fn.datebox.defaults.buttons);
        buttons.splice(1, 0, {
            text: '清空',
            handler: function(target){
                $('#'+$(target).attr('id')).datebox("setValue","").datebox('hidePanel');
            }
        });
        //下次来访时间
        var next_visit_time = $('#next_visit_time').datetimebox({
            showSeconds:false,
            editable:false
        })
    })

</script>

