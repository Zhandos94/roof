/**
 * Created by BADI on 23.11.2016.
 */

var mark_text = 'auto_input ',
    date = new Date(),
    apa_end_day = (date.getDate() - 1).toString()[1] ? (date.getDate() - 1).toString() : "0" + (date.getDate() - 1).toString(),

    day = date.getDate().toString()[1] ? date.getDate().toString() : "0" + date.getDate().toString(),
    month = (date.getMonth() + 1).toString()[1] ? (date.getMonth() + 1).toString() : "0" + (date.getMonth() + 1).toString(),
    year = (date.getFullYear() + 1).toString(),
    hours = date.getHours().toString() < 10 ? "0" + date.getHours().toString() : date.getHours().toString(),
    end_hh = (date.getHours() + 1).toString()[1] ? (date.getHours() + 1).toString() : "0" + (date.getHours() + 1).toString();

var mm = date.getMinutes() + 20;
if (mm > 60) {
    mm = (mm - 60).toString();
    mm = mm[1] ? mm : "0" + mm;
    hours = (date.getHours() + 1).toString();
    hours = hours[1] ? hours : "0" + hours;
    end_hh = (date.getHours() + 2).toString()[1] ? (date.getHours() + 2).toString() : "0" + (date.getHours() + 2).toString();

}

function autoInput() {
    getBank();

    $('#auction-sale_type').val(1).trigger('change');
    $('#auction-region_id').val(random(16, 1)).trigger('change');
    $('#lot-location').val(mark_text + 'Dostyk 15');
    $('#auction-start_sum').val(random(5000, 100));
    $('#auction-increment').val(random(5000, 100));
    $('#auction-auction_type').val(1).trigger('change');
    $('#auction-apa_end_datetime').val(year + '-' + month + '-' + apa_end_day + ' ' + hours + ':' + mm);
    $('#auction-start_date').val(year + '-' + month + '-' + day + ' ' + hours + ':' + mm);
    $('#auction-end_date').val(year + '-' + month + '-' + day + ' ' + end_hh + ':' + mm);
    $('#auction-colcat_id').val(9).trigger('change');

    wait_container_load();

    // console.log(year + '-' + month + '-' + apa_end_day + ' ' + hours + ':' + mm);
    // console.log(year + '-' + month + '-' + day + ' ' + hours + ':' + mm);
    // console.log(year + '-' + month + '-' + day + ' ' + end_hh + ':' + mm);
}

function getBank() {
    var id = random(49, 1);
    $.ajax({
        method: 'GET',
        data: {id: id},
        url: '/refs/get/banks',
        success: function (response) {
            var leonor = $('#auction-lienor_id');
            leonor
                .empty()
                .append('<option selected value=' + response.results.id + '>' + response.results.text + '</option>');
            leonor.trigger('change');
        }
    });
}

function makeText(max) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < max; i++)
        text += possible.charAt(random(max, 1));
    return text;
}

function random(max, min) {
    return (Math.floor(Math.random() * max) + min);
}

function wait_container_load() {
    setTimeout(function () {
        setTimeout(function () {
            $('#auction-title_ru').trigger('click');
            $('#auction-name_ru').trigger('click');
        }, 1000);

        $('#lotgoods-name').val(mark_text + 'test name of lot ' + makeText(10));
        $('#lotgoods-description').val(mark_text + makeText(10));
    }, 2000)
}


/***/


$('#click_random').click(function(e){
    randomAutoInput();
});


/***/


var attr_form = $('#attr-form').find('select').add('input');
var tagInput;
attr_form = $.makeArray(attr_form);
function randomAutoInput() {
    attr_form.forEach(function(elem) {

        autoInput(elem);
        //autoInput($(elem).attr('id'));
        
    });

}


function autoInput(elem) {

    switch (elem.tagName) {
        case 'INPUT':
            if ($(elem).prop('type') == 'text') {
                $(elem).val(makeText(20));
            } else if ($(elem).prop('type') == 'checkbox') {
                $(elem).prop('checked', randomChecked(30));
            }
            break;
        case 'SELECT': 
            var option_length = $(elem).find('option').length;
            var random_val = random(option_length, 1);
            $(elem).val(random_val);
            break;
    }

}


function randomChecked(max) {
    var random_digit = random(max, 15);
    if (max > random_digit) {
        return true;
    }
    return false;
}

