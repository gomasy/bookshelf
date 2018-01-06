import 'bootstrap-notify';
import 'datatables.net-bs';

import '../js/core.js';
import '../scss/dashboard.scss';

const lang = document.documentElement.lang;
const messages = require('../messages.json')[lang];
const btnName = [ 'edit', 'delete' ];
let $table;

if (lang === 'ja') {
    $.extend($.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Japanese.json'
        },
    });
}

$.notifyDefaults({
    alow_dismiss: true,
    newest_on_top: true,
    mouse_over: 'pause',
    placement: {
        from: 'bottom',
        align: 'right',
    },
});

const isSelected = () => {
    return !($('#main_' + btnName[0]).hasClass('disabled'));
};

const getSelectedRow = () => {
    return $table.row('.selected').data();
};

const sendRequest = ($form, hasId) => {
    let data = $form.serialize();
    if (hasId) data += '&id=' + getSelectedRow().id;

    return $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
    });
};

const showFormatError = res => {
    const msg = messages.invalid;
    msg.message = '<p>';
    for (const key in res.errors) {
        res.errors[key].forEach(error => {
            msg.message += error + '<br>';
        });
    }
    msg.message += '</p>';

    $.notify(msg, { type: 'warning' });
};

const getRowLinks = (title, url) => {
    return '<a href="' + url + '" title="' + messages.rowsAlt + '" target="_blank">' + title + '</a>';
};

const getButtonElement = (name, label) => {
    return '<li class="paginate_button disabled" id="main_' + name + '"><a href="#" id="btn-' + name + '" data-toggle="modal" data-target="#modal-' + name + '">' + label + '</a></li>';
};

$(document).ready(() => {
    if (typeof showResult === 'function') showResult($.notify, messages);

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
        rowCallback: (row, data) => {
            $('td:eq(0)', row).html(getRowLinks(data.title, data.ndl_url));
        },
        drawCallback: () => {
            btnName.forEach(name => {
                $('.pagination').append(getButtonElement(name, messages[name].label));
            });

            $('#btn-edit').on('click', () => {
                if (!isSelected()) return false;

                const obj = getSelectedRow();
                for (const key in obj) $('#input-' + key).val(obj[key]);
            });

            $('#btn-delete').on('click', () => {
                if (!isSelected()) return false;
            });
        },
    });

    $('#main tbody').on('click', 'tr', event => {
        const $row = $(event.currentTarget);

        if ($row.hasClass('selected')) {
            $row.removeClass('selected');
            btnName.forEach(name => {
                $('#main_' + name).addClass('disabled');
            });
        } else {
            $('tr.selected').removeClass('selected');
            $row.addClass('selected');
            if ($table.row('.selected').data() !== undefined) {
                btnName.forEach(name => {
                    $('#main_' + name).removeClass('disabled');
                });
            }
        }
    });

    const $registerForm = $('#register').on('submit', event => {
        event.preventDefault();
        const $req = sendRequest($registerForm);

        $req.done(result => {
            $table.row.add(result).draw(false);
            $registerForm[0].reset();
            $.notify(messages.add.success, { type: 'success' });
        });

        $req.fail(result => {
            const f = {
                404: () => {
                    $.notify(messages.not_exist, { type: 'warning' });
                },
                409: () => {
                    $.notify(messages.add.failure, { type: 'danger' });
                },
                422: () => {
                    showFormatError(result.responseJSON);
                },
                500: () => {
                    $.notify(messages.internal_error, { type: 'danger' });
                },
            };
            f[result.status]();
        });
    });

    const $editForm = $('#form-edit').on('submit', event => {
        event.preventDefault();
        const $req = sendRequest($editForm, true);

        $req.done(result => {
            $('#modal-edit').modal('hide');
            $table.row('.selected').remove();
            $table.row.add(result).draw(false);
            $form[0].reset();
        });

        $req.fail(result => {
            const f = {
                422: () => {
                    showFormatError(result.responseJSON);
                },
                500: () => {
                    $.notify(messages.internal_error, { type: 'danger' });
                },
            };
            f[result.status]();
        });
    });

    const $deleteForm = $('#form-delete').on('submit', event => {
        event.preventDefault();
        const $req = sendRequest($deleteForm, true);

        $req.done(result => {
            $('#modal-delete').modal('hide');
            $table.row('.selected').remove().draw(false);
            $.notify(messages.delete.success, { type: 'success' });
        });

        $req.fail(result => {
            const f = {
                404: () => {
                    $.notify(messages.delete.failure, { type: 'danger' });
                },
                422: () => {
                    showFormatError(result.responseJSON);
                },
                500: () => {
                    $.notify(message.internal_error, { type: 'danger' });
                },
            };
            f[result.status]();
        });
    });

    $('#btn-scan').on('click', () => {
        const url = location.origin + '/create?code={CODE}&_token=' + document.head.querySelector('meta[name="csrf-token"]').content;
        location.href = 'http://zxing.appspot.com/scan?ret=' + escape(url);
    });
});
