<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/icon.css">
    <script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/jquery.min.js"></script>
    <script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/jquery.easyui.min.js"></script>    
    <script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/common.js"></script>
    <script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
<script type="text/javascript">
    /*loadWindow();*/
</script>

<?= $content;?>

<script type="text/javascript">
    /*$(function(){
        loadWindow('close');
    });*/
</script>
</body>
</html>