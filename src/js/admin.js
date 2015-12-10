/**
 * WpRsrv wp-admin scripts.
 */

/* globals jQuery, Pikaday, console, ajaxurl */

function WprsrvAdmin($)
{
    'use strict';

    this.$ = $;
    this.$body = this.$('body');
    this.l10n = window.wprsrv_admin_l10n;

    this.pikadaySettings = {
        i18n: this.l10n.calendars,
        format: 'YYYY-MM-DD'
    };
}

WprsrvAdmin.prototype.spawnPikadayFields = function ($inputs)
{
    'use strict';

    var $fields;

    if ($inputs === undefined) {
        $fields = this.$('input.wprsrv-pikaday');
    } else {
        $fields = $inputs;
    }

    if (!$fields.length || !$fields) {
        return;
    }

    var calendarSettings = this.pikadaySettings;

    window.WprsrvAdmin.pikadayFields = [];

    $fields.each(function (idx, el) {
        calendarSettings.field = el;

        window.WprsrvAdmin.pikadayFields.push(new Pikaday(calendarSettings));
    });
};

WprsrvAdmin.prototype.reservationEditScreen = function ()
{
    'use strict';

    var isPostEdit = this.$body.hasClass('post-php');
    var isReservationEdit = this.$body.hasClass('post-type-reservation');

    if (!isPostEdit) {
        return;
    }

    if (!isReservationEdit) {
        return;
    }

    var titleInput = document.getElementById('title');
    var titleContainer = document.getElementById('titlewrap');
    var title = null;

    // Disable disable title input editing.
    if (titleInput) {
        title = titleInput.value;
        titleInput.type = 'hidden';

        var titleHeading = document.createElement('h2');
        titleHeading.innerHTML = title;

        titleContainer.appendChild(titleHeading);
    }
};

WprsrvAdmin.prototype.reservationAdminNotes = function ()
{
    'use strict';

    var noteAddBtn = document.getElementById('new-note-button');

    if (!noteAddBtn) {
        return;
    }

    var $noteBtn = this.$(noteAddBtn);
    var noteField = document.getElementById('new-note-field');
    var postIdField = document.getElementById('post_ID');
    var postId = postIdField.value;
    var userIdField = document.getElementById('user-id');
    var userId = userIdField.value;

    $noteBtn.on('click', function (evt) {
        evt.preventDefault();

        noteAddBtn.disabled = true;

        if (!noteField.value.replace(/ +/, '').length) {
            return;
        }

        var noteContent = noteField.value;

        var noteReq = this.$.post(ajaxurl, {
            'action': 'wprsrv_add_note',
            'post_id': postId,
            'user_id': userId,
            'note_content': noteContent
        });

        // If the request hangs, wait for 7.5secs and enable the form again.
        window.setTimeout(function () {
            noteAddBtn.disabled = false;
        }, 7500);

        noteReq.always(function () {
            noteAddBtn.disabled = false;
        });

        noteReq.done(function (res) {
            console.log('Note request success!');

            noteField.value = null;

            window.location = window.location;
        });

        noteReq.fail(function (res) {
            console.log('Note request error!');
        });

        return false;
    }.bind(this));
};

WprsrvAdmin.prototype.reservationAjaxActions = function ()
{
    'use strict';

    var isPostEdit = this.$body.hasClass('post-php');
    var isReservationEdit = this.$body.hasClass('post-type-reservation');

    if (!isPostEdit) {
        return;
    }

    if (!isReservationEdit) {
        return;
    }

    this.reservationAdminNotes();
};

WprsrvAdmin.prototype.reservableEditScreen = function ()
{
    'use strict';

    var isPostEdit = this.$body.hasClass('post-php');
    var isReservableEdit = this.$body.hasClass('post-type-reservable');

    if (!isPostEdit) {
        return;
    }

    if (!isReservableEdit) {
        return;
    }

    var $repeaterClonables = this.$('.wprsrv-clonerow');

    var rowRemove = function (evt) {
        evt.preventDefault();

        var $row = evt.data.row;

        $row.remove();

        return false;
    }.bind(this);

    var rowAdd = function (evt) {
        evt.preventDefault();

        var $addBtn = evt.data.addBtn;
        var $clonable = evt.data.clonable;

        var $new = $clonable.clone();

        $new.one('click', '.deletion', {'row': $new}, rowRemove);

        $new.insertBefore($addBtn);
        $new[0].className = 'wprsrv-repeater-row';

        var $inputs = $new.find('input');
        $inputs[0].name = 'wprsrv[reservable_disabled_days][start][]';
        $inputs[1].name = 'wprsrv[reservable_disabled_days][end][]';

        $new.attr('style', '');

        this.spawnPikadayFields($inputs);

        return false;
    }.bind(this);

    var setupRepeater = function (idx, el) {
        var $clonable = this.$(el);

        var $addBtn = $clonable.parent().find('.add-row');

        $addBtn.on('click', {'addBtn': $addBtn, 'clonable': $clonable}, rowAdd);
    }.bind(this);

    if ($repeaterClonables.length) {
        $repeaterClonables.each(setupRepeater);

        $repeaterClonables.parent()
            .find('.wprsrv-repeater-row').each(function(idx, el) {
                var $row = this.$(el);

                $row.on('click', '.deletion', {'row': $row}, rowRemove);
            }.bind(this));
    }

    this.reservableCalendars();
    this.reservableActions();
};

