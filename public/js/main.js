$(document).ready(function () {

    $('.select2').select2({
        placeholder: "เลือก...",
        tags: true,
        tokenSeparators: [',', ' '],
        ajax: {
            url: '/attribute',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                var query = {
                    search: params.term
                }
                return query;
            }
        },
        processResults: function (data) {
            return {
                results: data.items
            };
        }
    })

    // set required input
    $('input:required, select:required, textarea:required').each(function () {
        $(this).prev('label').addClass('required');
    })

    // dynamic background
    var getURL = window.location;
    particlesJS.load('particles-js', '/js/particles.json');

    // checkout expiration
    if ($('#expiration').length > 0) {
        var expired_date = $('#expiration').data('date');
        setInterval(function () {
            $.post('ajax/reload_expiration.php', {
                'expired_date': expired_date
            }, function (res) {
                if (res != '') {
                    $('#expiration').html(res);
                } else {
                    location.reload();
                }
            })
        }, 60000)
    }

    // report expiration
    if ($('span.timeleft').length > 0) {
        var array = [];
        var timeleft = $('span.timeleft');
        var i = 0;
        timeleft.each(function () {
            array[i] = $(this).data('timeleft');
            i++;
        })
        setInterval(function () {
            $.post('ajax/reload_expiration.php', {
                'expired_date': array
            }, function (res) {
                res = JSON.parse(res);
                var val = "";
                timeleft.each(function (key, value) {
                    if (res[key]['hour'] >= 0 && res[key]['min'] >= 0) {
                        val = (res[key]['hour'] > 0 ? res[key]['hour'] + 'h ' : '') + (res[key]['min'] > 0 ? res[key]['min'] + 'm ' : '') + 'left';
                        $(this).html(val);
                    } else {
                        $(this).prev('span').removeClass('pending');
                        $(this).prev('span').addClass('expired').attr('data-status', 'expired');
                        $(this).remove();
                    }
                })
            })
        }, 60000)
    }
})

var socket;

$(document).on('click', '#chatbox:not(.active)', function () {
    activateChatbox($(this));
    socket = new WebSocket('ws://localhost:8090');
    socket.onopen = function (e) {
        onOpen(e);
    };
    socket.onmessage = function (e) {
        onMessage(e);
    };
    socket.onerror = function (e) {
        onError(e);
    };
})

$(document).on('keypress', '#txtMessage', function (e) {
    if (e.which == 13) {
        $('#btnSend').trigger('click');
    }
})

$(document).on('click', '#chatbox.active .listbox i.fa-times', function () {
    socket.close();
    $(this).parentsUntil('body', '#chatbox').html('').removeClass('active');
})

$(document).on('click', "#chatbox.active .chatbox i.fa-times", function () {
    $(this).parentsUntil('.container', '.chatbox-wrapper').addClass('hidden');
})

$(document).on('click', '#chatbox.active .listbox .item', function () {
    var chatbox = $('#chatbox .chatbox');
    var id = $(this).data('id');
    var name = $(this).find('.name').html();
    var image = $(this).find('img').attr('src');
    chatbox.find('.name').html(name);
    chatbox.find('#txtTo').val(id);
    chatbox.find('.profile img').attr('src', image);
    chatbox.parent().removeClass('hidden');
})

function activateChatbox(obj) {
    obj.addClass('active');
    setTimeout(function () {
        obj.load('/load_chatbox');
    }, 300);
}

function onOpen(e) {
    setTimeout(function () {
        var user_name = htmlspecialchars($('#txtFrom').html());
        var user_id = htmlspecialchars($('#txtFrom').data('id'));
        var user_image = htmlspecialchars($('.usericon img').attr('src'));
        var jsonSend = JSON.stringify({
            "user_id": user_id,
            "user_name": user_name,
            "user_image": user_image
        });
        socket.send(jsonSend);
    }, 1000);
}

