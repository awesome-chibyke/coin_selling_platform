<script>
    const checkBoxes = document.querySelectorAll(".select_checker");

    for(let i = 0; i < checkBoxes.length; i++){
        let eachCheckbox = checkBoxes[i];

        eachCheckbox.addEventListener('click', function(){
            if(eachCheckbox.checked){
                if(eachCheckbox.value === 'other'){
                    document.querySelector("#unsubscribe_custom_holder").removeAttribute('hidden')
                }else{
                    document.querySelector("#unsubscribe_custom_holder").setAttribute('hidden', true)
                }
            }
        })
    }
</script>
