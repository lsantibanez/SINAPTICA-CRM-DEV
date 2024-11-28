$(document).ready(function() {

  // if (!isChrome()) {
  //   var Template = $("#LockTemplate").html();
  //   bootbox.dialog({
  //     title: "ATENCIÓN",
  //     message: Template,
  //     closeButton: false
  //   }).off("shown.bs.modal");
  // }

  $('.enviarForm').click(function( event ){
      //event.epreventDefault();
      var usuario = $('input[name="usuario"]').val();
      var password = $('input[name="password"]').val();
      //var captcha = $('#g-recaptcha-response').val();
      if((usuario == "") || (password == ""))
      {
        //alert('Debe completar todos los campos!');
        bootbox.alert('Debe completar todos los campos!');
        return false;
      }else{
        $("form").submit();
      }
  });
});

function isChrome() {
  var isChromium = window.chrome,
    winNav = window.navigator,
    vendorName = winNav.vendor,
    isOpera = winNav.userAgent.indexOf("OPR") > -1,
    isIEedge = winNav.userAgent.indexOf("Edge") > -1,
    isIOSChrome = winNav.userAgent.match("CriOS");

  if (isIOSChrome) {
    return true;
  } else if (
    isChromium !== null &&
    typeof isChromium !== "undefined" &&
    vendorName === "Google Inc." &&
    isOpera === false &&
    isIEedge === false
  ) {
    return true;
  } else {
    return false;
  }
}
