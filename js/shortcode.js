// JavaScript Document
    (function() {  
        tinymce.create('tinymce.plugins.lateralpress', {  
            init : function(ed, url) {  
			var newurl = url.substring(0, url.length -3);
                ed.addButton('lateralpress', {  
                    title : 'Insert LateralPress',  
                    image : newurl+'/images/logo-16.png',  
                    onclick : function() {  
                         ed.selection.setContent('[lateralpress]');        
                    }  
                }); 
				
            },  
            createControl : function(n, cm) {  
                return null;  
            },  
        });  
        tinymce.PluginManager.add('lateralpress', tinymce.plugins.lateralpress);  
    })();  