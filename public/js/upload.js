function empty(e){var t,n,r,i;var s=[t,null,false,0,"","0"];for(r=0,i=s.length;r<i;r++){if(e===s[r]){return true}}if(typeof e==="object"){for(n in e){return false}return true}return false}(function(e,t,n){"use strict";t.html5imageupload=function(r,i){this.element=i;this.options=t.extend(true,{},t.html5imageupload.defaults,r,t(this.element).data());this.input=t(this.element).find("input[type=file]");var s=t(e);var o=this;this.interval=null;this.drag=false;this.button={};this.button.edit='<div class="btn btn-info btn-edit"><i class="glyphicon glyphicon-pencil"></i></div>';this.button.saving='<div class="btn btn-warning saving">Saving... <i class="glyphicon glyphicon-time"></i></div>';this.button.zoomin='<div class="btn btn-default btn-zoom-in"><i class="glyphicon glyphicon-resize-full"></i></div>';this.button.zoomout='<div class="btn btn-default btn-zoom-out"><i class="glyphicon glyphicon-resize-small"></i></div>';this.button.zoomreset='<div class="btn btn-default btn-zoom-reset"><i class="glyphicon glyphicon-fullscreen"></i></div>';this.button.cancel='<div class="btn btn-danger btn-cancel"><i class="glyphicon glyphicon-remove"></i></div>';this.button.done='<div class="btn btn-success btn-ok"><i class="glyphicon glyphicon-ok"></i></div>';this.button.del='<div class="btn btn-danger btn-del"><i class="glyphicon glyphicon-trash"></i></div>';o._init()};t.html5imageupload.defaults={width:null,height:null,image:null,ghost:true,originalsize:true,url:false,removeurl:null,data:{},canvas:true,ajax:true,dimensionsonly:false,editstart:false,onAfterZoomImage:null,onAfterInitImage:null,onAfterMoveImage:null,onAfterProcessImage:null,onAfterResetImage:null,onAfterCancel:null,onAfterRemoveImage:null};t.html5imageupload.prototype={_init:function(){var n=this;var r=n.options;var i=n.element;var s=n.input;if(empty(t(i))){return false}else{t(i).children().css({position:"absolute"})}if(!(e.FormData&&"upload"in t.ajaxSettings.xhr())){t(i).empty().attr("class","").addClass("alert alert-danger").html("HTML5 Upload Image: Sadly.. this browser does not support the plugin, update your browser today!");return false}if(!empty(r.width)&&empty(r.height)&&t(i).innerHeight()<=0){t(i).empty().attr("class","").addClass("alert alert-danger").html("HTML5 Upload Image: Image height is not set and can not be calculated...");return false}if(!empty(r.height)&&empty(r.width)&&t(i).innerWidth()<=0){t(i).empty().attr("class","").addClass("alert alert-danger").html("HTML5 Upload Image: Image width is not set and can not be calculated...");return false}if(!empty(r.height)&&!empty(r.width)&&!empty(t(i).innerHeight()&&!empty(t(i).innerWidth()))){if(r.width/r.height!=t(i).outerWidth()/t(i).outerHeight()){t(i).empty().attr("class","").addClass("alert alert-danger").html("HTML5 Upload Image: The ratio of all sizes (CSS and image) are not the same...");return false}}var o,u,a,f;r.width=a=r.width||t(i).outerWidth();r.height=f=r.height||t(i).outerHeight();if(t(i).innerWidth()>0){o=t(i).outerWidth()}else if(t(i).innerHeight()>0){o=null}else if(!empty(r.width)){o=r.width}if(t(i).innerHeight()>0){u=t(i).outerHeight()}else if(t(i).innerWidth()>0){u=null}else if(!empty(r.height)){u=r.height}u=u||o/(a/f);o=o||u/(f/a);t(i).css({height:u,width:o});n._bind();if(!r.ajax){n._formValidation()}if(!empty(r.image)&&r.editstart==false){t(i).data("name",r.image).append(t("<img />").addClass("preview").attr("src",r.image));var l=t(""+this.button.del+"");t(l).click(function(e){e.preventDefault();n.reset()});t(i).append(t('<div class="preview tools"></div>').append(l))}else if(!empty(r.image)){n.readImage(r.image,r.image,r.image)}if(n.options.onAfterInitImage)n.options.onAfterInitImage.call(n)},_bind:function(){var e=this;var n=e.element;var r=e.input;t(n).unbind("dragover").unbind("drop").unbind("mouseout").on({dragover:function(t){e.handleDrag(t)},drop:function(n){e.handleFile(n,t(this))},mouseout:function(){e.imageUnhandle()}});t(r).unbind("change").change(function(r){e.drag=false;e.handleFile(r,t(n))})},handleFile:function(e,n){e.stopPropagation();e.preventDefault();var r=this;var i=this.options;var s=r.drag==false?e.originalEvent.target.files:e.originalEvent.dataTransfer.files;r.drag=false;if(i.removeurl!=null&&!empty(t(n).data("name"))){t.ajax({type:"POST",url:i.removeurl,data:{image:t(n).data("name")},success:function(e){if(r.options.onAfterRemoveImage)r.options.onAfterRemoveImage.call(r,e)}})}t(n).removeClass("notAnImage").addClass("loading");for(var o=0,u;u=s[o];o++){if(!u.type.match("image.*")){t(n).addClass("notAnImage");continue}var a=new FileReader;a.onload=function(e){return function(i){t(n).find("img").remove();r.readImage(a.result,i.target.result,e.name)}}(u);a.readAsDataURL(u)}},readImage:function(e,n,r){var i=this;var s=this.options;var o=this.element;i.drag=false;var u=new Image;u.onload=function(){var e=t('<img src="'+n+'" name="'+r+'" />');var a,f,l,c,h,p;a=l=u.width;f=c=u.height;h=a/f;p=t(o).outerWidth()/t(o).outerHeight();if(s.originalsize==false){l=t(o).outerWidth()+40;c=l/h;if(c<t(o).outerHeight()){c=t(o).outerHeight()+40;l=c*h}}else if(l<t(o).outerWidth()||c<t(o).outerHeight()){if(h<p){l=t(o).outerWidth();c=l/h}else{c=t(o).outerHeight();l=c*h}}var d=parseFloat((t(o).outerWidth()-l)/2);var v=parseFloat((t(o).outerHeight()-c)/2);e.css({left:d,top:v,width:l,height:c});i.image=t(e).clone().data({width:a,height:f,ratio:h,left:d,top:v,useWidth:l,useHeight:c}).addClass("main").mousedown(function(e){i.imageHandle(e)});i.imageGhost=s.ghost?t(e).addClass("ghost"):null;t(o).append(t('<div class="cropWrapper"></div>').append(t(i.image)));if(!empty(i.imageGhost)){t(o).append(i.imageGhost)}i._tools();t(o).removeClass("loading")};u.src=e},handleDrag:function(e){var t=this;t.drag=true;e.stopPropagation();e.preventDefault();e.originalEvent.dataTransfer.dropEffect="copy"},imageHandle:function(e){e.preventDefault();var n=this;var r=this.element;var i=this.image;var s=i.outerHeight(),o=i.outerWidth(),u=i.offset().top+s-e.pageY,a=i.offset().left+o-e.pageX;i.on({mousemove:function(e){var f=e.pageY+u-s,l=e.pageX+a-o;var c=t(r).outerWidth()!=t(r).innerWidth();if(parseInt(f-t(r).offset().top)>0){f=t(r).offset().top+(c?1:0)}else if(f+s<t(r).offset().top+t(r).outerHeight()){f=t(r).offset().top+t(r).outerHeight()-s+(c?1:0)}if(parseInt(l-t(r).offset().left)>0){l=t(r).offset().left+(c?1:0);}else if(l+o<t(r).offset().left+t(r).outerWidth()){l=t(r).offset().left+t(r).outerWidth()-o+(c?1:0);}i.offset({top:f,left:l});n._ghost()},mouseup:function(){n.imageUnhandle()}})},imageUnhandle:function(){var e=this;var n=e.image;t(n).unbind("mousemove");if(e.options.onAfterMoveImage)e.options.onAfterMoveImage.call(e)},imageZoom:function(e){var n=this;var r=n.element;var i=n.image;if(empty(i)){n._clearTimers();return false}var s=i.data("ratio");var o=i.outerWidth()+e;var u=o/s;if(o<t(r).outerWidth()){o=t(r).outerWidth();u=o/s;e=t(i).outerWidth()-o}if(u<t(r).outerHeight()){u=t(r).outerHeight();o=u*s;e=t(i).outerWidth()-o}var a=Math.round(parseFloat(i.css("top"))-parseFloat(u-i.outerHeight())/2);var f=parseInt(i.css("left"))-e/2;if(t(r).offset().left-f<t(r).offset().left){f=0}else if(t(r).outerWidth()>f+t(i).outerWidth()&&e<=0){f=t(r).outerWidth()-o}if(t(r).offset().top-a<t(r).offset().top){a=0}else if(t(r).outerHeight()>a+t(i).outerHeight()&&e<=0){a=t(r).outerHeight()-u}i.css({width:o,height:u,top:a,left:f});n._ghost()},imageCrop:function(){var e=this;var n=e.element;var r=e.image;var i=e.input;var s=e.options;var o=s.width!=t(n).outerWidth()?s.width/t(n).outerWidth():1;var u,a,f,l,c,h,p,d;u=s.width;a=s.height;f=parseInt(parseInt(t(r).css("top"))*o)*-1;l=parseInt(parseInt(t(r).css("left"))*o)*-1;c=parseInt(t(r).width()*o);h=parseInt(t(r).height()*o);p=t(r).data("width");d=t(r).data("height");var v={name:t(r).attr("name"),imageOriginalWidth:p,imageOriginalHeight:d,imageWidth:c,imageHeight:h,width:u,height:a,left:l,top:f};if(s.canvas==true){var m=t('<canvas class="final" id="canvas_'+t(i).attr("name")+'" width="'+u+'" height="'+a+'" style="position:absolute; top: 0; bottom: 0; left: 0; right: 0; z-index:100; width: 100%; height: 100%;"></canvas>');t(n).append(m);var g=t(m)[0].getContext("2d");var y=new Image;y.onload=function(){var n=t('<canvas width="'+c+'" height="'+h+'"></canvas>');var r=t(n)[0].getContext("2d");var o=t('<img src="" />');r.drawImage(y,0,0,c,h);var p=new Image;p.onload=function(){g.drawImage(p,l,f,u,a,0,0,u,a);if(s.ajax==true){e._ajax(t.extend({data:t(m)[0].toDataURL()},v))}else{var n=JSON.stringify(t.extend({data:t(m)[0].toDataURL()},v));t(i).after(t('<input type="text" name="'+t(i).attr("name")+'_values" class="final" />').val(n));t(i).data("required",t(i).prop("required"));t(i).prop("required",false);t(i).wrap("<form>").parent("form").trigger("reset");t(i).unwrap();e.imageFinal()}};p.src=t(n)[0].toDataURL()};y.src=t(r).attr("src")}else{if(s.ajax==true){e._ajax(t.extend({data:t(r).attr("src")},v))}else{var b=t(n).find(".cropWrapper").clone();t(b).addClass("final").show().unbind().children().unbind();t(n).append(t(b));e.imageFinal();var w=JSON.stringify(v);t(i).after(t('<input type="text" name="'+t(i).attr("name")+'_values" class="final" />').val(w))}}},_ajax:function(e){var n=this;var r=n.element;var i=n.image;var s=n.options;t(r).find(".tools").children().toggle();t(r).find(".tools").append(t(n.button.saving));if(s.dimensionsonly==true){delete e.data}t.ajax({type:"POST",url:s.url,data:t.extend(e,s.data),success:function(e){if(e.status=="success"){var i=e.url.split("?");t(r).find(".tools .saving").remove();t(r).find(".tools").children().toggle();t(r).data("name",i[0]);if(s.canvas!=true){t(r).append(t('<img src="'+i[0]+'" class="final" style="width: 100%" />'))}n.imageFinal()}else{t(r).find(".tools .saving").remove();t(r).find(".tools").children().toggle();t(r).append(t('<div class="alert alert-danger">'+e.error+"</div>").css({bottom:"10px",left:"10px",right:"10px",position:"absolute",zIndex:99}));setTimeout(function(){n.responseReset()},2e3)}},error:function(e,i){t(r).find(".tools .saving").remove();t(r).find(".tools").children().toggle();t(r).append(t('<div class="alert alert-danger"><strong>'+e.status+"</strong> "+e.statusText+"</div>").css({bottom:"10px",left:"10px",right:"10px",position:"absolute",zIndex:99}));setTimeout(function(){n.responseReset()},2e3)}})},imageReset:function(){var e=this;var n=e.image;var r=e.element;t(n).css({width:n.data("useWidth"),height:n.data("useHeight"),top:n.data("top"),left:n.data("left")});e._ghost();if(e.options.onAfterResetImage)e.options.onAfterResetImage.call(e)},imageFinal:function(){var e=this;var n=e.element;var r=e.input;t(n).children().not(".final").hide();var i=t('<div class="tools final">');t(i).append(t(e.button.edit).click(function(){t(n).children().show();t(n).find(".final").remove();t(r).data("valid",false)}));t(i).append(t(e.button.del).click(function(t){e.reset()}));t(n).append(i);t(n).unbind();t(r).unbind().data("valid",true);if(e.options.onAfterProcessImage)e.options.onAfterProcessImage.call(e)},responseReset:function(){var e=this;var n=e.element;t(n).find(".alert").remove()},reset:function(){var e=this;var n=e.element;var r=e.input;var i=e.options;e.image=null;t(n).find(".preview").remove();t(n).removeClass("loading").children().show().not("input[type=file]").remove();t(r).wrap("<form>").parent("form").trigger("reset");t(r).unwrap();t(r).prop("required",t(r).data("required")||false).data("valid",false);e._bind();if(i.removeurl!=null&&!empty(t(n).data("name"))){t.ajax({type:"POST",url:i.removeurl,data:{image:t(n).data("name")},success:function(t){if(e.options.onAfterRemoveImage)e.options.onAfterRemoveImage.call(e,t)}})}t(n).data("name",null);if(e.imageGhost){t(e.imageGhost).remove();e.imageGhost=null}if(e.options.onAfterCancel)e.options.onAfterCancel.call(e)},_ghost:function(){var e=this;var n=e.options;var r=e.image;var i=e.imageGhost;if(n.ghost==true&&!empty(i)){t(i).css({width:r.css("width"),height:r.css("height"),top:r.css("top"),left:r.css("left")})}},_tools:function(){var n=this;var r=n.element;var i=t('<div class="tools"></div>');t(i).append(t(n.button.zoomin).on({mousedown:function(t){n.interval=e.setInterval(function(){n.imageZoom(2)},1)},mouseup:function(t){e.clearInterval(n.interval);if(n.options.onAfterZoomImage)n.options.onAfterZoomImage.call(n)},mouseleave:function(t){e.clearInterval(n.interval);if(n.options.onAfterZoomImage)n.options.onAfterZoomImage.call(n)}}));t(i).append(t(n.button.zoomreset).on({click:function(e){n.imageReset()}}));t(i).append(t(n.button.zoomout).on({mousedown:function(t){n.interval=e.setInterval(function(){n.imageZoom(-2)},1)},mouseup:function(t){e.clearInterval(n.interval);if(n.options.onAfterZoomImage)n.options.onAfterZoomImage.call(n)},mouseleave:function(t){e.clearInterval(n.interval);if(n.options.onAfterZoomImage)n.options.onAfterZoomImage.call(n)}}));t(i).append(t(n.button.cancel).on({click:function(e){n.reset()}}));t(i).append(t(n.button.done).on({click:function(e){n.imageCrop()}}));t(r).append(t(i))},_clearTimers:function(){var t=e.setInterval("",9999);for(var n=1;n<t;n++)e.clearInterval(n)},_formValidation:function(){var e=this;var n=e.element;var r=e.input;t(n).closest("form").submit(function(e){t(this).find("input[type=file]").each(function(n,r){if(t(r).prop("required")){if(t(r).data("valid")!==true){e.preventDefault();return false}}});return true})}};t.fn.html5imageupload=function(e){if(t.data(this,"html5imageupload"))return;return t(this).each(function(){new t.html5imageupload(e,this);t.data(this,"html5imageupload")})}})(window,jQuery)