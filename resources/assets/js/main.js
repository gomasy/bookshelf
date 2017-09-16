$(document).ready(function() {
    var lang = document.documentElement.lang;
    $messages = require('../messages.json')[lang];

    if (typeof showResult == 'function') showResult($.notify, $messages);
    if (lang == 'ja') {
        $.extend($.fn.dataTable.defaults, {
            language: { url: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Japanese.json' },
        });
    }

    var $table, $messages;
    var btnName = [ 'edit', 'delete' ];

    var isSelected = function() {
        return !($('#main_' + btnName[0]).hasClass('disabled'));
    };
    var getSelectedRow = function() {
        return $table.row('.selected').data();
    };
    var sendRequest = function($form, hasId) {
        var data = $form.serialize();
        if (hasId) data += '&id=' + getSelectedRow().id;

        return $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
        });
    };
    var validErr = function(res) {
        var $msg = $messages.invalid;
        $msg.message = '<p>';
        for (key in res.errors) {
            res.errors[key].forEach(function(error) {
                $msg.message += error + '<br>';
            });
        }
        $msg.message += '</p>';

        $.notify($msg, { type: 'warning' });
    };

    $.notifyDefaults({
        alow_dismiss: true,
        newest_on_top: true,
        mouse_over: 'pause',
        placement: {
            from: 'bottom',
            align: 'right',
        },
    });

    $table = $('#main').DataTable({
        columns: [
            { data: 'title' },
            { data: 'volume' },
            { data: 'authors' },
            { data: 'published_date' },
            { data: 'ndl_url' },
        ],
        columnDefs: [
            { visible: false, targets: 4 },
        ],
        order: [[ 0, 'asc' ], [ 3, 'asc' ]],
        lengthMenu: [ 10, 20, 30, 50, 100, 200 ],
        displayLength: 100,
        scrollY: true,
        deferRender: true,
        ajax: 'list',
        rowCallback: function(row, data) {
            $('td:eq(0)', row).html('<a href="' + data.ndl_url + '" title="' + $messages.rowsAlt + '" target="_blank">' + data.title + '</a>');
        },
        drawCallback: function() {
            var btnElem = function(name) {
                return '<li class="paginate_button disabled" id="main_' + name + '"><a href="#" id="btn-' + name + '" data-toggle="modal" data-target="#modal-' + name + '">' + $messages[name].label + '</a></li>';
            };
            btnName.forEach(function(name) {
                $('.pagination').append(btnElem(name));
            });

            $('#btn-edit').on('click', function() {
                if (!isSelected()) return false;

                var obj = getSelectedRow();
                for (var key in obj) $('#input-' + key).val(obj[key]);
            });

            $('#btn-delete').on('click', function() {
                if (!isSelected()) return false;
            });
        },
    });

    $('#main tbody').on('click', 'tr', function() {
        var $row = $(this);
        if ($row.hasClass('selected')) {
            $row.removeClass('selected');
            btnName.forEach(function(name) {
                $('#main_' + name).addClass('disabled');
            });
        } else {
            $('tr.selected').removeClass('selected');
            $row.addClass('selected');
            if ($table.row('.selected').data() != null) {
                btnName.forEach(function(name) {
                    $('#main_' + name).removeClass('disabled');
                });
            }
        }
    });

    $('#form-register').on('submit', function(event) {
        event.preventDefault();
        var $form = $(this);
        var $ajax = sendRequest($form);

        $ajax.done(function(result) {
            $table.row.add(result.data).draw(false);
            $form[0].reset();
            $.notify($messages.add.success, { type: 'success' });
        });

        $ajax.fail(function(result) {
            var f = {
                404: function() { $.notify($messages.not_exist, { type: 'warning' }); },
                409: function() { $.notify($messages.add.failure, { type: 'danger' }); },
                422: function() { validErr(result.responseJSON); },
            };
            f[result.status]();
        });
    });

    $('#form-edit').on('submit', function(event) {
        event.preventDefault();
        var $form = $(this);
        var $ajax = sendRequest($form, true);

        $ajax.done(function(result) {
            $('#modal-edit').modal('hide');
            $table.row('.selected').remove();
            $table.row.add(result.data).draw(false);
            $form[0].reset();
        });

        $ajax.fail(function(result) {
            result.status == 422 && validErr(result.responseJSON);
        });
    });

    $('#form-delete').on('submit', function(event) {
        event.preventDefault();
        var $ajax = sendRequest($(this), true);

        $ajax.done(function(result) {
            $('#modal-delete').modal('hide');
            $table.row('.selected').remove().draw(false);
            $.notify($messages.delete.success, { type: 'success' });
        });

        $ajax.fail(function(result) {
            var f = {
                404: function() { $.notify($messages.delete.failure, { type: 'danger' }); },
                422: function() { validErr(result.responseJSON); },
            };
            f[result.status]();
        });
    });

    $('#btn-scan').on('click', function() {
        var url = location.origin + '/create?code={CODE}&_token=' + document.head.querySelector('meta[name="csrf-token"]').content;
        location.href = 'http://zxing.appspot.com/scan?ret=' + escape(url);
    });
});
