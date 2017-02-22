/*$("#slider1").slick({
    useTransform:false,
    variableWidth:true,
    dots: true,
    infinite: true,
    speed: 50,
    fade: true,
    cssEase: 'linear'
});*/
/*
$('.field-autotable-imagefiles').on('fileloaded', function(event, file, previewId, index, reader) {
    console.log("fileloaded");});*/
// $('#w0').fileinput({initialCaption : '123'});
    console.log("fileloaded");
    

$("#autotable-name,#autotable-code,#autotable-toplivo, #autotable-god_vypuska, #autotable-kpp, #autotable-benzin, #autotable-kerosin").change(function () {
    fill_change();
});



function fill_change(s) {

    $("#autotable-descr").val(
        Change("autotable-name", "этаж",'yghj')+
        // Change("autotable-kpp",'_blank_','gvbh') +
        // Change("autotable-toplivo", "из") +
        // temp_str +
        // Change("autotable-code", "dfgdfg") +
        Change("autotable-benzin") +
        Change("autotable-god_vypuska",'dfgfg','125') +
        //
        Change("autotable-kerosin", "")
    );
    var temp_str = $("#autotable-descr").val().trim();
    var last_char = temp_str.charAt(temp_str.length - 1);

    while (last_char === ',') {
        temp_str  = temp_str.slice(0, -1);
        last_char = temp_str.charAt(temp_str.length - 1);
    }
    $("#autotable-descr").val(temp_str);
};

function Change(elem, label_text, last_text) {
    last_text  = typeof last_text == 'undefined' ? '' : ' ' + last_text;
    label_text = typeof label_text == 'undefined' ? '' : label_text;
    var return_text = "", elem_val, elem_label;
    if ($('#' + elem).is('#' + elem)) {
        if ($('#' + elem).get(0).tagName === 'INPUT' || $('#' + elem).get(0).tagName === 'TEXTAREA') {
            if ($('#' + elem).prop("type") === 'text' || $('#' + elem).prop("type") === 'textarea') {
                elem_val = $('#' + elem).val().trim();
                elem_label = $('#' + elem).siblings('label').text();
            }
            if ($('#' + elem).prop("type") === 'checkbox') {
                elem_label = $('#' + elem).parent().text().replace(/(\r\n|\n|\r)/gm, " ").trim();
                elem_val = $('#' + elem).prop("checked");
            }
        }
        if ($('#' + elem).get(0).tagName === 'SELECT') {
            elem_val = ($('#' + elem + ' option:selected').val() > 0 ? $('#' + elem + ' option:selected').text() : '');
            elem_label = $('#' + elem).siblings('label').text();
        }
    } else {
        console.log('неверный id ' + elem);
        elem_val = false;
    }
    if (elem_val === true || elem_val != '') {
        elem_val = (typeof elem_val === 'boolean' ? '' : elem_val);
        if (label_text.trim() === '') {
            label_text = elem_label;
        } else if(label_text.trim() === '_blank_') {
            label_text ='';
        } else {
            label_text = label_text;
        }
        if(elem_val!==''){
            label_text += ': '
        }
        return_text = label_text + elem_val + last_text;
    } else {
        return_text = '';
    }
    if (return_text !== '') {
        return_text += ', ';
    }
    return return_text;
}
  


