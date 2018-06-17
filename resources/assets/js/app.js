//jQuery
jQuery = require('jquery');
$ = jQuery;

//jQuery-ui
require('jquery-ui/ui/widgets/tooltip');

//Google Analytics
let analytics = require('./googleAnalytics.js');
analytics.init();

//Vegas (background slideshow)
require('vegas');
