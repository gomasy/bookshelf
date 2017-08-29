$(document).ready(function() {
    var $table, $messages;
    var btnName = [ 'edit', 'delete' ];

    var isSelected = function() {
        return !($('#main_delete').hasClass('disabled'));
    };
    var getSelectedRow = function() {
        return $table.row('.selected').data();
    };
    var createPostData = function(form) {
        var data = form.serialize();
        data += '&id=' + getSelectedRow().id;

        return data;
    };
    var validErr = function(context) {
        var $msg = $messages.invalid;
        $msg.message = '<p>' + context + '</p>';
        $.notify($msg, { type: 'warning' });
    };

    $.notifyDefaults({
        placement: {
            from: 'bottom',
            align: 'right',
        },
        mouse_over: 'pause',
    });

    $.getJSON('assets/messages.json', function(obj) {
        var lang = document.documentElement.lang;
        $messages = obj[lang];

        if (lang == 'ja') {
            $.extend($.fn.dataTable.defaults, {
                language: { url: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Japanese.json' },
            });
        }

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
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html('<a href="' + data.ndl_url + '" title="' + $messages.rowsAlt + '" target="_blank">' + data.title + '</a>');
            },
            drawCallback: function(settings) {
                var btnElem = function(name) {
                    return '<li class="paginate_button disabled" id="main_' + name + '"><a href="#" id="btn-' + name + '" data-toggle="modal" data-target="#modal-' + name + '">' + eval('$messages.' + name + '.label') + '</a></li>';
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

        if (typeof showResult == 'function') showResult();
    });

    $('#main tbody').on('click', 'tr', function() {
        var $rows = $(this);

        if ($rows.hasClass('selected')) {
            $rows.removeClass('selected');

            btnName.forEach(function(name) {
                $('#main_' + name).addClass('disabled');
            });
        } else {
            $table.$('tr.selected').removeClass('selected');
            $rows.addClass('selected');

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
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            success: function(result) {
                $table.row.add(result.data).draw(false);
                $form[0].reset();
                $.notify($messages.add.success, { type: 'success' });
            },
            error: function(result) {
                var f = {
                    404: function() { $.notify($messages.not_exist, { type: 'warning' }); },
                    409: function() { $.notify($messages.add.failure, { type: 'danger' }); },
                    422: function() { validErr(result.responseJSON.code[0]); },
                };
                f[result.status]();
            },
        });
    });

    $('#form-edit').on('submit', function(event) {
        event.preventDefault();

        var $form = $(this);
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: createPostData($form),
            success: function(result) {
                $('#modal-edit').modal('hide');
                $table.row('.selected').remove();
                $table.row.add(result.data).draw(false);
                $form[0].reset();
            },
        });
    });

    $('#form-delete').on('submit', function(event) {
        event.preventDefault();

        var $form = $(this);
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: createPostData($form),
            success: function(result) {
                $('#modal-delete').modal('hide');
                $table.row('.selected').remove().draw(false);
                $.notify($messages.delete.success, { type: 'success' });
            },
            error: function(result) {
                var f = {
                    404: function() { $.notify($messages.delete.failure, { type: 'danger' }); },
                    422: function() { validErr(result.responseJSON.id[0]); },
                };
                f[result.status]();
            },
        });
    });

    $('#btn-scan').on('click', function() {
        var url = location.origin + '/create?code={CODE}&_token=' + document.head.querySelector('meta[name="csrf-token"]').content;
        location.href = 'http://zxing.appspot.com/scan?ret=' + escape(url);
    });
});
