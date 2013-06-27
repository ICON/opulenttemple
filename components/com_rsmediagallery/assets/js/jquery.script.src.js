jQuery(document).ready(function ($) {
	
	// hide previous button if we're javascript capable
    if ($("#rsmg_prev_page").length > 0)
		$("#rsmg_prev_page").hide();
	
	// items gallery
    rsmg_init_load_more($);
    rsmg_init_items($);
	
    var src = $("#rsmg_image_container img").attr("src");
	// IE needs a random string so it will not cache the image 
    if ($.browser.msie && $.browser.version < 9) {
        src += src.indexOf("?") == -1 ? "?" : "&";
        src += "random=" + Math.floor(Math.random() * 11000)
    }
    $("#rsmg_loader_container").removeAttr("style");
    var preloader = $("<img>").load(function () {
		// if it doesn't fit the page, just make it 100% so the browser will resize it
        if ($("#rsmg_thumb_container > img").outerWidth() > $("#rsmg_image_container").parent().outerWidth())
			$("#rsmg_image_container img").css("width", "100%");
        $("#rsmg_loader_container").remove();
        $("#rsmg_image_container").removeClass("rsmg_hidden_from_view");
        $("#rsmg_image_container img").show("blind", 500)
    }).attr("src", src);
});

function rsmg_add_lang(id, translation) {
    if (typeof id == "object") {
        for (j in id) rsmg_lang_vars[j] = id[j];
        return true;
    }
    return rsmg_lang_vars[id] = translation;
}

function rsmg_get_lang(id, arg) {
    if (typeof rsmg_lang_vars[id] != "undefined") {
        val = rsmg_lang_vars[id];
        if (arg) {
            if (val.indexOf("%s") > -1) val = val.replace("%s", arg);
            else if (val.indexOf("%d") > -1) val = val.replace("%d", arg)
        }
        return val
    }
    return id;
}

function rsmg_init_load_more($) {
	// do we have a load more button ?
    if ($("#rsmg_load_more").length > 0) {
		// get total images
        var total = $("#rsmg_load_more").attr("rel");
		// get images left
        var left = 0;
        if (total > 0) left = total - $("ul#rsmg_gallery").children().length;
        if (left < 0) left = 0;
        $("#rsmg_load_more").attr("rel", left);
        $("#rsmg_load_more").html(rsmg_get_lang("RSMG_LOAD_MORE", $("#rsmg_load_more").attr("rel")));
        $("#rsmg_load_more").click(function (e) {
            e.preventDefault();
            e.shiftKey ? rsmg_get_items($, false, {
                limitall: 1,
                limit: $("#rsmg_load_more").attr("rel")
            }) : rsmg_get_items($)
        });
        $(document).keydown(function (e) {
            if (e.shiftKey) $("#rsmg_load_more").html(rsmg_get_lang("RSMG_LOAD_ALL", $("#rsmg_load_more").attr("rel")));
        });
        $(document).keyup(function (e) {
            $("#rsmg_load_more").html(rsmg_get_lang("RSMG_LOAD_MORE", $("#rsmg_load_more").attr("rel")));
        })
    }
}

function rsmg_get_original_limitstart($) {
    return parseInt($("#rsmg_original_limitstart").val());
}

function rsmg_init_items($) {
	// init hover effect
    $("#rsmg_gallery li img").hover(function () {
        $(this).stop().animate({
            opacity: .7
        }, "slow")
    }, function () {
        $(this).stop().animate({
            opacity: 1
        }, "slow")
    });
	// init lightbox
    if (typeof rsmg_init_lightbox2 == "function") rsmg_init_lightbox2();
	// init equal size
	rsmg_init_equal_size($);
}

function rsmg_init_equal_size($) {
	var max = 0;
	var current_items = [];
	if ($('.rsmg_item_container').length == 0) return;
	var old_top = $('.rsmg_item_container').offset().top;
	var last	= $('.rsmg_item_container').length - 1;
	$('.rsmg_item_container').each(function(index, el) {
		var top = $(el).offset().top;
		if (old_top != top)
		{
			for (var i=0; i<current_items.length; i++)
				if (max > 0)
					current_items[i].css('height', max);
			
			top 	= $(el).offset().top;
			old_top = top;
			max 	= 0;
			current_items.length = 0;
		}
		
		max = Math.max(max, $(el).height());
		current_items.push($(el));
		
		if (index == last)
		{
			for (var i=0; i<current_items.length; i++)
				if (max > 0)
					current_items[i].css('height', max);
		}
	});
}

function rsmg_get_items_filter($, more) {
    var data = {
        limitstart: $("ul#rsmg_gallery").children().length + rsmg_get_original_limitstart($),
        Itemid: $("#rsmg_itemid").val()
    };
    if (more)
		for (var k in more)
			data[k] = more[k];
    return data;
}

