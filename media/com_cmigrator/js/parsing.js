var Parsing = new Class({
    Implements:[Options],
    initialize: function(options) {
        this.setOptions(options);
        this.addEvents();
        this.content_counter = 0;
        this.images_counter = 0;
        this.content_ids = [];
        this.images_ids = [];
    },
    addEvents:function () {
        document.id('parsing').addEvent('submit', function () {
            $('parsing-bar').set('text', this.translate('COM_CMIGRATOR_JS_PARSE_STARTED','Parsing started!'));
            document.id('close-parsing').set({
                styles:{
                    visibility:'hidden'
                }
            });
            document.id('parsing').set({
                styles:{
                    visibility:'hidden'
                }
            });
            document.id('progress-bar-parse').set({
                styles:{
                    border:'solid 1px gray'
                }
            });
            this.pb = new ProgressBar(
            {
                container:$('progress-bar-parse'),
                startPercentage:0,
                speed:1000,
                boxID:'box',
                percentageID:'perc',
                displayID:'text',
                displayText:false
            });
            this.conf = document.id('config-parse').get('value');
            this.content = 0;
            this.images = 0;
            this.counter = 0;
            var self = this;
            var req = new Request.JSON({
                url:self.options.baseUrl + 'index.php?option=com_cmigrator&view=parse&task=start&format=raw',
                method:'post',
                data:{
                    config:self.conf
                },
                onComplete:function (response) {
                    self.content = response.total;
                    self.images = response.total;
                    setTimeout(function () {
                        self.start('images',self.images,1);
                    }, 500);
                }
            });
            req.send();
            return false;
        }.bind(this));
    },
    
    start:function (task, total, limit) {
        var self = this;
        
        $('parsing-bar').set('text', self.translate('COM_CMIGRATOR_JS_PARSE_FOR','Parsing for ') + task + self.translate('COM_CMIGRATOR_JS_STARTED',' started!'));
        var request = new Request.JSON({
            url:self.options.baseUrl + 'index.php?option=com_cmigrator&view=parse&task=' + task + '&format=raw',
            method:'post',
            data:{
                start:0,
                limit:limit,
                config:self.conf
            },                
            // the request needs not to be asynchronious, otherwise it won't work
            async:false,
            onSuccess:function (response) {
                self.counter += response.counter;
                $('parsing-bar').set('html', '<p>' + self.translate('COM_CMIGRATOR_JS_PARSE_PARSING','Parsing ') + task + self.translate('COM_CMIGRATOR_JS_CURRENT','. Current: ') + response.processed + self.translate('COM_CMIGRATOR_JS_OF',' of ') + total+ '.</p><span>' + self.translate('COM_CMIGRATOR_JS_STATUS','Status: ') + self.counter + self.translate('COM_CMIGRATOR_JS_PARSE_ARTICLES_CHANGED',' articles changed!') +'</span>');
                // check the status of the request if ok, go on if error display it to user
                var percent = parseInt((parseInt(response.processed) / parseInt(total)) * 100);
                self.pb.set(percent);
                if(task == 'content') {
                    self.content_ids = self.content_ids.append(response.ids);
                    self.content_counter += response.counter;
                } else {
                    self.images_ids = self.images_ids.append(response.ids);
                    self.images_counter += response.counter;
                }
                setTimeout(function(){
                    // check if the total posts are less than the processed posts
                    if (parseInt(total) > parseInt(response.processed)) {
                        request.send('start=' + response.processed + '&limit='+ limit + '&config=' + self.conf);
                    } else {
                        if(task == 'images') {
                            task = 'content';
                            self.counter = 0;
                            self.start(task,total,100);
                        } else {
                            $('images-counter').set('value',self.images_counter);
                            $('content-counter').set('value',self.content_counter);
                            $('images-ids').set('value',self.images_ids);
                            $('content-ids').set('value',self.content_ids);
                            $('config-id').set('value', self.conf);                            
                            $('parsing-bar').set('text', self.translate('COM_CMIGRATOR_JS_PARSE_COMPLETE_SUCC','Parsing completed successfully!'));
                            document.id('parsing').submit();
                        }
                    }
                },1200);
            }.bind(this)
        });
        request.send();        
    },
    
    translate: function(key, def) {
        return Joomla.JText._(key, def);
    }
});