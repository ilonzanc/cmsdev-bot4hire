(function(){
    console.log("'assets/scripts/script.js' loaded");
    const togglenav = document.querySelectorAll('.toggleBars');
    for (let i = 0; i < togglenav.length; i++){
        togglenav[i].addEventListener('click', function(e){
            e.preventDefault();
            console.log('click');
            const navigation = document.querySelector('header ul.menu');
            if(navigation.classList.contains('nav-open')){
                navigation.classList.remove('nav-open');
            }
            else {
                navigation.classList.add('nav-open');
            }            
        })
    }
    
})();