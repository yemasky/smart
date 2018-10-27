<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">
td,input{width:100px !important;}
</style>
</head>
<body>
<form action="manage.php?action=setModule&method=UpdateModule" name="update_form" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td>频道</td>
        <td>名称</td>
        <td>父ID</td>
        <td>模块</td>
        <td>url</td>
        <td>排序</td>
        <td>action</td>
        <td>排序</td>
        <td>submenu</td>
        <td>菜单显示</td>
        <td>新推荐</td>
        <td>图标</td>
        <td>发布</td>
    </tr>
    <%foreach key=module_id item=module from=$arrayModule%>
    <tr>
        <td><input type="text" name="module_channel[<%$module_id%>]" value="<%$module.module_channel%>" /></td>
        <td><input type="text" name="module_name[<%$module_id%>]" value="<%$module.module_name%>" /></td>
        <td><input type="text" name="module_father_id[<%$module_id%>]" value="<%$module.module_father_id%>" /></td>
        <td><input type="text" name="_module[<%$module_id%>]" value="<%$module.module%>" /></td>
        <td><input type="text" name="url[<%$module_id%>]" value="<%$module.url%>" /></td>
        <td><input type="text" name="module_order[<%$module_id%>]" value="<%$module.module_order%>" /></td>
        <td><input type="text" name="_action[<%$module_id%>]" value="<%$module.action%>" /></td>
        <td><input type="text" name="action_order[<%$module_id%>]" value="<%$module.action_order%>" /></td>
        <td><input type="text" name="submenu_father_id[<%$module_id%>]" value="<%$module.submenu_father_id%>" /></td>
        <td><input type="text" name="is_menu[<%$module_id%>]" value="<%$module.is_menu%>" /></td>
        <td><input type="text" name="is_recommend[<%$module_id%>]" value="<%$module.is_recommend%>" /></td>
        <td><input type="text" name="ico[<%$module_id%>]" value="<%$module.ico%>" /></td>
        <td><input type="text" name="is_release[<%$module_id%>]" value="<%$module.is_release%>" /></td>
    </tr>
    <%/foreach%>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="更新"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>
<form action="index.php?action=setModule&method=Add" name="Add_form" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td>频道</td>
        <td>名称</td>
        <td>父ID</td>
        <td>模块</td>
        <td>url</td>
        <td>排序</td>
        <td>action</td>
        <td>排序</td>
        <td>submenu</td>
        <td>菜单显示</td>
        <td>新推荐</td>
        <td>图标</td>
        <td>发布</td>
    </tr>
    <tr>
        <td><input type="text" name="module_channel" value="" /></td>
        <td><input type="text" name="module_name" value="" /></td>
        <td><input type="text" name="module_father_id" value="" /></td>
        <td><input type="text" name="_module" value="" /></td>
        <td><input type="text" name="url" value="" /></td>
        <td><input type="text" name="module_order" value="" /></td>
        <td><input type="text" name="_action" value="" /></td>
        <td><input type="text" name="action_order" value="" /></td>
        <td><input type="text" name="submenu_father_id" value="" /></td>
        <td><input type="text" name="is_menu" value="" /></td>
        <td><input type="text" name="is_recommend" value="" /></td>
        <td><input type="text" name="ico" value="" /></td>
        <td><input type="text" name="is_release" value="" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="添加"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>
</body>
</html>