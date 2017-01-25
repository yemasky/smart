<div id="modal_delete" class="modal hide">
  <div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h5><i class="am-icon-warning am-icon-md am-yellow-EBC012"></i> <%$arrayLaguage['warning']['page_laguage_value']%></h5>
  </div>
  <div class="modal-body">
    <p class="alert alert-block" id="modal_delete_message"><%$arrayLaguage['warning_confirm_delete']['page_laguage_value']%></p>
  </div>
  <div class="modal-footer"> <a data-dismiss="modal" id="delete_sumbit" class="btn btn-primary" href="#sumbit">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
</div>
<div id="modal_fail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel"><i class="am-icon-frown-o am-icon-sm am-red-E45A5A"></i> <%$arrayLaguage['modal_fail']['page_laguage_value']%></h3>
            </div>
            <div class="modal-body"><p class="alert alert-error alert-block" id="modal_fail_message"></p></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><%$arrayLaguage['close']['page_laguage_value']%></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<div id="modal_update" class="modal hide">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h5><i class="am-icon-warning am-icon-md am-yellow-EBC012"></i> <%$arrayLaguage['warning']['page_laguage_value']%></h5>
          </div>
          <div class="modal-body"><p class="alert alert-block" id="modal_update_message"><%$arrayLaguage['warning_confirm_update']['page_laguage_value']%></p></div>
          <div class="modal-footer"> <a data-dismiss="modal" id="update_sumbit" class="btn btn-primary" href="#sumbit">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
        </div>
    </div>
</div>
<div id="modal_success" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><i class="am-success am-icon-sm am-icon-angellist am-green-54B51C"></i> <%$arrayLaguage['modal_success']['page_laguage_value']%></h3>
            </div>
            <div class="modal-body"><p class="alert alert-success alert-block" id="modal_success_message"></p></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><%$arrayLaguage['close']['page_laguage_value']%></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<div id="modal_info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><i class="am-success am-icon-sm am-icon-puzzle-piece am-blue-16A2EF"></i> <%$arrayLaguage['warm_prompt']['page_laguage_value']%></h3>
            </div>
            <div class="modal-body"><p class="alert alert-block" id="modal_info_message"></p></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><%$arrayLaguage['close']['page_laguage_value']%></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<div id="modal_loading" class="modal-loading hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="notice-loading">
        <div id="gritter-item-1" class="gritter-item-wrapper" style="">
            <div class="gritter-top"></div><div class="gritter-item">
                <div class="gritter-image loading"></div>
                <div class="gritter-with-image">
                    <span class="gritter-title">Data is being transmitted, Now.</span>
                    <p>Please wait for a while.</p>
                </div><div style="clear:both"></div>
            </div>
            <div class="gritter-bottom"></div>
        </div>
    </div>
</div>
<div id="modal_save" class="modal-loading hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="notice-loading">
        <div id="gritter-item-1" class="gritter-item-wrapper" style="">
            <div class="gritter-top"></div><div class="gritter-item">
                <div class="gritter-image loading"></div>
                <div class="gritter-with-image">
                    <span class="gritter-title">Is saving the data Now.</span>
                    <p>Please wait for a while.</p>
                </div><div style="clear:both"></div>
            </div>
            <div class="gritter-bottom"></div>
        </div>
    </div>
</div>
<script laguage="javascript">
$(document).ready(function(){
	var delete_url = '';
	// Form Validation
    $("#delete_sumbit").click(function(){
		$.getJSON(delete_url,function(data, status){
			//alert("Data: " + data + "\nStatus: " + status);
			if(data.success == 1) {
				$('#modal_success').modal('show');
				$('#modal_success_message').html(data.message);
			} else {
				$('#modal_fail').modal('show');
				$('#modal_fail_message').html(data.message);
			}
		});
	});
	$("#update_sumbit").click(function(){
		if($.isFunction(update_sumbit)) update_sumbit();
	});
	$(".btn.btn-danger.btn-mini").click(function(){
		delete_url = $(this).attr("url");
	});
	$('#modal_delete').on('hide.bs.modal', function() {
        window.location.reload();
    });
	$('#modal_fail').on('hide.bs.modal', function() {
        if(typeof(data.redirect) != "undefined" && data.redirect != '') window.location = data.redirect;
    });
    $('#modal_success').on('hide.bs.modal', function() {
        if(typeof(data.redirect) != "undefined" && data.redirect != '') window.location = data.redirect;
    });
})
</script>