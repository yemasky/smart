<link rel="stylesheet" href="<%$__RESOURCE%>editor/kindeditor/themes/default/default.css" />
<script src="<%$__RESOURCE%>editor/kindeditor/kindeditor-min.js"></script>
<script src="<%$__RESOURCE%>editor/kindeditor/lang/zh_CN.js"></script>
<script language="javascript">
var uploadJsonUrl = '<%$upload_images_url%>';
var fileManagerJsonUrl = '<%$upload_manager_img_url%>';
var editor = '';
KindEditor.ready(function(K) {
    editor = K.editor({
        uploadJson : uploadJsonUrl,
        fileManagerJson : fileManagerJsonUrl,
        allowFileManager : true
    });
    K('#upload_images').click(function() {
        editor.loadPlugin('image', function() {
            editor.plugin.imageDialog({
                imageUrl : K('#upload_images_url').val(),
                clickFn : function(url, title, width, height, border, align) {
                    //K('#upload_images_url').val(url);
                    uploadSuccess(url, title);
                    editor.hideDialog();
                }
            });
        });
    });
});
</script>