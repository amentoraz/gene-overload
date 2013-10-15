
function objetoAjax(){
        var xmlhttp=false;

        if (window.XMLHttpRequest) {              

          xmlhttp=new XMLHttpRequest();              

        } else {                                  

          try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
          } catch (e) {
                try {
                   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                        xmlhttp = false;
                }
          }
        }

        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
                xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
}


// Esta es la funcion qeu se esta usando actualmetne

function resultado_A(datos,destino){
        divResultado = document.getElementById(destino);
        if (window.XMLHttpRequest) {              
          AJAX=new XMLHttpRequest();              
        } else {                                  
          AJAX=new ActiveXObject("Microsoft.XMLHTTP");
        }

alert("!");
        AJAX.onreadystatechange = pintar();
        AJAX.open("GET", datos, false);                             
        AJAX.send(null);

}

function pintar() {
alert("#"+AJAX.responseText);
        if (httpRequest.readyState === 4) {  
	      if (httpRequest.status === 200) {  
                divResultado.innerHTML = AJAX.responseText
	    } 
        }
}


// Esta es la funcion qeu se esta usando actualmetne

function resultado_SYNC(datos,destino){
        divResultado = document.getElementById(destino);
        if (window.XMLHttpRequest) {              
          AJAX=new XMLHttpRequest();              
        } else {                                  
          AJAX=new ActiveXObject("Microsoft.XMLHTTP");
        }
        if (AJAX) {
          AJAX.open("GET", datos, false);                             
          AJAX.send(null);
          divResultado.innerHTML = AJAX.responseText
        } else {
          return false;
        }                                             
}





function resultado(datos,destino){

        ajax=objetoAjax();
        ajax.open("POST", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        document.getElementById(destino).innerHTML = ajax.responseText
                }
        }
        ajax.send(null);
}


function resultado_bis(datos2,destino2){

        divResultado2 = document.getElementById(destino2);
        ajax2=objetoAjax();
        ajax2.open("POST", datos2);
        ajax2.onreadystatechange=function(destino2) {
                if (ajax2.readyState==4) {
                        divResultado2.innerHTML = ajax2.responseText
                }
        }
        ajax2.send(null);
}

function resultado_bis3(datos3,destino3){

        divResultado3 = document.getElementById(destino3);
        ajax3=objetoAjax();
        ajax3.open("POST", datos3);
        ajax3.onreadystatechange=function(destino3) {
                if (ajax3.readyState==4) {
                        divResultado3.innerHTML = ajax3.responseText
                }
        }
        ajax3.send(null);
}



// Vamos a ver si le borramos la bandera

function resultado_bandera1(datos, destino1, idcampana, lang){

        divResultado1 = document.getElementById(destino1);
        divResultado2 = document.getElementById('div_opciones');
        ajax=objetoAjax();
        ajax2=objetoAjax();
        ajax2.open("POST", datos);
        ajax2.onreadystatechange=function() {
                if (ajax2.readyState==4) {
                  if (ajax2.responseText == 1)
                  {
                    divResultado1.innerHTML = '<a href="#" class="Ntooltip"><img src="img/flag_captured.png" style="vertical-align:middle;">&nbsp;&nbsp;&nbsp;<span style="width: 250px;"><table width="100%" class="tooltip_interno"><tr><td>'+texto+'</td></tr></table></span></a>';
                  } else {
                    divResultado1.innerHTML = '';

                  }
                }
        }
        ajax2.send(null);
}



// Vamos a ver si cambiamos el texto

function resultado_bandera2(datos, destino1, idcampana, lang){

    if (lang == 'en')
    {
      var texto = 'You currently hold the flag';
    } else {
      var texto = 'Eres el due&ntilde;o actual de la bandera';
    }

        divResultado1 = document.getElementById(destino1);
        divResultado2 = document.getElementById('div_opciones');
        ajax=objetoAjax();
        ajax2=objetoAjax();
        ajax.open("POST", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                  if (ajax.responseText == 1)
                  {
//alert('ganador');
                    divResultado1.innerHTML = '<a href="#" class="Ntooltip"><img src="img/flag_captured.png" style="vertical-align:middle;">&nbsp;&nbsp;&nbsp;<span style="width: 250px;"><table width="100%" class="tooltip_interno"><tr><td>'+texto+'</td></tr></table></span></a>';

                  } else {
//alert('no ganador');
                    divResultado1.innerHTML = '';

                  }
                }
        }
        ajax.send(null);
}




function resultado_exec(datos,destino,seccion,item){

	// Esto especificamente lo qeu hace es activar el seccion e item adecuado para el calendario, con eval...

        divResultado = document.getElementById(destino);
        ajax=objetoAjax();
        ajax.open("POST", datos);
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        divResultado.innerHTML = ajax.responseText
                }
        }
        ajax.send(null);

	allamar = ' { inputField : "seccion_item_fecha", ifFormat : "%Y/%m/%d", button : "f_trigger_c" } ';


}



function resultadoForm(url, formid){

         var Formulario = document.getElementById(formid);
         var longitudFormulario = Formulario.elements.length;
         var cadenaFormulario = ""
         var sepCampos
         sepCampos = ""
         for (var i=0; i <= Formulario.elements.length-1;i++) {
                         //Si estamos en los checkbox de servicios, vemos cuales est�n checkeados
                         if (Formulario.elements[i].name == 'servicios') {
                                 if (Formulario.elements[i].checked == true) {
                                         cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
                                 }
                         }
                         //... si no, adjuntamos el par�metro=valor en la cadena
                         else {
                        cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
                         }
             sepCampos="&";
                  }
  peticion=objetoAjax(); // roma
  peticion.open("POST", url, true);
  peticion.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  peticion.onreadystatechange = function () {
        document.location.href='#r';
        if (peticion.readyState == 4) {
     document.getElementById('resultados').innerHTML = peticion.responseText;
         //document.location.href='#r';
         tb_init($("#resultados")[0]); // roma
        }
  }

        peticion.send(cadenaFormulario);






}




