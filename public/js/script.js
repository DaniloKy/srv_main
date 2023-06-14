window.onload = (event) => {

    var dropdown = document.querySelector(".dropdown");
    var dropdown_content = document.querySelector(".dropdown_content");

    if(dropdown != null && dropdown_content != null){
        dropdown.addEventListener("click", function() {
            dropdown_content.classList.toggle("visually-hidden");
        });
    }
};