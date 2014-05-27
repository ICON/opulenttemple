var CmigrateProceed = new Class({
    //implements
    Implements: [Options],
    
    initialize: function(options) {
        //set options
        this.setOptions(options);
    },
    
    createElement : function () {
        var self = this;
        var div = new Element('div', {
            styles:{
                width:'100%',
                height:'100%',
                background:'#000',
                'z-index':9998,
                position:'absolute',
                top:0,
                left:0,
                opacity:'0.5'
            }, 
            'id':'back'
        });
        div.inject(document.body);
        var size = window.getSize();
        var left = (size.x-300)/2;
        var top = (size.y-150)/2-50;
        document.id('inner-' + self.options.task.toString()).set({
            styles:{
                visibility:'visible',
                left: left, 
                top: top
            }
        });
        return true;
    },
    
    close : function () {
        var self = this;
        document.id('inner-' + self.options.task.toString()).set({
            styles:{
                visibility:'hidden'
            }
        });
        document.id('close-' + self.options.task.toString()).set({
            styles:{
                visibility:'hidden'
            }
        });
        document.id('back').dispose();
    }
});