<script>
    const selectedElements = document.querySelectorAll(".buttonThatsTriggersCopy");
    selectedElements.forEach((element, index) => {
      element.addEventListener("click", function () {

        let idOfInputWithValueToBeCopied = element.getAttribute("data-target");
        let inputWithValueToBeCopied = document.querySelector("#" + idOfInputWithValueToBeCopied);

        inputWithValueToBeCopied.select();
        inputWithValueToBeCopied.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(inputWithValueToBeCopied.value);

        //swal("Copied the text: " + copyText.value);
        swal("Success!", "Text has been copied to clipboard", "success");
      });
    });
</script>
