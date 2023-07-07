<div class="main_page_title">
    <h1>Survive Utopia</h1>
    <a class="play_btn " href="<?=(session('userdata') == null || !session('userdata')['logged_in'])?base_url('login'):base_url('user/character');?>">Play Now</a>
</div>