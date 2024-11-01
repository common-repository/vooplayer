var my_editor = null;
(function() {
	tinymce.create('tinymce.plugins.Vooplayer', {
		init : function(ed, url) {
			url = url.replace("/js","");
			my_editor = ed;
			ed.addButton('vooplayer', {
				title : 'Vooplayer Shortcode',
				image : url+'/images/large_icon.jpg',
				onclick : function() {
					idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
					ed.windowManager.open({
							title: 'Vooplayer Shortcode',
							file : ajaxurl + '?action=voo_videolist',
							width : 800,
							height : 600,
							inline : 0
					},{
                    plugin_url : url,
                    wp: wp
                });
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Vooplayer",
				author : 'Vooplayer',
				authorurl : 'https://www.vooplayer.com',
				infourl : 'https://www.vooplayer.com',
				version : "3.0"
			};
		}
	});
	tinymce.PluginManager.add('vooplayer', tinymce.plugins.Vooplayer);
})();