function onMessage(e) {
    var obj = jQuery.parseJSON(e.data);
    var type = obj.type;

    switch (type) {
        case 'connect':
            var user_id = obj.data.user_id;
            $('#txtFrom').attr('data-id', user_id);
            var onlineUsers = obj.data.onlineUsers;
            var html = "";
            var listbox = $('.listbox .list');
            for (key in onlineUsers) {
                html += '<div class="item" data-id="' + key + '">' +
                    '<img src="' + onlineUsers[key]['user_image'] + '">' +
                    '<div class="name">' + onlineUsers[key]['user_name'] + '</div>' +
                    '</div>';
            }
            listbox.html(html);
            break;

        case 'message':
            var chatbox = $('.message-box');
            var mMessage = obj.data.msg;
            writeMessage('<div><span>' + mMessage + '</span></div>');
            chatbox.animate({
                scrollTop: chatbox.prop("scrollHeight")
            });
            break;

        case 'disconnect':
            // var user_id = obj.data.user_id;
            // $('.listbox .list .item[data-id="' + user_id + '"]').remove();
            var onlineUsers = obj.data.onlineUsers;
            var html = "";
            var listbox = $('.listbox .list');
            for (key in onlineUsers) {
                html += '<div class="item" data-id="' + key + '">' +
                    '<img src="' + onlineUsers[key]['user_image'] + '">' +
                    '<div class="name">' + onlineUsers[key]['user_name'] + '</div>' +
                    '</div>';
            }
            listbox.html(html);
            break;

        case 'update':
            var onlineUsers = obj.data.onlineUsers;
            var html = "";
            var listbox = $('.listbox .list');
            for (key in onlineUsers) {
                html += '<div class="item" data-id="' + key + '">' +
                    '<img src="' + onlineUsers[key]['user_image'] + '">' +
                    '<div class="name">' + onlineUsers[key]['user_name'] + '</div>' +
                    '</div>';
            }
            listbox.html(html);
            break;
    }
}

function onError(e) {
    writeMessage('<span style="color: red;">Error!!</span> ' + e.data);
}

function doSend() {
    var msg = htmlspecialchars($('#txtMessage').val());
    var txtTo = htmlspecialchars($('#txtTo').val());
    var chatbox = $('.message-box');
    var strMessage = '<div class="self"><span>' + msg + '</span></div>';
    var jsonSend = JSON.stringify({
        "toUser": txtTo,
        "mMessage": msg
    });
    socket.send(jsonSend);
    writeMessage(strMessage);

    chatbox.animate({
        scrollTop: chatbox.prop("scrollHeight")
    });
    $('#txtMessage').val('');
    $('#txtMessage').focus();
}

function writeMessage(message) {
    $('.message-box').append(message);
}

