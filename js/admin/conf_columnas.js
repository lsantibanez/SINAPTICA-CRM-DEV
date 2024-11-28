$(document).ready(function() {
  getColumnas();
});

function getColumnas() {
  $.ajax({
    type: "GET",
    url: "../includes/columnas/listar.php",
    success: function(data) {
      const elemento = $('#bodyTableColumnas');
      elemento.html(data);
    }, erro (error) {
      console.error(error)
    }
  });
}