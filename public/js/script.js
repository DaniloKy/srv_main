window.onload = (event) => {

    const dropdown = document.querySelector(".dropdown");
    const dropdown_content = document.querySelector(".dropdown_content");

    if(dropdown != null && dropdown_content != null){
        dropdown.addEventListener("click", function() {
            dropdown_content.classList.toggle("visually-hidden");
        });
    }

    const characterInputs = document.querySelectorAll('input[name="character"]');
    const classDetailDivs = document.querySelectorAll('.general_details');
    console.log(characterInputs, classDetailDivs);
    if(characterInputs != null && classDetailDivs != null){
        characterInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                const selectedClass = document.querySelector('input[name="character"]:checked').value;
                console.log(selectedClass);
                classDetailDivs.forEach(function(div) {
                    console.log(div.id, selectedClass + '-details');
                    if (div.id === selectedClass + '-details') {
                        console.log("remove class 'visually-hidden' from div.id"); 
                        div.classList.remove('visually-hidden');
                    } else {
                        div.classList.add('visually-hidden');
                    }
                });
            });
        });
    }


};