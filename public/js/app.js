$(document).ready(function() {
    $.getJSON('js/messages.json', function(obj) {
        $messages = obj[document.documentElement.lang];
        $table = $('#main').DataTable({
            'columns': [
                { 'data': 'title' },
                { 'data': 'title_ruby' },
                { 'data': 'volume' },
                { 'data': 'authors' },
                { 'data': 'isbn' },
                { 'data': 'jpno' },
                { 'data': 'published_date' },
                { 'data': 'ndl_url' },
            ],
            'columnDefs': [
                { 'visible': false, 'targets': 1 },
                { 'visible': false, 'targets': 5 },
                { 'visible': false, 'targets': 7 },
            ],
            'order': [[ 0, 'asc' ], [ 6, 'asc' ]],
            'lengthMenu': [ 10, 20, 30, 50, 100, 200 ],
            'displayLength': 100,
            'scrollY': true,
            'deferRender': true,
            'ajax': 'list',
            'rowCallback': function(row, data, index) {
                $('td:eq(0)', row).html('<a href="' + data.ndl_url + '" title="' + $messages.rowsAlt + '">' + data.title + '</a>');
            },
            'drawCallback': function(settings) {
                $('.pagination').append('<li class="paginate_button disabled" id="main_delete"><a href="#" id="delete" aria-controls="main" onclick="return false;">' + $messages.delete.label + '</a></li>');
            },
        });

        if (typeof customFunc == 'function') customFunc();
    });

    $.notifyDefaults({
        'placement': {
            'from': 'bottom',
            'align': 'right',
        },
        'mouse_over': 'pause',
    });

    $('#main tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#main_delete').addClass('disabled');
            $('#delete').attr('onclick', 'return false;');
        } else {
            $table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            if ($table.row('.selected').data() != null) {
                $('#main_delete').removeClass('disabled');
                $('#delete').attr('onclick', 'deleteBook(); return false;');
            }
        }
    });

    $('#register').on('submit', function(event) {
        event.preventDefault();

        var $form = $(this);
        $.ajax({
            'url': $form.attr('action'),
            'type': $form.attr('method'),
            'data': $form.serialize(),
            'success': function(result) {
                addSucceeded(result.data.title);
                $table.ajax.reload(null, false);
                $form[0].reset();
            },
            'error': function(result) {
                var f = {
                    404: function() { searchFailed(); },
                    409: function() { addFailed(result.responseJSON.data.title); },
                    422: function() { validationFailed(result.responseJSON.data); },
                };
                f[result.status]();
            },
        });
    });
});

function jumpZXingUrl() {
    var url = location.origin + '/create?code={CODE}&_token=' + document.head.querySelector('meta[name="csrf-token"]').content;
    location.href = 'http://zxing.appspot.com/scan?ret=' + escape(url);
}

function deleteBook() {
    if (window.confirm($messages.confirm)) {
        var bookId = $table.row('.selected').data().id;

        $.ajax({
            'url': 'delete',
            'type': 'POST',
            'data': {
                'id': bookId,
                '_token': document.head.querySelector('meta[name="csrf-token"]').content,
            },
            'success': function(result) {
                $table.row('.selected').remove().draw(false);
                deleteSucceeded();
            },
            'error': function(result) {
                var f = {
                    404: function() { deleteFailed(); },
                    422: function() { validationFailed(result.responseJSON.data); },
                };
                f[result.status]();
            },
        });
    }
}

function addSucceeded(title) {
    $.notify({
        'icon': 'glyphicon glyphicon-ok-sign',
        'title': $messages.add.success.title,
        'message': $messages.add.success.message + title,
    },{
        'type': 'success',
    });
}

function deleteSucceeded() {
    $.notify({
        'icon': 'glyphicon glyphicon-ok-sign',
        'title': $messages.delete.success.title,
        'message': $messages.delete.success.message,
    },{
        'type': 'success',
    });
}

function addFailed(title) {
    $.notify({
        'icon': 'glyphicon glyphicon-warning-sign',
        'title': $messages.add.failure.title,
        'message': $messages.add.failure.message + title,
    },{
        'type': 'danger',
    });
}

function deleteFailed() {
    $.notify({
        'icon': 'glyphicon glyphicon-warning-sign',
        'title': $messages.delete.failure.title,
        'message': $messages.delete.failure.message,
    },{
        'type': 'danger',
    });
}

function searchFailed() {
    $.notify({
        'icon': 'glyphicon glyphicon-exclamation-sign',
        'title': $messages.not_exist.title,
        'message': $messages.not_exist.message,
    },{
        'type': 'warning',
    });
}

function validationFailed(message) {
    $.notify({
        'icon': 'glyphicon glyphicon-exclamation-sign',
        'title': $messages.invalid.title,
        'message': '<p>' + message + '</p>',
    },{
        'type': 'warning',
    });
}
