'use strict';

(function (window, document, $, ms2Compare) {
    let _this = ms2Compare;

    $.extend(_this, {
        _form: null,
    });

    _this.options = {
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
    }

    _this.initialize = function () {
        $(document).on('submit', this.options.formSelector, function (e) {
            e.preventDefault();
            _this._form = $(this);
            let formData = _this._form.serializeArray();
            let action = _this._form.find('[name=' + _this.actionKey + ']:visible').val();
            _this.request(action, formData);
        });
        if ($(_this.options.resourcesContainerSelector).length > 0) {
            _this.resourcesView();
        }
    };

    _this.request = function (action, data) {
        data.push({
            name: _this.actionKey,
            value: action
        });
        $.ajax({
            url: _this.actionUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                _this.callbacks[action]['before'].call(_this);
            },
            success: function (response) {
                switch (response.success) {
                    case true:
                        _this.callbacks[action]['success'].call(_this, response);
                        break;
                    default:
                        _this.callbacks[action]['error'].call(_this, response);
                        break;
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.error(xhr.responseJSON);
            }
        });
    };

    _this.callbacks = {
        add: {
            before: function () {
            },
            success: function (response) {
                _this._form.addClass(_this.options.activeFormClass);
                _this.updateTotals(response.data.totals);
                _this.message.success(response.message);
            },
        },
        remove: {
            before: function () {
            },
            success: function (response) {
                _this._form.removeClass(_this.options.activeFormClass);
                _this.updateTotals(response.data.totals);
                $(_this.options.resourceUniqueSelectorPrefix + response.data.id).delay(50).fadeOut();
                _this.message.info(response.message);
                if (response.data.totals.lists[response.data.list] <= 0 && $(_this.options.resourcesContainerSelector).length > 0) {
                    location.reload();
                }
            },
        },
        clear: {
            before: function () {
            },
            success: function (response) {
                _this.updateTotals(response.data.totals);
                location.reload();
            },
        },
    };

    _this.resourcesView = function () {
        $(document).on('click', _this.options.optionsViewSelector, function (e) {
            e.preventDefault();
            let $table = $(this).parents(_this.options.resourcesTableSelector);
            let view = $(this).data('view');
            switch (view) {
                case 'all':
                    $table.find(_this.options.resourcesTableRow + '.' + _this.options.sameTableRowClass).fadeIn(200);
                    break;
                case 'diff':
                    $table.find(_this.options.resourcesTableRow + '.' + _this.options.sameTableRowClass).fadeOut(200);
                    break;
            }
        });
    };

    _this.updateTotals = function (totals) {
        $(_this.options.totalCountSelector).html(totals.total_count);
        if (totals.total_count > 0) {
            $(_this.options.totalSelector).addClass(_this.options.activeTotalClass);
        } else {
            $(_this.options.totalSelector).removeClass(_this.options.activeTotalClass);
        }
        $.each(totals.lists, function (key, value) {
            $(_this.options.totalListCountSelectorPrefix + key).html(value);
        });
    };

    _this.message = {
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
        _this.initialize();
        let html = $('html');
        html.removeClass('no-js');
        if (!html.hasClass('js')) {
            html.addClass('js');
        }
    });

    window.ms2Compare = _this;
})(window, document, jQuery, ms2Compare);
