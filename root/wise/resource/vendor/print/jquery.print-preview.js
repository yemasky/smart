/*!
 * jQuery Print Previw Plugin v1.0.1
 *
 * Copyright 2011, Tim Connell
 * Licensed under the GPL Version 2 license
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Date: Wed Jan 25 00:00:00 2012 -000
 */
 
(function($) { 
    
	// Initialization
	$.fn.printPreview = function(print_option) {
		this.each(function() {
			$(this).bind('click', function(e) {
			    e.preventDefault();
			    if (!$('#print-modal').length) {
			        if(typeof(print_option) == 'string') $.printPreview.loadPrintPreview(print_option);
					if(typeof(print_option) == 'object') {
						$.printPreview.loadPrintPreview(print_option.id);
						var distroy = print_option.distroy();
						if(distroy == false)$.printPreview.distroyPrintPreview();
					}
			    }
			});
		});
		return this;
	};
    
    // Private functions
    var mask, size, print_modal, print_controls;
    $.printPreview = {
        loadPrintPreview: function(print_id) {
            // Declare DOM objects
            print_modal = $('<div id="print-modal"></div>');
            print_controls = $('<div id="print-modal-controls">' + 
                                    '<a href="#" class="print" title="Print page">Print page</a>' +
                                    '<a href="#" class="edit" title="编辑打印内容">编辑打印内容</a>' +
                                    '<a href="#" class="close" title="Close print preview">Close</a>').hide();
            var print_frame = $('<iframe id="print-modal-content" scrolling="no" border="0" frameborder="0" name="print-frame" width="100%"  />');

            // Raise print preview window from the dead, zooooooombies
            print_modal
                .hide()
                .append(print_controls)
                .append(print_frame)
                .appendTo('body');

            // The frame lives
            for (var i=0; i < window.frames.length; i++) {
                if (window.frames[i].name == "print-frame") {    
                    var print_frame_ref = window.frames[i].document;
                    break;
                }
            }
            print_frame_ref.open();//document.title
            print_frame_ref.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
                '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">' + 
                '<head><title>' + ' ' + '</title>'+
				'<style type="text/css">table{width:100%;margin:0px auto;font-family:"Microsoft YaHei UI", "Microsoft YaHei UI Light", "Courier New", Courier,"Helvetica Neue",Helvetica,Arial,sans-serif;color:#333333;text-align:center;border-collapse:collapse;}table td{border:1px solid #333;/*width:100px;*/height:30px;font-size: 12px;}table th{border:1px solid #333;font-size: 14px;height:30px;}.lodger{width:100px;word-wrap:break-word;word-break:break-all;}.ticket{width:500px;}input{border: 1px solid #ccc;border-top: none;border-left: none; border-right: none; width: 130px;height: 25px;vertical-align: middle;}</style>'+
				'</head><body></body>' +
                '</html>');
            print_frame_ref.close();
            
            // Grab contents and apply stylesheet
            var $iframe_head = $('head link[media*=print], head link[media=all], head link[class=print_me]').clone(),
                //$iframe_body = $('body > *:not(#print-modal):not(script)').clone();
                //$iframe_body = $('body > *:#'+print_id+':not(script)').clone();
                $iframe_body = $('#'+print_id).clone();
            $iframe_head.each(function() {
                $(this).attr('media', 'all');
            });
            if (!$.support.msie && !($.support.version < 7) ) {
                $('head', print_frame_ref).append($iframe_head);
                $('body', print_frame_ref).append($iframe_body);
            }
            else {
                $('body > *:not(#print-modal):not(script)').clone().each(function() {
                    $('body', print_frame_ref).append(this.outerHTML);
                    //$('body', print_frame_ref).append(this.innerHTML);
                });
                $('head link[media*=print], head link[media=all]').each(function() {
                    $('head', print_frame_ref).append($(this).clone().attr('media', 'all')[0].innerHTML);
                });
            }
            
            // Disable all links
            $('a', print_frame_ref).bind('click.printPreview', function(e) {
                e.preventDefault();
            });
            
            // Introduce print styles
            $('head').append('<style type="text/css">' +
                '@media print {' +
                    '/* -- Print Preview --*/' +
                    '#print-modal-mask,' +
                    '#print-modal {' +
                        'display: none !important;' +
                    '}' +
                '}' +
                '</style>'
            );

            // Load mask
            $.printPreview.loadMask();

            // Disable scrolling
            $('body').css({overflowY: 'hidden', height: '100%'});
            $('img', print_frame_ref).on('load', function() {
                print_frame.height($('body', print_frame.contents())[0].scrollHeight);
            });
            
            // Position modal            
            starting_position = $(window).height() + $(window).scrollTop();
            var css = {
                    top:         starting_position,
                    height:      '100%',
                    overflowY:   'auto',
                    zIndex:      20000,
                    display:     'block'
                }
            print_modal
                .css(css)
                .animate({ top: $(window).scrollTop()}, 400, 'linear', function() {
                    print_controls.fadeIn('slow').focus();
                });
            print_frame.height($('body', print_frame.contents())[0].scrollHeight - 0 + 10);
            
            // Bind closure
            $('a', print_controls).bind('click', function(e) {
                e.preventDefault();		
				//window.print(); 
                if ($(this).hasClass('print')) {
                    $($iframe_body).find('.contents').each(function(){
                        var _this = $(this);
                        if(_this.has('input').length>0) {
                            var html = $.trim(_this.find('input').val());
                            _this.html(html);
                        }
                    });
                    print_frame[0].contentWindow.print();
                }
                else if($(this).hasClass('edit')) {
                    $($iframe_body).find('.contents').each(function(){
                        var _this = $(this);
                        if(_this.has('input').length>0) {
                            var html = $.trim(_this.find('input').val());
                            _this.html(html);
                        } else {
                            var html = $.trim(_this.html());
                            _this.html('<input type="text" value="'+html+'">');
                        }
                    });
                }
                else { $.printPreview.distroyPrintPreview(); }
            });
    	},
    	
    	distroyPrintPreview: function() {
    	    print_controls.fadeOut(100);
    	    print_modal.animate({ top: $(window).scrollTop() - $(window).height(), opacity: 1}, 400, 'linear', function(){
    	        print_modal.remove();
    	        $('body').css({overflowY: 'auto', height: 'auto'});
    	    });
    	    mask.fadeOut('slow', function()  {
    			mask.remove();
    		});				

    		$(document).unbind("keydown.printPreview.mask");
    		mask.unbind("click.printPreview.mask");
    		$(window).unbind("resize.printPreview.mask");
	    },
	    
    	/* -- Mask Functions --*/
	    loadMask: function() {
	        size = $.printPreview.sizeUpMask();
            mask = $('<div id="print-modal-mask" />').appendTo($('body'));
    	    mask.css({				
    			position:           'absolute', 
    			top:                0, 
    			left:               0,
    			width:              size[0],
    			height:             size[1],
    			display:            'none',
    			opacity:            0,					 		
    			zIndex:             9999,
    			backgroundColor:    '#000'
    		});
	
    		mask.css({display: 'block'}).fadeTo('400', 0.75);
    		
            $(window).bind("resize..printPreview.mask", function() {
				$.printPreview.updateMaskSize();
			});
			
			mask.bind("click.printPreview.mask", function(e)  {
				$.printPreview.distroyPrintPreview();
			});
			
			$(document).bind("keydown.printPreview.mask", function(e) {
			    if (e.keyCode == 27) {  $.printPreview.distroyPrintPreview(); }
			});
        },
    
        sizeUpMask: function() {
            if ($.support.msie) {
            	// if there are no scrollbars then use window.height
            	var d = $(document).height(), w = $(window).height();
            	return [
            		window.innerWidth || 						// ie7+
            		document.documentElement.clientWidth || 	// ie6  
            		document.body.clientWidth, 					// ie6 quirks mode
            		d - w < 20 ? w : d
            	];
            } else { return [$(document).width(), $(document).height()]; }
        },
    
        updateMaskSize: function() {
    		var size = $.printPreview.sizeUpMask();
    		mask.css({width: size[0], height: size[1]});
        }
    }
})(jQuery);