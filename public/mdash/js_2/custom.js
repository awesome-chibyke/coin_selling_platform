/*const errorDisplay = (message) => swal(message, 'Failed', 'error');

const successDisplay = (message) => swal(message, 'successful', 'success');

const successNoty = (message) => swal(message, 'successful', 'success');*/

const failJson = ({ responseJSON: response }) => {
    errorDisplay(`${response.error.message}`)
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ajaxStart(function() {
    $('.loading_modal').show();
}).ajaxStop(function() {
    $('.loading_modal').hide();
});

/*//this handles the copy
var clipboard = new ClipboardJS('.copybtn');

clipboard.on('success', function(e) {
    // console.info('Action:', e.action);
    // console.info('Text:', e.text);
    // console.info('Trigger:', e.trigger);
    alert("Copied : " + e.text);

    e.clearSelection();
});

clipboard.on('error', function(e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});*/



$(document).ready(function() {
    //handle payment proof upload here
    $(".payment_proof").click(function(e) {
        e.preventDefault();
        var matchingId = $(this).attr('data-id');
        $("#matching").val(matchingId);
        // Show modal on page load
        $("#activation-payment-modal").modal('show');
    })

    $("#upload_payment_proof").click(function(e) {
        e.preventDefault();
        var fd = new FormData();
        var files = $('#payment_proof')[0].files[0];
        //var files = $('input[type="file" id="payment_proof"]')[0].files;
        fd.append('image', files);
        //get other data inside the form
        var other_data = $('#payment_proof_form').serializeArray();
        $.each(other_data, function(key, input) {
            fd.append(input.name, input.value);
        });

        const myForm = $('form#payment_proof_form');
        swal({
                title: "Are you sure this is the correct proof of payment?",
                text: "Uploading a fake proof can get our account blocked!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((correct) => {
                if (correct) {
                    //user accepted starts here
                    $('#upload_payment_proof').html('Sending...');
                    $.ajax({
                            url: myForm.attr('action'),
                            data: fd,
                            type: 'POST',
                            contentType: false,
                            processData: false,
                        })
                        .done((response) => {
                            //let receiever = response.data.receiver;
                            successNoty(`${response.data.payer.name} your proof of payment has successfully been uploaded!`);
                            $('#content-container').load(`${location.href} #content-container`);
                        })
                        .fail(failJson)
                        .always(() => $('#upload_payment_proof').html('Upload proof'));
                    //use accepted ends here
                } else {
                    swal("Your action has been cancelled!");
                }
            });
    })

});

function confirmPayment(user_id, matching_id, route) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var fd = new FormData();
    fd.append('receiver_id', user_id);
    fd.append('matching_id', matching_id);
    fd.append('_token', token);
    swal({
            title: "Are you sure you want to confirm this payment?",
            text: "Confirming means, you have accepted that the user has made the payment!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((correct) => {
            if (correct) {
                //user accepted starts here
                $('#confirm' + matching_id).html('Sending...');
                $.ajax({
                        url: route,
                        data: fd,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                    })
                    .done((response) => {
                        //let receiever = response.data.receiver;
                        successNoty(`${response.data.user.name} your payment has been marked as completed!`);
                        $('#content-container').load(`${location.href} #content-container`);
                    })
                    .fail(failJson)
                    .always(() => $('#confirm' + matching_id).html('Confirm payment'));
                //use accepted ends here
            } else {
                swal("Your action has been cancelled!");
            }
        });
}

function flagPayment(user_id, matching_id, route) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var fd = new FormData();
    fd.append('receiver_id', user_id);
    fd.append('matching_id', matching_id);
    fd.append('_token', token);
    swal({
            title: "Are you sure you want to flag this payment?",
            text: "Flagging means, your notifying the admin that the user made a wrong payment",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((correct) => {
            if (correct) {
                //user accepted starts here
                $('#flag' + matching_id).html('Flagging...');
                $.ajax({
                        url: route,
                        data: fd,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                    })
                    .done((response) => {
                        //let receiever = response.data.receiver;
                        successNoty(`Payment has been flagged`);
                        $('#content-container').load(`${location.href} #content-container`);
                    })
                    .fail(failJson)
                    .always(() => $('#flagged' + matching_id).html('Flagged'));
                //use accepted ends here
            } else {
                swal("Your action has been cancelled!");
            }
        });
}