function rsmg_get_items($, clear, more, successFunction, overrideAsync) {
	// parent container
    var parent = $("#rsmg_gallery");
	
	// clear contents
    if (clear == true)
		parent.empty();
		
    if (overrideAsync === false)
		overrideAsync = false;
    else
		overrideAsync = true;
		
    $.ajax({
        type: "POST",
        url: rsmg_get_root() + "/index.php?option=com_rsmediagallery&task=getitems&format=raw",
        data: rsmg_get_items_filter($, more),
        async: overrideAsync,
        beforeSend: function () {
			// li container
            var li = $("<li>", {
                id: "rsmg_loader_container"
            });
			
			// ajax loader
            var loader = $("<div>", {
                "class": "rsmg_item_container"
            });
			
			// add loader image
            li.append(loader);
			
			// append the loader as the last item in the list
            parent.append(li);
			
			// hide load more
            $("#rsmg_load_more").hide("fade", 500)
        },
        success: function (data) {
            $("#rsmg_loader_container").remove();
			
            if (typeof data == "object" && data.items && data.total) {
                var k = parent.children().length;
                $(data.items).each(function (index, item) {
					// li container
                    var li = $("<li>");
					
					// div container
                    var div = $("<div>", {
                        "class": "rsmg_item_container"
                    });
					
					// thumbnail
					// thumbnail link
                    var a_thumb = $("<a>", {
                        href: item.href,
                        "class": "rsmg_lightbox",
                        rel: "{'link': '" + item.full + "', 'title': '#rsmg_item_" + k + "', 'id': '" + item.id + "'}"
                    });
                    if (item.open_in_new_page)
						a_thumb.attr("target", "_blank");
					// thumbnail image
                    var img_thumb = $("<img>", {
                        src: item.thumb,
                        alt: item.title
                    });
					img_thumb.attr({
						'width': item.thumb_width,
						'height': item.thumb_height
					});
                    a_thumb.append(img_thumb);
					
					// title in listing ?
                    var title = "";
                    if (item.show_title_list == 1) {
                        title = $("<a>", {
                            href: item.href,
                            "class": "rsmg_title"
                        }).html(item.title);
                        if (item.open_in_new_page)
							title.attr("target", "_blank")
                    }
					
					// description in listing ?
                    var description = "";
                    if (item.show_description_list == 1) description = $("<span>", {
                        "class": "rsmg_item_description"
                    }).html(item.description);
					
					// details
                    var details_container = $("<div>", {
                        id: "rsmg_item_" + k
                    }).css("display", "none");
					
					// show title in details ?
                    if (item.show_title_detail == 1)
						details_container.append($("<h2>", {"class": "rsmg_title"}).html(item.title));
					
					// show description in details ?
                    if (item.show_description_detail == 1)
						details_container.append(item.full_description);
					
					// download original link
                    var download_original = "";
                    if (item.download_original == 1) {
                        download_original = $("<div>", {
                            "class": "rsmg_download rsmg_toolbox"
                        });
                        a_download_original = $("<a>", {
                            href: item.download
                        }).html(rsmg_get_lang("RSMG_DOWNLOAD"));
                        download_original.append(a_download_original)
                    }
					
					// show views
                    var hits = "";
                    if (item.show_hits == 1) hits = $("<div>", {
                        "class": "rsmg_views rsmg_toolbox"
                    }).html(rsmg_get_lang(item.hits == 1 ? "RSMG_HIT" : "RSMG_HITS", item.hits));
					
					// show created date
                    var created = "";
                    if (item.show_created == 1) created = $("<div>", {
                        "class": "rsmg_calendar rsmg_toolbox"
                    }).html(rsmg_get_lang("RSMG_CREATED", item.created));
					
					// show modified date
                    var modified = "";
                    if (item.show_modified == 1) modified = $("<div>", {
                        "class": "rsmg_calendar rsmg_toolbox"
                    }).html(rsmg_get_lang("RSMG_MODIFIED", item.modified));
					
					// show a list of tags
                    var tags = "";
                    if (item.show_tags == 1) tags = $("<p>", {
                        "class": "rsmg_tags"
                    }).html(rsmg_get_lang("RSMG_TAGS") + ": <strong>" + item.tags + "</strong>");
					
					// clear spans
                    var clear1 = $("<span>", {
                        "class": "rsmg_clear"
                    });
                    var clear2 = $("<span>", {
                        "class": "rsmg_clear"
                    });
					
					// append all details
                    details_container.append(clear1, download_original, hits, created, modified, clear2, tags);
					
					// add details to item
                    li.append(div.append(a_thumb, title, description, details_container));
					
					// append item to parent
                    parent.append(li);
					
                    k++;
                });
				
				// init items (+ lightbox if available)
                rsmg_init_items($);
				
				// show load more
				var original_limitstart = rsmg_get_original_limitstart($);
                if (data.total > $("ul#rsmg_gallery").children().length + original_limitstart) {
                    var left = data.total - ($("ul#rsmg_gallery").children().length + original_limitstart);
                    $("#rsmg_load_more").html(rsmg_get_lang("RSMG_LOAD_MORE", left));
                    $("#rsmg_load_more").attr("rel", left);
                    $("#rsmg_load_more").show("fade", 500);
                }
				else
					$("#rsmg_load_more").attr("rel", 0);
					
                if (typeof successFunction == "function") {
                    successFunction(data);
                }
            }
        }
    })
}

function rsmg_hit_item(settings) {
	cid = parseInt(jQuery('#lightbox-image').attr('rel'));
	if (!isNaN(cid) && cid > 0 && rsmg_hit.indexOf(cid) == -1)
	{
		// add it to the stacks
		rsmg_hit.push(cid);
		rsmg_to_hit.push(cid);
		
		if (rsmg_hit_timer)
			clearTimeout(rsmg_hit_timer);
		
		rsmg_hit_timer = setTimeout(function() {
			// hit it
			jQuery.ajax({
				type: "POST",
				url: rsmg_get_root() + "/index.php",
				data: {
					'option': 'com_rsmediagallery',
					'task': 'hititem',
					'cid': rsmg_to_hit
				}
			});
			
			rsmg_to_hit.length = 0;
		}, 2500);
	}
}

var rsmg_lang_vars = {};
var rsmg_hit 	   = [];
var rsmg_to_hit	   = [];
var rsmg_hit_timer = false;