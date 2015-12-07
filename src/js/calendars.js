/**
 * Wprsrv Calendar Scripts.
 */

/* globals moment */
/* globals Pikaday */

/**
 * Simple method to add an amount of days to a Date object.
 *
 * @param {Number} num Number of days to add.
 */
Date.prototype.addDays = function (num)
{
    'use strict';

    this.setDate(this.getDate() + num);
};

/**
 * Extend Date to easily get date formatted as Ymd.
 *
 * @param {String} sep Separator string if wanted.
 *
 * @return {String}
 */
Date.prototype.toYmd = function (sep)
{
    'use strict';

    if (sep === undefined) {
        sep = '';
    }

    var y = this.getFullYear();

    var m = (function () {
        var lm = this.getMonth();
        lm++;

        if (lm.toString().length < 2) {
            lm = '0' + lm.toString();
        }

        return lm.toString();
    }.bind(this))();

    var d = (function () {
        var ld = this.getDate();

        if (ld.toString().length < 2) {
            ld = '0' + ld.toString();
        }

        return ld.toString();
    }.bind(this))();

    var ymd = y + sep + m + sep + d;

    return ymd;
};

(function (window, document, undefined) {

    'use strict';

    window.Wprsrv = window.Wprsrv || {};

    window.Wprsrv.Calendars = {

        /**
         * End date Pikaday datepicker field instance.
         */
        endPicker: null,

        /**
         * Starting date Pikaday datepicker field instance.
         */
        startPicker: null,

        /**
         * Disabled days.
         */
        disabledDates: [],

        /**
         * Get the field element for the start range date picker.
         *
         * @returns {Element}
         */
        getStartField: function ()
        {
            return document.getElementById('datepicker-field-start');
        },

        /**
         * Get the field element for the end range date picker.
         *
         * @returns {Element}
         */
        getEndField: function ()
        {
            return document.getElementById('datepicker-field-end');
        },

        /**
         * Get the field element for a single date picker.
         *
         * @returns {Element}
         */
        getDayField: function ()
        {
            return document.getElementById('datepicker-field');
        },

        /**
         * Get the next date which is disabled/blocked.
         *
         * @param {Date} date Date to begin looking with.
         *
         * @returns {Date|Boolean}
         */
        getNextDisabledDay: function (date)
        {
            console.log('Getting next disabled.');

            var disdays = this.disabledDates;

            if (!disdays) {
                return false;
            }

            disdays.push(date.toYmd('-'));
            disdays.sort();

            var nextDis = null;
            var picked = date.toYmd('-');
            var nextIs = false;

            disdays.forEach(function (item) {
                if (nextIs === true) {
                    nextDis = item;
                }

                if (item === picked) {
                   nextIs = true;
                }
            });

            return moment(nextDis).toDate();
        },

        /**
         * When the start date changes in a Pikaday calendar field.
         *
         * @param self
         */
        onStartChange: function (self)
        {
            var startDate = self.startPicker.getDate();
            var endDate = self.endPicker.getDate();

            self.startPicker.setStartRange(startDate);
            self.endPicker.setStartRange(startDate);
            self.endPicker.setMinDate(startDate);

            // Validate that range does not cross blocked days.
            if (startDate && endDate) {
                if (this.datesContainDisabled(startDate, endDate)) {
                    this.showValidationError(this.l10n.already_reserved);
                } else {
                    this.hideValidationError();
                }
            }
        },

        /**
         * When the end date changes in a Pikaday calendar field.
         *
         * @param self
         */
        onEndChange: function (self)
        {
            var startDate = self.startPicker.getDate();
            var endDate = self.endPicker.getDate();

            self.startPicker.setEndRange(endDate);
            self.startPicker.setMaxDate(endDate);
            self.endPicker.setEndRange(endDate);

            // Validate that range does not cross blocked days.
            if (startDate && endDate) {
                if (this.datesContainDisabled(startDate, endDate)) {
                    this.showValidationError(this.l10n.already_reserved);
                } else {
                    this.hideValidationError();
                }
            }
        },

        /**
         * Do two dates contain a disabled date inbetween them?
         *
         * @param {Date} start
         * @param {Date} end
         *
         * @return {Boolean}
         */
        datesContainDisabled: function (start, end)
        {
            if (!this.disabledDates.length) {
                return false;
            }

            while (start.toYmd() < end.toYmd()) {
                var startYmd = start.toYmd('-');

                if (this.disabledDates.indexOf(startYmd) !== -1) {
                    return true;
                }

                start.addDays(1);
            }

            return false;
        },

        /**
         * Load data for disables days which should not be selectable in the
         * calendars.
         *
         * @return {void}
         */
        loadDisabledDaysData: function ()
        {
            var disDays = window.reservableDisabledDays;

            if (disDays !== undefined && disDays.length) {
                disDays.forEach(function (item) {
                    var start = new Date(item.start + 'T00:00:00');
                    var end = new Date(item.end + 'T23:59:59');

                    while (start.toYmd() <= end.toYmd()) {
                        this.disabledDates.push(start.toYmd('-'));

                        start.setDate(start.getDate()+1);
                    }
                }.bind(this));

                return this.disabledDates;
            }

            return [];
        },

        /**
         * Baseline configuration for Pikaday instances.
         *
         * @param self
         * @param {Element} calContainer
         *
         * @return {Object}
         */
        getPikadayConfig: function (self)
        {
            var l10n = self.l10n.pikaday || {
                previousMonth: 'Previous Month',
                nextMonth: 'Next Month',
                months: [
                    'January', 'February', 'March', 'April',
                    'May', 'June', 'July', 'August',
                    'September', 'October', 'November', 'December'
                ],
                weekdays: [
                    'Sunday', 'Monday', 'Tuesday', 'Wednesday',
                    'Thursday', 'Friday', 'Saturday'
                ],
                weekdaysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
            };

            var dateDisplayFormat = 'YYYY-MM-DD';

            var now = new Date();

            var enabledYearRange = [
                parseInt(now.getFullYear()),
                parseInt(now.getFullYear()+2)
            ];

            var disabledDays = function (dateObj) {
                var dateStr = dateObj.toYmd('-');

                var isDisabled = false;

                self.disabledDates.forEach(function (item) {
                    if (item === dateStr) {
                        isDisabled = true;
                    }
                });

                return isDisabled;
            };

            var minimumCalDate = moment()
                .hour(0)
                .minute(0)
                .second(0)
                .milliseconds(0)
                .toDate();

            return {
                firstDay: 1,
                i18n: l10n,
                format: dateDisplayFormat,
                yearRange: enabledYearRange,
                disableDayFn: disabledDays,
                minDate: minimumCalDate
            };
        },

        /**
         * Span a calendar container for a Pikaday calendar field.
         *
         * @param field
         * @returns {Element}
         */
        spawnCalendarContainer: function (field, heading)
        {
            var container = document.createElement('div');

            container.id = field.id + '-calcont';
            container.className = 'pikaday-container';

            field.parentNode.insertBefore(container, field.nextSibling);

            if (heading !== undefined) {
                var headingStr = heading.toString();

                var headEl = document.createElement('strong');
                headEl.className = 'pikaday-heading';
                headEl.innerHTML = headingStr;

                field.parentNode.insertBefore(headEl, container);
            }

            return container;
        },

        /**
         * Spawn Pikaday datepicker instance for a single date picker input.
         *
         * @param {Element} field The input field to initialize Pikaday for.
         *
         * @returns {Pikaday}
         */
        spawnSinglePicker: function (field)
        {
            var calOpts = this.getPikadayConfig(this);

            calOpts.field = field;

            return new Pikaday(calOpts);
        },


        /**
         * Spawn Pikaday datepicker instance for the starting range date picker input.
         *
         * @param {Element} field The input field to initialize Pikaday for.
         *
         * @returns {Pikaday}
         */
        spawnStartPicker: function (field)
        {
            var fromText = this.l10n.date_form || 'From ...';

            var calOpts = this.getPikadayConfig(this);

            calOpts.field = field;

            var startPicker = new Pikaday(calOpts);

            startPicker.config({onSelect: function () {
                this.onStartChange(this);
            }.bind(this)});

            return startPicker;
        },

        /**
         * Spawn Pikaday datepicker instance for the ending range date picker input.
         *
         * @param {Element} field The input field to initialize Pikaday for.
         *
         * @returns {Pikaday}
         */
        spawnEndPicker: function (field)
        {
            var toText = this.l10n.date_to || 'To ...';

            var calOpts = this.getPikadayConfig(this);

            calOpts.field = field;

            var endPicker = new Pikaday(calOpts);

            endPicker.config({
                onSelect: function () {
                    this.onEndChange(this);
                }.bind(this)
            });

            return endPicker;
        },

        /**
         * Show a client-side validation error.
         *
         * @param {String} msg Validation message.
         * @param {Boolean} autohide Hide the message automatically. Defaults to
         *                           false.
         *
         * @return {void}
         */
        showValidationError: function(msg, autohide)
        {
            if (autohide === undefined) {
                autohide = false;
            }

            this.calendarValidationMessage.innerHTML = msg;
            this.calendarValidationMessage.className = 'validation is-active';

            if (autohide) {
                window.setTimeout(function () {
                    this.hideValidationError();
                }, 5000);
            }
        },

        /**
         * Hide client side validation message.
         *
         * @return {void}
         */
        hideValidationError: function ()
        {
            this.calendarValidationMessage.innerHTML = '';
            this.calendarValidationMessage.className = 'validation';
        },

        /**
         * Initialize the calendars.
         *
         * @param {Object} loadEvent DOMContentLoaded event bundle.
         *
         * @return {void}
         */
        init: function (loadEvent)
        {
            console.log('Initializing calendars.');

            this.loadLocalization();
            this.loadDisabledDaysData();

            var calendarStart = this.getStartField() || this.getDayField();
            var calendarEnd = this.getEndField();

            if (!calendarStart) {
                console.error('No calendars in sight!');
                return;
            }

            if (!calendarEnd) {
                this.startPicker = this.spawnSinglePicker(calendarStart);
            } else {
                this.startPicker = this.spawnStartPicker(calendarStart);
                this.endPicker = this.spawnEndPicker(calendarEnd);
            }

            this.calendarValidationMessage = calendarStart.parentNode.querySelector('.validation');
        },

        /**
         * Load localization strings.
         *
         * @return {void}
         */
        loadLocalization: function ()
        {
            this.l10n = window.WprsrvL10n.Calendars.l10n;
        }

    };

    document.addEventListener('DOMContentLoaded', function (evt) {
        window.Wprsrv.Calendars.init(evt);
    });

})(window, document);
