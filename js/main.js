Date.prototype.getShortMonth = function ()
{
    'use strict';

    var monthNum = this.getMonth();

    var monthStrs = {
        0: 'Jan',
        1: 'Feb',
        2: 'Mar',
        3: 'Apr',
        4: 'May',
        5: 'Jun',
        6: 'Jul',
        7: 'Aug',
        8: 'Sep',
        9: 'Oct',
        10: 'Nov',
        11: 'Dec'
    };

    return monthStrs[monthNum];
};

function Scripts() {}

Scripts.prototype.eqHeight = function ()
{
    'use strict';

    console.log('Equal heights');

    var sidebar = document.getElementById('sidebar');
    var main = document.getElementById('main');

    var side_h = sidebar.offsetHeight;
    var main_h = main.offsetHeight;

    if (side_h > main_h) {
        sidebar.style.height = main.style.height = side_h + 'px';
    } else if (main_h > side_h) {
        main.style.height = sidebar.style.height = main_h + 'px';
    }
};

Scripts.prototype.noticeClosers = function ()
{
    'use strict';

    var closers = document.querySelectorAll('.notice span.close');

    // Remove ones already closed by visitors.
    for (var j = 0; j < closers.length; j++) {
        var closer = closers[j];
        var parentEl = closer.parentNode;

        while (parentEl.className.indexOf('notice') === -1) {
            parentEl = parentEl.parentNode;
        }

        if (document.cookie.indexOf('notice-closed-'+parentEl.id) !== -1) {
            parentEl.parentNode.removeChild(parentEl);
        }
    }

    console.log(closers);

    var closeNotice = function (evt) {
        console.log('Closing notice');
        var btn = evt.target;

        var parentEl = btn.parentNode;

        while (parentEl.className.indexOf('notice') === -1) {
            parentEl = parentEl.parentNode;
        }

        parentEl.className += ' closing';
        var parentId = parentEl.id;

        if (parentId) {
            var now = new Date();
            now.setDate(now.getDate()+1);

            var dateStrParts = [
                now.getDay(), ', ',
                now.getDate(), ' ',
                now.getShortMonth(), ' ',
                now.getFullYear(), ' ',
                now.getHours(), ':',
                now.getMinutes(), ':',
                now.getSeconds(), ' GMT'
            ];

            var dateStr = dateStrParts.join();

            document.cookie = 'notice-closed-'+parentId+'=true; expires=' + dateStr + '; path=/';
        }

        setTimeout(function () {
            parentEl.parentNode.removeChild(parentEl);
        }, 500);
    };

    if (closers.length) {
        for (var i = 0; i < closers.length; i++) {
            closers[i].addEventListener('click', closeNotice);
        }
    }
};

document.addEventListener('DOMContentLoaded', function (evt)
{
    'use strict';

    console.log('Initing...');

    var S = new Scripts();

    window.setTimeout(S.eqHeight, 500);

    S.noticeClosers();
});
