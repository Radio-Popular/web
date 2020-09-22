
addEvent(window, 'load', function () {
  if (document.getElementById) {
    document.getElementById('hero-popup-player').onclick = function() {
      window.open(this.href, this.target, 'width=380,height=250');
      return false;
    }


    var form = document.getElementById('contact_form');

    form.onsubmit = function() {
      var form1_status = document.getElementById('form1_status');
      form1_status.className = 'alert alert-info';
      form1_status.innerHTML = 'Enviando mensaje...';

      var form1_submit = document.getElementById('form1_submit');
      form1_submit.disabled = true;


      var ajaxForm = new sack();

      ajaxForm.requestFile = form.action;
      ajaxForm.form1_status = form1_status;
      ajaxForm.form1_submit = form1_submit;
      ajaxForm.form1_comentario = document.getElementById('form1_comentario');
      ajaxForm.onCompletion = function() {
        this.form1_status.className = 'alert alert-success';
        this.form1_status.innerHTML = 'Mensaje enviado con &eacute;xito.';

        this.form1_comentario.value = '';
        this.form1_comentario.focus();
        this.form1_submit.disabled = false;
      }
      ajaxForm.onError = function() {
        this.form1_status.className = 'alert alert-danger';
        this.form1_status.innerHTML = 'Error enviando mensaje. Por favor vuelta a intentarlo mas tarde.';

        this.form1_submit.disabled = false;
      }
      ajaxForm.setVar('ajax', 1);
      ajaxForm.setVar('form1_nombre', document.getElementById('form1_nombre').value);
      ajaxForm.setVar('form1_email', document.getElementById('form1_email').value);
      ajaxForm.setVar('form1_comentario', ajaxForm.form1_comentario.value);
      ajaxForm.runAJAX();
      return false;
    }
  }
}
);
