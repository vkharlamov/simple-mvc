$(document).ready(function() {

    var gridHandler = {
        init :  function () {
             console.log($gridTaskHeader);
             _initSortColumn();
        }
    }

    var $gridTaskHeader = $('[id^=grid-task-header]');

    var _initSortColumn = function () {
        var dd = $gridTaskHeader.filter('.dropdown-menu');
        console.log('INit');
    }

    gridHandler.init();

})(jQuery);
