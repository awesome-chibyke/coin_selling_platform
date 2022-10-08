
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="trigger_redirect_to_a_page" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content modal-sm">
    <div class="modal-header">
    <h5 class="modal-title" id="mySmallModalLabel">Redirect To Payment Page</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-12">
                <p class="text-success text-center show_message_pusher" id="show_message_pusher"></p>
            </div>
            <div class="form-group col-md-12 col-12">
                <a href="" id="pusher_redirect_button" >Click this button to get redirected to payment page.</a>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>
<input type="hidden" value="{{ Request::segment(1) }}" id="page_name_holder" />
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

  <script>

    const the_page_name = document.querySelector("page_name_holder");
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('679190c24124eebe63ee', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('payments');

    channel.bind('flutter-wave-comfirmation', function(data) {
      //check he curent page
      if(the_page_name === 'payment-invoice'){
        location.reload();
      }else{

        let {flutter_wave_data, db_data} = data.payload;
        document.querySelector('#show_message_pusher').innerHTML = `${flutter_wave_data.currency}${flutter_wave_data.amount} have been sent your ${flutter_wave_data.bank_name} Bank Account with Number ${flutter_wave_data.account_number}`;

        const attributeValue = '{{URL::to('/')}}/payment-invoice/'+db_data.deposit_transaction_id;
        document.querySelector('#pusher_redirect_button').setAttribute('href', attributeValue)
      }
    });

    //user status
    channel.bind('notify-user-status-change', function(data) {
        let {user_id, type} = data.payload;
        if(user_id === '{{auth()->user()->unique_id}}' && type === '{{auth()->user()->userBlockedAccountStatus}}'){
            document.getElementById('logout-form2').submit();
            return;
        };

        const arrayOfTypes = [
            '{{auth()->user()->normalUserType}}',
            '{{auth()->user()->adminUserType}}',
            '{{auth()->user()->midAdminUserType}}',
            '{{auth()->user()->superAdminUserType}}'
        ];
        if(arrayOfTypes.indexOf(type) != -1 && user_id === '{{auth()->user()->unique_id}}'){ location.reload() };

    });
  </script>
