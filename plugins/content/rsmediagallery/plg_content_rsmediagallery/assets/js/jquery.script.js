function rsmg_init_content_equal_size(a){var b=0;var c=[];if(a(".rsmg_content_container").length==0)return;var d=a(".rsmg_content_container").offset().top;var e=a(".rsmg_content_container").length-1;a(".rsmg_content_container").each(function(f,g){var h=a(g).offset().top;if(d!=h){for(var i=0;i<c.length;i++)if(b>0)c[i].css("height",b);h=a(g).offset().top;d=h;b=0;c.length=0}b=Math.max(b,a(g).height());c.push(a(g));if(f==e){for(var i=0;i<c.length;i++)if(b>0)c[i].css("height",b)}})}jQuery(document).ready(function(a){a("ul.rsmg_content_gallery li img").hover(function(){a(this).stop().animate({opacity:.7},"slow")},function(){a(this).stop().animate({opacity:1},"slow")});a(document).piroBox_ext({piro_speed:700,bg_alpha:.5,piro_scroll:true,htmlClass:"content",selector:'a[class*="pirobox_gall_content"]'});rsmg_init_content_equal_size(jQuery)})