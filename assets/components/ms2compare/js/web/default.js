'use strict';

(function (window, document, $, ms2CompareConfig) {
    var ms2Compare = ms2Compare || {
        _form: null,
    };

    ms2Compare.config = {
        formSelector: '.ms2compare_form',
        activeFormClass: 'active',
        totalSelector: '.ms2compare_link',
        activeTotalClass: 'full',
        totalCountSelector: '.ms2compare_count',
        totalListCountSelectorPrefix: '.ms2compare_count_',
        resourcesContainerSelector: '.ms2compare_resources',
        resourceUniqueSelectorPrefix: '.ms2compare_resource_',
        optionsViewSelector: '.ms2compare_options-view',
        activeOptionsViewClass: 'active',
        resourcesTableSelector: '.ms2compare_table',
        resourcesTableRow: '.ms2compare_table-row',
        sameTableRowClass: 'same',
        actionUrl: ms2CompareConfig.actionUrl,
        actionKey: ms2CompareConfig.actionKey,
    }

    ms2Compare.initialize = function () {
        $(document).on('submit', this.config.formSelector, function (e) {
            e.preventDefault();
            ms2Compare._form = $(this);
            let formData = ms2Compare._form.serializeArray();
            let action = ms2Compare._form.find('[name=' + ms2Compare.config.actionKey + ']:visible').val();
            ms2Compare.request(action, formData);
        });
        if ($(this.config.resourcesContainerSelector).length > 0) {
            ms2Compare.resourcesView();
        }
    };

    ms2Compare.request = function (action, data) {
        data.push({
            name: this.config.actionKey,
            value: action
        });
        $.ajax({
            url: this.actionUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                ms2Compare.callbacks[action]['before'].call(ms2Compare);
            },
            success: function (response) {
                switch (response.success) {
                    case true:
                        ms2Compare.callbacks[action]['success'].call(ms2Compare, response);
                        break;
                    default:
                        ms2Compare.callbacks[action]['error'].call(ms2Compare, response);
                        break;
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.error(xhr.responseJSON);
            }
        });
    };

    ms2Compare.callbacks = {
        add: {
            before: function () {
            },
            success: function (response) {
                this._form.addClass(this.config.activeFormClass);
                this.updateTotals(response.data.totals);
                this.message.success(response.message);
            },
        },
        remove: {
            before: function () {
            },
            success: function (response) {
                this._form.removeClass(this.config.activeFormClass);
                this.updateTotals(response.data.totals);
                $(this.config.resourceUniqueSelectorPrefix + response.data.id).delay(100).fadeOut();
                this.message.info(response.message);
                if (response.data.totals.lists[response.data.list] <= 0 && $(this.config.resourcesContainerSelector).length > 0) {
                    location.reload();
                }
            },
        },
        clear: {
            before: function () {
            },
            success: function (response) {
                this.updateTotals(response.data.totals);
                location.reload();
            },
        },
    };

    ms2Compare.resourcesView = function () {
        $(document).on('click', this.config.optionsViewSelector, function (e) {
            e.preventDefault();
            let $table = $(this).parents(ms2Compare.config.resourcesTableSelector);
            let view = $(this).data('view');
            switch (view) {
                case 'all':
                    $table.find(ms2Compare.config.resourcesTableRow + '.' + ms2Compare.config.sameTableRowClass).fadeIn();
                    break;
                case 'diff':
                    $table.find(ms2Compare.config.resourcesTableRow + '.' + ms2Compare.config.sameTableRowClass).fadeOut();
                    break;
            }
        });
    };

    ms2Compare.updateTotals = function (totals) {
        $(this.config.totalCountSelector).html(totals.total_count);
        if (totals.total_count > 0) {
            $(this.config.totalSelector).addClass(this.config.activeTotalClass);
        } else {
            $(this.config.totalSelector).removeClass(this.config.activeTotalClass);
        }
        $.each(totals.lists, function (key, value) {
            $(ms2Compare.config.totalListCountSelectorPrefix + key).html(value);
        });
    };

    ms2Compare.message = {
        success: function (message) {
            alert(message);
        },
        info: function (message) {
            alert(message);
        },
        error: function (message) {
            alert(message);
        },
    };

    $(document).ready(function ($) {
        ms2Compare.initialize();
        let html = $('html');
        html.removeClass('no-js');
        if (!html.hasClass('js')) {
            html.addClass('js');
        }
    });

    window.ms2Compare = ms2Compare;
})(window, document, jQuery, ms2CompareConfig);
