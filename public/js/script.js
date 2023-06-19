window.onload = (event) => {

    const dropdown = document.querySelector(".dropdown");
    const dropdown_content = document.querySelector(".dropdown_content");

    if(dropdown != null && dropdown_content != null){
        dropdown.addEventListener("click", function() {
            dropdown_content.classList.toggle("visually-hidden");
        });
    }


    const buttons = document.querySelectorAll('button[type="button"][data-characterId]');
    for(const i of buttons){
        const id = i.getAttribute("data-characterId");
        const dialog = document.querySelector("dialog[data-characterId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }
};