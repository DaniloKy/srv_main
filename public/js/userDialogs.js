window.onload = (event) => {

    const buttons1 = document.querySelectorAll('button[type="button"][data-banId]');
    for(const i of buttons1){
        const id = i.getAttribute("data-banId");
        const dialog = document.querySelector("dialog[data-banId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }
    const buttons2 = document.querySelectorAll('button[type="button"][data-makeSuperId]');
    for(const i of buttons2){
        const id = i.getAttribute("data-makeSuperId");
        const dialog = document.querySelector("dialog[data-makeSuperId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }
    const buttons3 = document.querySelectorAll('button[type="button"][data-unbanId]');
    for(const i of buttons3){
        const id = i.getAttribute("data-unbanId");
        const dialog = document.querySelector("dialog[data-unbanId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }
    const buttons4 = document.querySelectorAll('button[type="button"][data-removeSuperId]');
    for(const i of buttons4){
        const id = i.getAttribute("data-removeSuperId");
        const dialog = document.querySelector("dialog[data-removeSuperId='"+id+"']");
        i.addEventListener("click", function(e){
            dialog.showModal();
        });
    }

};