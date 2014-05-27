var Migration = new Class({
    Implements:[Options],
    initialize:function (options) {
        this.setOptions(options);
        this.addEvents();
    },

    addEvents:function () {
        document.id('migration').addEvent('submit', function () {
            $('migration-bar').set('text', this.translate('COM_CMIGRATOR_JS_MIGRATION_STARTED','Migration started!'));
            document.id('close-migration').set({
                styles:{
                    visibility:'hidden'
                }
            });
            document.id('migration').set({
                styles:{
                    visibility:'hidden'
                }
            });
            document.id('progress-bar-migrate').set({
                styles:{
                    border:'solid 1px gray'
                }
            });
            this.pb = new ProgressBar(
            {
                container:$('progress-bar-migrate'),
                startPercentage:0,
                speed:1000,
                boxID:'box',
                percentageID:'perc',
                displayID:'text',
                displayText:false
            });
            this.conf = document.id('config-migrate').get('value');
            this.content = 0;
            this.categories = 0;
            this.users = 0;
            this.tags = 0;
            this.comments = 0;
            var self = this;
            var req = new Request.JSON({
                url:self.options.baseUrl + 'index.php?option=com_cmigrator&view=migrate&task=start&format=raw',
                method:'post',
                data:{
                    config:self.conf
                },
                onComplete:function (response) {
                    self.content = response.content;
                    self.categories = response.categories;
                    self.users = response.users;
                    self.tags = response.tags;
                    self.comments = response.comments;
                    setTimeout(function () {
                        self.start('users',self.users);
                    }, 500);
                }
            });
            req.send();
            return false;
        }.bind(this));
    },

    start:function (task, total) {
        var self = this;
        if (total == -1) {
            $(task).set('value',-1);
            $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_FOR','Migration for ') + task + self.translate('COM_CMIGRATOR_JS_MIGRATION_DISABLED',' disabled.'));
            self.pb.set(100);
            setTimeout(function(){
                if(task == 'users') {
                    task = 'categories';
                    total = self.categories;
                    self.start(task,total);
                }
                else if(task == 'categories') {
                    task = 'content';
                    total = self.content;
                    self.start(task,total);
                }
                else if(task == 'content') {
                    task = 'tags';
                    total = self.tags;
                    self.start(task,total);
                }
                else if(task == 'tags') {
                    task = 'comments';
                    total = self.comments;
                    self.start(task,total);
                }
                else {
                    $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_COMPLETE_SUCC','Migration completed successfully!'));
                    document.id('migration').submit();
                }
            },1200);
        } else if (total == 0) {
            $(task).set('value',0);
            $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_FOR','Migration for ') + task + self.translate('COM_CMIGRATOR_JS_MIGRATION_NOTHING',' : nothing to migrate!'));
            self.pb.set(100);
            setTimeout(function(){
                if(task == 'users') {
                    task = 'categories';
                    total = self.categories;
                    self.start(task,total);
                }
                else if(task == 'categories') {
                    task = 'content';
                    total = self.content;
                    self.start(task,total);
                }
                else if(task == 'content') {
                    task = 'tags';
                    total = self.tags;
                    self.start(task,total);
                }
                else if(task == 'tags') {
                    task = 'comments';
                    total = self.comments;
                    self.start(task,total);
                }
                else {
                    $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_COMPLETE_SUCC','Migration completed successfully!'));
                    document.id('migration').submit();
                }
            },1200);
        } else {
            $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_FOR','Migration for ') + task + self.translate('COM_CMIGRATOR_JS_STARTED',' started!'));
            var request = new Request.JSON({
                url:self.options.baseUrl + 'index.php?option=com_cmigrator&view=migrate&task=' + task + '&format=raw',
                method:'post',
                data:{
                    start: 0,
                    limit: 100,
                    config: self.conf,
                    total: total
                },
                
                // the request needs not to be asynchronious, otherwise it won't work
                async:false,
                onSuccess:function (response) {
                    $('migration-bar').set('html', '<p>' + self.translate('COM_CMIGRATOR_JS_MIGRATION_MIGRATING','Migrating ') + task + self.translate('COM_CMIGRATOR_JS_CURRENT','. Current: ') + response.processed + self.translate('COM_CMIGRATOR_JS_OF',' of ') + total+ '.</p><span>'+ self.translate('COM_CMIGRATOR_JS_STATUS','Status: ') + response.status + '</span>');
                    // check the status of the request if ok, go on if error display it to user
                    if (response.status == self.translate('COM_CMIGRATOR_JS_OK','OK')) {
                        var percent = parseInt((parseInt(response.processed) / parseInt(total)) * 100);
                        self.pb.set(percent);
                        setTimeout(function(){
                            // check if the total posts are less than the processed posts
                            if (parseInt(total) > parseInt(response.processed)) {
                                request.send('start=' + response.processed + '&limit=100' + '&config=' + self.conf + '&total=' + total);
                            } else {
                                if(task == 'users') {
                                    $('users').set('value','OK');
                                    task = 'categories';
                                    total = self.categories;
                                    self.start(task,total);
                                }
                                else if(task == 'categories') {
                                    $('categories').set('value','OK');
                                    task = 'content';
                                    total = self.content;
                                    self.start(task,total);
                                }
                                else if(task == 'content') {
                                    $('content').set('value','OK');
                                    task = 'tags';
                                    total = self.tags;
                                    self.start(task,total);
                                }
                                else if(task == 'tags') {
                                    $('tags').set('value','OK');
                                    task = 'comments';
                                    total = self.comments;
                                    self.start(task,total);
                                }
                                else {
                                    $('comments').set('value','OK');
                                    $('migration-bar').set('text', self.translate('COM_CMIGRATOR_JS_MIGRATION_COMPLETE_SUCC','Migration completed successfully!'));
                                    document.id('migration').submit();
                                }
                            }
                        },1200);
                    } else {
                        $('migration-bar').set('html', '<p style="text-align: center;">' + response.status + ':<br />' + self.translate('COM_CMIGRATOR_JS_MIGRATION_DB_ERROR','Database insert error!'));
                        document.id('close-migration').set({
                            styles:{
                                visibility:'visible'
                            }
                        });
                    }
                }.bind(this)
            });
            request.send();
        }
    },
    
    translate: function(key, def) {
        return Joomla.JText._(key, def);
    }
});