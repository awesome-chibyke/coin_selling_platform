<script>
    function updateButtonStatus(selectedButton, whatToDo = 'get_main_text_and_set_loading', valueToSet = 'Loading....'){
        //whatToDo can be  => get_main_text_and_set_loading, set_main_text
        if(whatToDo === 'get_main_text_and_set_loading'){
            const mainText = selectedButton.innerText;
            selectedButton.innerHTML = valueToSet;
            selectedButton.setAttribute('disabled', true);
            return mainText;
        }

        if(whatToDo === 'set_main_text'){
            selectedButton.innerHTML = valueToSet;
            selectedButton.removeAttribute('disabled');
            return '';
        }
    }
</script>
