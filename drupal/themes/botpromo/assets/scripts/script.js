(function(){
    console.log("'assets/scripts/script.js' loaded");
    const togglenav = document.querySelectorAll('.toggleBars');
    for (let i = 0; i < togglenav.length; i++){
        togglenav[i].addEventListener('click', function(e){
            e.preventDefault();
            const navigation = document.querySelector('header ul.menu');
            if(navigation.classList.contains('nav-open')){
                navigation.classList.remove('nav-open');

                togglenav[i].classList.remove('fa-times');
                togglenav[i].classList.add('fa-bars');
            }
            else {
                navigation.classList.add('nav-open');

                togglenav[i].classList.add('fa-times');
                togglenav[i].classList.remove('fa-bars');
            }
        })
    }

    const features = document.querySelectorAll('#block-views-block-features-block .views-row');
    for (let i = 0; i < features.length; i++) {
        if (window.innerWidth > 800) {
            if (i % 2 != 0) {
                console.log('uneven');
                features[i].querySelector('.group-left').setAttribute('style', 'float: right');
                features[i].querySelector('.group-right').setAttribute('style', 'float: right; width: 25%');
            }
            else {
                console.log('even');
            }
        }

    }
})();