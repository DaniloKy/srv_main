window.addEventListener('load', function(event){

    const buttons = document.querySelectorAll('button[type="button"][data-characterId]');
    for(const i of buttons){
        const id = i.getAttribute("data-characterId");
        const dialog = document.querySelector("dialog[data-characterId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }
});