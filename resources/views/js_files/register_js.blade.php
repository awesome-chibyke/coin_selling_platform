<script>
    const selectedElement = document.querySelector('#register_form_button');
    const register_form = document.querySelector('#register_form');

    selectedElement.addEventListener('click', function(){
        fetch('{{ route('register') }}', {
            method: 'POST', // *GET, POST, PUT, DELETE,
            //credentials: 'same-origin', // include, *same-origin, omit
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type':'application/json',
                'Accept':'application/json'
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: {lkhjhj:''} // body data type must match "Content-Type" header
        })
        .then(response => response.json())
        .then(data => console.log(data));

    })
</script>
