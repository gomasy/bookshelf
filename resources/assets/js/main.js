$(document).ready(function() {
    var $table, $messages;
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

        if (lang != 'en') {
            $.extend($.fn.dataTable.defaults, {
                language: { url: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Japanese.json' },
            });
        }

        $table = $('#main').DataTable({
            columns: [
                { data: 'title' },
                { data: 'volume' },
                { data: 'authors' },
                { data: 'isbn' },
                { data: 'jpno' },
                { data: 'published_date' },
                { data: 'ndl_url' },
            ],
            columnDefs: [
                { visible: false, targets: 3 },
                { visible: false, targets: 4 },
                { visible: false, targets: 6 },
            ],
            order: [[ 0, 'asc' ], [ 5, 'asc' ]],
            lengthMenu: [ 10, 20, 30, 50, 100, 200 ],
            displayLength: 100,
            scrollY: true,
            deferRender: true,
            ajax: 'list',
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html('<a href="' + data.ndl_url + '" title="' + $messages.rowsAlt + '">' + data.title + '</a>');
            },
            drawCallback: function(settings) {
                $('.pagination').append('<li class="paginate_button disabled" id="main_edit"><a href="#" id="btn-edit" data-toggle="modal" data-target="#modal-edit">' + $messages.edit.label + '</a></li>');
                $('.pagination').append('<li class="paginate_button disabled" id="main_delete"><a href="#" id="btn-delete" data-toggle="modal" data-target="#modal-delete">' + $messages.delete.label + '</a></li>');
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
            $('#main_edit').addClass('disabled');
            $('#main_delete').addClass('disabled');
        } else {
            $table.$('tr.selected').removeClass('selected');
            $rows.addClass('selected');

            if ($table.row('.selected').data() != null) {
                $('#main_edit').removeClass('disabled');
                $('#main_delete').removeClass('disabled');
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
                $.notify($messages.add.success, { type: 'success' });
                $table.ajax.reload(null, false);
                $form[0].reset();
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
                $table.ajax.reload(null, false);
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
