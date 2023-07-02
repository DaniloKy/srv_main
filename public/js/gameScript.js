window.addEventListener('load', function(event){

    const player_list_btn = document.querySelector('#players_list_btn');
    const player_list = document.querySelector('.players_list');
    
    player_list_btn.addEventListener('click', function(){
        player_list_btn.classList.toggle('active');
        player_list.classList.toggle('visually-hidden');
    });

});