function showErrors() {
    var selected = $(".invalid-feedback");
    let scrollAction = 'no';
    for(let i = 0; i < selected.length; i++){
        if($(selected[i]).find('strong').text() !== ''){
            $(selected[i]).css({'display':'block'}).siblings('.form-control').addClass('invalid-feedback'+i);
            scrollAction = 'yes';
        }
    }
    if(scrollAction === 'yes'){
        scrollIntoDomView('invalid-feedback0', useClassForFieldFocus = 'yes');
    }
}

function scrollIntoDomView(selectedElement, useClassForFieldFocus = 'yes'){
    let prefix = '';
    let offset = '';
    if(useClassForFieldFocus === 'no'){
        offset = $("#"+selectedElement).offset(); // Contains .top and .left
        prefix = '#';
    }else{
        offset = $("."+selectedElement).offset(); // Contains .top and .left
        prefix = '.';
    }

    //Subtract 20 from top and left:

    offset.left -= 200;
    offset.top -= 200;
    //Now animate the scroll-top and scroll-left CSS properties of <body> and <html>:

    $('html, body').animate({
        scrollTop: offset.top,
        scrollLeft: offset.left
    });
    $(prefix+selectedElement).focus();

}