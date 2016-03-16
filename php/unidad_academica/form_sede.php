<?php

class form_sede extends libro_unco_ei_formulario{
    function extender_objeto_js(){
        echo " 
            {$this->objeto_js}.evt__id_provincia__procesar = function(inicial){
                if(!inicial){
                    var provincia = this.controlador.dep('form_sede').ef('id_provincia').get_estado();
                    
                    if(provincia == 'nopar' || provincia == ''){
                        var telefono1 = this.controlador.dep('form_sede').ef('telefono_1').nodo();
                        var telefono2 = this.controlador.dep('form_sede').ef('telefono_2').nodo();
                        var telefono3 = this.controlador.dep('form_sede').ef('telefono_3').nodo();
                
                        var fax1 = this.controlador.dep('form_sede').ef('fax_1').nodo();
                        var fax2 = this.controlador.dep('form_sede').ef('fax_2').nodo();
                        var fax3 = this.controlador.dep('form_sede').ef('fax_3').nodo();                
                    
                        telefono1.childNodes[1].innerHTML = 'Telefono 1';
                        telefono2.childNodes[1].innerHTML = 'Telefono 2';
                        telefono3.childNodes[1].innerHTML = 'Telefono 3';
                
                        fax1.childNodes[1].innerHTML = 'Fax 1';
                        fax2.childNodes[1].innerHTML = 'Fax 2';
                        fax3.childNodes[1].innerHTML = 'Fax 3';
                    }
                }
            }
            
            {$this->objeto_js}.evt__id_localidad__procesar = function(inicial){
                var localidad = this.controlador.dep('form_sede').ef('id_localidad').get_estado();
                
                var telefono1 = this.controlador.dep('form_sede').ef('telefono_1').nodo();
                var telefono2 = this.controlador.dep('form_sede').ef('telefono_2').nodo();
                var telefono3 = this.controlador.dep('form_sede').ef('telefono_3').nodo();
                
                var fax1 = this.controlador.dep('form_sede').ef('fax_1').nodo();
                var fax2 = this.controlador.dep('form_sede').ef('fax_2').nodo();
                var fax3 = this.controlador.dep('form_sede').ef('fax_3').nodo();
                
                if(localidad == 'nopar' || localidad == ''){
                    telefono1.childNodes[1].innerHTML = 'Telefono 1';
                    telefono2.childNodes[1].innerHTML = 'Telefono 2';
                    telefono3.childNodes[1].innerHTML = 'Telefono 3';
                
                    fax1.childNodes[1].innerHTML = 'Fax 1';
                    fax2.childNodes[1].innerHTML = 'Fax 2';
                    fax3.childNodes[1].innerHTML = 'Fax 3';
                }
                else
                    this.controlador.ajax('get_caracteristica', localidad, this, this.actualizar_etiqueta );
                
                return false;
            }
            
            {$this->objeto_js}.actualizar_etiqueta = function(datos){
                var telefono1 = this.controlador.dep('form_sede').ef('telefono_1').nodo();
                var telefono2 = this.controlador.dep('form_sede').ef('telefono_2').nodo();
                var telefono3 = this.controlador.dep('form_sede').ef('telefono_3').nodo();
                
                var fax1 = this.controlador.dep('form_sede').ef('fax_1').nodo();
                var fax2 = this.controlador.dep('form_sede').ef('fax_2').nodo();
                var fax3 = this.controlador.dep('form_sede').ef('fax_3').nodo();

                telefono1.childNodes[1].innerHTML = 'Telefono 1 ('+datos+')';
                telefono2.childNodes[1].innerHTML = 'Telefono 2 ('+datos+')';
                telefono3.childNodes[1].innerHTML = 'Telefono 3 ('+datos+')';
                
                fax1.childNodes[1].innerHTML = 'Fax 1 ('+datos+')';
                fax2.childNodes[1].innerHTML = 'Fax 2 ('+datos+')';
                fax3.childNodes[1].innerHTML = 'Fax 3 ('+datos+')';
            }
        ";
    }
    
}

?>

