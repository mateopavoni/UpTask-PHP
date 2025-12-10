const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');


if(mobileMenuBtn){
    mobileMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('mostrar');
    });
}

if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar');
        }, 500);
    });
}

const anchoPantalla = document.body.clientWidth;


window.addEventListener('resize', function(){
    const nuevoAnchoPantalla = document.body.clientWidth;
    if(nuevoAnchoPantalla > 768){
        sidebar.classList.remove('mostrar');
    }
});