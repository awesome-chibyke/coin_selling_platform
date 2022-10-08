
$(document).ready(function () {//selected_bank bank_code
    let selected_bank_code = $("#selected_bank").val();
    selected_bank_code = typeof selected_bank_code === 'undefined' ? $(".bank_code").attr('data-bank-code') : selected_bank_code;
    let banks = getBanks(selected_bank_code);

    $(".bank_code").html(banks);
})

function fillBankDetails(a) {
    let selected_bank_code = $(".bank_code").attr('data-bank-code');
    let banks = getBanks(selected_bank_code);
    $(a).html(banks);
}

function dropBankName(a) {
    let selectedValue = $(a).find("option:selected").text();

    $(a).siblings('.bank_name').val(selectedValue)
}
