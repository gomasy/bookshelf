$(document).ready(function() {
    $.getJSON('assets/messages.json', function(obj) {
        lang = document.documentElement.lang;
        $messages = obj[lang];

        if (lang != 'en') {
            $.extend($.fn.dataTable.defaults, {
                language: { url: $messages['datatables']['url'] },
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
                    var bookAry = $table.row('.selected').data();

                    $('#input-title').val(bookAry['title']);
                    $('#input-volume').val(bookAry['volume']);
                    $('#input-authors').val(bookAry['authors']);
                    $('#input-pubdate').val(bookAry['published_date']);
                });
                $('#btn-delete').on('click', function() {
                    if (!isSelected()) return false;
                });
            },
        });

        if (typeof showResult == 'function') showResult();
    });

    $.notifyDefaults({
        placement: {
            from: 'bottom',
            align: 'right',
        },
        mouse_over: 'pause',
    });

    $('#main tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#main_edit').addClass('disabled');
            $('#main_delete').addClass('disabled');
        } else {
            $table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            if ($table.row('.selected').data() != null) {
                $('#main_edit').removeClass('disabled');
                $('#main_delete').removeClass('disabled');
            }
        }
    });

    $('#form-register').submit(function(event) {
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
                    422: function() { validateError(result.responseJSON.code[0]); },
                };
                f[result.status]();
            },
        });
    });

    $('#form-edit').submit(function(event) {
        event.preventDefault();

        var $form = $('#form-edit');
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: generatePostData($form, getSelectedRow($table).id),
            success: function(result) {
                $('#modal-edit').modal('hide');
                $table.ajax.reload(null, false);
                $form[0].reset();
            },
        });
    });

    $('#form-delete').submit(function(event) {
        event.preventDefault();

        var $form = $('#form-delete');
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: generatePostData($form, getSelectedRow($table).id),
            success: function(result) {
                $('#modal-delete').modal('hide');
                $table.row('.selected').remove().draw(false);
                $.notify($messages.delete.success, { type: 'success' });
            },
            error: function(result) {
                var f = {
                    404: function() { $.notify($messages.delete.failure, { type: 'danger' }); },
                    422: function() { validateError(result.responseJSON.id[0]); },
                };
                f[result.status]();
            },
        });
    });

    $('#btn-scan').click(function() {
        var url = location.origin + '/create?code={CODE}&_token=' + document.head.querySelector('meta[name="csrf-token"]').content;
        location.href = 'http://zxing.appspot.com/scan?ret=' + escape(url);
    });
});

function isSelected() {
    return !($('#main_delete').hasClass('disabled'));
}

function getSelectedRow(table) {
    return table.row('.selected').data();
}

function generatePostData(form, id) {
    var data = form.serialize();
    data += '&id=' + id;

    return data;
}

function validateError(message) {
    var $mes = $messages.invalid;
    $mes.message = '<p>' + message + '</p>';
    $.notify($mes, { type: 'warning' });
}