function htmlspecialchars(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

$(document).on('click', '#btnSend', function () {
    doSend();
});

$(document).on('click', '.kt-menu__link', function (e) {
    e.preventDefault();
    var nav_sub = $(this).parent().find('.kt-menu__nav__sub');
    if (nav_sub.length > 0) {
        var item = nav_sub.find('.kt-menu__item');
        var height = item.height();
        var length = item.length;
        var active = (nav_sub.hasClass('active') ? 0 : 1);
        var value = (((height + 13) * length) + 15) * active;
        $(this).parent().find('.kt-menu__nav__sub').toggleClass('active').css('height', value);
    } else {
        var url = $(this).attr('href');
        $(location).attr('href', url);
    }
})

$(document).on('select2:select', '.select2', function (e) {

    $.ajax({
        url: '/attribute/create',
        type: 'get',
        data: {
            'data': data
        }
    });

    var parent = $(this).parentsUntil('.productlist', '.detail');
    var data = e.params.data.text;
    var attr_len = parent.find('.select2-selection__rendered li').length;
    var detail_len = $('.orderlist.price .detail').length;
    var value = $('.select2 option:nth-child(' + attr_len + ')').val();

    $('.select2 option[data-select2-tag="true"]').remove();

    if (attr_len <= detail_len) {
        $('.orderlist.price .detail:nth-child(' + attr_len + ') .attr_data').val(data);
        $('.orderlist.price .detail:nth-child(' + attr_len + ') .attr_val').val(value);
    } else {
        if (parent.find('.select2-selection__choice__display').length > 1) {
            parent.find('.add').trigger('click', {
                'target': 'orderlist',
                'data': data,
                'value': value,
                'where': attr_len
            });
        } else {
            $('.orderlist.price .detail:nth-child(' + attr_len + ') .attr_data').val(data);
            $('.orderlist.price .detail:nth-child(' + attr_len + ') .attr_val').val(value);
        }
    }
})

$(document).on('select2:unselect', '.select2', function (e) {
    var parent = $(this).parentsUntil('.productlist', '.detail');
    var data = e.params.data.text;
    var value = e.params.data.id;
    var where = $('.orderlist.price .detail .attr_data[value="' + data + '"]').parentsUntil('.orderlist', '.detail').attr('data-object');

    $('.select2 option[data-select2-tag="true"]').remove();
    $('.select2 option[value="' + value + '"]').remove();

    if (parent.find('.select2-selection__choice__display').length > 0) {
        parent.find('.remove').trigger('click', {
            'target': 'orderlist',
            'data': data,
            'where': where
        });
    } else {
        $('.attr_data, .attr_val').val('');
    }
})

$(document).on('click', '#kt_aside_toggler', function () {
    $('body').toggleClass('kt-aside--minimize');
})

$(document).on('click', '#exportExcel', function () {
    var from = $('input[name="filter[from]"]');
    var to = $('input[name="filter[to]"]');
    var method = "exported";
    var object = "file";

    from.removeClass('invalid');
    to.removeClass('invalid');

    if (from.val() == '') {
        from.addClass('invalid');
    }
    if (to.val() == '') {
        to.addClass('invalid');
    }

    if ($('input.invalid').length == 0) {
        $.post('ajax/export_iframe.php?METHOD=' + method + '&OBJECT=' + object, {
            "filter": {
                "from": from.val(),
                "to": to.val()
            }
        }, function (res) {
            displayErrorMessage('Exported');
            $('body').append(res);
        })
    } else {
        displayErrorMessage('Exporting dates are required', 1);
    }
})

$(document).on('change', 'input[data-name="SKU"], input[data-name="NAME"], input[data-name="PRICE"], input[data-name="AMOUNT"], input[name="SHIPPING[COST]"], select[name="SHIPPING[TYPE]"], input[name="PAYMENT"], input[name="SEPARATE[COST]"], select[name="SEPARATE[TYPE]"]', function () {
    calculateOrder();
})

$(document).on('change', 'select[name="SHIPPING[TYPE]"]', function () {
    var type = $(this).val();
    var cost = $('input[name="SHIPPING[COST]"]');
    cost.val('');
    if (type == "Free") {
        cost.removeAttr('required');
        cost.attr('disabled', 'disabled');
        cost.prev('label').removeClass('required');
    } else {
        cost.attr('required', 'required');
        cost.removeAttr('disabled');
        cost.prev('label').addClass('required');
    }
})

$(document).on('click', 'button[id="submit"]', function () {
    $('#submitForm input, #submitForm select, #submitForm textarea').removeClass('invalid');
    $('#submitForm input:required:not(disabled), #submitForm select:required, #submitForm textarea:required').each(function () {
        if ($(this).val() == "") {
            $(this).addClass('invalid');
        }
    })

    // $('input:disabled').each(function() {
    //   if($(this).hasClass('invalid')) {
    //     $(this).removeClass('invalid');
    //   }
    // })

    // validate email
    // var email = $('input[name="CUSTOMER[EMAIL]"]');
    // var re = /\S+@\S+\.\S+/;
    // if (email.val() != '' && !re.test(email.val())) {
    //     email.addClass('invalid');
    // } else {
    //     email.removeClass('invalid');
    // }

    // validate phone
    // var phone = $('input[name="CUSTOMER[PHONE]"]');
    // if (phone.val().length != 10) {
    //     phone.addClass('invalid');
    //     // $('<span class="invalid">กรุณาตรวจสอบจำนวนหมายเลขโทรศัพท์</span>').insertAfter(phone.prev('label'));
    // } else {
    //     phone.removeClass('invalid');
    //     // phone.prev('span').remove();
    // }

    if ($('#submitForm input.invalid').length == 0) {
        // calculateOrder();
        $('form[name="SubmitForm"]').submit();
    } else {
        $('#submitForm input.invalid').focus();
        // displayErrorMessage('Required datas haven\'t entered yet', 1);
    }
    return false;
})

$(document).on('click', 'button#clear', function () {
    $('input, textarea').val('');
})

$(document).on('click', '.add', function (e, args) {
    if (args == undefined) {
        target = $(this).attr('data-target');
        data = '';
        value = '';
        where = '';
    } else {
        target = args.target;
        data = args.data;
        value = args.value;
        where = args.where;
    }

    var detail = $('.' + target + ' > .detail:last-child').clone();
    var number = eval(detail.attr('data-object'));
    var keyval = number - 1;
    var recent = number + 1;

    var image = $(detail).find('.image-input');
    if (image.length > 0) {
        image.attr('src', '/images/no-image.jpg');

        $(image).each(function () {
            id = $(this).attr('id');
            label = $(this).parent();
            f = label.attr('for');
            if (id != undefined && f != undefined) {
                id = id.replace(keyval, number);
                $(this).attr('id', id);
                f = f.replace(keyval, number);
                label.attr('for', f);
            }
        })
    }

    var input = detail.find('input, select');
    $(input).each(function () {
        id = $(this).attr('id');
        preview = $(this).attr('data-preview');
        if (id != undefined && preview != undefined) {
            id = id.replace(keyval, number);
            preview = preview.replace(keyval, number);
            $(this).attr('id', id);
            $(this).attr('data-preview', preview);
        }

        var name = $(this).attr('name');
        if (name != undefined) {
            name = name.replace(keyval, number);
            $(this).attr('name', name);
            $(this).val('');
        }
    })

    // detail.find('span.select2').remove();
    // detail.find('select.select2 option:not([value=""])').remove();
    // detail.find('select.select2').select2('destroy');

    detail.attr('data-object', recent);
    detail.find('.attr_data').val(data);
    detail.find('.attr_val').val(value);
    var orderlist = $('.' + target);
    $.each(detail, function (key, index) {
        $(this).appendTo(orderlist[key]);
    })

    $('.select2').select2({
        placeholder: "เลือก...",
        tags: true,
        tokenSeparators: [',', ' '],
        ajax: {
            url: '/attribute',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                var query = {
                    search: params.term
                }
                return query;
            }
        },
        processResults: function (data) {
            return {
                results: data.items
            };
        }
    })

    var width = $('span.select2:first').width();
    $('span.select2:last').css('width', width);
})

