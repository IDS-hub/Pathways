// moment.js language configuration
// language : russian (ru)
// author : Viktorminator : https://github.com/Viktorminator
// Author : Menelion ElensÃÂºle : https://github.com/Oire

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['moment'], factory); // AMD
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment')); // Node
    } else {
        factory(window.moment); // Browser global
    }
}(function (moment) {
    function plural(word, num) {
        var forms = word.split('_');
        return num % 10 === 1 && num % 100 !== 11 ? forms[0] : (num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20) ? forms[1] : forms[2]);
    }

    function relativeTimeWithPlural(number, withoutSuffix, key) {
        var format = {
            'mm': 'ÃÂ¼ÃÂ¸ÃÂ½ÃÆÃâÃÂ°_ÃÂ¼ÃÂ¸ÃÂ½ÃÆÃâÃâ¹_ÃÂ¼ÃÂ¸ÃÂ½ÃÆÃâ',
            'hh': 'Ãâ¡ÃÂ°ÃÂ_Ãâ¡ÃÂ°ÃÂÃÂ°_Ãâ¡ÃÂ°ÃÂÃÂ¾ÃÂ²',
            'dd': 'ÃÂ´ÃÂµÃÂ½ÃÅ_ÃÂ´ÃÂ½ÃÂ_ÃÂ´ÃÂ½ÃÂµÃÂ¹',
            'MM': 'ÃÂ¼ÃÂµÃÂÃÂÃâ _ÃÂ¼ÃÂµÃÂÃÂÃâ ÃÂ°_ÃÂ¼ÃÂµÃÂÃÂÃâ ÃÂµÃÂ²',
            'yy': 'ÃÂ³ÃÂ¾ÃÂ´_ÃÂ³ÃÂ¾ÃÂ´ÃÂ°_ÃÂ»ÃÂµÃâ'
        };
        if (key === 'm') {
            return withoutSuffix ? 'ÃÂ¼ÃÂ¸ÃÂ½ÃÆÃâÃÂ°' : 'ÃÂ¼ÃÂ¸ÃÂ½ÃÆÃâÃÆ';
        }
        else {
            return number + ' ' + plural(format[key], +number);
        }
    }

    function monthsCaseReplace(m, format) {
        var months = {
            'nominative': 'ÃÂÃÂ½ÃÂ²ÃÂ°Ãâ¬ÃÅ_ÃâÃÂµÃÂ²Ãâ¬ÃÂ°ÃÂ»ÃÅ_ÃÂ¼ÃÂ°Ãâ¬Ãâ_ÃÂ°ÃÂ¿Ãâ¬ÃÂµÃÂ»ÃÅ_ÃÂ¼ÃÂ°ÃÂ¹_ÃÂ¸ÃÅ½ÃÂ½ÃÅ_ÃÂ¸ÃÅ½ÃÂ»ÃÅ_ÃÂ°ÃÂ²ÃÂ³ÃÆÃÂÃâ_ÃÂÃÂµÃÂ½ÃâÃÂÃÂ±Ãâ¬ÃÅ_ÃÂ¾ÃÂºÃâÃÂÃÂ±Ãâ¬ÃÅ_ÃÂ½ÃÂ¾ÃÂÃÂ±Ãâ¬ÃÅ_ÃÂ´ÃÂµÃÂºÃÂ°ÃÂ±Ãâ¬ÃÅ'.split('_'),
            'accusative': 'ÃÂÃÂ½ÃÂ²ÃÂ°Ãâ¬ÃÂ_ÃâÃÂµÃÂ²Ãâ¬ÃÂ°ÃÂ»ÃÂ_ÃÂ¼ÃÂ°Ãâ¬ÃâÃÂ°_ÃÂ°ÃÂ¿Ãâ¬ÃÂµÃÂ»ÃÂ_ÃÂ¼ÃÂ°ÃÂ_ÃÂ¸ÃÅ½ÃÂ½ÃÂ_ÃÂ¸ÃÅ½ÃÂ»ÃÂ_ÃÂ°ÃÂ²ÃÂ³ÃÆÃÂÃâÃÂ°_ÃÂÃÂµÃÂ½ÃâÃÂÃÂ±Ãâ¬ÃÂ_ÃÂ¾ÃÂºÃâÃÂÃÂ±Ãâ¬ÃÂ_ÃÂ½ÃÂ¾ÃÂÃÂ±Ãâ¬ÃÂ_ÃÂ´ÃÂµÃÂºÃÂ°ÃÂ±Ãâ¬ÃÂ'.split('_')
        },

        nounCase = (/D[oD]? *MMMM?/).test(format) ?
            'accusative' :
            'nominative';

        return months[nounCase][m.month()];
    }

    function monthsShortCaseReplace(m, format) {
        var monthsShort = {
            'nominative': 'ÃÂÃÂ½ÃÂ²_ÃâÃÂµÃÂ²_ÃÂ¼ÃÂ°Ãâ¬_ÃÂ°ÃÂ¿Ãâ¬_ÃÂ¼ÃÂ°ÃÂ¹_ÃÂ¸ÃÅ½ÃÂ½ÃÅ_ÃÂ¸ÃÅ½ÃÂ»ÃÅ_ÃÂ°ÃÂ²ÃÂ³_ÃÂÃÂµÃÂ½_ÃÂ¾ÃÂºÃâ_ÃÂ½ÃÂ¾ÃÂ_ÃÂ´ÃÂµÃÂº'.split('_'),
            'accusative': 'ÃÂÃÂ½ÃÂ²_ÃâÃÂµÃÂ²_ÃÂ¼ÃÂ°Ãâ¬_ÃÂ°ÃÂ¿Ãâ¬_ÃÂ¼ÃÂ°ÃÂ_ÃÂ¸ÃÅ½ÃÂ½ÃÂ_ÃÂ¸ÃÅ½ÃÂ»ÃÂ_ÃÂ°ÃÂ²ÃÂ³_ÃÂÃÂµÃÂ½_ÃÂ¾ÃÂºÃâ_ÃÂ½ÃÂ¾ÃÂ_ÃÂ´ÃÂµÃÂº'.split('_')
        },

        nounCase = (/D[oD]? *MMMM?/).test(format) ?
            'accusative' :
            'nominative';

        return monthsShort[nounCase][m.month()];
    }

    function weekdaysCaseReplace(m, format) {
        var weekdays = {
            'nominative': 'ÃÂ²ÃÂ¾ÃÂÃÂºÃâ¬ÃÂµÃÂÃÂµÃÂ½ÃÅÃÂµ_ÃÂ¿ÃÂ¾ÃÂ½ÃÂµÃÂ´ÃÂµÃÂ»ÃÅÃÂ½ÃÂ¸ÃÂº_ÃÂ²ÃâÃÂ¾Ãâ¬ÃÂ½ÃÂ¸ÃÂº_ÃÂÃâ¬ÃÂµÃÂ´ÃÂ°_Ãâ¡ÃÂµÃâÃÂ²ÃÂµÃâ¬ÃÂ³_ÃÂ¿ÃÂÃâÃÂ½ÃÂ¸Ãâ ÃÂ°_ÃÂÃÆÃÂ±ÃÂ±ÃÂ¾ÃâÃÂ°'.split('_'),
            'accusative': 'ÃÂ²ÃÂ¾ÃÂÃÂºÃâ¬ÃÂµÃÂÃÂµÃÂ½ÃÅÃÂµ_ÃÂ¿ÃÂ¾ÃÂ½ÃÂµÃÂ´ÃÂµÃÂ»ÃÅÃÂ½ÃÂ¸ÃÂº_ÃÂ²ÃâÃÂ¾Ãâ¬ÃÂ½ÃÂ¸ÃÂº_ÃÂÃâ¬ÃÂµÃÂ´ÃÆ_Ãâ¡ÃÂµÃâÃÂ²ÃÂµÃâ¬ÃÂ³_ÃÂ¿ÃÂÃâÃÂ½ÃÂ¸Ãâ ÃÆ_ÃÂÃÆÃÂ±ÃÂ±ÃÂ¾ÃâÃÆ'.split('_')
        },

        nounCase = (/\[ ?[ÃâÃÂ²] ?(?:ÃÂ¿Ãâ¬ÃÂ¾ÃËÃÂ»ÃÆÃÅ½|ÃÂÃÂ»ÃÂµÃÂ´ÃÆÃÅ½Ãâ°ÃÆÃÅ½)? ?\] ?dddd/).test(format) ?
            'accusative' :
            'nominative';

        return weekdays[nounCase][m.day()];
    }

    return moment.lang('ru', {
        months : monthsCaseReplace,
        monthsShort : monthsShortCaseReplace,
        weekdays : weekdaysCaseReplace,
        weekdaysShort : "ÃÂ²ÃÂ_ÃÂ¿ÃÂ½_ÃÂ²Ãâ_ÃÂÃâ¬_Ãâ¡Ãâ_ÃÂ¿Ãâ_ÃÂÃÂ±".split("_"),
        weekdaysMin : "ÃÂ²ÃÂ_ÃÂ¿ÃÂ½_ÃÂ²Ãâ_ÃÂÃâ¬_Ãâ¡Ãâ_ÃÂ¿Ãâ_ÃÂÃÂ±".split("_"),
        monthsParse : [/^ÃÂÃÂ½ÃÂ²/i, /^ÃâÃÂµÃÂ²/i, /^ÃÂ¼ÃÂ°Ãâ¬/i, /^ÃÂ°ÃÂ¿Ãâ¬/i, /^ÃÂ¼ÃÂ°[ÃÂ¹|ÃÂ]/i, /^ÃÂ¸ÃÅ½ÃÂ½/i, /^ÃÂ¸ÃÅ½ÃÂ»/i, /^ÃÂ°ÃÂ²ÃÂ³/i, /^ÃÂÃÂµÃÂ½/i, /^ÃÂ¾ÃÂºÃâ/i, /^ÃÂ½ÃÂ¾ÃÂ/i, /^ÃÂ´ÃÂµÃÂº/i],
        longDateFormat : {
            LT : "HH:mm",
            L : "DD.MM.YYYY",
            LL : "D MMMM YYYY ÃÂ³.",
            LLL : "D MMMM YYYY ÃÂ³., LT",
            LLLL : "dddd, D MMMM YYYY ÃÂ³., LT"
        },
        calendar : {
            sameDay: '[ÃÂ¡ÃÂµÃÂ³ÃÂ¾ÃÂ´ÃÂ½ÃÂ ÃÂ²] LT',
            nextDay: '[ÃâÃÂ°ÃÂ²ÃâÃâ¬ÃÂ° ÃÂ²] LT',
            lastDay: '[ÃâÃâ¡ÃÂµÃâ¬ÃÂ° ÃÂ²] LT',
            nextWeek: function () {
                return this.day() === 2 ? '[ÃâÃÂ¾] dddd [ÃÂ²] LT' : '[Ãâ] dddd [ÃÂ²] LT';
            },
            lastWeek: function () {
                switch (this.day()) {
                case 0:
                    return '[Ãâ ÃÂ¿Ãâ¬ÃÂ¾ÃËÃÂ»ÃÂ¾ÃÂµ] dddd [ÃÂ²] LT';
                case 1:
                case 2:
                case 4:
                    return '[Ãâ ÃÂ¿Ãâ¬ÃÂ¾ÃËÃÂ»Ãâ¹ÃÂ¹] dddd [ÃÂ²] LT';
                case 3:
                case 5:
                case 6:
                    return '[Ãâ ÃÂ¿Ãâ¬ÃÂ¾ÃËÃÂ»ÃÆÃÅ½] dddd [ÃÂ²] LT';
                }
            },
            sameElse: 'L'
        },
        relativeTime : {
            future : "Ãâ¡ÃÂµÃâ¬ÃÂµÃÂ· %s",
            past : "%s ÃÂ½ÃÂ°ÃÂ·ÃÂ°ÃÂ´",
            s : "ÃÂ½ÃÂµÃÂÃÂºÃÂ¾ÃÂ»ÃÅÃÂºÃÂ¾ ÃÂÃÂµÃÂºÃÆÃÂ½ÃÂ´",
            m : relativeTimeWithPlural,
            mm : relativeTimeWithPlural,
            h : "Ãâ¡ÃÂ°ÃÂ",
            hh : relativeTimeWithPlural,
            d : "ÃÂ´ÃÂµÃÂ½ÃÅ",
            dd : relativeTimeWithPlural,
            M : "ÃÂ¼ÃÂµÃÂÃÂÃâ ",
            MM : relativeTimeWithPlural,
            y : "ÃÂ³ÃÂ¾ÃÂ´",
            yy : relativeTimeWithPlural
        },

        // M. E.: those two are virtually unused but a user might want to implement them for his/her website for some reason

        meridiem : function (hour, minute, isLower) {
            if (hour < 4) {
                return "ÃÂ½ÃÂ¾Ãâ¡ÃÂ¸";
            } else if (hour < 12) {
                return "ÃÆÃâÃâ¬ÃÂ°";
            } else if (hour < 17) {
                return "ÃÂ´ÃÂ½ÃÂ";
            } else {
                return "ÃÂ²ÃÂµÃâ¡ÃÂµÃâ¬ÃÂ°";
            }
        },

        ordinal: function (number, period) {
            switch (period) {
            case 'M':
            case 'd':
            case 'DDD':
                return number + '-ÃÂ¹';
            case 'D':
                return number + '-ÃÂ³ÃÂ¾';
            case 'w':
            case 'W':
                return number + '-ÃÂ';
            default:
                return number;
            }
        },

        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 7  // The week that contains Jan 1st is the first week of the year.
        }
    });
}));