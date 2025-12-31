const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

$(document).ready(function () {
            
  // Restaurar el estado desde localStorage
  $('.has-dropdown').each(function () {
      const target = $(this).data('bs-target'); // Ejemplo: #auth
      const name = localStorage.getItem('menu');
      const show = localStorage.getItem('show');
      

      if (target === name && show === 'expanded') {
          $(this).removeClass('collapsed'); // Asegurarse que no esté colapsado
          $(target).addClass('show'); // Mostrar el menú
      }
  });

  // Guardar el estado al hacer clic
  $('.menu').on('click', function () {

      if ($(this).hasClass('has-dropdown')){
        console.log('aqui ');
        const target = $(this).data('bs-target'); // Ejemplo: #auth
        const dropdown = $(target);
        console.log($(this));

        // Esperar para que Bootstrap actualice las clases
        
        if ($(this).hasClass('collapsed')) {
          localStorage.setItem('menu', '');
          localStorage.setItem('show', 'collapsed');
          //localStorage.setItem('mmenu', 'collapsed');
        } else {
          localStorage.setItem('menu', target);
          localStorage.setItem('show', 'expanded');
          //localStorage.setItem(target, 'expanded');
        }
      }else{
          localStorage.setItem('menu', '');
          localStorage.setItem('show', 'collapsed');
      }
    });
});