$(document).on('click', '.remove', function (e, args) {
    if (args == undefined) {
        target = $(this).attr('data-target');
        data = '';
        where = $(this).parent().data('object');
    } else {
        target = args.target;
        data = args.data;
        where = args.where;
    }

    var orderlist = $('.' + target);
    $.each(orderlist, function () {
        var len = $(this).find('.detail').length;
        if (len > 1) {
            $(this).find('[data-object="' + where + '"]').remove();
        }
    })

    $.each(orderlist, function () {
        var i = 1;
        var detail = $(this).children();
        detail.each(function () {
            $(this).attr('data-object', i);
            i++;
        })
    })
})

$(document).on('change', '.file-input', function () {
    var preview = $(this).attr('data-preview');
    $('#' + preview).attr('src', window.URL.createObjectURL(this.files[0]));
})

$(document).on('click', '.seemore', function () {
    $('.popup .detail').toggleClass('show');
    displayInventory();
})

$(document).on('click', 'textarea[data-name="MESSAGE"]', function () {
    if ($(this).val() != '') {
        $(this).select();
        document.execCommand("copy");
        $(this).blur();
        displayErrorMessage('Copied to Clipboard');
    }
})

$(document).on('click', '.clipboard', function () {
    var $this = $(this);
    var order_id = $this.data('id');
    $.post('ajax/clipboard.php', {
        'order_id': order_id
    }, function (res) {
        res = JSON.parse(res);
        var error = (res.response == 'error') ? 1 : 0;
        var message = (res.response == 'error') ? 'This order wasn\'t able to be copied' : 'Copied to clipboard';
        var textarea = $('<textarea>');
        $this.append(textarea);
        textarea.attr('data-name', 'MESSAGE');
        textarea.val(res.message);
        textarea.select();
        document.execCommand("copy");
        textarea.remove();
        displayErrorMessage(message, error);
    })
})

$(document).on('click', 'input[name="checkall"]', function () {
    if ($(this).is(':checked')) {
        $('input[name="checkthis"]').prop('checked', 1);
    } else {
        $('input[name="checkthis"]').prop('checked', 0);
    }
})

$(document).on('click', '.print', function () {
    var checkthis = $('input[name="checkthis"]:checked');
    if (checkthis.length > 0) {
        var id = [];
        var i = 0;
        checkthis.each(function () {
            id[i] = $(this).data('id');
            i++;
        })
    } else {
        var id = '';
        id = $(this).data('id');
    }
    $.post('ajax/print_order.php', {
        'order_id': id
    }, function (res) {
        printWindow = window.open('');
        printWindow.document.write(res);
        printWindow.print();
    })
})