WprsrvAdmin.prototype.reservableActions = function ()
{
    'use strict';

    var flushCacheBtn = document.getElementById('flush-reservable-cache');
    var $flushCacheBtn = this.$(flushCacheBtn);

    var postIdField = document.getElementById('post_ID');
    var postId = postIdField.value;

    var spinner = document.createElement('div');
    spinner.className = 'spinner';

    flushCacheBtn.parentNode.insertBefore(spinner, flushCacheBtn.nextSibling);
    flushCacheBtn.disabled = false;

    $flushCacheBtn.on('click', function (evt) {
        evt.preventDefault();

        spinner.className += ' is-active';

        var flushReq = this.$.post(ajaxurl, {
            action: 'wprsrv_flush_reservable_cache',
            post_id: postId
        });

        flushReq.done(function (res) {
            console.log(res);

            if (parseInt(res) === 0) {
                flushCacheBtn.disabled = true;

                window.setTimeout(function () {
                    flushCacheBtn.disabled = false;
                }, 5000);
            }
        });

        flushReq.error(function (res) {

        });

        flushReq.always(function (res) {
            spinner.className = 'spinner';
        });

        return false;
    }.bind(this));
};

WprsrvAdmin.prototype.reservableCalendars = function ()
{
    'use strict';

    var calendarMetabox = document.getElementById('reservablereservations');
    var reservableId = document.getElementById('post_ID').value;

    if (!reservableId) {
        return;
    }

    if (!calendarMetabox) {
        return;
    }

    var noCalendarText = this.l10n.could_not_fetch_calendar;
    var loader = document.getElementById('res-cal-loader');

    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'res-cal-loader';
        loader.innerHTML = '<span class="spinner"></span>';

        calendarMetabox.parentNode.insertBefore(loader, calendarMetabox);
    }

    var showAjaxLoader = function () {
        loader.className += ' is-active';
    };

    var hideAjaxLoader = function () {
        loader.className = loader.className.replace(' is-active', '');
    };

    var getCalendarRequest = function (calDate) {
        var yearNum = calDate.getFullYear();
        var monthNum = calDate.getMonth() + 1;

        if (monthNum.toString().length < 2) {
            monthNum = '0' + monthNum;
        }

        var paramDate = yearNum + '-' + monthNum;

        return this.$.post(ajaxurl, {
            action: 'wprsrv_get_reservation_calendar_view',
            reservable_id: reservableId,
            reservable_calendar_month: paramDate
        }, null, 'html');
    }.bind(this);

    var showPrev = function ($link) {
        var $curTable = $link.parents('table');
        var $prevTable = $curTable.prev('table');

        var curYear = parseInt($curTable.data('year'));
        var curMonth = parseInt($curTable.data('month'));

        var curDate = new Date();
        curDate.setFullYear(curYear);
        curDate.setMonth(curMonth-1);
        curDate.setMonth(curDate.getMonth()-1);

        var curPrettyMonth = (function () {
            var mon = (curDate.getMonth + 1).toString();

            if (mon.length < 2) {
                mon = '0' + mon;
            }

            return mon;
        })();

        if (!$prevTable.length) {
            showAjaxLoader();

            var req = getCalendarRequest(curDate);

            req.done(function (res, status) {
                if (parseInt(res) === 1) {
                    $curTable.before('<p class="empty-notice">' + noCalendarText + '</p>');
                    $curTable.removeClass('active');
                    return;
                }

                $curTable.before(res);
                $prevTable = $curTable.prev('table');
                $prevTable.data('year', curDate.getFullYear());
                $prevTable.data('month', curPrettyMonth);

                $curTable.removeClass('active');
                $prevTable.addClass('active');
            });

            req.always(function () {
                hideAjaxLoader();
            })
        } else {
            $curTable.removeClass('active');
            $prevTable.addClass('active');
        }
    };

    var showNext = function ($link) {
        var $curTable = $link.parents('table');
        var $nextTable = $curTable.next('table');

        var curYear = parseInt($curTable.data('year'));
        var curMonth = parseInt($curTable.data('month'));

        var curDate = new Date();
        curDate.setFullYear(curYear);
        curDate.setMonth(curMonth-1);
        curDate.setMonth(curDate.getMonth()+1);

        var curPrettyMonth = (function () {
            var mon = (curDate.getMonth + 1).toString();

            if (mon.length < 2) {
                mon = '0' + mon;
            }

            return mon;
        })();

        if (!$nextTable.length) {
            showAjaxLoader();

            var req = getCalendarRequest(curDate);

            req.done(function (res, status) {
                if (parseInt(res) === 1) {
                    $curTable.after('<p class="empty-notice">' + noCalendarText + '</p>');
                    $curTable.removeClass('active');
                    return;
                }

                $curTable.after(res);
                $nextTable = $curTable.next('table');
                $nextTable.data('year', curDate.getFullYear());
                $nextTable.data('month', curPrettyMonth);

                $curTable.removeClass('active');
                $nextTable.addClass('active');
            });

            req.always(function () {
                hideAjaxLoader();
            })
        } else {
            $curTable.removeClass('active');
            $nextTable.addClass('active');
        }
    };

    var $cmb = this.$(calendarMetabox);

    $cmb.on('click', '.prev-month', function (e) {
        var $link = this.$(e.target);

        showPrev($link);
    }.bind(this));

    $cmb.on('click', '.next-month', function (e) {
        var $link = this.$(e.target);

        showNext($link);
    }.bind(this));
};

WprsrvAdmin.prototype.initialize = function ()
{
    'use strict';

    console.log('Initializing Wprsrv admin scripts...');

    this.reservationEditScreen();
    this.reservationAjaxActions();
    this.reservableEditScreen();
    this.spawnPikadayFields();
};

jQuery(document).ready(function ()
{
    'use strict';

    window.WprsrvAdmin = new WprsrvAdmin(jQuery);

    window.WprsrvAdmin.initialize();
});