$(document).on('click', '#kt_header_mobile_toggler', function () {
    $('.kt-aside').toggleClass('kt-aside--on');
})

$(document).on('click', 'input[name="PAYMENT"]', function () {
    $('input[name="PAYMENT"]').prop('checked', 0);
    $('input[name="PAYMENT"]').parent('label').removeClass('active');
    $('.select-payment-detail').removeClass('active');
    $('.select-payment-detail').find('label').removeClass('required');
    $('.select-payment-detail').find('input').removeAttr('required');
    $('.select-payment-detail').find('input').removeClass('invalid');
    $('.select-payment-detail').find('select').removeAttr('required');
    $('.select-payment-detail').find('select').removeClass('invalid');
    $(this).prop('checked', 1);
    $(this).parent('label').addClass('active');

    var value = $('input[name="PAYMENT"]:checked').val();
    $('.select-payment-detail[data-payment="' + value + '"').addClass('active');
    $('.select-payment-detail[data-payment="' + value + '"').find('label').addClass('required');
    $('.select-payment-detail[data-payment="' + value + '"').find('input').attr('required', 'required');
    $('.select-payment-detail[data-payment="' + value + '"').find('select').attr('required', 'required');
})

function calculateOrder() {
    var total = 0;
    var total_amount = 0;
    var inventory = $('#inventory');
    var detail = $('.orderlist .detail');
    var html = '';
    inventory.html('');
    for (i = 0; i < detail.length; i++) {
        var name = detail.find('input[name="DETAIL[' + i + '][NAME]"]').val();
        var price = detail.find('input[name="DETAIL[' + i + '][PRICE]"]').val();
        var amount = detail.find('input[name="DETAIL[' + i + '][AMOUNT]"]').val();
        price = eval(price != '' ? price : 0);
        amount = eval(amount != '' ? amount : 0);

        total = total + (price * amount);
        total_amount = total_amount + amount;

        html = html + '<div class="row"><div class="col-4">' + name + '</div><div class="col-4 text-right">' + amount + ' ชิ้น</div><div class="col-4 text-right">' + price + ' บาท</div></div>';
    }

    var shipping_type = $('select[name="SHIPPING[TYPE]"]').val();
    var shipping = $('input[name="SHIPPING[COST]"]').val();
    if (shipping != '' && shipping != 0 && shipping_type != '') {
        total = total + eval(shipping);
        html = html + '<div class="row"><div class="col-8">ค่าส่ง ' + shipping_type + '</div><div class="col-4 text-right">' + shipping + ' บาท</div></div>';
    } else if (shipping_type == 'free') {
        html = html + '<div class="row"><div class="col-8">ค่าส่ง</div><div class="col-4 text-right">ฟรี</div></div>';
    }

    if ($('.select-payment-detail.active[data-payment="2"]').length > 0) {
        var separate_type = $('select[name="SEPARATE[TYPE]"]').val();
        var separate = $('input[name="SEPARATE[COST]"').val();
        if (separate != '' && separate != 0 && separate_type != '') {
            total = total - eval(separate);
            html = html + '<div class="row"><div class="col-8">แยกชำระ เป็น' + separate_type + '</div><div class="col-4 text-right">' + separate + ' บาท</div></div>';
        }
    }

    $('input[data-name="TOTAL"]').val(total);
    $('input[data-name="TOTAL_AMOUNT"]').val(total_amount);

    inventory.append(html);
    displayInventory();
}

function displayInventory() {
    var detail = $('.popup .detail');
    var len = detail.lenght;
    var pos = detail.height();
    var padding = 40;
    pos = (pos - padding) * -1;
    if ($('.popup .detail').hasClass('show')) {
        $('.popup .detail').css('top', pos + 'px');
    } else {
        $('.popup .detail').css('top', '0px');
    }
}

function displayErrorMessage(message, error = 0) {
    var errorMessage = $('#errorMessage');
    var response = (error) ? 'error' : '';
    errorMessage.children().html(message);
    errorMessage.children().addClass(response);
    errorMessage.addClass('show in');
    setTimeout(function () {
        errorMessage.removeClass('in');
        setTimeout(function () {
            errorMessage.removeClass('show');
            errorMessage.children().removeClass('error');
        }, 600);
    }, 3000);
}

$.fn.digits = function () {
    return this.each(function () {
        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    })
}
