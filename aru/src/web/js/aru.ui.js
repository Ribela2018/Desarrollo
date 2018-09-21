/**
 * Modulo encargado de interaccion en interfaz
 */
$ARU.UI = (function() {
    var tab = null;
    var positions = Array();
    function init()
    {
        _loadIndex();
        _location();
        _attachphoto();
        _validarDatos();
        _applyMetroSelect();
        _loadFirstView();
        _loadCp();
        _initDataTableAll();
        _changePass();
        _viewdetalle();
        _menu();
        _createRoles();
        _treeDirectory();
        _controllersByRol();
        _MaskCell();
        _CreatePositionCode();
        _addDivision();
        _genExcel();
        _genExcelPHPExcel();
        _genEmail();
        _genPrint();
        _deleteCp();
        _confirmDeleteCp();
        _asigPayRoll();
        _getEmployeePayRoll();
        _getTablePayRoll();
        _deletePayRollEmployee();
        _getDateNomina();
        _modificationPayRoll();
        _confirmarFinalizarNomina();
        _confirmarBotonFinalizar();
        _getTableAllEmployee();
        _getCheckReport();
        _getPdf();
        _getSendEmail();
        _refrescartabNomina();
        _optionesDivision();
        _optionesPosition();
        _optionesCp();
        _deleteDivision();
        _EditarDivision();
        _EditarPosition();
        _EditarCodigoPosicion();
        _confirEditCp();
        _desactivarPositionCodeEmployee();
        _confirmarDesactivarEmpleadoCp();
        _deleteCargoPosicionCode();
        _calenderBirth();
        _jornadaDeclareDay();
        _genExcelDeclareDay();
        _getTimeStartJornada();
        _getEmployeeHours();
        _optionHourEmployee();
        _deleteHour();
        _saveHorarioDay();
        _leyendShow();
        _separarAsigHoursDay();
        _changeStaticHour();
        _asigHourDescanso();
        _solicitudVacaciones();
        _holiday();
        _asigTelfCorp();
        _btnAsigTelf();
        _deleteAsigTelf();
        _filtrarDirectoryPhone();
        _getPDFConstancia();
        _genUserNameDinamyc();
        _esquemaNumeros();
        _SendConstan();
        _signature();
//        _getSignatureImage();
        _sticker();
        _selectCompanyPosition();
        _selectCompanyDepartmentPosition();
  
        _saveDepartment();
        _editDepartment();
        _newEditPositionEditDepartment();

        _messageModal();
        _savePositionNew();
        _employeePositionAdd();
        _saveEmployeePositionAdd();
        _createDepartment();
        //_updateDepartment();
        _addNewPosition();
        _editarCargo();
        _editNewPosition();
        _salvarPostion();
        _deleteNewPosition();
        _bottonDeleteNewPosition();
        _deleteNewDepartement();

        _bottonDeleteDepartament();
        _initModuleHrEmployee();
//        _editEmployee();
        _editSaveEmployee();
        _validataFormNewEmployee();
        _validateField();
        _confirmarDelete();
        _typeRecruitment();
//        setTimeout(function(){  console.log('entro settime');
//                                _cambioStatus(); }, 6000);
        _cambioStatus();
      
    }
    var formatNumber = {
        separador: ".", // separador para los miles
        sepDecimal: ',', // separador para los decimales
        formatear:function (num){
         num +='';
         var splitStr = num.split('.');
         var splitLeft = splitStr[0];
         var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
         var regx = /(\d+)(\d{3})/;
         while (regx.test(splitLeft)) {
         splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
         }
         return this.simbol + splitLeft  +splitRight;
        },
        new:function(num, simbol){
         this.simbol = simbol ||'';
         return this.formatear(num);
        }
    }

    function _typeRecruitment()
    {
        $("#contract_type_employee").change(function() {
            
          var type= $(this).val();
          
          if (type=='2')
          {
              $('#end_date_employee').attr('disabled', true);
          }
          else {
              $('#end_date_employee').attr('disabled', false);
          }
            
        });
    }
    
    


    function _validataFormNewEmployee()
    {
   
        var form = $('#validateHrEmployee');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                'dni_employee': {
                    required: false
                },
                'name_employee': {
                    required: true
                },
                'second_name_employee': {
                    required: false
                },
                'surname_employee': {
                    required: true
                }
            },
            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.attr("name") == "gender") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                } else if (element.attr("name") == "payment[]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_payment_error");
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                success.hide();
                error.show();
                App.scrollTo(error, -200);
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                    label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                    label.remove(); // remove error label here
                } else { // display success icon for other inputs
                    label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                }
            },
            submitHandler: function(form) {
//                    success.show();
//                    error.hide();
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                form.submit();
            }

        });
    }


    function _editSaveEmployee()
    {
        $('#edit-employee').on('click', function() {

            $.ajax({
                type: 'GET',
                url: "/Hr/Employee/EditEmployee",
                data: {"dni": $('#dni_employee').val(), "name": $('#name_employee').val(),
                    "second_name": $('#second_name_employee').val(), "surname": $('#surname_employee').val(),
                    "second_surname": $('#second_surname_employee').val(), "country": $('#country_location_current_employee').val(),
                    "gmt_location_current": $('#gmt_location_current_employee').val(), "company": $('#company_employee').val(),
                    "department": $('#department_employee').val(), "position": $('#position_employee').val(),
                    "start_date": $('#start_date_employee').val(), "end_date": $('#end_date_employee').val(),
                    "contract_type": $('#contract_type_employee').val(), 'id': $('#idEditEmployee').text()},
                beforeSend: function() {

                },
                success: function(data, textStatus, jqXHR) {

                    data = JSON.parse(data);
                
                    if (data.response == "true")
                    {
                        $(".cont-mensaje-edit").show('true');
//                      $('.cont-mensaje-edit').removeClass('ocultar');
                        $('.cont-mensaje-edit').removeClass('alert-danger');
                        $('.cont-mensaje-edit').addClass('alert-success');
                        $('#mensaje-editar-empleado').html("Registro Exitoso");


                           $('#div_tabla_employee').load('/Hr/employee/RefreshEmployee', '#table_employee', function() {
                            Metronic.init();
                            _initDataTable('#table_employee', 1, 10);



                        });
                        setTimeout('$("#modal-create-employee").modal("hide");', 1500);
                        
                       
                        
                    }

                    if (data.response == "false")
                    {
                        if (data.message == "Faltan algunos campos por llenar.")
                        {
                            
//                             $("#view-new-edit-position").hide();
                            $(".cont-mensaje-edit").show('true');
//                          $('.cont-mensaje-edit').removeClass('ocultar');
                            $('.cont-mensaje-edit').removeClass('alert-success');
                            $('.cont-mensaje-edit').addClass('alert-danger');
                            $('#mensaje-editar-empleado').html("Faltan algunos campos por llenar.");
                        }

                        else
                        {
                            console.log("Ooops a ocurrido un error.");
                        }
                    }

                },
                complete: function(msg) {


                },
                error: function(result) {
                    alert(result.responseText);
                }
            });


        });
    }


    function _editEmployee()
    {
        $('.edit-employee').on('click', function() {


            $(".cont-mensaje-edit").hide();
            $('.create-save-employee').addClass('ocultar');
            $('.create-edit-employee').removeClass('ocultar');
//            $('.cont-mensaje-edit').addClass('ocultar');
            
            var id = $(this).attr('id');

            $.ajax({
                type: 'GET',
                url: "/Hr/employee/employee",
                data: {'id': id},
                beforeSend: function() {
//                   $("#country_location_current_employee").prop("disabled", true);
                    $("#company_employee").prop("disabled", true);
                    $("#department_employee").prop("disabled", true);
                    $("#position_employee").prop("disabled", true);
              

                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    
                    var DataArray = JSON.parse(data.employee.personal_data);
                    var DataArrayCompany= JSON.parse(data.employee.parameters).company[0];
              
         
                    $('#idEditEmployee').text(id);
                    $('#dni_employee').val(data.employee.identification);
                    $('#name_employee').val(DataArray.name);
                    $('#second_name_employee').val(DataArray.second_name);
                    $('#surname_employee').val(DataArray.surname);
                    $('#second_surname_employee').val(DataArray.second_surname);
                    
                    var html;
                    var html_1;

                    html = "<option value='empty'>Seleccione un Pais</option>";
                    for (i = 0; i < data.country.length; i++) {
                        html += "<option value=" + data.country[i].id + ">" + data.country[i].name + "</option>";
                    }

                    $("#country_location_current_employee").html(html);
//
                    html_1 = "<option value='empty'>Seleccione una Compañia</option>";
                    for (i = 0; i < data.company.length; i++) {
                        html_1 += "<option value=" + data.company[i].id + ">" + data.company[i].name + "</option>";
                    }
                    $("#company_employee").html(html_1);
                    
                    if(DataArrayCompany!=null){
                        $('#start_date_employee').val(DataArrayCompany.start_date);
                        $('#end_date_employee').val(DataArrayCompany.end_date);
                        $('#contract_type_employee').select2('val', DataArrayCompany.contract_type);
                        $('#company_employee').select2('val', DataArrayCompany.id);
                    }else{
                        $('#start_date_employee').val(null);
                        $('#end_date_employee').val(null);
                        $('#contract_type_employee').select2('val', 'empty');
                        $('#company_employee').select2('val', 'empty');
                    }

                    var idGtm =JSON.parse(data.employee.parameters).city_time_zone;
                    gtmCountry(idGtm);
                   

                    $("#modal-create-employee").modal("show");
//                  

                },
                complete: function(data) {

                    $("#country_location_current_employee").prop("disabled", false);
                    $("#company_employee").prop("disabled", true);

                },
                error: function(result) {

                    alert("Ooops ocurrió un error");
                }
            });



        });

    }



    function  gtmCountry(idGtm)
    {

        var request = $.ajax({
            type: 'GET',
            url: "/Sys/city/CountryByGmt",
            data: {"id": idGtm},
            beforeSend: function() {

            },
            success: function(data, textStatus, jqXHR) {

                data = JSON.parse(data);
                console.log(data);
                if (data.response=="true")
                {
                    var htmlGMT;
                    htmlGMT = "<option value='empty'>Seleccione una GMT</option>";
                    
                    for(i=0; i<data.city.length; i++){
                        htmlGMT += "<option value=" + data.city[i].id + ">" + data.city[i].name + "</option>";
                    }
                    $("#gmt_location_current_employee").html(htmlGMT);
                    if(idGtm != null){
                        $('#country_location_current_employee').select2('val', data.country.id);
                        $('#gmt_location_current_employee').select2('val', idGtm);
                    }else{
                        $('#country_location_current_employee').select2('val', 'empty');
                        $('#gmt_location_current_employee').select2('val', 'empty');
                    }
                    
                }else{
                    $("#gmt_location_current_employee").html("<option value='empty'>Seleccione una GMT</option>");
                    $('#country_location_current_employee').select2('val', 'empty');
                        $('#gmt_location_current_employee').select2('val', 'empty');
                }



            },
            complete: function(msg) {

            },
            error: function(result) {
                alert(result.responseText);
            }
        });

    }



    function companyByDepartament(idCompany, idDepartement)
    {

        $.ajax({
            type: 'GET',
            url: "/Hr/Department/DepartmentByCompany",
            data: {"id": idCompany},
            beforeSend: function() {
                $("#department_employee").select2('val', 'empty');
                $("#department_employee").prop("disabled", true);
                $("#position_employee").select2('val', 'empty');
                $("#position_employee").prop("disabled", true);
            },
            success: function(data, textStatus, jqXHR) {

                data = JSON.parse(data);
                var htmlDepar;
                if (data.response != "false") {
                    for (i = 0; i < data.departments.length; i++) {
                        htmlDepar += "<option value=" + data.departments[i].id + ">" + data.departments[i].name + "</option>";
                    }
                }
                $("#department_employee").html(htmlDepar);
                $("#department_employee").select2('val', idDepartement);

            },
            complete: function(msg) {
                $("#department_employee").prop("disabled", true);
                $("#position_employee").prop("disabled", true);
            },
            error: function(result) {
                alert(result.responseText);
            }
        });

    }


    function DepartementByPosition(idCompany, idDepartement, idPosition)
    {
        $.ajax({
            type: 'GET',
            url: "/Hr/position/PositionByCompanyAndDepartment1",
            data: {"id_company": idCompany, "id_department": idDepartement},
            beforeSend: function() {

                $("#position_employee").select2('val', 'empty');
                $("#position_employee").prop("disabled", true);
            },
            success: function(data, textStatus, jqXHR) {

                data = JSON.parse(data);

                var htmlPosition;
                htmlPosition = "<option value='empty'>Seleccione un Cargo</option>";
                for (i = 0; i < data.length; i++) {
                    htmlPosition += "<option value=" + data[i].id + ">" + data[i].name + "</option>";
                }
                $("#position_employee").html(htmlPosition);
                $("#position_employee").select2('val', idPosition);

            },
            complete: function(msg) {

                $("#position_employee").prop("disabled", true);
            },
            error: function(result) {
                alert(result.responseText);
            }
        });
    }



    function _bottonDeleteDepartament()
    {
        $('#deleteDepartamentModal').on('click', function() {
            var id = $('#idDepartamentModalDelete').text();

            $.ajax({
                type: 'POST',
                url: "/Hr/department/delete",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);

                    if (textStatus == "success")
                    {
                        $('#delete_departament').addClass('ocultar');
                        $('.botton-delete').addClass('ocultar');
                        $('#mensajeDepartamentDetele').removeClass('ocultar');
                        if (data.response == "true")
                        {
                            $('#responseMensajeDepartamentDelete').html('<span class="verde"><h4><i style="font-size:42px"></i>  Proceso Completado</h4></span>');
                        }
                        if (data.response == "false" && data.message == "Este Departamento Posee Cargos Asociados")
                        {
                            $('#responseMensajeDepartamentDelete').html('<span class="letra_empleado"><h4><i class="icon-shield" style="font-size:42px"></i>  ' + data.message + '</h4></span>');
                        }
                        if (data.response == "false" && data.message == "Este Departamento Posee Otros Departamentos Asociados")
                        {
                            $('#responseMensajeDepartamentDelete').html('<span class="letra_empleado"><h4><i class="icon-shield" style="font-size:42px"></i>  ' + data.message + '</h4></span>');
                        }




                        Metronic.init();
                        _refreshTableDepartament();
                        setTimeout('$("#modal-add-delete-departament").modal("hide");', 2000);
                    }


                    if (textStatus == 'false')
                    {

                    }

                }
            });




        });

    }



    function _deleteNewDepartement()
    {
        $('.eliminar_departamento').on('click', function() {

            $('#delete_departament').removeClass('ocultar');
            $('.botton-delete').removeClass('ocultar');
            $('#mensajeDepartamentDetele').addClass('ocultar');

            var position_id = $(this).find('div#id').text();
            $('#idDepartamentModalDelete').html(position_id);
            $('#modal-add-delete-departament').modal('show');
        });

    }



    function _refreshTableDepartament() {
       console.log ("aqui");
        $('#tabla_department-1').load('/Hr/department/RefreshTableDepartment', '#table_department', function() {
            Metronic.init();
            _initDataTable('#table_department', 1, 10);

        });
    }
    function _refreshTablePosition() {
        $('#tabla_position-1').load('/Hr/position/RefreshTablePosition', '#table_position_cargo', function() {
            Metronic.init();
            _initDataTable('#table_position_cargo', 1, 10);

        });
    }


    function _bottonDeleteNewPosition()
    {
        $('#deletePositionModal').on('click', function() {
            
            $('#modal-confir-delete').modal('show');
          var id = $('#idPositionModalDelete').text();
          var namePosition= $('#namePositionModalDelete').text();
          $('#idConfirDelete').text(id);
          $('#namePositionDeleteModalConfir').html("<span><h4>"+namePosition+"</h4></span>");
         
         
         
          $.ajax({
                type: 'GET',
                url: "/Hr/position/EmployeesPosition",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                   
                   console.log (data);
                }

            });
         
         
        });
    }
    
    
    function _confirmarDelete()
    {
        $('#deletePositionModalConfir').on('click', function() {
            
            var id= $('#idConfirDelete').text();
          
             $.ajax({
                type: 'POST',
                url: "/Hr/position/deletePosition",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {

                    if (textStatus == "success")
                    {
                        data = JSON.parse(data);
                        if (data.response == "true") {
                            $('#delete_position_confir').hide();
                            $('#delete_position').addClass('ocultar');
                            $('.botton-delete').addClass('ocultar');
                            $('#mensajePositionDetele').removeClass('ocultar');
                            $('#responseMensajePositionDelete').html('<span class="verde"><h4>Proceso completado</h4></span>');
                            Metronic.init();
                            _refreshTablePosition();
                            setTimeout('$("#modal-confir-delete").modal("hide");', 1500);
                            setTimeout('$("#modal-add-delete-position").modal("hide");', 1500);
                        } else {
                            $('#delete_position_confir').addClass('ocultar');
                            $('.botton-delete').addClass('ocultar');
                            $('#mensajePositionDetele').removeClass('ocultar');
                            $('#responseMensajePositionDelete').html('<span class="rojo"><h4>' + data.message + '</h4></span>');
                            Metronic.init();
                            _refreshTablePosition();
                            setTimeout('$("#modal-confir-delete").modal("hide");', 1500);
                            setTimeout('$("#modal-add-delete-position").modal("hide");', 1500);
                        }
                    }


                    if (textStatus == 'false')
                    {

                    }

                }
            });
            
            
            
        });
        
        
        
    }



    function _deleteNewPosition()
    {
        $('.eliminar_cargo').on('click', function() {

            var position_id = $(this).find('div#idPositionDelete').text();
            var name_position_delete = $(this).find('div#namePositionDelete').text();

            $('#delete_position').removeClass('ocultar');
            $('.botton-delete').removeClass('ocultar');
            $('#mensajePositionDetele').addClass('ocultar');

            $('#idPositionModalDelete').html(position_id);
            $('#namePositionModalDelete').html(name_position_delete);
            $('#namePositionDeleteModal').html('<span><h4>' + name_position_delete + '</h4></span>');
            $('#modal-add-delete-position').modal('show');
        });

    }


    function _editNewPosition()
    {
        $('#editPositionModal').on('click', function() {

            var position_name = $('#Position_name').val();
            var position_id = $('#idPositionModal').text();
            var parent_id = $('#Position_position_id').val();
//            $('.botonesEditar').addClass('ocultar');
            $('#responseMensajePosition').removeClass('ocultar');
            if (position_name != '' && position_id != '')
            {

                $.ajax({
                    type: 'POST',
                    url: "/Hr/position/edit",
                    data: {'position_id': position_id, 'position_name': position_name, 'parent_id': parent_id},
                    success: function(data, textStatus, jqXHR) {

                        if (textStatus == "success")
                        {
                            
//                           
                            $('#alert-position-1').hide();
//                            $('#edit_position').addClass('ocultar');
//                          $('#mensajePosition').removeClass('ocultar');
                            $('#edit-alert-edit-position').show('true');
                            $('#alert-position-1').hide();
                           
                            $('#responseMensajePosition').html('<span class="verde"><h4>Registro exitoso</h4></span>');
                            Metronic.init();
                            _refreshTablePosition();
                            setTimeout('$("#modal-add-edit-position").modal("hide");', 1500);
                        }


                        if (textStatus == 'false')
                        {

                        }

                    }
                });
            }
                    
            else
            {
                 $('#alert-position-1').show('true');
//                $('#alert-position-1').removeClass('ocultar');
                $('.botonesEditar').removeClass('ocultar');
            }


        });

    }


    function _editarCargo()
    {
        $('.editar_cargo').on('click', function() {


            var position_id = $(this).find('div#idPosition').text();
            var companyId = $(this).find('div#companyId').text();
            var departmentId = $(this).find('div#departmentId').text();
            var namePosition = $(this).find('div#namePosition').text();
             $('#to-save-employee-position').hide();


            $('#editView').load('/Hr/position/edit?position_id=' + position_id + '&departmentId=' + departmentId + '&companyId=' + companyId, function() {

                 $('.select2-position').select2({
                            allowClear: true
                        });
                $("#Position_company_id").select2('val', companyId);
                $("#Position_department_id").select2('val', departmentId);
                $("#Position_name").val(namePosition);
            });

            $('#idPositionModal').html(position_id);
            $('#modal-add-edit-position').modal('show');

        });
    }



    function _salvarPostion()
    {
        $('#newPositionModal').on('click', function() {

       
            var id_company = $('#Position_company_id').val();
            var id_depart = $('#Position_department_id').val();
            var name_position = $('#Position_name').val();
            var id_positionParent = $("#Position_position_id").val();

            if (id_company != '' && id_depart != '' && name_position != '')
            {
                
//                

                $.ajax({
                    type: 'POST',
                    url: "/Hr/position/SaveNewPositionComplete",
                    data: {'id_company': id_company, 'id_depart': id_depart, 'name_position': name_position, "id_position_parent": id_positionParent},
                    success: function(data, textStatus, jqXHR) {

                        data = JSON.parse(data);
                        if (data.response == "true") {
                            _selectPositionDepartment();
                            $("#Position_company_id").select2('val', '');
                            $("#Position_department_id").select2('val', '');
                            $("#Position_name").val(null);
                            $('.mensajeError').hide();
                            $("#body-create-position").hide();
                            $("#edit-alert-save-position").show('true');
//                            $('#modal-new-position').modal('show');
                           
                            
                            setTimeout('$("#modal-add-new-position").modal("hide");', 1000);

                            _refreshTablePosition();

                        }

                    }
                });
            }


            else
            {
                 
                $('.mensajeError').show('true');
//                $('.mensajeError').removeClass('ocultar');
            }


        });
    }

    function _addNewPosition()
    {

        $('#create-position').on('click', function() {
            
         $(".mensajeError").hide();
         $("#body-create-position").show();
         $("#edit-alert-save-position").hide();
         $("#mensaje-error-position").hide();

            
           
            $('#reloadModalNewPosition').load('/Hr/position/create', function() {
                Metronic.init();
                _applyMetroSelect();
                _selectCompanyPosition();
                _selectCompanyDepartmentPosition();
                _validateField();
                $("#mensaje-error-position").hide();
            });
            
            $('#modal-add-new-position').modal('show');
        });
    }





    function _saveEmployeePositionAdd() {
        $('#button-save-add-employee-position').on('click', function() {
            var id = $("#id-position-modal-employee-position").html();
            var employees = $('#employee-add').val().split(",");
            if (employees[0] == "") {
                employees = null;
            }
            if (employees !== null)
            {
              
                $.ajax({
                    type: 'POST',
                    url: "/Hr/position/SaveAddEmployeePosition",
                    data: {'id': id, 'employees': employees},
                    success: function(data, textStatus, jqXHR) {
                        data = JSON.parse(data);
                        if (data.response == "true") {
                            $('#error-to-save').hide();
                            $('#to-save-employee-position').show('true');
                            setTimeout('$("#modal-add-employee-position").modal("hide");', 1500);
                            //$('#modal-add-employee-position').modal('hide');
                        } else {
                            $('#to-save-employee-position').hide();
                            $('#error-to-save').show('true');
                            setTimeout('$("#modal-add-employee-position").modal("hide");', 1500);
                        }

                    }
                });
            }
            else {
                $('#to-save-employee-position').hide();
                $('#error-to-save').show('true');
//                  setTimeout('$("#modal-add-employee-position").modal("hide");', 1500);
            }


        });
    }

    function _savePositionNew()
    {
        console.log("_savePositionNew()");
        $("#btn-green-save-position").on("click", function() {

            var id_company = $('#Position_company_id').val();
            var id_depart = $('#Position_department_id').val();
            var name_position = $('#Position_name').val();

            $.ajax({
                type: 'POST',
                url: "/Hr/position/SaveNewPosition",
                data: {'id_company': id_company, 'id_depart': id_depart, 'name_position': name_position},
                success: function(data, textStatus, jqXHR) {

                    data = JSON.parse(data);
                    if (data.response == "true") {
                        _selectPositionDepartment();
                        $("#Position_company_id").select2('val', '');
                        $("#Position_department_id").select2('val', '');
                        $("#Position_name").val(null);
                        $('#modal-new-position').modal('show');
                    }
                    else 
                    {
                        $('#mensaje-error-position').show('true');
                    }

                }
            });

        });
    }




    function _employeePositionAdd() {
        $('a.employee-position').on('click', function(e) {
            $('#error-to-save').hide();
              $('#to-save-employee-position').hide();
            var id = e.target.id;
            console.log (id);
            $('#employee-add').val('val', '');
            $.ajax({
                type: 'GET',
                url: "/Hr/position/EmployeesPosition",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    if (data.response == "false") {
                        $('#label-employee-position').hide();
                    } else {
                        $('#error-to-save').hide();
                        $('#to-save-employee-position').hide();
                        $("#id-position-modal-employee-position").html(data.id);
                        if (data.type == "lead") {
                            $('#employee-add').val(data.employee_position);
                            $('#employee-add').select2({
                                tags: data.employees,
                                placeholder: 'Seleccione los Empleados',
                                maximumSelectionSize: 1

                            });

                        } else {
                            $('#employee-add').val(data.employee_position);
                            $('#employee-add').select2({
                                tags: data.employees,
                                placeholder: 'Seleccione los Empleados'
                            });
                        }
                    }

                }

            });
            $('#modal-add-employee-position').modal('show');
        });
    }

    function _messageModal() {
        $("#no-message").on("click", function() {
            $("#modal-create-department").modal('hide');
            //location.href = "/Hr/department/index";
        });
//            $("#yes-message").on("click", function(){
//                $("#modal-message").modal('hide');
//            });
    }
    function _changeSelect() {
        $("#Department_employee_id").change(function() {
            if ($(this).val() != "") {
                $("div.radio").removeClass('disabled');
                $(".position-perfil").prop('disabled', false);
                $("#position-select").prop('disabled', false);
                //$("#button-new-edit-position").prop('disabled', false);
                $("#position-select").select2('val', '');
            } else {
                $("div.radio").addClass('disabled');
                $(".position-perfil").parent().removeClass("checked");
                $("#position-select").val(null);
                $(".position-perfil").prop('disabled', true);
                $("#position-select").prop('disabled', true);
                //$("#button-new-edit-position").prop('disabled', true);
                $("#position-select").select2('val', '');
                $("#view-new-edit-position").hide();
            }


        });
    }
    function _saveDepartment() {
        $('#save-department').on('click', function() {

            $.ajax({
                type: 'POST',
                url: "/Hr/Department/saveDepartment",
                data: {"Department[company_id]": $('#Department_company_id').val(),
                    "Department[parent_id]": $('#Department_parent_id').val(),
                    "Department[name]": $('#Department_name').val(),
                    "Department[employee_id]": $('#Department_employee_id').val(),
                    "responsible_type": $('.position-perfil:checked').val(),
                    "position": $("#position-select").val(),
                    "name_position": $("#name_position").val()
                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    if (data.response == "true") {

                        $("#alert-department-1").hide();
                        $("#alert-department-2").hide();
                        $("#alert-department-3").hide();
                        $("#view-new-edit-position").hide();
                        $("#alert-save-department").show('true');


                        $("#Department_company_id").select2('val', '');
                        $("#Department_parent_id").select2('val', '');
                        $("#Department_name").val(null);
                        $("#name_position").val(null);
                        $("#Department_employee_id").select2('val', '');
                        $("#position-select").select2('val', '');

                        var html = "<option value>Seleccione un Cargo</option>";

                        if (data.position != 'null') {
                            console.log(data.position);
                            for (i = 0; i < data.position.length; i++) {
                                html += "<option value=" + data.position[i].name + ">" + data.position[i].name + "</option>";
                            }
                        }

                        //$("#button-new-edit-position").prop('disabled', true);
                        $("div.radio").addClass('disabled');
                        $("#position-select").html(html);
                        $("#position-select").val(null);
                        $("#position-select").prop('disabled', true);
                        $("#name_position").val(null);
                        $(".position-perfil").parent().removeClass("checked");
                        $(".position-perfil").prop('disabled', true);
                        $("#alert-save-department").delay(3000).hide("true");
                        $("#modal-message").modal('show');
                        $.get("/Hr/department/refreshtabledepartment", function(data) {
                            $("#refresh_table_department").html(data);
                            _updateDepartment();
                            _initDataTable('#table_department', 0, 10);
                        });



                    } else {
                        if (data.company == "false" && data.name == "false") {


                            $("div#alert-department-2").hide();
                            $("div#alert-department-3").hide();

                            $("div#alert-department-1").show('true');
                        } else if (data.company == "true" && data.name == "false") {

                            $("div#alert-department-1").hide();
                            $("div#alert-department-3").hide();

                            $("div#alert-department-2").show('true');
                        } else if (data.company == "false" && data.name == "true") {

                            $("div#alert-department-1").hide();
                            $("div#alert-department-2").hide();

                            $("div#alert-department-3").show('true');
                        }

                    }

                }
            });

        });
    }



    function _editDepartment() {
        $('#edit-department').on('click', function() {
            console.log($("#name_position").val());
            $.ajax({
                type: 'POST',
                url: "/Hr/Department/editDepartment",
                data: {"Department[id]": $("#edit-id").html(),
                    "Department[company_id]": $('#edit-company_id').val(),
                    "Department[parent_id]": $('#edit-parent_id').val(),
                    "Department[name]": $('#edit-name-department').val(),
                    "Department[employee_id]": $('#edit-employee_id').val(),
                    "responsible_type": $('.position-perfil:checked').val(),
                    "position": $("#position-select-update").val(),
                    "name_position": $("#edit-name_position").val()
                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    if (data.response == "true") {

                        $("div#alert-department-1").hide();
                        $("div#alert-department-2").hide();
                        $("div#alert-department-3").hide();
                        $("#edit-alert-save-department").show('true');
                        $.get("/Hr/department/refreshtabledepartment", function(data) {
                            $("#refresh_table_department").html(data);
                            _updateDepartment();
                            setTimeout('$("#modal-update-department").modal("hide");', 2000);
                        
                            _initDataTable('#table_department', 0, 10);
                        });
                        //location.reload();
                    } else {
                        if (data.company == "false" && data.name == "false") {
//
                            $("div#alert-department-2").hide('true');
                            $("div#alert-department-3").hide('true');

                            $("div#alert-department-1").show('true');
                        } else if (data.company == "true" && data.name == "false") {

                            $("div#alert-department-1").hide('true');
                            $("div#alert-department-3").hide('true');

                            $("div#alert-department-2").show('true');
                        } else if (data.company == "false" && data.name == "true") {

                            $("div#alert-department-1").hide('true');
                            $("div#alert-department-2").hide('true');

                            $("div#alert-department-3").show('true');
                        }
                    }

                }
            });


        });
    }

    function _newEditPosition() {
        console.log("_newEditPosition()");
        $("#button-new-edit-position").on('click', function() {
            $.get("/Hr/position/create", function(data) {
                console.log(data);
                $("#body-create-position").html(data);
                //_initSelectModalPosition();
                $("#department-opcional").hide();
                $("#company-opcional").hide();
                $("#mensaje-error-position").hide();
                $("#parent-position-opcional").hide();
                _modalMessagePositionYesAndNo();
                $("#modal-create-position").modal('show');
            });

            //$("#view-new-edit-position").show(true);

        });

        //$("#view-new-edit-position").show(true);

    }

    function _newEditPositionEditDepartment() {

        $("#button-edit-position").on('click', function() {

            $("#edit-test-position").show(true);
        });
    }

    function _selectCompanyPosition() {
        $("#Position_company_id").change(function() {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                url: "/Hr/department/DepartmentByCompany",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    var html = "";

                    for (i = 0; i < data.departments.length; i++) {
                        html += "<option value='" + data.departments[i].id + "'>" + data.departments[i].name + "</option>";
                    }
                    $('select#Position_department_id').html("<option value> Seleccione un Departamento </option>" + html
                            )
                }
            });
        });
    }

    function _selectCompanyDepartmentPosition() {
        $(".select-company-department").change(function() {

            var id_company = $("#Position_company_id").val();
            var id_department = $("#Position_department_id").val();

            if (id_company == "" || id_department == "") {
                $("select#Position_position_id").html("<option value> Seleccione un Cargo </option>");
                $("select#Position_position_id").select2('val', '');
            } else {
                $("select#Position_position_id").select2('val', '');
                $.ajax({
                    type: 'GET',
                    url: "/Hr/position/PositionByCompanyAndDepartment",
                    data: {'id_company': id_company, 'id_department': id_department},
                    success: function(data, textStatus, jqXHR) {

                        data = JSON.parse(data);
                        var html = "";

                        for (i = 0; i < data.positions.length; i++) {
                            html += "<option value='" + data.positions[i].id + "'>" + data.positions[i].name + "</option>";
                        }
                        $('select#Position_position_id').html("<option value> Seleccione un Cargo </option>" + html);
                    }
                });

            }

        });
    }
    function _selectCompanyDepartment() {
        $("#Department_company_id").change(function() {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                url: "/Hr/department/DepartmentByCompany",
                data: {'id': id},
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);

                    var html = "";
                    if (data.response != "false") {
                        for (i = 0; i < data.departments.length; i++) {
                            html += "<option value='" + data.departments[i].id + "'>" + data.departments[i].name + "</option>";
                        }
                    }

                    $('select#Department_parent_id').html("<option value> Seleccione un Departamento </option>" + html
                            )
                }
            });
            $.ajax({
                type: 'GET',
                url:"/Hr/employee/EmployeeByCompany",
                data: {'id':id},
                success: function (data, textStatus, jqXHR) {
                    data=JSON.parse(data);
                    var html_employee = "";
                    if(data.response != "false") {
                        for(i =0; i<data.employees.length; i++){
                            var name = JSON.parse(data.employees[i].personal_data).name;
                            var surname = JSON.parse(data.employees[i].personal_data).surname;
                            html_employee += "<option value='"+ data.employees[i].id+"'>" + name +" "+ surname + "</option>"; 
                        }
                    }
                    $('select#Department_employee_id').html("<option value> Seleccione un Responsable </option>" + html_employee)
                }
            });
        });
    }

    function _contanciasTrabajo()
    {
        $('a#ingresoMonto').on('click', function() {
            $('#mensajeConfir').removeClass("alert alert-danger rojo verde alert-success");
            $('#mensajeConfir').html("");
            $('#ingreseSueldo').val('');
            $('#ingreseSueldo').mask("#.##0,00", {reverse: true});

            var id = $(this).find('div#idSolicitudeProof').text();
            var idEmployee = $(this).find('div#idEmployee').text();
            var nameProof = $(this).find('div#nombreProof').text();
            var cedulaProof = $(this).find('div#cedulaProof').text();
            var fechaProof = $(this).find('div#fechaProof').text();
            var posicionProof = $(this).find('div#posicionProof').text();
            var divisionProof = $(this).find('div#divisionProof').text();
            var fechaSProof = $(this).find('div#fechaSProof').text();
            var motivoProof = $(this).find('div#motivoProof').text();
            var dirigidoProof = $(this).find('div#dirigidoProof').text();
            var statusProof = $(this).find('div#statusProof').text();
            var fechaAdminision = $(this).find('div#dateAdminProof').text();
            //var typeContratacion = $(this).find('div#type_contratado_Proof').text();

            if (fechaAdminision != '')
            {
                $('#fechaProofModal').attr('disabled', true);
            }
            else
            {
                $('#fechaProofModal').attr('disabled', false);
            }

            if (statusProof == "Confirmada")
            {

//                     $('.noProce').addClass("disabled");
//                     $('#status_enp').attr("disabled",false);
//                     $('.prueba').addClass("disabled");
            }

            if (statusProof == "Confirmada" || statusProof == "Impresa" || statusProof == "Por Retirar" || statusProof == "Entregada" || statusProof == "No Entregada")
            {
                $('.noProce').addClass("disabled");
                $('.noViews').addClass("disabled");
                $('#status_enp').attr("disabled", false);
            }
            else
            {
                $('.noProce').removeClass("disabled");
                $('.noViews').removeClass("disabled");
            }


            if (statusProof == "No Procede")
            {
                $('.noProce').addClass("disabled");
                $('.noViews').addClass("disabled");
                $('.noPv').addClass("disabled");
                $('#status_enp').attr("disabled", false);

            }
            else
            {
                $('.noPv').removeClass("disabled");
            }
            $('div#idEmployeeModal').text(idEmployee);
            $('div#nombreProofModal').text(nameProof);
            $('div#cedulaProofModal').text(formatNumber.new(cedulaProof));
            $('div#fechaProofModal').text(fechaProof);
            $('div#posicionProofModal').text(posicionProof);
            $('div#divisionProofModal').text(divisionProof);
            $('div#fechaSProofModal').text(fechaSProof);
            $('div#motivoProofModal').text(motivoProof);
            $('div#dirigidoProofModal').text(dirigidoProof);
            $('div#idConstanciaModal').text(id);
            $('div#statusProofModal').text(statusProof);
            $('#fechaProofModal').text(fechaAdminision);
//                $("#type_contra").select2('val', typeContratacion);

            $('#responsiveApro').modal("show");

            var id = $(this).find('div#idSolicitudeProof').text();
            var fecha = null;
//            _callBackConstancias('/employee/changeStatusEnProceso', id, false, fecha, null);
            console.log("entrara a _changeSigners()");
            _changeSigners();
        });
    }
    
    function _changeSigners()
    {
        $( "#edit-signers_id").unbind( "change" );
        $('select#edit-signers_id').on('change', function() {
            console.log("le dio");
            $( "#edit-signers_id").bind( "change" );
            $.ajax({
                type: 'GET',
                url: "/employee/saveselectionsigner",
                data: {'idEmployee':$(this).val()},
                success: function(data, textStatus, jqXHR) {
                    obj = JSON.parse(data);
                    console.log(data.signer_position);
                    console.log(obj, data);
                    $('div#signer_nameimp').text(obj.signer_name);
                    $('div#signer_positionimp').text(obj.signer_position);
                    $('div#signer_name p').text(obj.signer_name);
                    $('div#signer_position p').text(obj.signer_position);
                    console.log(obj.signer_position);
                }
            });
//         console.log($('#edit-signers_id option:selected').text());
        }); 
    }


    function _fueraConstancia()
    {
        $('a#constanciaConfirmar').on('click', function() {
            var id = $('div#idConstanciaModal').text();
            var sueldo = $('#ingreseSueldo').val();
            var fecha = $('#fechaProofModal').text();
            var type_contra = $('#type_contra').val();


            var payMont = JSON.parse($ARU.AJAX.sessionPayRollEmployee("GET", "/Employee/sessionPayRollEmployee?sueldo=" + sueldo + '&id=' + id));

            if (sueldo !== '')
            {
                _callBackConstancias('/employee/ChangeStatusConfirmada', id, true, fecha, type_contra);
            }
            else
            {
                $('#mensajeConfir').addClass("alert alert-danger");
                $('#mensajeConfir').addClass("rojo");
                $('#mensajeConfir').html("<h4>Todos los campos son requeridos</h4>");
                $('#mensajeConfir').show();

            }
        });

      
        $('a#imprimirConstanciaProof').on('click', function() {
            
            
              var dirigidoProofImpri= $(this).find('div#dirigidoProofImpri').text();
//                console.log (dirigidoProofImpri);
                var nameProofImpri = $(this).find('div#nombreProofImpri').text();
                console.log (nameProofImpri);
                var cedulaProofImpri = $(this).find('div#cedulaProofImpri').text();
            //    console.log (cedulaProofImpri);
                var fechaProofImpri = $(this).find('div#dateAdminProof').text();
               // console.log (fechaProofImpri);
                var posicionProofImpri = $(this).find('div#posicionProofImpri').text();
              //  console.log (posicionProofImpri);
           
               
            var id = $(this).find('div#idSolicitudeProofImpri').text();
            var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
            var f = new Date();
//
//           
//            
//            var nameProofImpri = $(this).find('div#nombreProofImpri').text();
//            var cedulaProofImpri = $(this).find('div#cedulaProofImpri').text();
//            var fechaProofImpri = $(this).find('div#fechaProofImpri').text();
//            var posicionProofImpri = $(this).find('div#posicionProofImpri').text();
//            var divisionProofImpri = $(this).find('div#divisionProofImpri').text();
//            var fechaSProofImpri = $(this).find('div#fechaSProofImpri').text();
//            var motivoProofImpri = $(this).find('div#motivoProofImpri').text();
//  var dirigidoProofImpri = $(this).find('div#dirigidoProofImpri').text();
//            var statusProofImpri = $(this).find('div#statusProofImpri').text();
//            var typecontratadoImpri = $(this).find('div#type_contratado_Impri').text();
//     
//
////              var nombreConfiConst=$('#nombreProofModal').text();
////              var cedulaConfiConst=$('#cedulaProofModal').text();
////              var fechaConfiConst=$('#fechaProofModal').text();
////              var posicionConfiConst=$('#posicionProofModal').text();
////              var dirigidoConfiConst=$('#dirigidoProofModal').text();
//
            $('#nombreConstanciaImpri').text(nameProofImpri);
            $('#cedulaConstanciaImpri').text(cedulaProofImpri);  
            $('#fechaConstanciaImpri').text(fechaProofImpri);
//            $('#typeContratadoImpri').text(typecontratadoImpri);
            $('#cargoConstanciaImpri').text(posicionProofImpri);
          //  $('#dirigidaConstanciaImpri').text(dirigido);
          // console.log(dirigida);
            $('#dirigidaConstanciaImpri').text(dirigidoProofImpri);
////             console.log($('#dirigidoProofImpri').text());
////            console.log($('#suelLetra').text());
//            var sueldo = $('#suelNumero').text();
//            
            var fecha = null;
//
            var sueldo = $('#ingreseSueldo').val();
//
//        
            var payMont = JSON.parse($ARU.AJAX.montPay("GET", "/employee/montConstaNumPay", "&sueldo=" + sueldo));
            
     
            $('#suelLetra1Impri').html(payMont.sueldo);
            $('#mesLetra1Impri').html(meses[f.getMonth()]);
            $('#suelNumero1Impri').html(sueldo);
 
    
              
            
            var ficha = $('#constanciaTrabajoImprimir1');
            console.log(ficha);
            var ventimp = window.open(' ', 'popimpr');
            console.log(ventimp);
            ventimp.document.write($('#constanciaTrabajoImprimir1').html());
            ventimp.document.close();
            ventimp.print();
            ventimp.close();

            _callBackConstancias('/employee/ChangeStatusImpresa', id, false, fecha, null);
        });



        $('a#PDFconstanciaView').on('click', function() {


          
            var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            var diasSemana = new Array("Domingo", "Lunes", "Martes", "MiÃ©rcoles", "Jueves", "Viernes", "SÃ¡bado");
            var f = new Date();

            var IdEmployeeConst = $('#idEmployeeModal').text();
            var nombreConfiConst = $('#nombreProofModal').text();
            var cedulaConfiConst = $('#cedulaProofModal').text();
            var fechaConfiConst = $('#fechaProofModal').text();
            var type_contra = $('#type_contra option:selected').val();
            var posicionConfiConst = $('#posicionProofModal').text();
            var dirigidoConfiConst = $('#dirigidoProofModal').text();
            
            
    
            $('#idEmployeeView').text(IdEmployeeConst);
            $('#idEmployeePdf').text(IdEmployeeConst);
            $('#nombreConstanciaView').text(nombreConfiConst);
            $('#nombreConstanciaImprimir').text(nombreConfiConst);
            $('#nombreConstanciaPdf').text(nombreConfiConst);
            $('#cedulaConstanciaView').text(cedulaConfiConst);
            $('#cedulaConstanciaImprimir').text(cedulaConfiConst);
            $('#cedulaConstanciaPdf').text(cedulaConfiConst);
            $('#fechaConstanciaView').text(fechaConfiConst);
            $('#fechaConstanciaImprimir').text(fechaConfiConst);
            $('#fechaConstanciaPdf').val(fechaConfiConst);
            $('#type_contraView').text(type_contra);
            $('#cargoConstanciaView').text(posicionConfiConst);
            $('#cargoConstanciaImprimir').text(posicionConfiConst);
            $('#cargoConstanciaPdf').text(posicionConfiConst);
            $('#dirigidaConstanciaView').text(dirigidoConfiConst);
            $('#dirigidaConstanciaPdf').text(dirigidoConfiConst);
            //console.log(dirigidoConfiConst);
            $('#dirigidaConstanciaImprimir').text(dirigidoConfiConst);
            
//            $.ajax({
//                type: 'GET',
//                url: '/employee/getDataSigner',
//                success: function(data, textStatus, jqXHR) {
//                    result = JSON.parse(data);
//                    console.log(result.signer_name);
//                    $('div#signer_nameimp').text(result.signer_name);
//                    $('div#signer_positionimp').text(result.signer_position);
//                    $('div#signer_name p').text(result.signer_name);
//                    $('div#signer_name p').text("rossmary prana");
//                    $('div#signer_position p').text(result.signer_position);
//                }
//            });
            
           $('#sueldo').mask("#.##0,00", {reverse: true});

            var sueldo = $('#ingreseSueldo').val();
//            var sueldo = sueldo.number( 2000.6156, 2 )
         
            if (sueldo != '' && type_contra != '0')
            {
                var payMont = JSON.parse($ARU.AJAX.montPay("GET", "/employee/montConstaNumPay", "&sueldo=" + sueldo));
                $('#suelLetra').html(payMont.sueldo);
                $('#mesLetra').html(meses[f.getMonth()]);
                console.log(meses[f.getMonth()]);
                $('#suelNumero').html(sueldo);
                $('#responsiveConfir').modal("show");
                $('#sueldoletraPDF').text(payMont.sueldo);
                console.log($('#sueldoletraPDF').text());
            }
            else
            {
                $('#mensajeConfir').addClass("alert alert-danger");
                $('#mensajeConfir').addClass("rojo");
                $('#mensajeConfir').html("<h4>Todos los campos son requeridos</h4>");
                $('#mensajeConfir').show();
            }


        });

        $('a#PDFconstanciaConfir11').on('click', function()
        {

            var idEmployee = $(this).find('div#idEmployeePDF').text();
            var fechaSolicitudPDF = $(this).find('div#fechaSolicitudPDF').text();

            var nameTable = "PDFconstancia";
            if (nameTable == 'PDFconstancia')
            {
                var name = "proofEmployee";
            }
            var value = null;
            var dirigido = $(this).find('div#dirigidaConstanciaPdf').text();
            var idEmployee = $(this).find('div#idEmployeePdf').text();
            //console.log(idEmployee);
            var id = $(this).find('div#idsolicitud').text();
            var sueldo = $('#ingreseSueldo').val();
            var fechaIngreso = $('#fechaProofModal').text();
            var sueldoletraPDF = $('#sueldoletraPDF').text();



//            AQUI ES EL QUE FUNCIONA
            var url = '?data=' + value + '&name=' + name + '&nameTable=' + name + '&dirigido=' + dirigido +  '&sueldo=' + sueldo + '&sueldoletraPDF='+ sueldoletraPDF + '&id=' + id + '&idEmployee=' + idEmployee + '&fechaSolicitudPDF=' + fechaSolicitudPDF + '&fechaIngreso=' + fechaIngreso ;
           window.open('/report/export/pdf' + url, '_blank');

            var sueldo = $('#ingreseSueldo').val();

            if (sueldo != '')
            {
                var nameTable = "PDFconstancia";
                if (nameTable == 'PDFconstancia')
                {
                    var name = "proofEmployee";
                }
                var value = null;
                var dirigido = $('#dirigidoPDF').text();
                //console.log(dirigido);
                var sueldo = $('#ingreseSueldo').val();

//                var url = '?data=' + value + '&name=' + name + '&nameTable=' + name + '&dirigido=' + dirigido + '&sueldo=' + sueldo + '&idEmployee=' + idEmployee + '&fechaSolicitudPDF=' + fechaSolicitudPDF;
//                window.open('/report/export/pdf' + url, '_blank');
            }
            else
            {
                $('#mensajeConfir').addClass("alert alert-danger");
                $('#mensajeConfir').addClass("rojo");
                $('#mensajeConfir').html("Ingrese el Sueldo mensual del colaborador");
                $('#mensajeConfir').show();
                $('#mensajeConfir').fadeToggle(6000);
            }

        });


        $('a#constanciaNoProcede').on('click', function() {
            var id = $('div#idConstanciaModal').text();
            $('#mensajeNoProcede').removeClass("alert alert-danger rojo verde alert-success");
            $('#mensajeNoProcede').addClass("ocultar");
            $('#idConstanciaNoProcede').text(id);
            $('#responsiveNoProcede').modal('show');
        });


        $('a#observacionNoProcede').on('click', function() {

            $('#mensajeNoProcede').removeClass("alert alert-danger rojo verde alert-success");
            $('#mensajeNoProcede').addClass("ocultar");

            var observacion = $('#observacionNprocede').val();
            var id = $('#idConstanciaNoProcede').text();

            if (observacion != '')
            {

                $.ajax({
                    type: 'GET',
                    url: '/employee/ChangeStatusNoProcede',
                    data: {id: id, observacion: observacion},
                    success: function(data, textStatus, jqXHR) {
                        result = JSON.parse(data);
                        if (result.response) {

                            $('#mensajeNoProcede').removeClass("ocultar");
                            $('#mensajeNoProcede').addClass("alert alert-success");
                            $('#mensajeNoProcede').addClass("verde");
                            $('#mensajeNoProcede').html("<h4>Proceso Exitoso</h4>");
                            $('#mensajeNoProcede').show();

                            $('#admin_refresh_constancia_1').load('/Employee/AdminProofEmployee #admin_constancia_1', function() {
                                _initDataTable('#admin_constancia_1', 1, 5);
                            });
                            $('#admin_refresh_constancia_2').load('/Employee/AdminProofEmployee #admin_constancia_2', function() {
                                _initDataTable('#admin_constancia_1', 1, 5);
                            });
                            $('#admin_refresh_constancia_3').load('/Employee/AdminProofEmployee #admin_constancia_3', function() {
                                _initDataTable('#admin_constancia_3', 1, 5);
                            });

                        }
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '/employee/SetObservation',
                    data: {id_constancy: id, observation: observacion},
                    success: function(data, textStatus, jqXHR) {
                        result = JSON.parse(data);
                        console.log(result);
                        
                    }
                });
                setTimeout('$("#responsiveNoProcede").modal("hide");', 1500);
                setTimeout('$("#responsiveApro").modal("hide");', 2000);

            }
            else
            {
                $('#mensajeNoProcede').removeClass("ocultar");
                $('#mensajeNoProcede').addClass("alert alert-danger");
                $('#mensajeNoProcede').addClass("rojo");
                $('#mensajeNoProcede').html("<h4>El campo comentario/Observación es requerido</h4>");
                $('#mensajeNoProcede').show();
            }


        });

        $('a#noProcedeConstancia').on('click', function() {

            var nombreProofNoProcede = $(this).find('div#nombreProofNoProcede').text();
            var dirigidoProofNoProcede = $(this).find('div#dirigidoProofNoProcede').text();
            var motivoProofNoProcede = $(this).find('div#motivoProofNoProcede').text();
            var observacionProofNoProcede = $(this).find('div#observacionProofNoProcede').text();


            $('#colaboradorNoproce').text(nombreProofNoProcede);
            $('#dirigidoNoproce').text(dirigidoProofNoProcede);
            $('#motivoNoproce').text(motivoProofNoProcede);
            $('#observacion_noprocede').val(observacionProofNoProcede);

            console.log(nombreProofNoProcede);
            $('#mensaje_NoProcede').modal('show');

        });



    }

    function _cambioStatus() {
        $('table#admin_constancia_1 a#status_enp_1, table#admin_constancia_2 a#status_enp_2').on('click', function() {
//              var status = $('#status_enp option:selected').val();
              var click = $(this).attr("id");
//              console.log($(this));
           // var id = $('div#idConstanciaModal').text();
              var id = $(this).parent().parent().parent().attr("id"); 
       
              var direccion;
              
//            if (status == 1) {
//                direccion = "/employee/ChangeStatusAbierta";
//            }
            if (click == 'status_enp_1') {
                direccion = "/employee/ChangeStatusPorRetirar";
                
              
            }
            if (click == 'status_enp_2') {
                direccion = "/employee/ChangeStatusEntregada";

            }
//              if (status == 4) {
//                direccion = "/employee/ChangeStatusPorFirmar";
//            }
            
//            if (status == 2) {
//                direccion = "/employee/ChangeStatusNoEntregada";
//            }
            var fecha = null;
            _callBackConstancias(direccion, id, true, fecha, null);

        });
    }



    function _callBackConstancias(direccion, id, status, click, fecha, datos)
    { 
        $.ajax({
            type: 'GET',
            url: direccion,
            data: {id: id, fecha: fecha, datos: datos},
            success: function(data, textStatus, jqXHR) {
                    location.reload();
//                result = JSON.parse(data);
//           
//                if (result.response) {
//
//                    $('#admin_refresh_constancia_1').load('/Employee/AdminProofEmployee #admin_constancia_1', function() {
//                        _initDataTable('#admin_constancia_1', 1, 5, "desc");
//                    });                                                                               
//                    $('#admin_refresh_constancia_2').load('/Employee/AdminProofEmployee #admin_constancia_2', function() {
//                        _initDataTable('#admin_constancia_2', 1, 5, "desc");    
//                    });   
//                    $('#admin_refresh_constancia_3').load('/Employee/AdminProofEmployee #admin_constancia_3', function() {
//                        _initDataTable('#admin_constancia_3', 1, 5, "desc");  
//                    });    
//                        if (direccion == '/employee/changeStatusEnProceso')
//                        {
//                           // _cambioStatus();
//                        }
//                        else
//                        {
                            $('#mensajeConfir').addClass("alert alert-success");
                            $('#mensajeConfir').addClass("verde");
                            $('#mensajeConfir').html("<h4>Cambio de Estatus Exitoso</h4>");
                            $('#mensajeConfir').show();
                            $("#status_enp").select2('val', '0');
                            
                            setTimeout('$("#responsiveApro").modal("hide");', 1500);
                        }
//                        _cambioStatus();

//                    }
//
//                }
            });
         
    }

    function _SendConstan()
    {
        $('a#enviarConstan').on('click', function() {

            var addressed = $('#idDirigido').val();
            var motivo = $('#motivo option:selected').text();
            var reason = $('#motivo option:selected').val();
            var reason_email = $('#motivo option:selected').text();
            var identity = $(".modal-body label.cedula-employee").text();

            if (addressed != '' && reason != '')
            {
                $ARU.AJAX.SaveProofEmployee("GET", "/Employee/proofemployeesave?addressed=" + addressed + '&reason=' + reason+ '&reason_email=' + reason_email + '&identity_email=' + identity);
            }
            else
            {
                $('#mensajeConfirConstancia').removeClass("ocultar");
                $('#mensajeConfirConstancia').addClass("alert alert-danger");
                $('#mensajeConfirConstancia').addClass("rojo");
                $('#mensajeConfirConstancia').html("<h4>Todos los campos son requeridos</h4>");

            }

        });
    }


    function _genUserNameDinamyc()
    {
        if ($('div#notificationUser').length) {
            setTimeout('$("div#notificationUser").hide("slow");', 2000);
        }
        $("input#Users_id_employee").on('keyup', function() {
            var value = $(this).val();
            console.log(value);
            $.ajax({
                'url': '/Users/DinamycUsername',
                'type': 'POST',
                'data': {'name': value},
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data != 'Empty') {
                        $("input#Users_username").val(data);
                        $("input#Users_email").val(data);
                    } else {
                        $("input#Users_username").val('');
                        $("input#Users_email").val('');
                    }
                }
            });

        }).keyup();
    }


    function _getPDFConstancia()
    {
        $('a#PDFconstancia').on('click', function() {

            $('#mensajeConfirConstancia').addClass("ocultar");
            $("#idDirigido").val('');
            $("#motivo").select2('val', '');
            $('#ModalProofEmployee').modal("show");


        });
        $('a#PDFconstanciaConfir11').on('click', function()
        {
            var idEmployee = $(this).find('div#idEmployeePDF').text();
            var fechaSolicitudPDF = $(this).find('div#fechaSolicitudPDF').text();

            var nameTable = "PDFconstancia";
            if (nameTable == 'PDFconstancia')
            {
                var name = "proofEmployee";
            }
            var value = null;
            var dirigido = $(this).find('div#dirigidoPDF').text();

            var id = $(this).find('div#idsolicitud').text();



            var sueldo = $('#ingreseSueldo').val();
//             var url = '?data='+value+'&name='+name+'&nameTable='+name+'&dirigidoC='+dirigido+'&sueldo='+sueldo+'&id='+id+'&idEmployee='+idEmployee+'&fechaSolicitudPDF='+fechaSolicitudPDF;
//             window.open('/report/export/pdf'+url ,'_blank');

            var sueldo = $('#ingreseSueldo').val();
            var sueldoletra = $('#suelLetra').val();

            if (sueldo != '')
            {
                var nameTable = "PDFconstancia";
                if (nameTable == 'PDFconstancia')
                {
                    var name = "proofEmployee";
                }
                var value = null;
                var dirigido = $('#idDirigido').val();
//             console.log (dirigido);
                var sueldo = $('#ingreseSueldo').val();

//             var url = '?data='+value+'&name='+name+'&nameTable='+name+'&dirigido='+dirigido+'&sueldo='+sueldo+'&idEmployee='+idEmployee+'&fechaSolicitudPDF='+fechaSolicitudPDF;
//             window.open('/report/export/pdf'+url ,'_blank');
            }
            else
            {
                $('#mensajeConfir').addClass("alert alert-danger");
                $('#mensajeConfir').addClass("rojo");
                $('#mensajeConfir').html("Ingrese el Sueldo mensual del colaborador");
                $('#mensajeConfir').show();
                $('#mensajeConfir').fadeToggle(6000);
            }

        });

    }

    function _genUserNameDinamyc()
    {
        if ($('div#notificationUser').length) {
            setTimeout('$("div#notificationUser").hide("slow");', 2000);
        }
        $("input#Users_id_employee").on('keyup', function() {
            var value = $(this).val();
            console.log(value);
            $.ajax({
                'url': '/Users/DinamycUsername',
                'type': 'POST',
                'data': {'name': value},
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data != 'Empty') {
                        $("input#Users_username").val(data);
                        $("input#Users_email").val(data);
                    } else {
                        $("input#Users_username").val('');
                        $("input#Users_email").val('');
                    }
                }
            });

        }).keyup();
    }

    function _esquemaNumeros()
    {
        $('.classSueldo').keyup(function() {
            this.value = (this.value + '').replace(/[^0-9.,]/g, '');
        });
    }
    function _filtrarDirectoryPhone()
    {
        $('#seleFiltroDirectory').on('change', function() {
            var filtrar = $('#seleFiltroDirectory').val();

            if (filtrar == 'nombre') {
                $('#employeeActive').load('/Employee/RefreshGetAllEmployeeByDirectory #tableDirectoryPhone', function() {
                    Metronic.init();
                    _initDataTable('#tableDirectoryPhone', 1, 5);
                    _deleteAsigTelf();
                });
            }
            if (filtrar == 'cp') {
                $('#employeeActive').load('/Employee/RefreshGetAllEmployeeByDirectory #tableDirectoryPhone', function() {
                    Metronic.init();
                    _initDataTable('#tableDirectoryPhone', 0, 5);
                    _deleteAsigTelf();
                });
            }

        });
    }

    function _asigTelfCorp()
    {
        $('a#optionDirectori').on('click', function(event)
        {
            var nameEmployee = $(this).parent().parent().find('td').find('div#nameModalModification').text();
            var position = $(this).parent().parent().find('td').find('div#position').text();
            var idEmployee = $(this).parent().parent().find('td').find('div#idEmployee').text();
            $("#Employee_cod_phone").select2('val', '');
            $("#Employee_cellphone").html(" ");
            $("#Employee_home_phone").html(" ");
            $('#idEmployeeTelf').html(idEmployee);
            $('#nombreEmpleadoDirectorioTelef').html("<h4><span class='verde'>Colaborador (PosiciÃ³n): </span>" + nameEmployee + " (" + position + ")" + '</h4>');
//            $('#positionEmpleadoDirectorioTelef').html("<h4><span class='verde'>PosiciÃ³n: </span>"+position+'</h4>');
            $('#tituloAsignarTeflCorp').html('<h4><i class="fa fa-phone-square azul"></i>  Asignaci&oacute;n de TelefÃ³no Corporativo</h4>');
            $('#asigTelfCorpo').modal('show');
        });

    }

    function _btnAsigTelf()
    {
        $('a#btnAsigTelf').on('click', function(event)
        {
            var idEmployee = $('div#idEmployeeTelf').text();
            var cellphoneCorp = $('#Employee_cellphone').val();
            var homephone = $('#Employee_home_phone').val();

            if (cellphoneCorp == '') {
                cellphoneCorp = "Empty";
            }
            if (homephone == '') {
                homephone = "Empty";
            }
            if (idEmployee == '') {
                idEmployee = "Empty";
            }


            $ARU.AJAX.SaveDirectoryTelfByEmployee("GET", "/Employee/SaveDirectoryTelf?idEmployee=" + idEmployee + '&cellphoneCorp=' + cellphoneCorp + '&homephone=' + homephone);

        });

    }

    function _deleteAsigTelf()
    {
        $('a#positionPhoneCorp').on('click', function(event)
        {
            var positionTelf = $(this).find('div.positionPhone').text();
            var typeTelf = $(this).find('div.telfCorp').text();
            var idEmployee = $(this).parent().parent().find('td').find('div#idEmployee').text();

            if (positionTelf != '') {
                $ARU.AJAX.deleteAsigTelfAjax("GET", "/Employee/deleteAsigTelf?positionTelf=" + positionTelf + '&idEmployee=' + idEmployee + '&typeTelf=' + typeTelf);

            }
        });


    }




    function _holiday()
    {
        var date = new Date();
        var d = date.getDate();
        var url = '/CountryHoliday/HolidayDate';
        var mj = date.getMonth();
        var y = date.getFullYear();

        var h = {};

        $('#calendarHoliday').fullCalendar('destroy'); // destroy the calendar
        $('#calendarHoliday').fullCalendar({ //re-initialize the calendar

            defaultView: 'month',
            slotMinutes: 15,
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
//                events: '',
            dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            buttonText: {
                today: "Hoy",
            },
            events: function(start, end, callback) {
                var m = new Date($('#calendarHoliday').fullCalendar('getDate')).getMonth() + 1;
                var y = new Date($('#calendarHoliday').fullCalendar('getDate')).getFullYear();
                if (m < 10) {
                    m = '0' + m
                }
                $.ajax({
                    url: url,
                    data: 'HolidayYear=' + y,
                    success: function(response) {

                        callback(JSON.parse(response));
                        console.log(response);
                    }
                });

            },
            eventClick: function(calEvent, jsEvent, view) {

                console.log(calEvent.id);

            },
            eventRender: function(event, element) {
                $(element).find(".fc-event-time").remove();
//                    $(element).find(".fc-event-inner").attr('style', 'background-color:#ff0000; font-weight: bold');

            }

        });

    }



    function _solicitudVacaciones()
    {
        var date = new Date();
        var d = date.getDate();
        var url = '';
        var mj = date.getMonth();
        var y = date.getFullYear();

        var h = {};

        $('#calendarVacaciones').fullCalendar('destroy'); // destroy the calendar
        $('#calendarVacaciones').fullCalendar({ //re-initialize the calendar

            defaultView: 'month',
            slotMinutes: 15,
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
//                events: '/Employee/BirthDateAjax?birthMont='+m,
            dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            buttonText: {
                today: "Hoy",
            },
            events: function(start, end, callback) {
                var m = new Date($('#calendarVacaciones').fullCalendar('getDate')).getMonth() + 1;
                var y = new Date($('#calendar').fullCalendar('getDate')).getFullYear();
                if (m < 10) {
                    m = '0' + m
                }
                $.ajax({
                    url: url,
                    data: 'birthMont=' + m,
                    success: function(response) {
                        callback(JSON.parse(response));
                        console.log(response);
                    }
                });

            },
            eventClick: function(calEvent, jsEvent, view) {

                console.log(calEvent.id);

            },
            eventRender: function(event, element) {
                $(element).find(".fc-event-time").remove();
                $(element).find(".fc-event-inner").attr('style', 'background-color:#1bbc9b; font-weight: bold');

            }

        });

    }

    function _changeStaticHour() {

        $('div#selectHorariosCheckByDay input').on('click', function() {
            if ($(this).is(":checked")) {
                var idHour = $(this).val();
                var idEmployee = $('#idEmployeeAsig').text();
                $ARU.AJAX.saveHourEmployeeByDay("GET", '/EmployeeHour/SetStaticHourEmployee?idEmployee=' + idEmployee + '&idHour=' + idHour);
            }
        });
    }

    function _leyendShow() {

        $('#titleColor').on('click', function() {
            if ($('div#leyendColor').is(":visible")) {
                $('div#leyendColor').slideUp("slow");
            } else {
                $('div#leyendColor').slideDown("slow");
            }
        });

        $('#titleHour').on('click', function() {
            if ($('div#leyendHour').is(":visible")) {
                $('div#leyendHour').slideUp("slow");
            } else {
                $('div#leyendHour').slideDown("slow");
            }
        });

    }


    function _saveHorarioDay()
    {
        $('a#saveHorarioEmployeeByDay').on('click', function(event)
        {
            var idEmployee = $('#idEmployee').text();
            var idEmployeeHour = $('#hourEmployee').val();
            var endDate = $('#fecha_desd_hour').val();
            var startDate = $('#fecha_ini_hour').val();

            if (idEmployeeHour == '') {
                idEmployeeHour = "Empty";
            }
            if (idEmployee == '') {
                idEmployee = "Empty";
            }
            if (endDate == '') {
                endDate = "Empty";
            }
            if (startDate == '') {
                startDate = "Empty";
            }

            $ARU.AJAX.saveHourEmployeeByDay("GET", "/EmployeeHoursDay/SaveHourEmployeeDay?idEmployeeHour=" + idEmployeeHour + '&startDate=' + startDate + '&endDate=' + endDate);

        });
    }


    function _optionHourEmployee()
    {

        $('a#asigCalenderHourEmployee').on('click', function(event)
        {

            var idEmployee = $(this).parent().parent().find('td input.checkboxes').val();
            var nameEmployee = $(this).parent().parent().find('td:nth-child(3)').text();
            $('#nameEmployeeHour').html('<h4>  (' + nameEmployee + ') </h4>');
            $('#fecha_ini_hour').val('');
            $('div#selectHorarios').load('/Hour/RefreshGetHoursByEmployee?idEmployee=' + idEmployee, function() {


            });

            $('#idEmployee').html(idEmployee);

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var url = '/EmployeeHoursDay/EmployeeHourDateAjax';

            $('#asignacionHorario').modal('show');
            $('#asignacionHorario').on('show.bs.modal', function(e) {


                $('#calendarAsignacion').fullCalendar('destroy');

                $('#calendarAsignacion').fullCalendar({
                    lang: 'es',
                    height: 400,
                    defaultView: 'month',
                    slotMinutes: 15,
                    editable: false,
                    droppable: false,
                    dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                    monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    buttonText: {
                        today: "Hoy",
                    },
                    events: function(start, end, callback) {
                        m = new Date($('#calendarAsignacion').fullCalendar('getDate')).getMonth() + 1;
                        var y = new Date($('#calendarAsignacion').fullCalendar('getDate')).getFullYear();
                        var idEmployee = $('div#idEmployee').text();
                        console.log(m + ' ' + y);
                        if (m < 10) {
                            m = '0' + m
                        }
                        $.ajax({
                            url: url,
                            data: 'workDate=' + m + '&workYear=' + y + '&idEmployee=' + idEmployee,
                            success: function(response) {
                                callback(JSON.parse(response));

                            }
                        });
                    },
                    eventClick: function(calEvent, jsEvent, view) {

                        var id = calEvent.id;

                        $('#otroModal').modal('show');
                        $('a#botonok').on('click', function(event)
                        {
                            $.ajax({
                                url: '/EmployeeHoursDay/DeleteHourEmployeeDay',
                                data: 'id=' + id,
                                success: function(response) {

                                    $('#calendarAsignacion').fullCalendar('refetchEvents');
                                    $("#otroModal").modal("hide");
                                }
                            });

                        });
                    },
                    eventRender: function(event, element) {
                        $(element).find(".fc-event-time").remove();
                        $(element).find(".fc-event-inner").attr('style', 'background-color:#AAAAAA;');
                    }

                });

            });

        });


        $('a#updateHourEmployee').on('click', function(event) {

            $('#start_time_break').val('');
            $('#end_time_break').val('');
            var nameEmployee = $(this).parent().parent().find('td:nth-child(3)').text();
            var idEmployee = $(this).parent().parent().find('td input.checkboxes').val();

            $('#titleModalModificarModal').html("<h4>Actualizar Horario del Colaborador (" + nameEmployee + ")</h4>");

            $('#UpdateHourEmployeeModal').modal('show');

            $('div#selectHorariosCheckByDay').load('/Hour/getEmployeeHoursCheckByDay?idEmployee=' + idEmployee, function() {
                Metronic.init();
                _changeStaticHour();
                Metronic.init();
                ComponentsPickers.init();
                _asigHourDescanso();
            });

            $('#idEmployeeAsig').html(idEmployee);

        });

    }

    function _asigHourDescanso()
    {

        $('.btonAsiHourBreak').on('click', function(event)
        {


            var start_time = $(this).parent().parent().find('td input.rangoTiempoInicio').val();
            var start_end = $(this).parent().parent().find('td input.rangoTiempoFin').val();
            var idEmployeeHour = $(this).find('input.btonAsignarDescanso').attr('id');
            var idEmployee = $('#idEmployeeAsig').text();
            $ARU.AJAX.asignacionDescansoHour("GET", "/EmployeeHour/SaveBreakHour?startBreak=" + start_time + '&endBreak=' + start_end + '&idEmployeeHour=' + idEmployeeHour);


        });

    }

    function _deleteHour() {

        $('a#deleteHourEmployee').on('click', function(event)
        {
            var idHour = $(this).find('div.idHour').text();
            if (idHour != '') {

                var idEmployee = $(this).parent().parent().find('td input.checkboxes').val();
                $ARU.AJAX.deleteHourEmployee("GET", "/EmployeeHour/deleteHourEmployee?idHour=" + idHour + '&idEmployee=' + idEmployee);

            }

        });
    }

    function _getEmployeeHours()
    {

        $('a#botonAsignacionHorarios').on('click', function(event)
        {
            $("#asignacionDescanso").select2('val', '');
            $('#contenidoHorarios').removeClass('ocultar');
            $('#successHorariosMensajeHorario').addClass('ocultar');
            $('#successHorariosMensaje').addClass('ocultar');
            $('#sindataAsiganacion').addClass('ocultar');
            var nameTable = $('table.dataTable').attr('id');
            var arrayIdEmployee = new Array();
            var oTableCkeck = $('#' + nameTable).dataTable();
            var asignacionHoras = $('#asignacionHours').val();

            var textAsignacionHoras = $('#asignacionHours option:selected').text();
            var arrayName = new Array();

            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayName[index] = '<div style="font-size:15px">' + $(this).parent().parent().parent().find('#nameModalModification').text() + '</div>';
            });

            console.log(arrayName)
            if ((arrayName != '') && (asignacionHoras != '')) {
                $('#tituloConfirAsigHorario').html("<span><h4><i class='fa fa-clock-o verde'></i>  AsignaciÃ³n de Horario a Colaboradores<h4></span>");
                $('#tituloContenidoAsignacionHourEmployee').html("<h4>Â¿EstÃ¡s seguro que desea asignar el siguiente Horario <span class='verde'>( " + textAsignacionHoras + " )</span> a los siguiente colaboradores?</h4>");
                $('#ContenidoAsignacionHourEmployee').html(arrayName);
                $('#confirmarAsigacionHorario').modal('show');
            }

            else
            {
                $('#sindataAsiganacion').removeClass('ocultar');
                $('#contenidoHorarios').addClass('ocultar');
                $('#tituloConfirAsigHorario').html("<span><h4><i class='fa fa-clock-o verde'></i>  AsignaciÃ³n de Horario a Colaboradores<h4></span>");
                $('#mensajeAviso').html("<h4><span class='rojo'>Selecione los datos para realizar la asignaciÃ³n del horario</span></h4>");

                $('#confirmarAsigacionHorario').modal('show');
            }

        });


        $('a#botonAsignacionDescanso').on('click', function(event)
        {


            $('#contenidoHorariosDescanso').removeClass('ocultar');
            $('#successHorariosMensajeHorarioDescanso').addClass('ocultar');
            $('#successHorariosMensajeDescanso').addClass('ocultar');
            $('#sindataAsiganacionDescanso').addClass('ocultar');

            $("#asignacionHours").select2('val', '');
            var nameTable = $('table.dataTable').attr('id');
            var oTableCkeck = $('#' + nameTable).dataTable();
            var timeBreak = $('#asignacionDescanso option:selected').text();
            var timeBreakSeparate = timeBreak.split('-');
            var start_break = timeBreakSeparate[0].replace(" ", "");
            var end_break = timeBreakSeparate[1].replace(" ", "");


            var arrayName = new Array();

            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayName[index] = '<div style="font-size:15px">' + $(this).parent().parent().parent().find('#nameModalModification').text() + '</div>';
            });

            if ((arrayName != '') && (timeBreak != '')) {

                $('#tituloConfirAsigHorarioDescanso').html("<span><h4><i class='fa fa-cutlery verde'></i>  Horario de descanso<h4></span>");
                $('#tituloContenidoAsignacionHourEmployeeDescanso').html("<h4>Â¿EstÃ¡s seguro que desea asignar el siguiente horario de descanso <span class='verde'>( " + start_break + " - " + end_break + ")</span> a los siguiente colaboradores?</h4>");
                $('#ContenidoAsignacionHourEmployeeDescanso').html(arrayName);
                $('#confirmarAsigacionDescando').modal('show');
            }

            else
            {
                $('#sindataAsiganacionDescanso').removeClass('ocultar');
                $('#contenidoHorariosDescanso').addClass('ocultar');
                $('#tituloConfirAsigHorarioDescanso').html("<span><h4><i class='fa fa-cutlery verde'></i>  Horario de descanso<h4></span>");
                $('#mensajeAvisoDescanso').html("<h4><span class='rojo'>Selecione los datos para realizar la asignaciÃ³n del horario de descanso</span></h4>");
                $('#confirmarAsigacionDescando').modal('show');
            }

        });




    }


    function _separarAsigHoursDay()
    {
        $('a#asignarEmpleado').on('click', function(event)
        {

            var nameTable = $('table.dataTable').attr('id');
            var arrayIdEmployee = new Array();
            var oTableCkeck = $('#' + nameTable).dataTable();
            var asignacionHoras = $('#asignacionHours').val();
            var idsPayRoll = new Array();

            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayIdEmployee[index] = $(this).val();

            });
            if ((arrayIdEmployee != '') && (asignacionHoras != '')) {
                idsPayRoll = arrayIdEmployee;
                var value = '';
                value = JSON.stringify(idsPayRoll);

                $ARU.AJAX.asignacionHorarios("GET", "/EmployeeHour/GetAsignaHorarioEmployee?value=" + value + '&asignacionHoras=' + asignacionHoras);

            }
        });

        $('a#asignarEmpleadoDescanso').on('click', function(event)
        {
            var nameTable = $('table.dataTable').attr('id');
            var arrayIdEmployee = new Array();
            var oTableCkeck = $('#' + nameTable).dataTable();
            var asignacionDescanso = $('#asignacionDescanso').val();
            var idsPayRoll = new Array();
            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayIdEmployee[index] = $(this).val();

            });


            var timeBreak = asignacionDescanso.split('-');
            var start_break = timeBreak[0].replace(" ", "");
            var end_break = timeBreak[1].replace(" ", "");

            if ((arrayIdEmployee != '') && (asignacionDescanso != '')) {
                idsPayRoll = arrayIdEmployee;
                var value = '';
                value = JSON.stringify(idsPayRoll);


                $ARU.AJAX.asignacionDescanso("GET", "/EmployeeHour/GetHoursBreak?value=" + value + '&start_break=' + start_break + '&end_break=' + end_break);

            }
        });
    }



    function _getTimeStartJornada()
    {
        $('#start_time').val('');
        $('#end_time').val('');
        $('a#botoncrearNuevoHorario').on('click', function(event)
        {

            $('#contenidoNewHorario').removeClass('ocultar');
            $('#successNuevoHorario').addClass('ocultar');

            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            $('#tituloConfirNewHorario').html("<h4><i class='fa fa-clock-o verde'></i>  Nuevo Horario Laboral</h4>");
            $('#tituloConfirmarNewHorario').html("<span style='font-size:16px'>Â¿EstÃ¡s seguro que desea crear un nuevo horario laboral?</span>");
            $('#confirmarNewHorario').html("<span class='verde' style='font-size:15px;'>(" + start_time + ") a (" + end_time + ")</span>");
            $('#confirmarNewHorarioAsignacion').modal('show');
        });

        $('a#botonConfirmarNuevoHorario').on('click', function(event)
        {
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            $ARU.AJAX.createNewListHours("GET", "/Hour/GetNewHorarioEmployee", "start_time=" + start_time + "&end_time=" + end_time);

//            $('div.table-scrollable').load('/EmployeeHour/EmployeeHours #sample_0', function() {
//                     
//                        
//            });
//            
//            $('div#listaHorario').html("hola");

        });

    }


    function _genExcelDeclareDay() {

        $('a#botonExcel').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {

            var start_date = $('#fecha_ini').val();
            var end_date = $('#fecha_fin').val();
            var position = $('#cargo').val();
            var employee = $('#empleado').val();
            var division = $('#departemento').val();
            var dependency = $('#pedencia').val();

            if (start_date == '') {
                start_date = "Empty";
            }
            if (end_date == '') {
                end_date = "Empty";
            }
            if (position == '') {
                position = "Empty";
            }
            if (employee == '') {
                employee = "Empty";
            }
            if (division == '') {
                division = "Empty";
            }
            if (dependency == '') {
                dependency = "Empty";
            }

            console.log(employee);


            var response = $.ajax({type: "GET",
                url: '/site/excel?start_date=' + start_date + '&end_date=' + end_date + "&position=" + position + "&employee=" + employee + "&division=" + division + "&dependency=" + dependency,
                async: true,
                success: function(response) {


                }
            }).responseText;


        });

    }

    function _jornadaDeclareDay()
    {
        $('a#reporteFiltrarJornada').on('click', function() {
            var start_date = $('#fecha_ini').val();
            var end_date = $('#fecha_fin').val();
            var position = $('#cargo').val();
            var employee = $('#empleado').val();
            var division = $('#departemento').val();
            var dependency = $('#dependencia').val();

            if (start_date == '') {
                start_date = "Empty";
            }
            if (end_date == '') {
                end_date = "Empty";
            }
            if (position == '') {
                position = "Empty";
            }
            if (employee == '') {
                employee = "Empty";
            }
            if (division == '') {
                division = "Empty";
            }
            if (dependency == '') {
                dependency = "Empty";
            }

            $('#tituloCargaData').html("<h4>Carga de reporte </h4>");
            $('#mesajeCargaDataReport').html("<h3>Cargando Datos </h3>");
            $('#cargandoData').modal('show');

            $('#refreshTableEmployee').load('/report/employee/reportDeclareDay?start_date=' + start_date + '&end_date=' + end_date + '&position=' + position + '&employee=' + employee + '&division=' + division + '&dependency=' + dependency + ' #tableEmployeeHour', function(responseText, textStatus, XMLHttpRequest) {

                if (textStatus == "success") {
                    $('#mesajeCargaDataReport').html('<h3><span class="verde"> Carga de Datos Exitosa!!</span></h3>');
                    setTimeout('$("#cargandoData").modal("hide");', 1500);
                }
                if (textStatus == "error") {
                    $('#mesajeCargaDataReport').html('<h3><span class="rojo"> Error al Cargar los Datos!!</span></h3>');
                    setTimeout('$("#cargandoData").modal("hide");', 1500);
                }

            });
        });

        $('a#reporteFiltrarEmployeeH').on('click', function() {
            var start_date = $('#fecha_ini').val();
            var end_date = $('#fecha_fin').val();

            if (start_date == '') {
                start_date = "Empty";
            }
            if (end_date == '') {
                end_date = "Empty";
            }

            $('#tituloCargaData').html("<h4>Carga de reporte </h4>");
            $('#mesajeCargaDataReport').html("<h3>Cargando Datos </h3>");
            $('#cargandoData').modal('show');

            $('#refreshTableEmployee').load('/report/employee/EmployeeHours?start_date=' + start_date + '&end_date=' + end_date + ' #tableEmployeeHour', function(responseText, textStatus, XMLHttpRequest) {

                if (textStatus == "success") {
                    $('#mesajeCargaDataReport').html('<h3><span class="verde"> Carga de Datos Exitosa!!</span></h3>');
                    setTimeout('$("#cargandoData").modal("hide");', 1500);
                }
                if (textStatus == "error") {
                    $('#mesajeCargaDataReport').html('<h3><span class="rojo"> Error al Cargar los Datos!!</span></h3>');
                    setTimeout('$("#cargandoData").modal("hide");', 1500);
                }

            });
        });

        $('#empleado').on('change', function() {
            $("#departemento").select2('val', '');
            $("#cargo").select2('val', '');
            $("#dependencia").select2('val', '');
        });

        $('#departemento').on('change', function() {
            $("#empleado").select2('val', '');
            $("#cargo").select2('val', '');
            $("#dependencia").select2('val', '');
        });

        $('#cargo').on('change', function() {
            $("#departemento").select2('val', '');
            $("#empleado").select2('val', '');
            $("#dependencia").select2('val', '');
        });

        $('#dependencia').on('change', function() {
            $("#departemento").select2('val', '');
            $("#empleado").select2('val', '');
            $("#cargo").select2('val', '');
        });
    }

    function _calenderBirth()
    {
        var date = new Date();
        var d = date.getDate();
        var url = '/Employee/BirthDateAjax';
        var mj = date.getMonth();
        var y = date.getFullYear();

        var h = {};

        $('#calendar').fullCalendar('destroy'); // destroy the calendar
        $('#calendar').fullCalendar({ //re-initialize the calendar

            defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/ 
            slotMinutes: 15,
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
//                events: '/Employee/BirthDateAjax?birthMont='+m,
            dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            buttonText: {
                today: "Hoy",
            },
            events: function(start, end, callback) {
                var m = new Date($('#calendar').fullCalendar('getDate')).getMonth() + 1;
                var y = new Date($('#calendar').fullCalendar('getDate')).getFullYear();
                console.log(m + ' ' + y);
                if (m < 10) {
                    m = '0' + m
                }
                $.ajax({
                    url: url,
                    data: 'birthMont=' + m + '&birthYear=' + y,
                    success: function(response) {
                        callback(JSON.parse(response));
                        console.log(response);
                    }
                });

            },
            eventClick: function(calEvent, jsEvent, view) {

                console.log(calEvent.id);

            },
            eventRender: function(event, element) {
                $(element).find(".fc-event-time").remove();
                $(element).find(".fc-event-inner").attr('style', 'background-color:#1bbc9b; font-weight: bold');

            }

        });
    }




    var result = (location.pathname).split('/');

    function _initDataTableAll()
    {
        _initDataTable('#adminPositionCodeActive', 6, 5);
        _initDataTable('#adminPositionCodeInactives', 6, 5);
        _initDataTable('#adminDivision', 3, 5);
        _initDataTable('#admin_constancia_1', 3, 5, 'desc');
        _initDataTable('#admin_constancia_2', 3, 5, 'desc');
        _initDataTable('#admin_constancia_3', 3, 5, 'desc');
        _initDataTable('#adminPosition', 2, 5);
        _initDataTable('#hoursEmployee', 1, -1);
        _initDataTable('#tableDirectoryPhone', 0, 5);
        _initDataTable('#table_active', 1, 4);
        _initDataTable('#table_inactive', 1, 4);
        _initDataTable('#table_department', 0, 10);
        _initDataTable('#table_position_cargo', 0, 10);
        _initDataTable('#table_employee', 0, 10);
    }

    function _initDataTable(tabId, sorting, page, order) {
        var order = (order !=undefined || order !="undefined")?order:'asc';
        $(tabId).dataTable({
            "aaSorting": [[sorting, order]],
            "iDisplayLength": [5],
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
            "pageLength": page,
            "pagingType": "full_numbers",
            "fnDrawCallback": function() {
                _EditarCodigoPosicion();
                _desactivarPositionCodeEmployee();
                _optionesPosition();
//                _EditarPosition();
                _optionesDivision();
//                _EditarDivision();
//                _confirEditCp();
                _deleteHour();
                _optionHourEmployee();
                _asigTelfCorp();
//               _deleteAsigTelf();
                _contanciasTrabajo();
                _fueraConstancia();
                _employeePositionAdd();
                _editarCargo();
                _deleteNewPosition();
                _deleteNewDepartement();
                _editEmployee();
//                _editSaveEmployee();
//                _editDepartment();
                _updateDepartment();
//                 _aprobacionConsta();
//                 _changeStatusProof();
//                 _imprimirConstanciaProof();
//                 _getPDFConstancia();
//                 _contanciaConfirmar();

            },
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0]},
            ],
            "language": {
                "lengthMenu": "  _MENU_ Registros",
                "sSearch": "Buscar: _INPUT_",
                "emptyTable": "No existen registros",
                "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> de <b>_TOTAL_</b> Registros",
                "sInfoFiltered": " (filtrado de _MAX_ registros totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "last": "&Uacute;ltimo",
                    "first": "Primero"
                }
            },
        });
//        var tableWrapper = jQuery(tabId+'_wrapper');
//
//        tabId.find('.group-checkable').change(function () {
//            var set = jQuery(this).attr("data-set");
//            var checked = jQuery(this).is(":checked");
//            jQuery(set).each(function () {
//                if (checked) {
//                    $(this).attr("checked", true);
//                    $(this).parents('tr').addClass("active");
//
//                } else {
//                    $(this).attr("checked", false);
//                    $(this).parents('tr').removeClass("active");
//                }
//            });
//            jQuery.uniform.update(set);
//        });
//        tabId.on('change', 'tbody tr .checkboxes', function () {
//            $(this).parents('tr').toggleClass("active");
//        });
//        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline");

    }

    function _MaskCell() {

        $("#Employee_extension_numeric").inputmask("mask", {
            "mask": "9999",
        });

        $('#Employee_cod_phone').on('change', function() {
            var codePhone = $("#Employee_cod_phone").val();
            $("#Employee_cellphone").val(" ");
            $("#Employee_home_phone").val(" ");


            switch (codePhone) {
                case "+58":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+58(499) 999-9999",
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+58(299) 999-9999"
                    });
                    break;
                case "+57":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+57(999) 999-9999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+57(9) 999-9999"
                    });
                    break;
                case "+56":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+56(9) 999-99999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+56(99) 999-99999"
                    });
                    break;
                case "+51":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+51(9) 999-99999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+51(99) 999-99999"
                    });
                    break;
                case "+507":

                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+507 999-9999999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+507 999-9999999"
                    });
                    break;
                case "+1":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+1(999) 999-9999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+1(999) 999-9999"
                    });
                    break;
                case "+34":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+344(99) 999-9999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+34(99) 999-9999"
                    });
                    break;
            }
        });

        if ((result[2] == "infoEmployee") || (result[2] == "firstView")) {
            var country = $("#Employee_country").val();
            switch (country) {
                case "VEN":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+58(0499) 999-9999",
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+58(0299) 999-9999"
                    });
                    break;
                case "COL":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+57(999) 999-9999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+57(9) 999-9999"
                    });
                    break;
                case "CHL":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+56(9) 999-99999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+56(99) 999-99999"
                    });
                    break;
                case "PER":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+51(9) 999-99999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+51(99) 999-99999"
                    });
                    break;
                case "PAN":

                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+507 999-9999999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+507 999-9999999"
                    });
                    break;
                case "USA":
                    $("#Employee_cellphone").inputmask("mask", {
                        "mask": "+1(999) 999-9999"
                    });
                    $("#Employee_home_phone").inputmask("mask", {
                        "mask": "+1(999) 999-9999"
                    });
                    break;
            }

        }

    }


    function rolCreate(result)
    {
        console.log(result);

        switch (result) {
            case "1":
                $('#error_rol').removeClass("rojo");
                $('#error_rol').removeClass("icon-remove-circle");
                $('#error_rol').html("");
                $('#mensaje').addClass("icon-ok-circle");
                $('#mensaje').addClass("verde");
                $('#mensaje').html("<h4>Nuevo Rol Creado!</h4>");
                $('#rol').modal('show');

                break;
            case "2":
                $('#error_rol').removeClass("icon-ok-circle");
                $('#error_rol').removeClass("verde");
//                    $('#error_rol').addClass("icon-remove-circle"); 
                $('#error_rol').addClass("rojo");
                $('#error_rol').html("<h4>Ingrese Un nombre para el Nuevo rol</h4>");
                break;

        }



    }

    function _createRoles()
    {
        $('#crearRol').on('click', function() {
            var name_rol = $("#Rol_name_rol").val();

            $ARU.AJAX.createRol("GET", "/Rol/CreateRol", "name_rol=" + name_rol);

        });
    }

    function _menu()
    {

        var result = (location.pathname).split('/');
        switch (result[1]) {
            case ('Employee'):
                if (result[2] != "DirectoryPhone") {
                    $('li#employee').addClass(' active');
                }
                else {
                    $('li#edirectorioTefl').addClass(' active');
                }
                break;

            case ('EventEmployee'):
                $('li#create').addClass(' active');
                break;
            case ('positionCode'):
                $('li#codigoposicion').addClass(' active');
                break;
            case ('division'):
                $('li#division').addClass(' active');
                break;

        }
    }

    function _loadCp() {

        var form = $('#submit_form');
        var error = $('.alert-danger', form);
        var lider = false;

        var success = $('.alert-success', form);
        var handleTitle = function(tab, navigation, index) {
            var total = navigation.find('li').length;

            var current = index + 1;
            // set wizard title
            $('.step-title', $('#form_wizard_12')).text('Paso ' + (index + 1) + ' de ' + total);
            // set done steps
            jQuery('li', $('#form_wizard_12')).removeClass("done");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("done");
            }


            if (current == 1) {

                $('#form_wizard_12').find('.button-previous').hide();

            } else {

                $('#form_wizard_12').find('.button-previous').show();
            }

            if (current >= total) {

                $('#form_wizard_12').find('.button-next').hide();
                $('#form_wizard_12').find('.button-submitcp').show();
                displayConfirm();
                displayConfirmpc();

            } else {

                $('#form_wizard_12').find('.button-next').show();
                $('#form_wizard_12').find('.button-submitcp').hide();
            }
            App.scrollTo($('.page-title'));
        }

        // default form wizard
        $('#form_wizard_12').bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            onTabClick: function(tab, navigation, index, clickedIndex) {
                success.hide();
                error.hide();
                if (form.valid() == false) {
                    $('.alert-danger').html('Todos los Campos son Obligatorios');

                    return false;
                }
                else {
                    return false;
                }
                handleTitle(tab, navigation, clickedIndex);
            },
            onNext: function(tab, navigation, index) {
                success.hide();
                error.hide();

                var id_dependencia = $("#PositionCode_id_dependencia").val();
                var new_division = $("#PositionCode_new_division").val();
                var new_position = $("#new_position").val();
                var id_employee = $("#PositionCode_id_employee").val();
                var start_date = $("#PositionCode_start_date").val();
                var leader = $("#leader:checked").val();
                var id_division = '';
                var id_position = '';
                var lider = '';
                var dependency = '';


                var employee = $ARU.AJAX.employeeExist("GET", "/PositionCode/CheckNewEmployee", "id_employee=" + id_employee + "&start_date=" + start_date);

                if (form.valid() == false) {
                    $('.alert-danger').html('Todos los Campos son Obligatorios');

                    return false;
                } else {
                    if (employee == 'true') {
                        $('.alert-danger').html('El Colaborador ya Existe y aÃºn se Encuentra Activo');
                        $('.alert-danger').css('display', 'block');
                        return false;
                    } else if (employee != 'false' && employee != 'true') {
                        $('.alert-danger').html('Debe Seleccionar una Fecha Mayor a ' + employee);
                        $('.alert-danger').css('display', 'block');
                        return false;
                    } else if (employee == 'false') {

                        if ((new_division != "") && (id_dependencia != "") && (new_position != "")) {

                            if (leader != '1') {
                                $('.alert-danger').html('Se Necesita Crear un Lider/Coordinador/Gerente para esta DivisiÃ³n');
                                $('.alert-danger').css('display', 'block');
                                return false;
                            } else {

                                dependency = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckDependencyByDivision", "id_division=" + id_dependencia + "&id_position=false&checkDivision=false");
                                if (dependency == 'false') {
                                    $('.alert-danger').html('No Existe la DivisiÃ³n de Dependencia');
                                    $('.alert-danger').css('display', 'block');
                                    return false;
                                } else if (dependency == 'true') {
                                    id_division = $ARU.AJAX.crearDivision("GET", "/Division/GetNewDivision", "new_division=" + new_division + "&id_dependencia=" + id_dependencia).split('"').join('');
                                    id_position = $ARU.AJAX.crearCargo("GET", "/Position/getNewPosition", "new_position=" + new_position + "&leader=" + leader).split('"').join('');
                                }

                            }

                        }
                        if ((new_division != "") || (id_dependencia != "") || (new_position != "")) {
                            //validacion nueva division y posicion llena      
                            if (new_division != "" && new_position == "") {
                                id_division = new_division;
                                id_position = $("#PositionCode_id_position").val();

                                lider = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckLeaderByPosition", "id_position=" + id_position);
                                dependency = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckDependencyByDivision", "id_division=" + id_dependencia + "&id_position=" + id_position + "&checkDivision=false");

                                if (lider == 'false') {
                                    $('.alert-danger').html('Se Necesita Crear un Lider/Coordinador/Gerente para esta DivisiÃ³n');
                                    $('.alert-danger').css('display', 'block');
                                    return false;
                                } else if (lider == 'true') {

                                    if (dependency == 'false') {
                                        $('.alert-danger').html('No Existe la DivisiÃ³n de Dependencia');
                                        $('.alert-danger').css('display', 'block');
                                        return false;
                                    } else if (dependency == 'true') {
                                        id_division = $ARU.AJAX.crearDivision("GET", "/Division/GetNewDivision", "new_division=" + new_division + "&id_dependencia=" + id_dependencia).split('"').join('');
                                    }
                                }
                            }

                            //validacion lleno division y nuevo cargo nuevo lider
                            if (new_position != "" && new_division == "") {
                                id_position = new_position;
                                id_division = $("#PositionCode_id_division").val();

                                lider = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckLeaderByDivision", "id_division=" + id_division);
                                dependency = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckDependencyByDivision", "id_division=" + id_division + "&id_position=false&checkDivision=true");

                                if (lider == 'false') {
                                    if (leader != '1') {
                                        $('.alert-danger').html('Se Necesita Crear un Lider/Coordinador/Gerente para esta DivisiÃ³n');
                                        $('.alert-danger').css('display', 'block');
                                        return false;
                                    } else if (leader == '1') {

                                        if (dependency == 'false') {
                                            $('.alert-danger').html('No Existe la DivisiÃ³n de Dependencia');
                                            $('.alert-danger').css('display', 'block');
                                            return false;
                                        } else if (dependency == 'true') {
                                            id_position = $ARU.AJAX.crearCargo("GET", "/Position/getNewPosition", "new_position=" + new_position + "&leader=" + leader).split('"').join('');
                                        }

                                    }
                                } else {
                                    if (dependency == 'false') {
                                        $('.alert-danger').html('No Existe la DivisiÃ³n de Dependencia');
                                        $('.alert-danger').css('display', 'block');
                                        return false;
                                    } else if (dependency == 'true') {
                                        id_position = $ARU.AJAX.crearCargo("GET", "/Position/getNewPosition", "new_position=" + new_position + "&leader=" + leader).split('"').join('');
                                    }
                                }

                            }

                        } else if ((new_division == "") && (id_dependencia == "") && (new_position == "")) {
                            if (new_division == "") {
                                id_division = $("#PositionCode_id_division").val();
                            }
                            if (new_position == "") {
                                id_position = $("#PositionCode_id_position").val();
                            }

                            lider = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckLeaderExist", "id_division=" + id_division + "&id_position=" + id_position + "&checkLeader=true");
                            dependency = $ARU.AJAX.leaderExist("GET", "/PositionCode/CheckDependencyByDivision", "id_division=" + id_division + "&id_position=" + id_position + "&checkDivision=true");

                            if (lider == 'false') {
                                $('.alert-danger').html('Se Necesita Crear un Lider/Coordinador/Gerente para esta DivisiÃ³n');
                                $('.alert-danger').css('display', 'block');
                                return false;
                            } else if (lider == 'true') {
                                if (dependency == 'false') {
                                    $('.alert-danger').html('No Existe la DivisiÃ³n de Dependencia');
                                    $('.alert-danger').css('display', 'block');
                                    return false;
                                }
                            }
                        }
                    }
                }

                $ARU.AJAX.posicion("GET", "/PositionCode/GetPositionCode", "id_division=" + id_division + "&id_position=" + id_position + "&check=true &id_employee=" + id_employee);
                id_division = '';
                id_position = '';
                lider = '';
                dependency = '';

                handleTitle(tab, navigation, index);
            },
            onPrevious: function(tab, navigation, index) {
                success.hide();
                error.hide();
                handleTitle(tab, navigation, index);
            },
            onTabShow: function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                var $percent = (current / total) * 100;
                $('#form_wizard_12').find('.progress-bar').css({
                    width: $percent + '%'
                });
            }
        });


        $('#form_wizard_12').find('.button-previous').hide();
        $('#form_wizard_12.button-submit').click(function() {
//                alert('Finished! Hope you like it :)');
        }).hide();


        var displayConfirm = function() {
            $('#tab3 .form-control-static', form).each(function() {
                var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                if (input.is(":text") || input.is("textarea")) {
                    $(this).html(input.val());
                } else if (input.is("select")) {
                    $(this).html(input.find('option:selected').text());
                } else if (input.is(":radio") && input.is(":checked")) {
                    $(this).html(input.attr("data-title"));
                } else if ($(this).attr("data-display") == 'payment') {
                    var payment = [];
                    $('[name="payment[]"]').each(function() {
                        payment.push($(this).attr('data-title'));
                    });
                    $(this).html(payment.join("<br>"));
                }
            });
        }

        var displayConfirmpc = function() {

            $('#tab2 .form-control-static', form).each(function() {
                var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                if (input.is(":text") || input.is("textarea")) {
                    $(this).html(input.val());
                } else if (input.is("select")) {
                    $(this).html(input.find('option:selected').text());
                } else if (input.is(":radio") && input.is(":checked")) {
                    $(this).html(input.attr("data-title"));
                } else if ($(this).attr("data-display") == 'payment') {
                    var payment = [];
                    $('[name="payment[]"]').each(function() {
                        payment.push($(this).attr('data-title'));
                    });
                    $(this).html(payment.join("<br>"));
                }
            });



        }
    }



    function _loadFirstView()
    {
        var form = $('#submit_form');
        var error = $('.alert-danger', form);
        var lider = false;

        var success = $('.alert-success', form);
        var handleTitle = function(tab, navigation, index) {
            var total = navigation.find('li').length;

            var current = index + 1;
            // set wizard title
            $('.step-title', $('#form_wizard_1')).text('Paso ' + (index + 1) + ' de ' + total);
            // set done steps
            jQuery('li', $('#form_wizard_1')).removeClass("done");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("done");
            }


            if (current == 1) {

                $('#form_wizard_1').find('.button-previous').hide();

            } else {

                $('#form_wizard_1').find('.button-previous').show();
            }

            if (current >= total) {

                $('#form_wizard_1').find('.button-next').hide();
                $('#form_wizard_1').find('.button-submitcp').show();
                displayConfirm();


            } else {

                $('#form_wizard_1').find('.button-next').show();
                $('#form_wizard_1').find('.button-submitcp').hide();
            }
            App.scrollTo($('.page-title'));
        }

        // default form wizard
        $('#form_wizard_1').bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            onTabClick: function(tab, navigation, index, clickedIndex) {
                success.hide();
                error.hide();
                if (form.valid() == false) {
                    $('.alert-danger').html('Todos los Campos son Obligatorios');

                    return false;
                }
                else {
                    return false;
                }
                handleTitle(tab, navigation, clickedIndex);
            },
            onNext: function(tab, navigation, index) {
                success.hide();
                error.hide();


                if (form.valid() == false) {
                    $('.alert-danger').html('Todos los Campos son Obligatorios');

                    return false;
                }

                handleTitle(tab, navigation, index);
            },
            onPrevious: function(tab, navigation, index) {
                success.hide();
                error.hide();
                handleTitle(tab, navigation, index);
            },
            onTabShow: function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                var $percent = (current / total) * 100;
                $('#form_wizard_1').find('.progress-bar').css({
                    width: $percent + '%'
                });
            }
        });


        $('#form_wizard_1').find('.button-previous').hide();
        $('#form_wizard_1 .button-submit').click(function() {
//                alert('Finished! Hope you like it :)');
        }).hide();


        var displayConfirm = function() {
            $('#tab3 .form-control-static', form).each(function() {
                var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                if (input.is(":text") || input.is("textarea")) {
                    $(this).html(input.val());
                } else if (input.is("select")) {
                    $(this).html(input.find('option:selected').text());
                } else if (input.is(":radio") && input.is(":checked")) {
                    $(this).html(input.attr("data-title"));
                } else if ($(this).attr("data-display") == 'payment') {
                    var payment = [];
                    $('[name="payment[]"]').each(function() {
                        payment.push($(this).attr('data-title'));
                    });
                    $(this).html(payment.join("<br>"));
                }
            });
        }


    }

    /**
     * carga al principio de la interfaz de declarar la jornada de trabajo
     */
    function _loadIndex()
    {
        $('#declare').on('click', function() {



            var navigation = '';
            tab = $('#declare_day').find('ul.steps').find('li.active').index() + 1;


            var hour_date = $('#tab2 label.control-label').text();

            if (tab == 2)
            {
                $("#declare").attr("disabled", true);
                setTimeout('$("#declare").attr("disabled",false);', 1700);

            }
            if (hour_date.length !== 0 && tab == 3)
            {

                var hour = hour_date.split(" ");

                console.log("/report/employee/KnowBreakEndHour", "&hour_event_actual=" + _getTime().time_event + "&hour_event=" + hour[0]);
                var validateHourDescanso = JSON.parse($ARU.AJAX.calcularHoraEvento("GET", "/report/employee/KnowBreakEndHour", "&hour_event_actual=" + _getTime().time_event + "&hour_event=" + hour[0]));


                if (validateHourDescanso.validation == "FALSE")
                {
                    var validateHour = validateHourDescanso.value;
                    _buttons(tab, navigation, 4, validateHour, null);
                } else {
                    if (validateHourDescanso.remaining_value != null) {
                        var validateHour = validateHourDescanso.value;
                        var remainingHour = validateHourDescanso.remaining_value;
                        _buttons(tab, navigation, 6, validateHour, remainingHour);
                    } else {

                        _buttons(tab, navigation, 2, validateHour, null);
                    }
                }
            }

            var fin_jornada = $('#tab3 label.control-label').text();
            if (fin_jornada.length !== 0)
            {
                var validateHour = JSON.parse($ARU.AJAX.calcularHoraEvento("GET", "/report/employee/KnowEndHour", "&date_event=" + _getTime().date_event + "&hour_event=" + _getTime().time_event));

                console.log(validateHour);

                if (validateHour.validation == "TRUE")
                {
                    _buttons(tab, navigation, 3, validateHour.value, null);
                }
                else
                {
                    var fin_jornada_limite = $('#tab4 label.control-label').text();

                    if (fin_jornada_limite.length !== 0)
                    {
                        _buttons(tab, navigation, -1, validateHour.value, null);
                    }
                    else
                    {
                        _buttons(tab, navigation, 5, validateHour, null);
                    }
                }
            }
        });

        $('#declare_day').bootstrapWizard(
                {
                    nextSelector: '.button-next',
                    previousSelector: '.button-previous',
                    onTabClick: function(tab, navigation, index, clickedIndex)
                    {
                        return false;//para desactivar que el usuario pueda navegar con los botones de arriba
                    },
                    onNext: function(tab, navigation, index)
                    {
                        var previus = index - 1;
                        var total = navigation.find('li').length;
                        var current = index + 1;
                        var validateHour = null;
                        tab.parent().find('li:eq(' + previus + ')').addClass("done");
                        $('.step-title', $('#declare_day')).text('Paso ' + current + ' de ' + total);
                        _progressBar('#declare_day', current);
                        _buttons(tab, navigation, index, validateHour, null);
                    },
                    onInit: function(tab, navigation, index)
                    {
                        $activeTab = $('ul.steps li.active');
                        $index = $activeTab.index();
                        $element = $('ul.steps li.active');
                        var total = navigation.find('li').length;
                        var current = $index + 1;
                        var validateHour = null;
                        $('.step-title', $('#declare_day')).text('Paso ' + current + ' de ' + total);
                        _progressBar('#declare_day', $index + 1);
                        _buttons(tab, navigation, $index, validateHour, null);
                    }
                });
    }



    /**
     * Funcion encargada de aumentar el tamano de la barra en el momento de declarar
     * @param string obj es el id del elemento donde se va a buscar la barra
     * @param int index
     * @return void
     */
    function _progressBar(obj, index)
    {
        var percentage = null;
        if (index != 0)
            percentage = 100 / $(obj).find('ul.steps').find('li').length * index;
        else
            percentage = 100;
        $(obj).find('.progress-bar').css('width', percentage + '%');
    }
    /**
     *
     */
    function _location()
    {
        var current = null;
        $('input.button-next,input.button-submit').on('click', function()
        {
            var location = this.value;
            tab = $('#declare_day').find('ul.steps').find('li.active').index() + 1;
            console.log("/EventEmployee/Declarar", "tab=" + tab + "&location=" + location + "&date_event=" + _getTime().date_event + "&time_event=" + _getTime().time_event);
            $ARU.AJAX.sendEvent("GET", "/EventEmployee/Declarar", "tab=" + tab + "&location=" + location + "&date_event=" + _getTime().date_event + "&time_event=" + _getTime().time_event);

        });
    }
    /**
     *
     */
    function _getTime()
    {
        var d = new Date();
        var day_event = d.getDate();
        var month_event = d.getMonth() + 1;
        var year_event = d.getFullYear();
        var hour_event = d.getHours();
        var minutes_event = d.getMinutes();
        var seconds_event = d.getSeconds();
        var date_event = year_event + '-' + month_event + '-' + day_event;
        var time_event = hour_event + ':' + minutes_event + ':' + seconds_event;
        return {
            date_event: date_event,
            time_event: time_event
        }
    }

    /**
     *
     */
    function _buttons(tab, navigation, index, validateHour, remainingHour)
    {
        var stylo = '';
        switch (index)
        {
            case 0:
                $('#declare_day').find('#puesto_trabajo').show();
                $('#declare_day').find('#remoto').show();
                $('#declare_day').find('#aceptar').hide();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').hide();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').show();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').hide();
                $('#declare_day').find('div#diferenciaHora').hide();
                break;
            case 1:
                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').hide();
                $('#declare_day').find('#aceptar').show();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').show();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').hide();
                $('#declare_day').find('div#diferenciaHora').hide();
                break;
            case 2:

                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').hide();
                $('#declare_day').find('#aceptar').show();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#end_break').show();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').hide();
                $('#declare_day').find('div#diferenciaHora').hide();
                break;
            case 6:

                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').show();
                $('#declare_day').find('#aceptar').show();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break_30_minutes').show();
                $('#declare_day').find('div#end_break_30_minutes').html("<div style='font-size:14px' class='text-center' ><h4>¿Esta seguro que desea declarar el fin del Descanso/Almuerzo?, <br>Su fin de Descanso/Almuerzo culmina en <span class='rojo'>" + remainingHour + "</span>, <br> Su final de descanso sera por defecto a las <span class='letra_empleado'>" + validateHour + "</span></h4></div>");
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').hide();
                $('#declare_day').find('div#diferenciaHora').hide();
                break;

            case 3:

                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').hide();
                $('#declare_day').find('#aceptar').hide();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').show();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').show();
                $('#declare_day').find('div#time_comple').hide();
                $('#declare_day').find('div#diferenciaHora').hide();



                break;
            case -1:
                var ini = $('#tab1 label').text();
                var fin = $('#tab4 label').text();
                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#aceptar').hide();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#time_comple').show();
                $('#declare_day').find('#limite').show();
                $('#declare_day').find('#cancelar').hide();
                $('#declare_day').find('div#time_comple').html("<div style='font-size:14px' >Su jornada laboral correspondiente a <span class='verde'>" + ini + "-" + fin + "</span> ha finalizado.</div><div style='margin-top:10px; font-size:14px' ><span><span class='rojo'>Importante: </span>Debe esperar un perÃ­odo de tiempo de <span class='verde'>16 horas</span> para poder declarar nuevamente.</span></div>");
                $('#declare_day').find('div#diferenciaHora').hide();
                break;

            case 4:



                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#aceptar').hide();
                $('#declare_day').find('#fin').hide();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').show();
                $('#declare_day').find('#limite').show();
                $('#declare_day').find('div#time_comple').html("<div style='font-size:14px' class='text-center' ><h4>Su fin de Descanso/Almuerzo finaliza a las <span class='rojo'>" + validateHour + "</span> </h4></div>");
                $('#declare_day').find('div#diferenciaHora').hide();

                break

            case 5:
                $('#declare_day').find('#puesto_trabajo').hide();
                $('#declare_day').find('#remoto').hide();
                $('#declare_day').find('#aceptar').hide();
                $('#declare_day').find('#fin').show();
                $('#declare_day').find('#fin_jornada').hide();
                $('#declare_day').find('div#start_time').hide();
                $('#declare_day').find('div#start_break').hide();
                $('#declare_day').find('div#end_break_30_minutes').hide();
                $('#declare_day').find('div#end_break').hide();
                $('#declare_day').find('div#end_time').hide();
                $('#declare_day').find('div#time_comple').show();
                $('#declare_day').find('#limite').hide();
                $('#declare_day').find('#cancelar').show();
                $('#declare_day').find('div#time_comple').html("<div style='font-size:14px' align='center'><h4>Â¿EstÃ¡ seguro que desea declarar fin de jornada laboral?</h4><h4><span> Para culminar su jornada laboral <span class='label label-sm label-success' style='font-size:15px'>legal</span> a usted le corresponde declarar</h4><h4> a la siguiente hora <span class='rojo' style='font-weight: bold; color:#ff0000'>" + validateHour.value + "</span></h4></div>");

                var diferencia = '';
                if (validateHour.difstart != '00:00:00') {
                    diferencia += "<div id='diferencia_star'>Tiempo de diferencia de inicio de jornada: <span style='font-weight: bold; color:#000000'>" + validateHour.difstart + "</span></div>";
                }

                else {
                    diferencia += "<div id='diferencia_star'>Tiempo de diferencia de inicio de jornada: <span style='font-weight: bold; color:#000000'>" + validateHour.difstart + "</span></div>";
                }
                if (validateHour.difbreak != '00:00:00')
                {
                    diferencia += "<div id='diferencia_breck'>Tiempo de diferencia en el descanso: <span style='font-weight: bold; color:#000000'>" + validateHour.difbreak + "</span></div>";
                }
                else {
                    diferencia += "<div id='diferencia_breck'>Tiempo de diferencia en el descanso: <span style='font-weight: bold; color:#000000'>" + validateHour.difbreak + "</span></div>";
                }
                if (validateHour.endhour) {
                    diferencia += "<div id='hora_legal'>Hora legal de salida: <span style='font-weight: bold; color:#000000'>" + validateHour.endhour + "</span></div>";
                }
                else {
                    diferencia += "<div id='hora_legal'>Hora legal de salida: <span style='font-weight: bold; color:#000000'>" + validateHour.endhour + "</span></div>";
                }
                if (validateHour.value) {
                    diferencia += "<div id='hora_legal'>Hora correspondiente de salida: <span style='font-weight: bold; color:#000000'>" + validateHour.value + "</span></div>";
                }
                else {
                    diferencia += "<div id='hora_legal'>Hora correspondiente de salida: <span style='font-weight: bold; color:#000000'>" + validateHour.value + "</span></div>";
                }


                $('#diferenciaHora').html(diferencia);
                $('#declare_day').find('div#diferenciaHora').show();

                break

        }
    }

    /**
     * 
     * funcion para enviar la captura de la foto a la accion 
     */

    function _attachphoto() {

        var settings = {
            url: "/Employee/Photo",
            dragDrop: false,
            showDone: false,
            fileName: "myfile",
            allowedTypes: "pdf,gif,jpeg,png,jpg,xlsx,xls,txt,cap,pcap,csv",
            returnType: "json",
            showFileCounter: false,
            multiple: false,
            onSuccess: function(files, data, xhr)
            {

                $("#filename").html(files);
                $("#foto").attr('src', "/" + data[1]);
                $("#load_photo").attr('src', "/" + data[1]);
                $("#photomain").attr('src', "/" + data[1]);
//                                $('div.ajax-file-upload-filename:last').attr('name', data[0]); 
            },
            showDelete: true,
            deleteCallback: function(data, pd) {
                for (var i = 0; i < data.length; i++)
                {
                    $.post("/Employee/deletejquery", {op: "delete", name: data[i]},
                    function(resp, textStatus, jqXHR)
                    {
                        //Show Message  
                        $("#status").html("");
                    });
                }
                pd.statusbar.remove(); //You choice to hide/not.
            }
        }
        var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
    }


    function _format(state) {
        if (!state.id)
            return state.text; // optgroup
        return "&nbsp;&nbsp;" + state.text; //colocar link para las imagenes
    }

    function _applyMetroSelect() {

        $('#options_left_all').click(function() {
            $('#select_right option').each(function() {
                $(this).appendTo('#select_left');
            });
        });
        $('#options_right_all').click(function() {
            $('#select_left option').each(function() {
                $(this).appendTo('#select_right');
            });
        });


        $(".select2").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });
        $("#Employee_id_nationality").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });

        $("#Employee_nationality").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });

        $("#Employee_id_marital_status").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });


        $("#Employee_cod_phone").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });


        $("#Employee_country").select2({
            placeholder: "Select",
            allowClear: true,
            formatResult: _format,
            formatSelection: _format,
            escapeMarkup: function(m) {
                return m;
            }
        });


        $("#Employee_state").select2({
            placeholder: "Select",
            allowClear: true,
            formatResult: _format,
            formatSelection: _format,
            escapeMarkup: function(m) {
                return m;
            }
        });

        $("#Address_id_city").select2({
            placeholder: "Select",
            allowClear: true,
            formatResult: _format,
            formatSelection: _format,
            escapeMarkup: function(m) {
                return m;
            }
        });



        $('.date-picker-formtodmy').datepicker({
            rtl: Metronic.isRTL(),
            format: 'dd-mm-yyyy',
            autoclose: true
        });


    }

    /**
     * funcion para listar las acciones de un controlador
     * @returns {undefined}
     */

    function _controllersByRol()
    {
        $('a#controllersByRoles').on('click', function() {
            var nameController = ($(this).find('div#controllers').text());
            var rol = $('#rol').text();

            $ARU.AJAX.idRol("POST", "/Rol/IdRol", "nameController=" + nameController + "&rol=" + rol);
        });
    }

    function viewActionController(result)
    {
        var html = "<div><h2>ACCIONES</h2></div>";//creamos una variable donde almacenar la informaciÃ³n
        for (var i in result)
        {
            html += "<div><a href='#'>" + result[i] + "</a></div>";
        }
        $('#ActionByRoles').html(html);
    }


    /**
     * funcion para generar el directorio de los controladores y acciones asociadas a un rol
     * @returns {undefined}
     */


    function _treeDirectory() {

        var DataSourceTree = function(options) {
            this._data = options.data;
            this._delay = options.delay;
        };

        DataSourceTree.prototype = {
            data: function(options, callback) {
                var self = this;

                setTimeout(function() {
                    var data = $.extend(true, [], self._data);

                    callback({data: data});

                }, this._delay)
            }
        };



        var treeDataSource = new DataSourceTree({
            data: [
                {name: 'Sales', type: 'folder'},
                {name: 'Reports', type: 'item', additionalParameters: {id: 'I1'}},
                {name: 'Finance', type: 'item', additionalParameters: {id: 'I2'}},
                {name: 'Finance1', type: 'item', additionalParameters: {id: 'I3'}}
            ],
            delay: 400
        });


//
//            $('#MyTree').tree({
//                dataSource: treeDataSource,
//                loadingHTML: '<img src="/themes/metronic/img/input-spinner.gif"/>',
//            });


    }

    function _validarDatos() {

        var form = $('#submit_form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                'Employee[first_name]': {
                    required: true
                },
                'Employee[last_name]': {
                    required: true
                },
                'Employee[identity_card]': {
                    required: true
                },
                'Employee[email_personal]': {
                    required: true,
                    email: true
                },
                'Employee[email_company]': {
                    required: true,
                    email: true
                },
                'Employee[skype]': {
                    required: true,
                },
                'Employee[cellphone]': {
                    required: true,
                },
                'Employee[extension_numeric]': {
                    maxlength: 4
                },
                'Address[address_line_1]': {
                    required: true,
                },
                'Address[address_line_2]': {
                    required: false,
                }
                ,
                'Employee[country]': {
                    required: true,
                },
                'Employee[state]': {
                    required: true,
                },
                'Address[zip]': {
                    required: true,
                },
                'Address[id_city]': {
                    required: true,
                },
                'Users[validar_pass]': {
                    required: true,
                    minlength: 5
                },
                'Users[pass]': {
                    required: true,
                    minlength: 5
                },
                'Users[confir_pass]': {
                    required: true,
                    minlength: 5,
                    equalTo: "#Users_pass"
                }
                ,
                'Users[password]': {
                    required: true,
                    minlength: 5,
                }
                ,
                'Employee[cp]': {
                    required: true,
                }
                ,
                'Employee[codeDependence]': {
                    required: true,
                }
                ,
                'Rol[name_rol]': {
                    required: true,
                },
                'PositionCode[id_position]': {
                    required: true,
                },
                'PositionCode[id_employee]': {
                    required: true,
                },
                'PositionCode[start_date]': {
                    required: true,
                },
                'PositionCode[new_division]': {
                    required: true,
                },
                'PositionCode[id_dependencia]': {
                    required: true,
                },
                'new_position': {
                    required: true,
                },
                'PositionCode[id_division]': {
                    required: true,
                },
                'dirigido': {
                    required: true,
                },
                'sueldo': {
                    required: true,
                },
            },
            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.attr("name") == "gender") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_gender_error");
                } else if (element.attr("name") == "payment[]") { // for uniform radio buttons, insert the after the given container
                    error.insertAfter("#form_payment_error");
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                success.hide();
                error.show();
                App.scrollTo(error, -200);
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                    label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                    label.remove(); // remove error label here
                } else { // display success icon for other inputs
                    label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                }
            },
            submitHandler: function(form) {
//                    success.show();
//                    error.hide();
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                form.submit();
            }

        });

    }

    function _changePass() {
        $('#changepass').on('click', function()

        {
            var confirmarPass = encodeURIComponent($("#Users_validar_pass").val());
            var password = encodeURIComponent($("#Users_pass").val());
            var passComparar = encodeURIComponent($("#Users_confir_pass").val());
            if (password == passComparar) {
                $ARU.AJAX.sendPass("GET", "/Users/ChangePass", "confirmar_pass=" + confirmarPass + "&pass=" + password);
            }
        });

    }

    function _viewdetalle()
    {
        $('a#detalle').on('click', function() {
            var id = ($(this).find('div#id_employ').text());
            $ARU.AJAX.searchEmployee("GET", "/Employee/DynamicEmployee", "id_employee=" + id);
        });
    }

    function successPass(result) {

        switch (result) {
            case "1":
                $('#error_contra').removeClass("rojo");
                $('#error_contra').removeClass("icon-remove-circle");
                $('#error_contra').html("");
                $('#mensaje').addClass("icon-ok-circle");
                $('#mensaje').addClass("verde");
                $('#mensaje').html("<h4>Cambio de ContraseÃ±a Exitoso!</h4>");
                $('#cambio_pass').modal('show');
                break;
            case "2":
                $('#error_contra').removeClass("icon-ok-circle");
                $('#error_contra').removeClass("verde");
                $('#error_contra').addClass("icon-remove-circle");
                $('#error_contra').addClass("rojo");
                $('#error_contra').html("<h4>ContraseÃ±a Actual No Valida</h4>");
                break;

        }
    }

    function viewEmployeeModal(result) {

        $('#title').html(result[0].name);
        $('#name').html(result[0].name);
        $('#second_name').html(result[0].second_name);
        $('#last_name').html(result[0].last_name);
        $('#titlelast').html(result[0].last_name);
        $('#second_last_name').html(result[0].second_last_name);
        $('#date_birth').html(result[0].date_birth);
        $('#identity_card').html(result[0].identity_card);
        $('#email_personal').html("<i class='icon-envelope'></i> " + result[0].email_personal);
        $('#email_company').html("<i class='icon-envelope'></i> " + result[0].email_company);
        $('#cellphone').html("<i class='icon-call-end'></i> " + result[0].cellphone);
        $('#skype').html("<a href='skype:" + result[0].skype + "?call'><i class='fa fa-skype'></i></a> " + result[0].skype);
        $('#homephone').html("<i class='icon-home'></i> " + result[0].homephone);
        $('#extension_numeric').html(result[0].extension_numeric);
        $('#nationality').html(result[0].nationality);
        $('#maritalstatus').html(result[0].maritalstatus);
        $('#address_line_1').html(result[1].address_line_1);
        $('#address_line_2').html(result[1].address_line_2);
        $('#zip').html(result[1].zip);
        $('#country').html(result[1].country);
        $('#state').html(result[1].state);
        $('#city').html(result[1].city);

        if (result[0].imagen_rute != null) {
//            $("#photo").attr('src', "/" + result[0].imagen_rute);
            $("#photo").attr('src', "/themes/metronic/img/profile/profile.jpg");//BORRAR CUANDO SE CARGE LAS FOTOS DE CARNET
        }
        else {
            $("#imagen_rute").addClass('photo-modal-view-employee');
            $("#photo").attr('src', "/themes/metronic/img/profile/profile.jpg");
        }
        $('#detalle_empleado').modal('show');
    }

    function _CreatePositionCode()
    {
        $('a#positioncode').on('click', function() {

            var id_division = $("#PositionCode_id_division").val();
            var id_position = $("#PositionCode_id_position").val();
            var id_employee = $("#PositionCode_id_employee").val();
            var start_date = $("#PositionCode_start_date").val();
            var id_dependencia = $("#PositionCode_id_dependencia").val();
            var new_division = $("#PositionCode_new_division").val();
            var new_position = $("#new_position").val();
            var leader = $("#leader:checked").val();
            var position = $("div#posicion").text();

            if (id_division == "" & (new_division == "" || id_dependencia == "") || (id_position == "" & new_position == "") || id_employee == "" || start_date == "") {
                $('#error').addClass("alert alert-danger");
                $('#error').addClass("rojo");
                $('#error').show("slow");
                $('#error').html("Faltan Datos Para Realizar el Registro");
            } else {
                $('#error').removeClass("rojo");
                $('#error').removeClass("alert alert-danger");
                $('#error').removeClass("icon-remove-circle");
                $('#error').html("");
                $ARU.AJAX.createPositionCode("GET", "/PositionCode/CreatePositionCode", "id_employee=" + id_employee + "&id_position=" + id_position + "&new_position=" + new_position + "&leader=" + leader + "&id_division=" + id_division + "&new_division=" + new_division + "&id_dependencia=" + id_dependencia + "&start_date=" + start_date + "&check=" + "false" + "&codePosition=" + position);

            }

        });
    }


    function createPosition(result)
    {
        switch (result) {
            case true:
                $('div#mensaje').html("<h4>CreaciÃ³n Exitosa de CÃ³digo de PosiciÃ³n!</h4>");
                $('#codigo_posicion').modal('show');
                $("#PositionCode_id_division").select2('val', '');
                $("#PositionCode_id_position").select2('val', '');
                $("#PositionCode_id_employee").select2('val', '');
                $("#PositionCode_start_date").val(" ");

                break;
            case false:

                $('#error').addClass("alert alert-danger");
                $('#error').addClass("rojo");
                $('#error').html("Falla en el Registro");
                $('#error').show();
                $('#error').fadeToggle(3000);
                break;

            case 'EmployeeAlreadyExists':

                $('#error').addClass("alert alert-danger");
                $('#error').addClass("rojo");
                $('#error').html("El Colaborador ya Existe y aÃºn estÃ¡ Activo");
                $('#error').show();
                $('#error').fadeToggle(3000);
                break;

            case "sinlider":

                $('#error').addClass("alert alert-danger");
                $('#error').addClass("rojo");
                $('#error').html("Se Necesita Crear un Lider/Coordinador/Gerente para esta DivisiÃ³n");
                $('#error').show();
                $('#error').fadeToggle(3000);
                break;

        }



    }

    function _addDivision()
    {
        $('a#siguiente').on('click', function() {

            var id_dependencia = $("#PositionCode_id_dependencia").val();
            var new_division = $("#PositionCode_new_division").val();
            var new_position = $("#new_position").val();
            var leader = $("#leader:checked").val();
            var id_division = '';
            var id_position = '';

            var idDivision = $("#PositionCode_id_division option:selected").text();
            var newDivision = $("input#PositionCode_new_division").val();

            if (idDivision != "DivisiÃ³n")
            {
                $("p#idDivision").attr("data-display");
                $("p#idDivision").removeClass("ocultar");
            }
            else
            {
                $("p#idDivision").addClass("ocultar");
                $("p#idDivision").html(" ");
            }

            var newPosicion = $("input#new_position").val();
            var idPosicion = $("#PositionCode_id_position option:selected").text();

            if (idPosicion != "Cargo")
            {
                $("p#idPosition").attr("data-display");
                $("p#idPosition").removeClass("ocultar");
            }
            else
            {
                $("p#idPosition").addClass("ocultar");
                $("p#idPosition").html(" ");
            }
        });

        $('p#test').on('click', function() {

            $("#PositionCode_id_division").select2('val', '');
            $("#PositionCode_dependencia").select2('val', '');
            $('#error').removeClass("rojo");
            $('#error').removeClass("alert alert-danger");
            $('#error').removeClass("icon-remove-circle");
            $('#error').html("");

            $("input.dependencia").val("");
            if ($("input.dependencia").css("display") == "none") {
                // $(this).html("<");
                $("input.dependencia").toggle("slide");
                $("#seleDepen").toggle("slide");
                $("#selenuevadivision").toggle("slide");
                $("div#selectDivision").hide("fast");
                $("div#mensaje").html("Nombre de la DivisiÃ³n");
                $("div#mensajedependencia").html("Dependencia");
                $('#test').removeClass("newGroup icon-plus");
                $('#test').addClass("cancelarnewGroup icon-arrow-left");



            } else {
                $("div#mensaje").html("");
                $("div#mensajedependencia").html("");
                $("input.dependencia").hide("fast");
                $("div#selectDivision").toggle("slide");
                $("#seleDepen").hide("fast");
                $("#selenuevadivision").hide("fast");
                $('#test').removeClass("cancelarnewGroup icon-arrow-left");
                $('#test').addClass("newGroup icon-plus");

                //$(this).html("+");
            }
        });
        $('p#cargo').on('click', function() {

            $("#new_position").val("");
            $("#PositionCode_id_position").select2('val', '');
            if ($("input.cargo").css("display") == "none") {
                //$(this).html("<");
                $("input.cargo").toggle("slide");
                $("div#selectCargo").hide("fast");
                $("div#mensajeCargo").html("Nombre del Cargo");
                $("div#mensajeLider").html("Lider");
                $("div#checkbox").toggle("slide");
                $("#selenuevocargo").toggle("slide");
                $('#cargo').removeClass("newGroup icon-plus");
                $('#cargo').addClass("cancelarnewGroup icon-arrow-left ");
            }

            else
            {

                $("div#mensajeCargo").html("");
                $("div#mensajeLider").html("");
                $("input.cargo").hide("fast");
                $("div#checkbox").hide("fast");
                $("div#selectCargo").toggle("slide");
                $("#selenuevocargo").hide("fast");
                $('#cargo').removeClass("cancelarnewGroup icon-arrow-left");
                $('#cargo').addClass("newGroup icon-plus");
            }

        });

    }




    function createDivision(result)
    {
        $('div#newid').html(result);
    }

    /**
     * funcion para cargar mensajes de cracion de cargos nuevos en la organizacion
     */

    function createCargo(result)
    {
        $('#cargoresul').html(result);
    }

    function viewPositionCode(result) {

        $("div#posicion").html(result);
    }


    /**
     * funcion para el excel
     */

    function _genExcel() {

        $('a.botonExcel').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {
            $("#complete").html("<h3>Generando Excel... !!</h3>");
            $('#administrarPosicion').modal('show');

            var ids = new Array();//Creamos un Array como contenedor de los ids. 
            var idTable = $('div.tab-content div.active table').attr('id');
            var name = genNameFile(idTable);

            $("div.tab-content div.active #" + idTable + " td#ids").each(function(index) { //Con esta funcion de jquery recorremis la columna (oculta) de los ids.
                ids[index] = $(this).text(); //incluimos los ids de la columna en el array.
            });
            if (ids != '') {
                //$ARU.AJAX.excelCp("GET", "/site/excel","ids="+ ids +"&name="+ name +"&table="+ idTable, ids, idTable,name );

                var response = $.ajax({type: "GET",
                    url: '/site/excel?ids=' + ids + '&name=' + name + "&table=" + idTable,
                    async: true,
                    success: function(response) {
                        //Abrimos una Ventana (sin recargarla pagina) al controlador "Site", que a su ves llama a la funcion actionExcel().
                        setTimeout("window.open('/site/excel?ids=" + ids + "&name=" + name + "&table=" + idTable + "','_top');", 500);
                        //Mostramos los Mensajes y despues de la Descarga se Ocultan Automaticamente.
                        setTimeout('$("#complete").html("<h3>Archivo Excel Generado... !!</h3>");', 1800);
                        setTimeout('$("#administrarPosicion").modal("hide");', 2500);
                    }
                }).responseText;

            }

            else {
                $("#complete").removeClass("verde");
                $("#complete").addClass("rojo");
                setTimeout('$("#complete").html("<h3>No Existen Datos... !!</h3>");', 1800);
                setTimeout('$("#administrarPosicion").modal("hide");', 2500);
            }

        });

    }

    function _genExcelPHPExcel() {
        $('a#botonPHPExcel').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {
            $("#complete").html("<h3>Generando Excel... !!</h3>");
            $('#administrarPosicion').modal('show');


            var idTable = $('.table-export').attr('id');
            var name = genNameFile(idTable);

            var ids = new Array();


            $("#" + idTable + " td#ids").each(function(index) { //Con esta funcion de jquery recorremis la columna (oculta) de los ids.
                ids[index] = $(this).text(); //incluimos los ids de la columna en el array.
            });

            var start_date = $('#startDate').text();
            var end_date = $('#endDate').text();
            var dates = new Array(start_date, end_date);


            if (ids != '') {
                var data = new Array(ids, dates);
                var Alldata = JSON.stringify(data);
                console.log(Alldata);
                //$ARU.AJAX.excelCp("GET", "/site/excel","ids="+ ids +"&name="+ name +"&table="+ idTable, ids, idTable,name );

//                var response = $.ajax({type: "GET",
//                    url: '/site/excel?ids=' + ids + '&name=' + name + "&table=" + idTable,
//                    async: true,
//                    success: function(response) {
                //Abrimos una Ventana (sin recargarla pagina) al controlador "Site", que a su ves llama a la funcion actionExcel().
                setTimeout("window.open('/report/export/excel?data=" + Alldata + "&name=" + name + "&table=" + idTable + "','_top');", 500);
                //Mostramos los Mensajes y despues de la Descarga se Ocultan Automaticamente.
                setTimeout('$("#complete").html("<h3>Archivo Excel Generado... !!</h3>");', 1800);
                setTimeout('$("#administrarPosicion").modal("hide");', 2500);
//                    }
//                }).responseText;

            } else {

                setTimeout('$("#complete").html("<h3>No Existen Datos... !!</h3>");', 1800);
                setTimeout('$("#administrarPosicion").modal("hide");', 2500);
            }

        });
    }

    /**
     * funcion para el email
     */



    function _genEmail() {

        $('a.botonCorreo').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {

            $("#complete").html("<h3>Enviando Correo... !!</h3>");
            $('#administrarPosicion').modal('show');

            var ids = new Array();//Creamos un Array como contenedor de los ids. 
            var idTable = $('div.tab-content div.active table').attr('id');
            var name = genNameFile(idTable);

            $("div.tab-content div.active #" + idTable + " td#ids").each(function(index) { //Con esta funcion de jquery recorremis la columna (oculta) de los ids.
                ids[index] = $(this).text(); //incluimos los ids de la columna en el array.
            });

            if (ids != '')
            {
                $ARU.AJAX.emailCp("GET", "/site/sendemail", "ids=" + ids + "&name=" + name + "&table=" + idTable);


//                        $.ajax({ 
//                                    type: "GET",   
//                                    url: '/site/sendemail?ids='+ids+'&name='+name+"&table="+idTable,   
//                                    async: false,
//                                    success:  function (response) {
////                                             setTimeout('$("#complete").html("<h3>Correo Enviado con Exito... !!</h3>");',1800 );
////                                             setTimeout('$("#administrarPosicion").modal("hide");',2500 );
//                                    }
//                                  });
            }
//                      
            else {


                setTimeout('$("#complete").html("<h3>No Existen Datos... !!</h3>");', 1800);
                setTimeout('$("#administrarPosicion").modal("hide");', 2500);

            }

        });

    }




    /**
     * funcion para imprimir
     */

    function _genPrint() {
        $('a.printButton').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {

            var ids = new Array();//Creamos un Array como contenedor de los ids. 
            var idTable = $('div.tab-content div.active table').attr('id');
            var name = genNameFile(idTable);

            $("div.tab-content div.active #" + idTable + " td#ids").each(function(index) { //Con esta funcion de jquery recorremis la columna (oculta) de los ids.
                ids[index] = $(this).text(); //incluimos los ids de la columna en el array.
            });

            if (ids != '')
            {
                //Creamos la variable que contiene la tabla generada.
                var response = $.ajax({type: "GET",
                    url: "/site/print?ids=" + ids + "&table=" + idTable + "&name=" + name,
                    async: false,
                }).responseText;
                //Creamos la variable que alberga la pagina con la tabla generada.
                var content = '<!DOCTYPE html><html><meta charset="es">' +
                        '<head><link href="/css/print.css" media="all" rel="stylesheet" type="text/css"></head>' +
                        '<body>'
                        //Tabla con Formato
                        + response +
                        '<script type="text/javascript">function printPage() { window.focus(); window.print();return; }</script>' +
                        '</body></html>';


                //Creamos un 'iframe' para simular la apertura de una pagina nueva sin recargar ni alterar la anterior.
                var newIframe = document.createElement('iframe');
                newIframe.width = '0';
                newIframe.height = '0';
                newIframe.src = 'about:blank';
                document.body.appendChild(newIframe);
                newIframe.contentWindow.contents = content;
                newIframe.src = 'javascript:window["contents"]';
                newIframe.focus();
                //setTimeout(function() {
                newIframe.contentWindow.printPage();
                //}, 10);
                return;
            }

            else {
                $('#administrarPosicion').modal('show');
                $("#complete").removeClass("verde");
                $("#complete").addClass("rojo");
                setTimeout('$("#complete").html("<h3>No Existen Datos... !!</h3>");', 1800);
                setTimeout('$("#administrarPosicion").modal("hide");', 2500);

            }

        });
    }


    /**
     * Titulos para los reportes
     */


    function genNameFile(idTable) {
        var name = '';
        switch (idTable) {
            case 'adminPositionCodeActive':
                name = 'ARU Administrar CÃ³digo de PosiciÃ³n Activos';
                break;
            case 'adminPositionCodeInactives':
                name = 'ARU Administrar CÃ³digo de PosiciÃ³n Inactivos';
                break;
            case 'tableHousr':
                name = 'ARU Reporte de Jornada Laboral';
                break;
        }
        return name;
    }

    /**
     * 
     * 
     */

    function _deleteCp() {

        $('a#deleteCp').on('click', function(event)
        {

            var idEmployee = ($(this).find('div#id_employ').text());

            var first_name = ($(this).find('div#name_employ').text());
            var last_name = ($(this).find('div#last_employ').text());
            var idPosition = ($(this).find('div#id_position').text());
            var idDivision = ($(this).find('div#id_division').text());
            var positionCode = ($(this).find('div#position_code').text());

            $('#first_name').html('<h4>' + first_name + ' ' + last_name + '</h4>');
            $('#id_employee').html(idEmployee);
            $('#id_posi').html(idPosition);
            $('#id_divi').html(idDivision);
            $('#position_c').html(positionCode);
            $('.employefooter').removeClass("ocultar");
            $('#elimarConfirmar').modal('show');

//                   $ARU.AJAX.endDate("GET","/PositionCode/SetEndDate","id_employee="+id);
        });

    }

    function _confirmDeleteCp(id) {

        $('a#confirmEndDate').on('click', function(event)//Al pulsar la imagen de Excel, es Generada la siguiente Funcion:
        {
            var idEmployee = ($(this).find('div#id_employee').text());
            var idPosition = ($(this).find('div#id_posi').text());
            var idDivision = ($(this).find('div#id_divi').text());
            var positionCode = ($(this).find('div#position_c').text());

            $ARU.AJAX.endDate("GET", "/PositionCode/SetEndDate", "id_employee=" + idEmployee + "&id_position=" + idPosition);
        });

        $('a#botonconfirmaractivecp').on('click', function(event)
        {
            var idPositionCode = $('div#idPC').text();
            $ARU.AJAX.confirmarActivarCpEmployee("GET", "/PositionCode/ActivePositionCode", "id=" + idPositionCode);
        });

        $('a#botonconfirmardeletecp').on('click', function(event)
        {

            var idEmployee = $('div#id_employee_eliminar').text();
            var idPosition = $('div#id_posi_eliminar').text();
            var idDivision = $('div#id_divi_eliminar').text();
            var positionCode = $('div#position_c_eliminar').text();

            $ARU.AJAX.confirmarDelete("GET", "/PositionCode/DeletePositionCode", "position_code=" + positionCode);
        });

        $('a#confirmUpdate').on('click', function(event)
        {
            var idEmployee = ($(this).find('div#id_employee').text());
            var idPosition = ($(this).find('div#id_posi').text());
            var idDivision = ($(this).find('div#id_divi').text());
            var positionCode = ($(this).find('div#position_c').text());

//            $ARU.AJAX.endDate("GET", "/PositionCode/UpdatePositionCode", "position_code=" + positionCode + '&id_employee=' + id_employee + '&start_date=' + start_date);
        });
    }



    function _asigPayRoll()
    {
        $("#options_right, #options_left").on("click", function()
        {
            switch ($(this).attr('id'))
            {
                case "options_right":
                    $('#select_left :selected').each(function(i, selected)
                    {
                        $("#select_left option[value='" + $(selected).val() + "']").remove();
                        $('#select_right').append("<option value='" + $(selected).val() + "'>" + $(selected).text() + "</option>");
                    });
                    break;
                case "options_left":
                    $('#select_right :selected').each(function(i, selected)
                    {
                        $("#select_right option[value='" + $(selected).val() + "']").remove();
                        $('#select_left').append("<option value='" + $(selected).val() + "'>" + $(selected).text() + "</option>");
                    });
                    break;
            }
        });
    }

    function _getEmployeePayRoll()
    {
        var idsPayRoll = new Array();
        $('#options_left, #options_left_all').on('click', function(event)
        {
            $("#payRoll select option").prop("selected", true);
            var payRoll = $("#Payroll_name").val();
            var assigned = $("#select_left").val();

            if ((payRoll != null) && (assigned != null)) {
                idsPayRoll[payRoll] = assigned;
                console.log(idsPayRoll);
            }

        });

        $('a#asigNomina').on('click', function(event)
        {

            var value = '';
            value = JSON.stringify(idsPayRoll);
            $ARU.AJAX.payRoll("GET", "/PayRollEmployee/GetNewPayRollEmployee", "arrayPayRoll=" + value);

        });
        idsPayRoll = new Array();

        $('#Payroll_name').change('change', function(event)
        {

            var idPayRoll = $("#Payroll_name").val();

            $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + idPayRoll);

        });

    }

    function _getTablePayRoll()
    {
        var payRoll = $("#Payroll_name").val();
        var tabId;
        var tabName;

        $('a.tabb').on('click', function(event)
        {
            tabId = $(this).attr('id');
            tabName = $(this).text();
//              console.log('mas '+tab);
            if (tabId != 0) {
                $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + tabId);
            } else {
                console.log('tab inicial');
            }
        });


        $('a#asigNominatable').on('click', function(event)
        {


            var nameTable = $('table.dataTable').attr('id');
            var arrayIdEmployee = new Array();
            var oTableCkeck = $('#' + nameTable).dataTable();
            var payRoll = $("#payRoll option:selected").text();
            var newPayRoll = $("#inputNewPayRoll").val();
            var concepPayRoll = '';

            if (newPayRoll != '') {
                concepPayRoll = newPayRoll;
            }
            else {
                concepPayRoll = payRoll;
            }

            var idsPayRoll = new Array();
            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayIdEmployee[index] = $(this).val();

            });


            if ((arrayIdEmployee != '') && (payRoll != '')) {
                idsPayRoll = arrayIdEmployee;
                var value = '';
                value = JSON.stringify(idsPayRoll);
                $ARU.AJAX.payRoll("GET", "/PayRollEmployee/GetNewPayRollEmployee", "arrayPayRoll=" + value + "&concepPayRoll=" + concepPayRoll);

            }

            else {
                $('#titleEmptyEmployee').html("<h4>N&oacute;mina</h4>");
                $('#messengerEmptyEmployee').html("<span style='font-size:20px'><i class='fa fa-exclamation-triangle'></i>  Seleccione por lo menos un Colaborador</span>");
                $('#emptyEmployeeSelect').modal('show');
                setTimeout('$("#emptyEmployeeSelect").modal("hide");', 2000);

            }
        });


        $('a#newPayRoll').on('click', function(event)
        {
            if ($("div#nuevaNomina").css("display") == "none") {
                $("div#showNewPayRoll").hide("fast");
                $("div#nuevaNomina").toggle("slide");
                $("input.inputNewPayRoll").toggle("slide");
                $('#morePayRoll').removeClass("newGroup icon-plus");
                $('#morePayRoll').addClass("cancelarnewGroup icon-arrow-left ");
                $('#morePayRoll').attr("data-original-title", "Regresar");
            }
            else {


                $("div#nuevaNomina").hide("fast");
                $("input.inputNewPayRoll").hide("fast");
                $("div#showNewPayRoll").toggle("slide");
                $('#morePayRoll').removeClass("cancelarnewGroup icon-arrow-left");
                $('#morePayRoll').addClass("newGroup icon-plus");
                $('#morePayRoll').attr("data-original-title", "Agregar Nuevo tipo de NÃ³mina");

            }
        });
    }

    function _deletePayRollEmployee()
    {

        $('a#confirDeleteEmployeePayRoll').on('click', function(event)
        {

            var arrayIdEmployee = new Array();
            var arrayName = new Array();
            var payRoll = $("li.active a.tabb").attr('id');
            var verificarData = '';
            var oTableCkeck = '';

            verificarData = $('div#dataTable_' + payRoll).find("#sample_" + payRoll + " > tbody > tr > td:nth-child(1) > div > span > input").val();

            if (verificarData == undefined) {

                $('#titleEmptyEmployee').html("<h4>N&oacute;mina</h4>");
                $('#messengerEmptyEmployee').html("<span style='font-size:20px'><i class='fa fa-exclamation-triangle'></i>  Seleccione por lo menos un Colaborador</span>");
                $('#emptyEmployeeSelect').modal('show');
                setTimeout('$("#emptyEmployeeSelect").modal("hide");', 2000);
            }

            else {

                oTableCkeck = $('div#dataTable_' + payRoll + ' table#sample_' + payRoll).dataTable();
                $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                    arrayIdEmployee[index] = $(this).val();

                });

                if (arrayIdEmployee == '') {

                    $('#titleEmptyEmployee').html("<h4>N&oacute;mina</h4>");
                    $('#messengerEmptyEmployee').html("<span style='font-size:20px'><i class='fa fa-exclamation-triangle'></i>  Seleccione por lo menos un Colaborador</span>");
                    $('#emptyEmployeeSelect').modal('show');
                    setTimeout('$("#emptyEmployeeSelect").modal("hide");', 2000);
                }

                else {
                    var namePayRoll = $("li.active a.tabb").text();
                    $('#tituloConfirEliminar').html("<h4>N&oacute;mina</h4>");
                    $('#messengerConfirtDelete').html("<span class='ladrillo' style='font-size:18px'>Â¿Est&aacute; seguro que desea eliminar los siguientes colaboradores de la " + namePayRoll + "?</span>");

                    $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                        arrayName[index] = '<div>' + $(this).parent().parent().parent().find('#nameModalModification').text() + '</div>';
                    });
                    $('#messengerContent').html(arrayName);
                    $('#confirmarEliminar').modal('show');

                }
                oTableCkeck.fnDestroy();
            }
        });

        $('a#deleteEmployeePayRoll').on('click', function(event)
        {

            var arrayIdEmployee = new Array();
            var payRoll = $("li.active a.tabb").attr('id');
            var verificarData = '';
            var oTableCkeck = '';

            verificarData = $('div#dataTable_' + payRoll).find("#sample_" + payRoll + " > tbody > tr > td:nth-child(1) > div > span > input").val();



            oTableCkeck = $('div#dataTable_' + payRoll + ' table#sample_' + payRoll).dataTable();
            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayIdEmployee[index] = $(this).val();

            });
            console.log(arrayIdEmployee);
            var value = '';
            value = JSON.stringify(arrayIdEmployee);
            $ARU.AJAX.deletePayRoll("GET", "/PayRollEmployee/DeletePayRollByEmployee", "arrayEmployee=" + value + "&idPayRoll=" + payRoll);
            oTableCkeck.fnDestroy();

        });
    }


    function updateTable(result)
    {

        $('li.active a.tabb div.dataTable').empty();
        $('div.dataTable').html(result);
        var table = '';
        var nameTable = $('table.dataTable').attr('id');
        var tabId = $('li.active a.tabb').attr('id');

        Metronic.init();
        ComponentsPickers.init();

        if (tabId != 0) {
            table = $('div.dataTable #sample_' + tabId).dataTable({
                "aaSorting": [[1, 'asc']],
                "iDisplayLength": [5],
                "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
                "pageLength": 5,
                "pagingType": "full_numbers",
                "aoColumnDefs": [
                    {
                        "aTargets": [6],
                        "mData": null,
                        "mRender": function(data, type, full) {
                            return '<a href="#" id="updatePayRollEmployee" class="btn btn-xs default"><i class="icon-pencil"></i> Modificar</a> <a href="#" id="updpayroll" class="btn btn-xs default"><i class="fa fa-eye"></i> Detalle</a>';
                        }
                    },
                    {"bSortable": false, "aTargets": [0]},
                ],
                "language": {
                    "lengthMenu": "  _MENU_ Registros",
                    "sSearch": "Buscar: _INPUT_",
                    "emptyTable": "No existen colaboradores asignados a esta n&oacute;mina",
                    "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> de <b>_TOTAL_</b> Registros",
                    "sInfoFiltered": " (filtrado de _MAX_ registros totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "last": "&Uacute;ltimo",
                        "first": "Primero"
                    }
                },
            });

//            table.fnClearTable();

            var tableWrapper = jQuery('#sample_' + tabId + '_wrapper');

            table.find('.group-checkable').change(function() {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function() {
                    if (checked) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("active");

                    } else {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("active");
                    }
                });
                jQuery.uniform.update(set);
            });
            table.on('change', 'tbody tr .checkboxes', function() {
                $(this).parents('tr').toggleClass("active");
            });
            tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline");
            Metronic.init();
        } else {
            console.log('DataTable Inicial');
        }

        $('a#updpayroll').on('click', function(event)
        {
            var idEmployee = $(this).parent().parent().find('input[type="checkbox"]').val();
            var idPayRoll = $("ul#listaNomina li.active").find('a.tabb').attr('id');
            var namePayRoll = $("ul#listaNomina li.active").find('a.tabb').text();

            $('div#messengerModification').addClass("ocultar");
            $('#titleModalView').html("<h4><i class='icon-eyeglasses'></i>  Detalles " + namePayRoll + "</h4>");
            $('#PayRollModalEmployee').modal('show');

            $('.modalView').load('/Remuneration/CreateRemuneration?idEmployee=' + idEmployee + '&idPayRoll=' + idPayRoll, function() {

                Demo.init(); // init demo features
                ComponentsFormTools.init();
                Metronic.init();


            });
        });

        $('a#updatePayRollEmployee').on('click', function(event)
        {
            var idPayRoll = $("ul#listaNomina li.active").find('a.tabb').attr('id');
            var namePayRoll = $("ul#listaNomina li.active").find('a.tabb').text();
            var idEmployee = $(this).parent().parent().find('input[type="checkbox"]').val();
            var date = $(this).parent().parent().find('div#dateModalModification').text();
            var idCurrency = $(this).parent().parent().find('div#idCurrencyModalModification').text();
            console.log(idPayRoll);
            var employeePayRoll = JSON.parse($ARU.AJAX.getEmployeePayRoll("GET", "/PayRollEmployee/GetDataEmployeePayRoll", 'idEmployee=' + idEmployee + '&idPayRoll=' + idPayRoll));
//            console.log(employeePayRoll[7]);

            Metronic.init();
            $('div#messengerModification').addClass("ocultar");
            $('div#mensajeconfirAsig').addClass("ocultar");
            $('div#mensajeconfirUptade').addClass("ocultar");
            $('#titleModalModificar').html("<h4><i class='fa fa-pencil'></i>  Modificar " + namePayRoll + "</h4>");
            $('#titleNombreEmpleado').html("<h4>Colaborador (Cargo):   ");
            $('#NombreEmpleado').html("<span style='color:#000000;'><h4>" + employeePayRoll [0] + " (" + employeePayRoll[1] + ")</h4></span>");
            $('#titleDivisionEmpleado').html("<h4>DivisiÃ³n del Colaborador:   ");
            $('#DivisionEmpleado').html("<h4><span style='color:#000000;'>" + employeePayRoll[2] + "</span></h4>");
            $("#start_date_mod").val(employeePayRoll[4]);
            $("#end_date_mod").val(employeePayRoll[5]);
            $('#titleDateEmpleado').html("<h4>fecha de Ingreso a la NÃ³mina: ");
            $('#titleSalarioEmpleado').html("<h4>Salario Base:  </h4>");
            $('#salarioBase').val(employeePayRoll[6]);
            $('#idModalModification').html(idEmployee);
            $('#currency').val(employeePayRoll[7]);
            $('#ModificarNomina').modal('show');

        });

    }



    function _confirmarFinalizarNomina() {

        $('a#confirmarFinalizadoBoton').on('click', function(event)
        {

            var arrayIdEmployee = new Array();
            var arrayName = new Array();
            var payRoll = $("li.active a.tabb").attr('id');
            var verificarData = '';
            var oTableCkeck = '';
            $('div#contenidoFinalityPayRoll').removeClass("ocultar");
            $('div#mensajeRetorno').addClass("ocultar");

            verificarData = $('div#dataTable_' + payRoll).find("#sample_" + payRoll + " > tbody > tr > td:nth-child(1) > div > span > input").val();

            if (verificarData == undefined) {

                $('#titleEmptyEmployee').html("<h4>N&oacute;mina</h4>");
                $('#messengerEmptyEmployee').html("<span style='font-size:20px'><i class='fa fa-exclamation-triangle'></i>  Seleccione por lo menos un Colaborador</span>");
                $('#emptyEmployeeSelect').modal('show');
                setTimeout('$("#emptyEmployeeSelect").modal("hide");', 2000);
            }

            else {

                oTableCkeck = $('div#dataTable_' + payRoll + ' table#sample_' + payRoll).dataTable();
                $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                    arrayIdEmployee[index] = $(this).val();

                });

                if (arrayIdEmployee == '') {

                    $('#titleEmptyEmployee').html("<h4>N&oacute;mina</h4>");
                    $('#messengerEmptyEmployee').html("<span style='font-size:20px'><i class='fa fa-exclamation-triangle'></i>  Seleccione por lo menos un Colaborador</span>");
                    $('#emptyEmployeeSelect').modal('show');
                    setTimeout('$("#emptyEmployeeSelect").modal("hide");', 2000);
                }

                else {
                    var namePayRoll = $("ul#listaNomina li.active a.tabb").text();
                    $('#tituloConfirFinalizado').html("<h4>N&oacute;mina</h4>");
                    $('#mensajeconfirFinalizado').html("<span class='ladrillo' style='font-size:18px'>Â¿Est&aacute; seguro que desea finalizar en nÃ³mina los siguientes colaboradores de la " + namePayRoll + "?</span>");

                    $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                        arrayName[index] = '<div>' + $(this).parent().parent().parent().find('#nameModalModification').text() + '</div>';
                    });
                    $('#contenidoEmpleado').html(arrayName);
                    $('#confirmarFinalizado').modal('show');

                }
                oTableCkeck.fnDestroy();
            }
        });


    }


    function _confirmarBotonFinalizar() {
        $('a#finalizarEmpleado').on('click', function(event)

        {


            var arrayIdEmployee = new Array();
            var payRoll = $("li.active a.tabb").attr('id');
            var verificarData = '';
            var oTableCkeck = '';


            verificarData = $('div#dataTable_' + payRoll).find("#sample_" + payRoll + " > tbody > tr > td:nth-child(1) > div > span > input").val();

            oTableCkeck = $('div#dataTable_' + payRoll + ' table#sample_' + payRoll).dataTable();
            $("input:checked", oTableCkeck.fnGetNodes()).each(function(index) {
                arrayIdEmployee[index] = $(this).val();

            });
            var value = '';
            value = JSON.stringify(arrayIdEmployee);
            $ARU.AJAX.finalizarNominaEmployee("GET", "/PayRollEmployee/FinEmployeePayRoll", "arrayEmployee=" + value + "&idPayRoll=" + payRoll);
            oTableCkeck.fnDestroy();
        });

    }

    function refreshDataTableEmployee() {

        Metronic.init();
        var table = '';
        table = $('#sample_0').dataTable({
            "aaSorting": [[1, 'asc']],
            "iDisplayLength": [5],
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
            "pageLength": 5,
            "pagingType": "full_numbers",
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0]},
            ],
            "language": {
                "lengthMenu": "  _MENU_ Registros",
                "sSearch": "Buscar: _INPUT_",
                "emptyTable": "No existen colaboradores asignados a esta n&oacute;mina",
                "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> de <b>_TOTAL_</b> Registros",
                "sInfoFiltered": " (filtrado de _MAX_ registros totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "last": "&Uacute;ltimo",
                    "first": "Primero"
                }
            },
        });
        var tableWrapper = jQuery('#sample_0_wrapper');
        table.find('.group-checkable').change(function() {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function() {
                if (checked) {
                    $(this).attr("checked", true);
                    $(this).parents('tr').addClass("active");

                } else {
                    $(this).attr("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });
        table.on('change', 'tbody tr .checkboxes', function() {
            $(this).parents('tr').toggleClass("active");
        });
        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline");
        Metronic.init();
    }

    function successAsigPayRoll(result)
    {

        var payRollName = $('select#payRoll option:selected').text();
        switch (result) {
            case true:
                $('#mensajeconfirAsig').addClass("verde");
                $('#mensajeconfirAsig').removeClass("rojo");
                $('#mensajeconfirAsig').removeClass("ocultar");
                $('#scrollArray').removeAttr("style");
                $('#mensajeconfirAsig').html("<h2>AsignaciÃ³n de NÃ³mina Exitoso</h2>");
                $('#administrarNomina').modal('show');
                setTimeout('$("#administrarNomina").modal("hide");', 3000);

                //REINICIA EL DOM DE LAS PESTAÃ‘AS DE PAYRROL
                $('#contentNomina').load('/PayRoll/GetAllPayRoll #contentNomina', function() {
                    refreshDataTableEmployee();
                    _getTablePayRoll();
                    _deletePayRollEmployee();
                    _confirmarFinalizarNomina();

                    Metronic.init();
                });


                break;

            case false:
                $('#mensajeconfirAsig').addClass("rojo");
                $('#mensajeconfirAsig').removeClass("verde");
                $('#mensajeconfirAsig').html("<h2>Error de AsignaciÃ³n de NÃ³mina</h2>");
                $('#administrarNomina').modal('show');
                setTimeout('$("#administrarNomina").modal("hide");', 3000);
                break;
            default:
                var contendidos = new Array();

                $('#mensajeconfirAsig').addClass("rojo");
                $('#mensajeconfirAsig').removeClass("verde");
                $('#mensajeconfirAsig').removeClass("ocultar");
                $('#scrollArray').attr("style", "height:130px; overflow: scroll;  overflow-x: hidden;");
                $('#mensajeconfirAsig').html("<h4>Los Siguientes Colaboradores Ya Pertenecen a la NÃ³mina " + payRollName + "</h4>");
                $.each(result, function(key, value) {
                    contendidos[key] = '<div>' + value + '</div>';
                });
                $('#contenidoEmployeeall').html(contendidos);
                $('#administrarNomina').modal('show');
                setTimeout('$("#administrarNomina").modal("hide");', 3000);
                setTimeout('$("#contenidoEmployeeall").empty();', 3000);

                break;
        }
    }

    function successDeleteEmployeePayRoll(result)
    {
        switch (result) {
            case true:
                var payRoll = $("li.active a.tabb").attr('id');
                $('#contenidoConfirmarEliminar').removeClass("ocultar");
                $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + payRoll);
                $('#confirmarEliminar').modal('hide');

                break;

            case false:
                $('#contenidoConfirmarEliminar').removeClass("ocultar");
                $('#contenidoConfirmarEliminar').html("<h2>Error al Eliminar de la NÃ³mina</h2>");
                $('#confirmarEliminar').modal('hide');

                break;
            default:


                break;
        }

    }


    function _getDateNomina() {

        $('a#capturarDatePayRoll').on('click', function(event)
        {

            var basePayRoll = $('input#salarioBase').val();
            var start_date = $('input#start_date_mod').val();
            var end_sate = $('input#end_date_mod').val();
            var currency = $('#currency').val();


            var idEmployee = $('div#idModalModification').text();
            var payRoll = $("li.active a.tabb").attr('id');

            var arrayData = {
                "salary": basePayRoll,
                "start_date": start_date,
                "end_date": end_sate,
                "id_currency": currency,
            };


            value = JSON.stringify(arrayData);
            console.log(value);
            $ARU.AJAX.updateNominas("GET", "/PayRollEmployee/UpdatePayRollByEmployee", "arrayData=" + value + "&idPayRoll=" + payRoll + "&idEmployee=" + idEmployee);


        });

    }

    function successUpdateayRoll(result) {

        switch (result) {
            case true:

                var payRoll = $("li.active a.tabb").attr('id');
                $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + payRoll);
                $('div#mensajeconfirUptade').addClass("verde");
                $('div#mensajeconfirUptade').addClass("alert alert-success");
                $('div#mensajeconfirUptade').removeClass("ocultar");
                $('div#mensajeconfirUptade').removeClass("rojo");
                $('div#mensajeconfirUptade').html("Proceso Completado");
                setTimeout('$("#ModificarNomina").modal("hide");', 3000);

                break;

            case false:
                $('#mensajeconfirUptade').addClass("rojo");
                $('#mensajeconfirUptade').removeClass("verde");
                $('#mensajeconfirUptade').html("<h2>Error al Eliminar de la NÃ³mina</h2>");
                $('#administrarNomina').modal('show');
                setTimeout('$("#administrarNomina").modal("hide");', 3000);
                break;
            default:


                break;
        }

    }

    function _modificationPayRoll()
    {
        $('a#saveModificationPayRoll').on('click', function(event)
        {
            var $inputs = $('#remunerationValues :input');
            var values = {};
            $inputs.each(function(index) {
                values[this.name] = $(this).val();
            });

            var idEmployee = $('#idEmployee').text();
            var payRoll = $("li.active a.tabb").attr('id');
            var value = JSON.stringify(values);
            $ARU.AJAX.modificationPayRollEmployee("GET", "/Remuneration/UpdateRemuneration", "arrayCategory=" + value + "&idPayRoll=" + payRoll + "&idEmployee=" + idEmployee);

        });

    }

    function successModicationPayRoll(result)
    {

        switch (result) {
            case true:

                $('div#messengerModification').addClass("verde");
                $('div#messengerModification').addClass("alert alert-success");
                $('div#messengerModification').removeClass("ocultar");
                $('div#messengerModification').removeClass("rojo");
                $('div#messengerModification').removeClass("alert-danger");
                $('div#messengerModification').html("Proceso Completado");
                setTimeout('$("#PayRollModalEmployee").modal("hide");', 2000);
                break;

            case false:
                $('div#messengerModification').removeClass("ocultar");
                $('div#messengerModification').addClass("rojo");
                $('div#messengerModification').addClass("alert alert-danger");
                $('div#messengerModification').removeClass("verde");
                $('div#messengerModification').html("Proceso no completado");
                setTimeout('$("#PayRollModalEmployee").modal("hide");', 2000);
                break;
            default:


                break;
        }

    }

    function successFinalityPayRoll(result)
    {
        switch (result) {
            case true:

                $('div#sussecConfirFinalityPayRoll').removeClass("ocultar");
                $('div#contenidoFinalityPayRoll').addClass("ocultar");
                $('div#mensajeRetorno').removeClass("ocultar");

                $('div#sussecConfirFinalityPayRoll').addClass("verde");
                $('div#sussecConfirFinalityPayRoll').html("<h4>Proceso Completado</h4>");
                setTimeout('$("#confirmarFinalizado").modal("hide");', 2000);
                var payRoll = $("li.active a.tabb").attr('id');
                $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + payRoll);
                break;

            case false:
                $('div#contenidoFinalityPayRoll').addClass("ocultar");
                $('div#sussecConfirFinalityPayRoll').removeClass("ocultar");

                $('div#mensajeRetorno').removeClass("ocultar");
                $('div#sussecConfirFinalityPayRoll').addClass("rojo");
                $('div#sussecConfirFinalityPayRoll').addClass("alert alert-danger");
                $('div#sussecConfirFinalityPayRoll').removeClass("verde");
                $('div#sussecConfirFinalityPayRoll').html("Proceso no completado");
                setTimeout('$("#confirmarFinalizado").modal("hide");', 2000);
                var payRoll = $("li.active a.tabb").attr('id');
                $ARU.AJAX.idPayRoll("GET", "/PayRollEmployee/GetEmployeeByPayRoll", "idPayRoll=" + payRoll);
                break;
            default:


                break;
        }

    }



    function _getTableAllEmployee()
    {
        $('select#employeeAll').on('change', function() {
            var list = $(this).val();
            console.log(list);
            if (list == '') {
                console.log('vacio');
            } else {
                console.log('lleno');
                $("#statusEmployee").select2('val', '');
            }
        });

        $('a#reporteFiltrar').on('click', function(event)
        {
            var idsStatus = $('#typeEventList').val();
            var statusEmployee = $('#statusEmployee').val();
            var idDivision = $('#departemento').val();
            var idsEmployee = $('#employeeAll').val();
            var startDate = $('#fecha_ini').val();
            var endDate = $('#fecha_fin').val();

            if (idsStatus) {
            } else {
                idsStatus = "Empty";
            }
            if (idDivision) {
            } else {
                idDivision = "Empty";
            }
            if (idsEmployee) {
                idDivision = "Empty";
            } else {
                idsEmployee = "Empty";
            }
            if (startDate) {
            } else {
                startDate = "Empty";
            }
            if (endDate) {
            } else {
                endDate = "Empty";
            }
            if (statusEmployee) {
            } else {
                statusEmployee = "Empty";
            }

            var color = false;
            if ($("#setColor").attr("checked")) {
                color = true;
            } else {
                color = false;
            }

            var passSD = encodeURIComponent(startDate);
            var passED = encodeURIComponent(endDate);
            var statusEmployee = encodeURIComponent(statusEmployee);

            console.log(statusEmployee);


            $('#titleModalCargaDatos').html('Tabla de DeclaraciÃ³n');
            $('#mensajeRefresh').html('<h3><span class="verde"> Cargando Datos!!</span></h3>');
            $('#ModalRefreshTable').modal('show');
            $('#allEmployee').load('/report/Employee/GetAllHourEmployee?idsEmployee=' + idsEmployee + '&idDivision=' + idDivision + '&idsStatus=' + idsStatus + '&startDate=' + passSD + '&endDate=' + passED + '&color=' + color + '&statusEmployee=' + statusEmployee + ' #tableAllEmployee ', function(responseText, textStatus, XMLHttpRequest) {

                if (textStatus == "success") {
                    $('#mensajeRefresh').html('<h3><span class="verde"> Carga de Datos Exitosa!!</span></h3>');
                    setTimeout('$("#ModalRefreshTable").modal("hide");', 1500);
                }
                if (textStatus == "error") {
                    $('#mensajeRefresh').html('<h3><span class="verde"> Error al Cargar los Datos!!</span></h3>');
                    setTimeout('$("#ModalRefreshTable").modal("hide");', 1500);
                }
                _viewdetalle();
                Metronic.init();
            });

        });
    }

    function _getCheckReport()
    {

        $("#quitarDisable").click(function() {
            if ($("#quitarDisable").attr("checked")) {

                var startDate = $('#fecha_ini').val();
                var d = new Date(convierteAlias(startDate));

                var dFormat = (d.getDate() + 1) + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
                console.log(dFormat);
                if (startDate) {
                    $("#refreshFecha").empty();
                    $("#refreshFecha").html("<div class='input-group input-medium date' id='pruebaparasetear' data-date-format='dd-mm-yyyy' ><label class='letra_empleado'>Fecha Fin</label><input type='text' id='fecha_fin' name='fecha_fin' class='form-control date-picker form-control-inline input-medium' data-date-start-date=" + startDate + " /></div>");
                    ComponentsPickers.init();
//                    $("#fecha_fin").attr("disabled", false);
//                    var d = new Date(convierteAlias(startDate));
//                    var dFormat = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
//                    $('#fecha_fin').attr('data-date-start-date', dFormat);
//                    $('#fecha_fin').addClass('date-picker');
                }
//                 ComponentsPickers.init();
            }
            else {
                $("#fecha_fin").attr("disabled", true);
                $("#fecha_fin").val('');


            }

        });

        $("input#fecha_ini").change(function() {

            var startDate = $(this).val();
            if (startDate) {
                $("#fecha_fin").val('');
                $("#fecha_fin").attr("disabled", true);
                $("#quitarDisable").attr("checked", false);
                $("#quitarDisable").parent().removeClass("checked");

            }

        });

    }


    function _getPdf() {

        $('a#exportarPdf').on('click', function() {
            var nameTable = $('table.table-export').attr('id');
            console.log(nameTable);
            if (nameTable == 'adminHourEmployee') {
                var idsStatus = $('#typeEventList').val();
                var idDivision = $('#departemento').val();
                var idsEmployee = $('#employeeAll').val();
                var startDate = $('#fecha_ini').val();
                var endDate = $('#fecha_fin').val();
                var statusEmployee = $('#statusEmployee').val();

                if (idsStatus) {
                } else {
                    idsStatus = "Empty";
                }
                if (idDivision) {
                } else {
                    idDivision = "Empty";
                }
                if (idsEmployee) {
                    idDivision = "Empty";
                } else {
                    idsEmployee = "Empty";
                }
                if (startDate) {
                } else {
                    startDate = "Empty";
                }
                if (endDate) {
                } else {
                    endDate = "Empty";
                }
                if (statusEmployee) {
                } else {
                    statusEmployee = "Empty";
                }

                var color = false;
                if ($("#setColor").attr("checked")) {
                    color = true;
                } else {
                    color = false;
                }

                var passSD = encodeURIComponent(startDate);
                var passED = encodeURIComponent(endDate);

                var arrayData = new Array();
                arrayData[0] = idsStatus;
                arrayData[1] = idDivision;
                arrayData[2] = idsEmployee;
                arrayData[3] = passSD;
                arrayData[4] = passED;
                arrayData[5] = color;
                arrayData[6] = statusEmployee;
                console.log(arrayData[6]);

                var name = 'Reporte - DeclaraciÃ³n de Colaboradores';
            }

            if (nameTable == 'tableHousrEmployee') {

                var start_date = $('#fecha_ini').val();
                var end_date = $('#fecha_fin').val();
                var idEmployee = $('div#idEmployee').text();

                if (start_date == '') {
                    start_date = "Empty";
                }
                if (end_date == '') {
                    end_date = "Empty";
                }

                var arrayData = new Array();
                arrayData[0] = start_date;
                arrayData[1] = end_date;
                arrayData[2] = idEmployee;

                var name = $('h3#titleFile').text();
            }

            if (nameTable == 'tableDirectoryPhone')
            {
                var name = 'Directorio-TelefÃ³nico';
                var arrayData = $('#seleFiltroDirectory').val();
            }

            var value = JSON.stringify(arrayData);
            var url = '?data=' + value + '&name=' + name + '&nameTable=' + nameTable;
            window.open('/report/export/pdf' + url, '_blank');
        });
    }


    function _getSendEmail()
    {
        var nameTable = $('div.table-responsive > table.table-export').attr('id');
//          console.log (nameTable);
        $('a#sendCorreo').on('click', function() {

            $("#titleModalGeneral").html("Enviar Correo");
            $("#enviandoCorreo").html("<h3>Enviando Correo....</h3>");
            $("#ModalGeneral").modal("show");

            if (nameTable == 'adminHourEmployee') {
                var idsStatus = $('#typeEventList').val();
                var idDivision = $('#departemento').val();
                var idsEmployee = $('#employeeAll').val();
                var startDate = $('#fecha_ini').val();
                var endDate = $('#fecha_fin').val();

                if (idsStatus) {
                } else {
                    idsStatus = "Empty";
                }
                if (idDivision) {
                } else {
                    idDivision = "Empty";
                }
                if (idsEmployee) {
                } else {
                    idsEmployee = "Empty";
                }
                if (startDate) {
                } else {
                    startDate = "Empty";
                }
                if (endDate) {
                } else {
                    endDate = "Empty";
                }

                var color = false;
                if ($("#setColor").attr("checked")) {
                    color = true;
                } else {
                    color = false;
                }

                var passSD = encodeURIComponent(startDate);
                var passED = encodeURIComponent(endDate);

                var arrayData = new Array();
                arrayData[0] = idsStatus;
                arrayData[1] = idDivision;
                arrayData[2] = idsEmployee;
                arrayData[3] = passSD;
                arrayData[4] = passED;
                arrayData[5] = color;

                var value = JSON.stringify(arrayData);
                var name = 'Reporte - Declaraci\u00f3n de Colaboradores';
            }
            if (nameTable == 'tableHousrEmployee') {

                var start_date = $('#fecha_ini').val();
                var end_date = $('#fecha_fin').val();
                var idEmployee = $('div#idEmployee').text();

                if (start_date == '') {
                    start_date = "Empty";
                }
                if (end_date == '') {
                    end_date = "Empty";
                }

                var arrayData = new Array();
                arrayData[0] = start_date;
                arrayData[1] = end_date;
                arrayData[2] = idEmployee;
                var value = JSON.stringify(arrayData);
                var name = $('h3#titleFile').text();
            }
            if (nameTable == 'tableHousr') {
                var name = genNameFile(nameTable);
                var ids = new Array();
                $("#" + nameTable + " td#ids").each(function(index) { //Con esta funcion de jquery recorremis la columna (oculta) de los ids.
                    ids[index] = $(this).text(); //incluimos los ids de la columna en el array.
                });

                var start_date = $('#startDate').text();
                var end_date = $('#endDate').text();
                var dates = new Array(start_date, end_date);
                var data = new Array(ids, dates);
                var value = JSON.stringify(data);
            }
            var url = 'data=' + value + '&name=' + name + '&nameTable=' + nameTable;
            $ARU.AJAX.sentEmailDeclare("GET", "/report/export/sendEmail", "data=" + value + "&name=" + name + "&nameTable=" + nameTable);

        });
    }

    function convierteAlias(nuevoAlias)
    {
        var especiales = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        var normales = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        i = 0;
        while (i < especiales.length) {
            //nuevoAlias = nuevoAlias.replace(especiales[i], normales[i]);
            nuevoAlias = nuevoAlias.split(normales[i]).join(especiales[i]);
            i++
        }
        return nuevoAlias;
    }

    function successSendeEmail(result)
    {
        switch (result) {
            case true:
                setTimeout('$("#ModalGeneral").modal("hide");', 2000);

                break;

            case false:

                setTimeout('$("#ModalGeneral").modal("hide");', 2000);

                break;
            default:
                break;
        }
    }

    function _refrescartabNomina()
    {
        $('a.tabb').on('click', function(event)
        {
            var nomina = $(this).attr('id');
            var tag = $('#categoryValue').find('div.active').attr('id');
            $('div#' + tag).removeClass('active');
            $('div#tab_Asignaciones_' + nomina).addClass('active');

//         $('#categoryDescription').find('ul.nav').find('li.active').removeClass('active');
//         $('#categoryDescription > div > ul > li:nth-child(4) > a');

            $('#categoryDescription > div > ul > li').removeClass('active');
            $('#categoryDescription > div > ul > li:nth-child(4)').addClass('active');
        });

    }

    function _optionesCp()
    {

    }



    function _optionesDivision()
    {
        $('a#editarDivision').on('click', function(event)
        {
            var nameDivision = $(this).find('div#nameDivision').text();
            var idDivision = $(this).find('div#idDivisionCp').text();
//          var dependenci=$(this).find('div#dependecyDivision').text();
//          var acronimo=$(this).find('div#acronimo').text();
            $('#contenidoDivisionEditar').removeClass('ocultar');
            $('#contenidoDivisionEditSuccess').addClass('ocultar');

            $('#contenidoEditarNameDivision').html('<input type="text" name="editarDivisionName" id="editarDivisionName" class="form-control" value="' + nameDivision + '"/>');
            $('#titleModalEditar').html('<h4><i class="fa fa-pencil-square-o verde"></i>  Editar DivisiÃ³n</h4>');
            $('#confirmacionIdEditar').html('<div id="idDivisionEditar" class="ocultar">' + idDivision + '</div>');
            $('#confirmacionNameEditar').html('<div id="nameDivisionEditar" class="ocultar" >' + nameDivision + '</div>');
            $('#modalEditarDivision').modal('show');
        });
        $('a#confirDeleteDivision').on('click', function(event)
        {
            $('#contenidoDivisionDelete').removeClass('ocultar');
            $('#mensajeSuccessDeleteDivision').addClass('ocultar');

            var nameDivisionEliminar = $(this).find('div#nameDivisionEliminar').text();
            var idDivision = $(this).find('div#idDivisionCpEliminar').text();
            $('#titleModalEliminar').html('<h4><i class="fa fa-trash-o rojo"></i>  Eliminar DivisiÃ³n</h4>');
            $('#mensajeConfirmarDelete').html('<span style="font-size:16px">Â¿EstÃ¡ seguro que desea Eliminar la siguiente DivisiÃ³n?</span>');
            $('#DivisionDeleteView').html('<br><span style="font-size:14px;" class="ladrillo">' + nameDivisionEliminar + '</span>');
            $('#confirmacionIdEliminar').html('<div id="idDivisionDelete" class="ocultar">' + idDivision + '</div>');
            $('#modalEliminarDivision').modal('show');
        });

    }


    function _EditarDivision()
    {
        $('a#botonEditarName').on('click', function(event)
        {
            var id = $('#idDivisionEditar').text();
            var newName = $('#editarDivisionName').val();
            $ARU.AJAX.sentUpdateIdDivision("GET", "/Division/UpdateDivision", "id=" + id + "&newName=" + newName);
        });
    }

    function successEditDivision(result)
    {
        switch (result) {
            case true:
                $('#contenidoDivisionEditar').addClass('ocultar');
                $('#contenidoDivisionEditSuccess').removeClass('ocultar');
                $('#contenidoDivisionEditSuccess').html('<span class="verde"><h4>Proceso Completado</h4></span>');
                setTimeout('$("#modalEditarDivision").modal("hide");', 2000);
                $('#adminRefreshDivision').load('/Division/ViewDivision #adminDivision', function() {
                    _initDataTableAll();
                });

                break;

            case false:
                $('#contenidoDivisionEditar').addClass('ocultar');
                $('#contenidoDivisionEditSuccess').removeClass('ocultar');
                $('#contenidoDivisionEditSuccess').html('<span class="rojo"><h4>Error en el proceso</h4></span>');
                setTimeout('$("#modalEditarDivision").modal("hide");', 2000);
                $('#adminRefreshDivision').load('/Division/ViewDivision #adminDivision', function() {
                    _initDataTableAll();
                });
                break;
            default:
                break;
        }
    }

    function _deleteDivision()
    {
        $('a#botonConfirmarDivisionDelete').on('click', function(event)
        {
            var id = $('#idDivisionDelete').text();
            $ARU.AJAX.sentDeleteIdDivision("GET", "/Division/DeleteDivision", "id=" + id);
        });
    }

    function successDeleteDivision(result)
    {

        switch (result) {
            case true:
                $('#contenidoDivisionDelete').addClass('ocultar');
                $('#mensajeSuccessDeleteDivision').removeClass('ocultar');
                $('#mensajeSuccessDivision').html('<span class="verde"><h3>Proceso Completado</h3></span>');
                setTimeout('$("#modalEliminarDivision").modal("hide");', 2000);
                $('#adminRefreshDivision').load('/Division/ViewDivision #adminDivision', function() {
                    _initDataTableAll();
                });
                break;

            case false:
                $('#contenidoDivisionDelete').addClass('ocultar');
                $('#mensajeSuccessDeleteDivision').removeClass('ocultar');
                $('#mensajeSuccessDivision').html('<span class="rojo"><h3>Error en el Proceso de EliminaciÃ³n</h3></span>');
                setTimeout('$("#modalEliminarDivision").modal("hide");', 2000);
                $('#adminRefreshDivision').load('/Division/ViewDivision #adminDivision', function() {
                    _initDataTableAll();
                });
                break;
            case 'DivisionExistPC':
                $('#contenidoDivisionDelete').addClass('ocultar');
                $('#mensajeSuccessDeleteDivision').removeClass('ocultar');
                $('#mensajeSuccessDivision').html('<span class="rojo"></h3>Esta DivisiÃ³n estÃ¡ Siendo Ocupada por un Colaborador</h3></span>');
                setTimeout('$("#modalEliminarDivision").modal("hide");', 2000);

                break;
            case 'DivisionExistObstacle':
                $('#contenidoDivisionDelete').addClass('ocultar');
                $('#mensajeSuccessDeleteDivision').removeClass('ocultar');
                $('#mensajeSuccessDivision').html('<span class="rojo"></h3>Esta DivisiÃ³n Posee un OstÃ¡culo</h3></span>');
                setTimeout('$("#modalEliminarDivision").modal("hide");', 2000);

                break;
            default:

//                var contenidos = new Array();
//                $.each(result, function(key, value) {
//                    contenidos[key] = '<div>' + value + '</div>';
//                });
//               
//               console.log (contenidos);


                break;
        }
    }




    function _optionesPosition() {
        $('a#editarPosition').on('click', function(event)
        {
            var namePosition = $(this).find('div#namePosition').text();
            var idPosition = $(this).find('div#idLider').text();

            $('div#contenidoPositionEditar').removeClass('ocultar');
            $('div#contenidoPositionEditSuccess').addClass('ocultar');
            $('#contenidoEditarNamePosition').html('<input type="text" name="editarPositionName" id="editarPositionName" class="form-control" value="' + namePosition + '"/>');
            $('#titleModalEditarPosition').html('<h4><i class="fa fa-pencil-square-o verde"></i>  Editar Cargo</h4>');
            $('#confirmacionIdEditarPosition').html('<div id="idPositionEditar" class="ocultar" >' + idPosition + '</div>');
            $('#confirmacionNameEditarPosition').html('<div id="namePositionEditar" class="ocultar"  >' + namePosition + '</div>');
            $('#modalEditarPosition').modal('show');
        });
        $('a#eliminarPosition').on('click', function(event)
        {
            var namePosition = $(this).find('div#namePositionEliminar').text();
            var idPosition = $(this).find('div#idPositionCpEliminar').text();
            $('#contenidoEliminarPosicion').removeClass('ocultar');
            $('#mensajeSuccessEliminarPosition').addClass('ocultar');
            $('#titleModalEliminarPosition').html('<h4><i class="fa fa-trash-o rojo"></i>  Eliminar Cargo</h4>');
            $('#tituloPosicionDelete').html('<h4>Â¿Esta seguro que desea eliminar el siguiente cargo?</h4>');
            $('#namePosicionDelete').html('<span class="ladrillo" style="font-size:16px;">' + namePosition + '</span>');
            $('#idPosicionDelete').html(idPosition);

            $('#modalEliminarPosition').modal('show');
        });

    }


    function _EditarPosition()
    {
        $('a#botonEditarNamePosition').on('click', function(event)
        {

            var id = $('#idPositionCp').text();
            var newName = $('#editarPositionName').val();
            $ARU.AJAX.sentUpdateIdPosition("GET", "/Position/UpdatePosition", "id=" + id + "&newName=" + newName);
        });
    }


    function successEditPosition(result)
    {
        console.log(result);
        switch (result) {
            case true:
                $('#contenidoPositionEditar').addClass('ocultar');
                $('#contenidoPositionEditSuccess').removeClass('ocultar');
                $('#contenidoPositionEditSuccess').html('<span class="verde" style="font-size:16px">ActualizaciÃ³n Exitosa</span>');
                setTimeout('$("#modalEditarPosition").modal("hide");', 3000);
                $('#adminRefreshPosition').load('/Position/ViewPosition #adminPosition', function() {
                    _initDataTableAll();
                });
                break;

            case false:
                $('#contenidoPositionEditar').addClass('ocultar');
                $('#contenidoPositionEditSuccess').removeClass('ocultar');
                $('#contenidoPositionEditSuccess').html('<span class="rojo" style="font-size:16px">Error en el proceso</span>');
                setTimeout('$("#modalEditarDivision").modal("hide");', 3000);
                $('#adminRefreshPosition').load('/Position/ViewPosition #adminPosition', function() {
                    _initDataTableAll();
                });
                break;
            default:
                break;
        }
    }


    function _EditarCodigoPosicion()
    {
        $('a#editarPcAdministrador').on('click', function(event)
        {


            $('#contenidoEditarCodigoDPosicion').removeClass('ocultar');
            $('#successEditarCp').addClass('ocultar');
            var nameEmployee = $(this).find('div#name_employ').text();
            var lastEmployee = $(this).find('div#last_employ').text();
            var namePosition = $(this).find('div#name_position').text();
            var nameDivision = $(this).find('div#name_division').text();
            var startDate = $(this).find('div#start_date').text();
            var cp = $(this).find('div#position_code').text();
            $('#id_employee_list').val('');
            $('div#contenidoEditarCodigoDPosicion input#start_date').val('');
            $('#titleModaleditarPosition').html('<h4>Editar CodigÃ³ de PosiciÃ³n</h4>');

            $('#nombreEmpleadoCp').html('<span class="letra_empleado" style="font-size:15px">Colaborador (Cargo): </span><span>' + nameEmployee + ' ' + lastEmployee + '</span> <span>  (' + namePosition + ')</span>');

            $('#divisionEmpleadoCp').html('<span class="letra_empleado" style="font-size:15px">DivisiÃ³n: </span><span>' + nameDivision + '</span>');
            $('#cargoEmpleadoCp').html('<span class="letra_empleado" style="font-size:15px">Fecha de Ingreso: </span><span>' + startDate + '</span>');
            $('#codigoPosicionEmployee').html('<span class="letra_empleado" style="font-size:15px">CÃ³digo de PosiciÃ³n: </span><span>' + cp + '</span>');
            $('#codigoPosicionEmployeeOculto').html(cp);
            $('#modalEditarCodigoPosicion').modal('show');


        });
        $('a#desactivarPcAdministrador').on('click', function(event)
        {
//                $('#titleModaldesactivarPosition').html('<h4>Desactivar CÃ³digo de PosiciÃ³n</h4>');
//                $('#modaldesactivarCodigoPosicion').modal('show');
        });
        $('a#eliminarPcAdministrador').on('click', function(event)
        {

            $('#contenidoPC').removeClass('ocultar');
            $('#mensajeSuccess').addClass('ocultar');
            var idEmployee = ($(this).find('div#id_employ').text());

            var first_name = ($(this).find('div#name_employ').text());
            var last_name = ($(this).find('div#last_employ').text());
            var idPosition = ($(this).find('div#id_position').text());
            var leaderPosition = ($(this).find('div#leader_position').text());
            var idDivision = ($(this).find('div#id_division').text());
            var positionCode = ($(this).find('div#position_code').text());

            $('#first_name').html('<h4>' + first_name + ' ' + last_name + '</h4>');
            $('#id_employee_eliminar').html(idEmployee);
            $('#id_posi_eliminar').html(idPosition);
            $('#id_divi_eliminar').html(idDivision);
            $('#position_c_eliminar').html(positionCode);
            if (leaderPosition == 'leader') {
                $('#modalEliminarCodigoPosicion #title2 h5.modal-title').html('TambiÃ©n SerÃ¡n eliminados los colaboradores del Mismo');
            } else {
                $('#modalEliminarCodigoPosicion #title2 h5.modal-title').html('');
            }
            $('#titleModalEliminarPosition').html('<h4>Eliminar CÃ³digo de PosiciÃ³n</h4>');
            $('#modalEliminarCodigoPosicion').modal('show');
        });

        $('a#activarPcAdministrador').on('click', function(event)
        {

            $('#contenidoPCA').removeClass('ocultar');
            $('#successPCA').addClass('ocultar');
            var id = ($(this).find('div#id_positionCode').text());
            var first_name = ($(this).find('div#name_employ').text());
            var last_name = ($(this).find('div#last_employ').text());
            $('#idPC').html(id);
            $('#first_name_active').html('<h4>' + first_name + ' ' + last_name + '</h4>');

            $('#titleModalActivarPosition').html('<h4>Activar CÃ³digo de PosiciÃ³n</h4>');
            $('#modalActivarCodigoPosicion').modal('show');
        });

    }

//    function _paginationTable(){
//        $('ul.pagination a').on('click', function(event)
//       {
//           $('#adminPositionCodeActive').dataTable().fnDraw();
//           
//       });
//    }


    function _confirEditCp()
    {

        $('a#botonEditarCpEmployee').on('click', function(event)
        {
            var id_employee = $('#id_employee_list').val();
            var start_date = $('input#start_date').val();
            var positionCode = $('div#codigoPosicionEmployeeOculto').text();
            if (id_employee == '')
            {
                id_employee = "EmployeeEmpty";
            }
            if (start_date == '')
            {
                start_date = "StarDateEmpty";
            }
            console.log(start_date);
            $ARU.AJAX.actualizarCodigoPosicion("GET", "/PositionCode/UpdatePositionCode", "position_code=" + positionCode + '&id_employee=' + id_employee + '&start_date=' + start_date);

        });
    }


    function successDeleteCodigoPosicion(result)
    {
        switch (result) {
            case true:
                $('#contenidoPC').addClass('ocultar');
                $('#mensajeSuccess').removeClass('ocultar');
                $('#mensajeSuccess').html('<span class="verde" style="font-size:16px">Proceso Exitoso</span>');
                setTimeout('$("#modalEliminarCodigoPosicion").modal("hide");', 2000);
                $('#adminPC').load('/PositionCode/AdminPositionCode #refrescarcpcp', function() {
                    _initDataTableAll();
                });
                break;

            case false:
                $('#contenidoPC').addClass('ocultar');
                $('#mensajeSuccess').removeClass('ocultar');
                $('#mensajeSuccess').html('<span class="rojo" style="font-size:16px">Error en el proceso</span>');
                setTimeout('$("#modalEliminarCodigoPosicion").modal("hide");', 2000);
                break;
            default:
                break;
        }
    }

    function successUpdatePositionCode(result)
    {
        switch (result) {
            case true:
                $('#contenidoEditarCodigoDPosicion').addClass('ocultar');
                $('#successEditarCp').removeClass('ocultar');
                $('#mensajeSuccessEditarCodigoP').html('<span class="verde" style="font-size:16px">Proceso Completado</span>');
                setTimeout('$("#modalEditarCodigoPosicion").modal("hide");', 3000);

                $('#adminPC').load('/PositionCode/AdminPositionCode #refrescarcpcp', function() {
                    _initDataTableAll();
                });

                break;

            case false:
                $('#contenidoEditar').addClass('ocultar');
                $('#successEditarCp').removeClass('ocultar');
                $('#mensajeSuccessEditarCodigoP').html('<span class="rojo" style="font-size:16px">Error en el proceso</span>');
//                 setTimeout('$("#modalEditarCodigoPosicion").modal("hide");', 3000);
                break;
            default:
                console.log("string");
                break;
        }
    }

    function _desactivarPositionCodeEmployee()
    {
        $('a#desactivarPcAdministrador').on('click', function(event)
        {
            $('#contenidoDesactivarCp').removeClass('ocultar');
            $('#mensajeSuccessDesactivar').addClass('ocultar');
            var idEmployee = ($(this).find('div#id_employ').text());
            var first_name = ($(this).find('div#name_employ').text());
            var last_name = ($(this).find('div#last_employ').text());
            var idPosition = ($(this).find('div#id_position').text());
            var idDivision = ($(this).find('div#id_division').text());
            var positionCode = ($(this).find('div#position_code').text());

            var namePosition = $(this).find('div#name_position').text();
            var nameDivision = $(this).find('div#name_division').text();

            $('#first_name_desca').html('<span class="letra_empleado" style="font-size:14px">Colaborador: </span>' + first_name + ' ' + last_name + '  (' + namePosition + ')');
            $('#divisionDesactivarCp').html('<span class="letra_empleado" style="font-size:14px">DivisiÃ³n: </span>' + nameDivision);
            $('#codigoPosicionDesactivarCp').html('<span class="letra_empleado" style="font-size:14px">CÃ³digo de PosiciÃ³n: </span>' + positionCode);
            $('#id_employee_desca').html(idEmployee);
            $('#id_posi_desca').html(idPosition);
            $('#titleModaldesactivarPosition').html('<h4>Desactivar CÃ³digo de PosiciÃ³n</h4>');
            $('#modaldesactivarCodigoPosicion').modal('show');
//            
        });
    }

    function _confirmarDesactivarEmpleadoCp()
    {
        $('a#botondescativarCodigoPosicion').on('click', function(event)
        {

            var idEmployee = $('div#id_employee_desca').text();
            var idPosition = $('div#id_posi_desca').text();
            $ARU.AJAX.endDate("GET", "/PositionCode/SetEndDate", "id_employee=" + idEmployee + "&id_position=" + idPosition);
        });
    }


    function messageCp(result) {

        switch (result) {
            case true:

                $('#contenidoDesactivarCp').addClass('ocultar');
                $('#mensajeSuccessDesactivar').removeClass('ocultar');
                $('#mensajeDesactivarCodigoPosicion').html('<span class="verde" style="font-size:16px">Proceso Exitoso</span>');
                setTimeout('$("#modaldesactivarCodigoPosicion").modal("hide");', 2000);
                $('#adminPC').load('/PositionCode/AdminPositionCode #refrescarcpcp', function() {
                    _initDataTableAll();
                });


                break;
            case false:
                $('#contenidoDesactivarCp').addClass('ocultar');
                $('#mensajeSuccessDesactivar').removeClass('ocultar');
                $('#mensajeDesactivarCodigoPosicion').html('<span class="rojo" style="font-size:16px">Error en el Proceso</span>');
                setTimeout('$("#modaldesactivarCodigoPosicion").modal("hide");', 2000);

                break;
        }
    }


    function _deleteCargoPosicionCode()
    {
        $('a#botonConfirDeletePosition').on('click', function(event)
        {
            var id = $('div#idPosicionDelete').text();
            $ARU.AJAX.borrarPosicionCp("GET", "/Position/DeletePosition", "id=" + id);
        });
    }


    function successDeletePositionCp(result)
    {
        switch (result) {
            case true:
                $('#mensajeEliminarSuccess').html('<span class="verde"><h4>Proceso Exitoso</h4></span>');
                $('#contenidoEliminarPosicion').addClass('ocultar');
                $('#mensajeSuccessEliminarPosition').removeClass('ocultar');
                setTimeout('$("#modalEliminarPosition").modal("hide");', 3000);
                $('#adminRefreshPosition').load('/Position/ViewPosition #adminPosition', function() {
                    _initDataTableAll();
                });
                break;
            case false:
                $('#mensajeEliminarSuccess').html('<span class="rojo"><h4>Error En el proceso</h4></span>');
                $('#contenidoEliminarPosicion').addClass('ocultar');
                $('#mensajeSuccessEliminarPosition').removeClass('ocultar');
                setTimeout('$("#modalEliminarPosition").modal("hide");', 3000);
                $('#adminRefreshPosition').load('/Position/ViewPosition #adminPosition', function() {
                    _initDataTableAll();
                });
                break;
            default:
                $('#mensajeEliminarSuccess').html('<span class="rojo"><h4>Este cargo esta siendo utilizado en el CÃ³digo de PosiciÃ³n</h4></span>');
                $('#contenidoEliminarPosicion').addClass('ocultar');
                $('#mensajeSuccessEliminarPosition').removeClass('ocultar');
                setTimeout('$("#modalEliminarPosition").modal("hide");', 3000);
                break;
        }
    }
    function successActivePositionCp(result)
    {
        switch (result) {
            case true:
                $('#mensajeSuccessActiveCodePosition').html('<span class="verde"><h4>Proceso Exitoso</h4></span>');
                $('#contenidoPCA').addClass('ocultar');
                $('#successPCA').removeClass('ocultar');
                setTimeout('$("#modalActivarCodigoPosicion").modal("hide");', 3000);
                $('#employeeActive').load('/PositionCode/AdminPositionCode #adminPositionCodeActive', function() {
                    _initDataTableAll();
                });
                $('#employeeInactive').load('/PositionCode/AdminPositionCode #adminPositionCodeInactives', function() {
                    _initDataTableAll();
                });
                break;
            case false:
                $('#mensajeSuccessActiveCodePosition').html('<span class="rojo"><h4>Error En el proceso</h4></span>');
                $('#contenidoPCA').addClass('ocultar');
                $('#successPCA').removeClass('ocultar');
                setTimeout('$("#modalActivarCodigoPosicion").modal("hide");', 3000);

                $('#employeeActive').load('/PositionCode/AdminPositionCode #adminPositionCodeActive', function() {
                    _initDataTableAll();
                });
                $('#employeeInactive').load('/PositionCode/AdminPositionCode #adminPositionCodeInactives', function() {
                    _initDataTableAll();
                });
                break;
            default:
                $('#mensajeSuccessActiveCodePosition').html('<span class="rojo"><h4>Este cargo esta siendo utilizado en el CÃ³digo de PosiciÃ³n</h4></span>');
                $('#contenidoPCA').addClass('ocultar');
                $('#successPCA').removeClass('ocultar');
                setTimeout('$("#modalActivarCodigoPosicion").modal("hide");', 3000);
                break;
        }
    }


    function successAsignacionHorarioEmployee(result)
    {
        switch (result) {
            case true:
                $('div#tableHE').load('/EmployeeHour/EmployeeHours #getBody', function() {
                    Metronic.init();
                    _getTimeStartJornada();
                    _getEmployeeHours();
//                        _changeHourEmployee();
                    _initDataTable('#hoursEmployee', 1, -1);
                    Metronic.init();
                });
                $('.checked').removeClass('checked');
                $('input:checkbox').removeAttr('checked');
                $('.gradeX').removeClass('active');
                $("#asignacionHours").select2('val', '');
                $('#contenidoHorarios').addClass('ocultar');
                $('#successHorariosMensajeHorario').removeClass('ocultar');
                $('#mensajeAsignacionHorario').html('<span class="verde"><h4>AsignaciÃ³n Exitosa de Horario</h4></span>');
                setTimeout('$("#confirmarAsigacionHorario").modal("hide");', 3000);

                break;
            case false:
                $('.checked').removeClass('checked');
                $('input:checkbox').removeAttr('checked');
                $('.gradeX').removeClass('active');
                $("#asignacionHours").select2('val', '');
                $('#contenidoHorarios').addClass('ocultar');
                $('#successHorariosMensaje').removeClass('ocultar');
                $('#mensajeAsignacionHorario').html('<span class="rojo"><h4>Falla en la AsignaciÃ³n</h4></span>');
                setTimeout('$("#confirmarAsigacionHorario").modal("hide");', 3000);
                break;
            default:
                console.log(result);
                $('.checked').removeClass('checked');
                $('input:checkbox').removeAttr('checked');
                $('.gradeX').removeClass('active');
                var contendidos = new Array();
                var textAsignacionHoras = $('#asignacionHours option:selected').text();
                $('#contenidoHorarios').addClass('ocultar');
                $('#successHorariosMensaje').removeClass('ocultar');
                $('#scrollArray').attr("style", "height:130px; overflow: scroll;  overflow-x: hidden;");
                $('#mensajeconfirAsig').html("<span class='ladrillo'><h4>Los Siguientes Colaboradores Ya Pertenecen a este Horario <span class='verde'>" + textAsignacionHoras + "</span></h4><span>");
                $.each(result, function(key, value) {
                    contendidos[key] = '<div style="font-size:15px">' + value + '</div>';
                });
                $('#mensajeAsignacionHorario').html(contendidos);
                setTimeout('$("#confirmarAsigacionHorario").modal("hide");', 3000);
                setTimeout('$("#asignacionHours").select2("val", "");', 3000);
                break;
        }
    }


    function successNewHorario(result)
    {
        switch (result) {
            case true:
                $('#contenidoNewHorario').addClass('ocultar');
                $('#successNuevoHorario').removeClass('ocultar');
                $('#mensajeNuevoHorario').html("<span class='verde'><h4>Proceso Exitoso</h4></span>");
                $('div#listaHorario').load('/Hour/RefreshGetEmployeeHours', function() {
                });


                setTimeout('$("#confirmarNewHorarioAsignacion").modal("hide");', 3000);

                break;
            case false:
                $('#contenidoNewHorario').addClass('ocultar');
                $('#successNuevoHorario').removeClass('ocultar');
                $('#mensajeNuevoHorario').html("<span class='rojo'><h4>Fallas en el Proceso</h4></span>");
                setTimeout('$("#confirmarNewHorarioAsignacion").modal("hide");', 3000);
                break;
            default:
                $('#contenidoNewHorario').addClass('ocultar');
                $('#successNuevoHorario').removeClass('ocultar');
                $('#mensajeNuevoHorario').html("<div><span><h4><i class='fa fa-exclamation-triangle amarrillo'></i>  Horario ya registrado</h4></span></div><div>" + result + "</div>");
                setTimeout('$("#confirmarNewHorarioAsignacion").modal("hide");', 3000);
                break;
        }

    }

    function successDeleteHours(result)
    {

        switch (result) {
            case true:

                $('div#tableHE').load('/EmployeeHour/EmployeeHours #getBody', function() {
                    Metronic.init();
                    _getTimeStartJornada();
                    _getEmployeeHours();
//                        _changeHourEmployee();
                    _initDataTable('#hoursEmployee', 1, -1);
                    Metronic.init();
                    ComponentsPickers.init();
                });

                break;
            case false:

                break;
            default:

                break;
        }

    }


    function successSaveEmployeeByHour(result)
    {
        console.log(result);
        switch (result) {
            case true:

                $('#calendarAsignacion').fullCalendar('refetchEvents');
                $('#fecha_ini_hour').val('');
                $('#fecha_desd_hour').val('');
                $('#hourEmployee').val('');
                $('#mensajeRespuesta').addClass("alert alert-success");
                $('#mensajeRespuesta').addClass("verde");
                $('#mensajeRespuesta').html("AsignaciÃ³n de Horario Exitosa");
                $('#mensajeRespuesta').show();
                $('#mensajeRespuesta').fadeToggle(6000);

                break;
            case false:

                $('#mensajeRespuesta').addClass("alert alert-danger");
                $('#mensajeRespuesta').addClass("verde");
                $('#mensajeRespuesta').html("Error al asignar horario");
                $('#mensajeRespuesta').show();
                $('#mensajeRespuesta').fadeToggle(6000);
                break;
            case "HourExist":

                $('#mensajeRespuesta').addClass("alert alert-warning");
                $('#mensajeRespuesta').addClass("amarrillo");
                $('#mensajeRespuesta').html("El horario ya se encuentra asignado a este dÃ­a");
                $('#mensajeRespuesta').show();
                $('#mensajeRespuesta').fadeToggle(6000);
                break;
            case "NotData":

                $('#mensajeRespuesta').addClass("alert alert-danger");
                $('#mensajeRespuesta').addClass("rojo");
                $('#mensajeRespuesta').html("Todos los Campos son requeridos");
                $('#mensajeRespuesta').show();
                $('#mensajeRespuesta').fadeToggle(6000);
                break;
            default:

                break;
        }
    }
    function successSaveEmployeeBreakHour(result)
    {
        console.log(result);
        switch (result) {
            case true:

                var idEmployee = $('#idEmployeeAsig').text();
                $('#mensajeConfirmacionHorarioDia').addClass("alert alert-success");
                $('#mensajeConfirmacionHorarioDia').addClass("verde");
                $('#mensajeConfirmacionHorarioDia').html("AsignaciÃ³n de Horario Exitosa");
                $('#mensajeConfirmacionHorarioDia').show();
                $('#mensajeConfirmacionHorarioDia').fadeToggle(6000);

                $('div#selectHorariosCheckByDay').load('/Hour/getEmployeeHoursCheckByDay?idEmployee=' + idEmployee + ' #tableAsigHorarios', function() {
                    Metronic.init();
                    _changeStaticHour();
                    Metronic.init();
                    ComponentsPickers.init();
                    _asigHourDescanso();
                });
                break;
            case false:
                $('#mensajeConfirmacionHorarioDia').addClass("alert alert-danger");
                $('#mensajeConfirmacionHorarioDia').addClass("verde");
                $('#mensajeConfirmacionHorarioDia').html("Error al asignar horario");
                $('#mensajeConfirmacionHorarioDia').show();
                $('#mensajeConfirmacionHorarioDia').fadeToggle(6000);

                break;
            case "HoursNotExist":
                $('#mensajeConfirmacionHorarioDia').addClass("alert alert-warning");
                $('#mensajeConfirmacionHorarioDia').addClass("amarrillo");
                $('#mensajeConfirmacionHorarioDia').html("No existe el horario seleccionado");
                $('#mensajeConfirmacionHorarioDia').show();
                $('#mensajeConfirmacionHorarioDia').fadeToggle(6000);
                break;
            case "EmptyData":
                $('#mensajeConfirmacionHorarioDia').addClass("alert alert-danger");
                $('#mensajeConfirmacionHorarioDia').addClass("rojo");
                $('#mensajeConfirmacionHorarioDia').html("Todos los Campos son requeridos");
                $('#mensajeConfirmacionHorarioDia').show();
                $('#mensajeConfirmacionHorarioDia').fadeToggle(6000);
                break;
            default:

                break;
        }
    }

    function successAsignacionBreak(result)
    {
        switch (result) {
            case true:
                $('#contenidoHorariosDescanso').addClass('ocultar');
                $('#successHorariosMensajeDescanso').removeClass('ocultar');
                $('#mensajeconfirAsigDescanso').html('<span class="verde"><h4>AsignaciÃ³n Exitosa</h4></span>');
                setTimeout('$("#confirmarAsigacionDescando").modal("hide");', 3000);
                break;
            case false:
                $('#contenidoHorariosDescanso').addClass('ocultar');
                $('#successHorariosMensajeDescanso').removeClass('ocultar');
                $('#mensajeconfirAsigDescanso').html('<span class="rojo"><h4>Falla en la AsignaciÃ³n de horario de descanso</h4></span>');
                setTimeout('$("#confirmarAsigacionDescando").modal("hide");', 3000);
                break;
            case "HoursNotExist":

                break;
            case "EmptyData":

                break;
            default:

                break;
        }
    }
    function successAsigTelf(result)
    {
        switch (result) {
            case true:
                $('#mensajeAsignacionTelfCorp').addClass("alert alert-success");
                $('#mensajeAsignacionTelfCorp').addClass("verde");
                $('#mensajeAsignacionTelfCorp').html("AsignaciÃ³n de nÃºmero telefÃ³nico exitosa");
                $('#mensajeAsignacionTelfCorp').show();
                $('#mensajeAsignacionTelfCorp').fadeToggle(6000);
                $('#employeeActive').load('/Employee/RefreshGetAllEmployeeByDirectory #tableDirectoryPhone', function() {
                    Metronic.init();
                    _initDataTable('#tableDirectoryPhone', 0, 5);
                    _deleteAsigTelf();
                });
                break;
            case false:
                $('#mensajeAsignacionTelfCorp').addClass("alert alert-danger");
                $('#mensajeAsignacionTelfCorp').addClass("rojo");
                $('#mensajeAsignacionTelfCorp').html("Error al asignar n&uacute;mero telef&oacute;nico");
                $('#mensajeAsignacionTelfCorp').show();
                $('#mensajeAsignacionTelfCorp').fadeToggle(6000);
                break;
            case "HoursNotExist":

                break;
            case "EmptyData":

                break;
            default:

                break;
        }
    }
    function successDeleteTelfAsig(result)
    {

        switch (result) {
            case "true":
                $('#employeeActive').load('/Employee/RefreshGetAllEmployeeByDirectory #tableDirectoryPhone', function() {
                    _initDataTable('#tableDirectoryPhone', 0, 5);
                    _deleteAsigTelf();
                });
                break;
            case "false":
                $('#employeeActive').load('/Employee/RefreshGetAllEmployeeByDirectory #tableDirectoryPhone', function() {
                    _initDataTable('#tableDirectoryPhone', 0, 5);
                    _deleteAsigTelf();
                });
                break;

            default:

                break;
        }
    }
    function successSaveProof(result)
    {

        var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        var diasSemana = new Array("Domingo", "Lunes", "Martes", "MiÃ©rcoles", "Jueves", "Viernes", "SÃ¡bado");
        var f = new Date();

        $('#mensajeConfirConstancia').removeClass("alert alert-success alert-danger verde rojo");
        switch (result.response) {
            case "true":
                console.log(result.response);
                $('#mensajeConfirConstancia').removeClass("ocultar");
                $('#mensajeConfirConstancia').addClass("alert alert-success");
                $('#mensajeConfirConstancia').addClass("verde");
                $('#mensajeConfirConstancia').html("<h4>Solicitud de constancia de trabajo fue enviada</h34>");
                $('#mensajeConfirConstancia').show();


                $('#admin_refresh_constancia_1').load('/Employee/ProofEmployee #admin_constancia_1', function() {
                    Metronic.init();
                    _initDataTable('#admin_constancia_1', 1, 5);
                 });
                $('#admin_refresh_constancia_2').load('/Employee/ProofEmployee #admin_constancia_2', function() {
                    Metronic.init();
                    _initDataTable('#admin_constancia_2', 1, 5);
                 });
                 $('#admin_refresh_constancia_3').load('/Employee/ProofEmployee #admin_constancia_3', function() {
                    Metronic.init();
                    _initDataTable('#admin_constancia_3', 1, 5);
                 });

//                $('#mensajeConfirProof').fadeToggle(6000);
                setTimeout('$("#ModalProofEmployee").modal("hide");', 1500);
                break;
            case "false":
                if (result.val == 0)
                {
                    $('#mensajeConfirConstancia').removeClass("ocultar");
                    $('#mensajeConfirConstancia').addClass("alert alert-danger");
                    $('#mensajeConfirConstancia').addClass("rojo");
                    $('#mensajeConfirConstancia').html("<h4>ERROR</h34>");
                    $('#mensajeConfirConstancia').show();
//                    $('#mensajeConfirProof').fadeToggle(6000);
                    setTimeout('$("#ModalProofEmployee").modal("hide");', 1500);
                }
                else
                {
                    $('#mensajeConfirConstancia').removeClass("ocultar");
                    $('#mensajeConfirConstancia').addClass("alert alert-danger");
                    $('#mensajeConfirConstancia').addClass("rojo");
                    $('#mensajeConfirConstancia').html("<h4>No puede solicitar mas de 3 (tres) constancias este mes (" + meses[f.getMonth()] + ")</h4>");

                }

                break;

            default:

                break;
        }
    }
    
    function getSignatureImage($result){
        var signature_etelix_f = "data:image/png;base64,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";
        var signature_etelix_m = "data:image/png;base64,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";
        var signature_sacet_m = "data:image/png;base64,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";
        var signature_sacet_f = "data:image/png;base64,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";
        
        if(result.signature_email == 'SACET'){
           // return signature_sacet;
            return ((result.id_gender == 1)?signature_sacet_m:signature_sacet_f);
        }else {
            return ((result.id_gender == 1)?signature_etelix_m:signature_etelix_f);
        }
    }
    

    function _signature()
    {
        var canvas = document.getElementById("signature_canvas");
        var signature_email = null;
        if (canvas != null)
        {
            console.log("entro ");
            var context = canvas.getContext("2d", "2d");
            var imageObj = new Image();
            var data = $.ajax({type: "GET", 
                               async: false, 
                               url: "/employee/signatureInfo", 
                            success: function(data) {}
                       }).responseText;
            result = JSON.parse(data);
            imageObj.onload = function() {
                        console.log(result.position.length);

                        context.drawImage(imageObj, 0, 0);

                        context.font = "bold 10.77pt Arial";
                        context.fillStyle = "#324692";
                        context.fillText(result.name, 55, 25);

                        context.font = "bold oblique 8pt Arial";
                        context.fillStyle = "#324692";
                        context.fillText(result.position, 53, 41);
                        if (result.last_position != "undefined" && result.last_position != null)
                            context.fillText(result.last_position, 53, 56);

                        if (result.corporation_phone != "indefinite" && result.corporation_phone != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Cel: ", 15, 85);
                            context.font = "8pt Arial";
                            context.fillText(result.corporation_phone, 44, 85);
                        }
                        if (result.extension_numeric != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Telf: ", 15, 103);
                            context.fillText(" - Ext: ", 135, 103);
                            context.font = "8pt Arial";
                            context.fillText(result.office_phone, 43, 103);
                            context.fillText(result.extension_numeric, 170, 103);
                        }
                        if (result.email != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Email: ", 15, 120);
                            context.font = "8pt Arial";
                            context.fillText(result.email, 53, 120);
                        }
            }
            imageObj.src = getSignatureImage(result);

//            //        para codificar imagenes se recomienda usar http://www.base64-image.de/, hasta descubrir porque no funciona en el controlador
            function downloadCanvas(link, signature_canvas, filename) {
                link.href = document.getElementById(signature_canvas).toDataURL();
                link.download = filename;
            }
            document.getElementById('download').addEventListener('click', function() {
                downloadCanvas(this, 'signature_canvas', 'signature_' + result.username + '.png');
            }, false);
        }
    }

    function _sticker()
    {
        
        var signature_etelix = "data:image/png;base64,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";
        var signature_sacet = "data:image/png;base64,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";
        var canvas = document.getElementById("signature_canvas");
        var signature_email = null;
        if (canvas != null)
        {
            var context = canvas.getContext("2d", "2d");
            var imageObj = new Image();
            imageObj.onload = function() {
                var tttt = $.ajax({type: "GET", async: false, url: "/employee/signatureInfo", success: function(data) {

                        result = JSON.parse(data);
                        console.log(result.position.length);

                        context.drawImage(imageObj, 0, 0);
 console.log (result.name);
                        context.font = "bold 10.77pt Arial";
                        context.fillStyle = "#324692";
                        context.fillText(result.name, 55, 25);

                        context.font = "bold oblique 8pt Arial";
                        context.fillStyle = "#324692";
                        context.fillText(result.position, 53, 41);
                        if (result.last_position != "undefined" && result.last_position != null)
                            context.fillText(result.last_position, 53, 56);

                        if (result.corporation_phone != "indefinite" && result.corporation_phone != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Cel: ", 15, 85);
                            context.font = "8pt Arial";
                            context.fillText(result.corporation_phone, 44, 85);
                        }
                        if (result.extension_numeric != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Telf: ", 15, 103);
                            context.fillText(" - Ext: ", 135, 103);
                            context.font = "8pt Arial";
                            context.fillText(result.office_phone, 43, 103);
                            context.fillText(result.extension_numeric, 170, 103);
                        }
                        if (result.email != null) {
                            context.font = "700 8pt Arial";
                            context.fillStyle = "#000000";
                            context.fillText("Email: ", 15, 120);
                            context.font = "8pt Arial";
                            context.fillText(result.email, 53, 120);
                        }
                    }});
                  result = JSON.parse(tttt);
            if(result.signature_email == 'SACET'){
                imageObj.src = signature_sacet;
            }else {
                imageObj.src = signature_etelix;
            }  
            };
             
            
            //        para codificar imagenes se recomienda usar http://www.base64-image.de/, hasta descubrir porque no funciona en el controlador
            //        imageObj.src = "data:image/png;base64,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";
//        imageObj.src = "data:image/png;base64,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";
            //        imageObj.src = "data:image/png;base64,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";
            //        imageObj.src = "data:image/png;base64,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";
            //        imageObj.src = "data:image/png;base64,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";
            //imageObj.src = "data:image/png;base64,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";

            //        imageObj.src = result.signature;

            function downloadCanvas(link, signature_canvas, filename) {
                link.href = document.getElementById(signature_canvas).toDataURL();
                link.download = filename;
            }

            document.getElementById('download').addEventListener('click', function() {
                downloadCanvas(this, 'signature_canvas', 'signature_' + result.username + '.png');
            }, false);
        }
        
        
//        var canvas = document.getElementById("sticker_canvas");
//        if (canvas != null)
//        {
//            var context = canvas.getContext("2d", "2d");
//            var imageObj = new Image();
//            imageObj.onload = function() {
//                $.ajax({type: "GET", url: "/employee/signatureInfo", success: function(data) {
//
//                        result = JSON.parse(data);
//                        console.log(result.position.length);
//
//                        context.drawImage(imageObj, 0, 0);
//
//                        context.font = "bold 10.77pt Arial";
//                        context.fillStyle = "#324692";
//                        context.fillText(result.name, 50, 27);
//
//                        context.font = "oblique 8pt Arial";
//                        context.fillStyle = "#324692";
//
////                    var texto=null;
////                    texto.split (result.division, " ");
////                    
//                        if (result.position.length >= 30) {
//                            context.fillText(result.position, 50, 43, 170);
//                        } else {
//                            context.fillText(result.position, 50, 43);
//                        }
//                        context.font = "oblique bold 7pt Arial";
//                        if (result.division.length >= 30) {
//                            context.fillText(result.division, 50, 55, 350);
//                        } else {
//                            context.fillText(result.division, 50, 55);
//                        }
////                    result.corporation_phone="04241441771";    //para probar el numero telefonico
//                        if (result.corporation_phone != "indefinite") {
//                            context.font = "700 8pt Arial";
//                            context.fillStyle = "#000000";
//                            context.fillText("Cel: ", 14, 77);
//                            context.font = "8pt Arial";
//                            context.fillText(result.corporation_phone, 40, 77);
//                        }
//                        if (result.extension_numeric != null) {
//                            context.font = "700 8pt Arial";
//                            context.fillStyle = "#000000";
//                            context.fillText(" - Ext: ", 135, 91);
//                            context.font = "8pt Arial";
//                            context.fillText(result.extension_numeric, 170, 91);
//                        }
//                        if (result.email != null) {
//                            context.font = "8pt Arial";
//                            context.fillStyle = "#000000";
//                            context.fillText(result.email, 53, 120);
//                        }
//                    }});
//            };
//            imageObj.src = "data:image/png;base64,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";
//            function downloadCanvas(link, signature_canvas, filename) {
//                link.href = document.getElementById(signature_canvas).toDataURL();
//                link.download = filename;
//            }
//
//            document.getElementById('sticker_download').addEventListener('click', function() {
//                downloadCanvas(this, 'sticker_canvas', 'stiker_' + result.username + '.png');
//            }, false);
//        }

    }

    function _createDepartment() {
     
        $("#create-deparment").on('click', function() {
         
            $("#alert-department-1").hide();
            $.get("/Hr/department/create", function(data) {
                $("#body-create-department").html(data);
                Metronic.init();
                _selectCompanyDepartment();
                _applyMetroSelect();
                _changeSelect();
                _newEditPosition();
                _validateField();

            });

            $("#modal-create-department").modal('show');

        });
    }

    function _updateDepartment() {
        console.log("_updateDepartment()");
        $(".edit-department").on('click', function(event) {
            $.get("/Hr/department/update", {'id': event.target.id}, function(data) {
                $("#body-update-department").html(data);
                Metronic.init();
                _selectInitUpdateDepartment();
                _changeSelectEdit();
                _newUpdatePosition();
                _validateField();
                $("#modal-update-department").modal('show');
            });

        });
    }
    function _selectInitUpdateDepartment() {
        console.log("_selectInitUpdateDepartment()");
        $(".select2-update-department").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });
    }

    function _changeSelectEdit() {
  
        $("#edit-employee_id").change(function() {
            if ($(this).val() != "") {
                $("div.radio").removeClass('disabled');
                $(".position-perfil").prop('disabled', false);
                $("#position-select").prop('disabled', false);

                $("#position-select").select2('val', '');
            } else {
                $("div.radio").addClass('disabled');
                $(".position-perfil").parent().removeClass("checked");
                $("#position-select").val(null);
                $(".position-perfil").prop('disabled', true);
                $("#position-select").prop('disabled', true);
                $("#position-select").select2('val', '');
                $("#view-new-edit-position").hide();
            }


        });
    }

    function _modalMessagePositionYesAndNo() {
        console.log("_modalMessagePositionYesAndNo()");
        $("#btn-no-position").on("click", function() {
            $("#modal-create-position").modal('hide');
        });
    }

    function _initSelectModalPosition() {
        console.log("_initSelectModalPosition()");
        $(".select2-position").select2({
            placeholder: "Select",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        });
    }

    function _selectPositionDepartment() {
        console.log("_selectPositionDepartment()");
//        var html = "<option value>Seleccione un Cargo</option>";
        $.ajax({
            type: 'GET',
            url: "/Hr/position/refreshSelectPosition",
            data: {"path":$('#path-create-update').html()},
            success: function(data, textStatus, jqXHR) {
//                if (data.position != 'null') {
//                    data = JSON.parse(data);
//                    console.log(data);
//                    for (i = 0; i < data.position.length; i++) {
//                        html += "<option value=" + data.position[i].name + ">" + data.position[i].name + "</option>";
//                    }
//                }
//                $("#position-select").html(html);
//                $("#position-select-update").html(html);
                  
                    $("div.select-position-department").html(data);
                    if($('#path-create-update').html()=="create"){
                        $("#position-select").select2({
                                    placeholder: "Select",
                                    allowClear: true,
                                    escapeMarkup: function(m) {
                                        return m;
                                    }
                        });
                    }else{
                        $("#position-select-update").select2({
                                    placeholder: "Select",
                                    allowClear: true,
                                    escapeMarkup: function(m) {
                                        return m;
                                    }
                        });
                    }
                    
            }
        });



    }
    function _newUpdatePosition() {
    
       
        $("#button-new-update-position").on('click', function() {
            $.get("/Hr/position/create", function(data) {
                 
                $("#body-create-position").html(data);
                //_initSelectModalPosition();
                 $("#mensaje-error-position").hide();
                $("#department-opcional").hide();
                $("#parent-position-opcional").hide();
                $("#company-opcional").hide();
                _modalMessagePositionYesAndNo();
                $("#modal-create-position").modal('show');
            });

            //$("#view-new-edit-position").show(true);

        });

        //$("#view-new-edit-position").show(true);

    }
    function _initModuleHrEmployee() {
        _modalEmployeeHr();
        _selectCountryGMT();
        _selectCompanyHr();
        _saveEmployeeHr();
        _selectDepartmentHr();
    }

    function _modalEmployeeHr() {
        $("#create-employee").on("click", function() {

         
            $('.create-save-employee').removeClass('ocultar');
            $('.create-edit-employee').addClass('ocultar');
            $('.cont-mensaje-edit').hide();
         
            
//            $(".cont-mensaje-edit").hide();
//            $("#mensaje-editar-empleado").hide();
//            $('.cont-mensaje-edit').addClass('ocultar');

            $('#dni_employee').val('');
            $('#name_employee').val('');
            $('#second_name_employee').val('');
            $('#surname_employee').val('');
            $('#second_surname_employee').val('');
            $('#country_location_current_employee').select2('val', 'empty');
            $('#gmt_location_current_employee').select2('val', 'empty');
            $('#gmt_location_current_employee').select2('val', 'empty');
            $('#company_employee').select2('val', 'empty');
            $('#department_employee').select2('val', 'empty');
            $('#position_employee').select2('val', 'empty');
            $('#start_date_employee').val('');
            $('#end_date_employee').val('');
            $('#contract_type_employee').select2('val', 'empty');



            $.ajax({
                type: 'GET',
                url: "/Hr/employee/ModalEmployeeData",
//                url: "/Sys/Company/GetCompanies", 
//                url: "/Sys/Country/GetCountry",                
                beforeSend: function() {
                    $("#country_location_current_employee").prop("disabled", true);
                    $("#company_employee").prop("disabled", true);

                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    html = "<option value='empty'>Seleccione un Pais</option>";
                    for (i = 0; i < data.country.length; i++) {
                        html += "<option value=" + data.country[i].id + ">" + data.country[i].name + "</option>";
                    }

                    $("#country_location_current_employee").html(html);

                    html_1 = "<option value='empty'>Seleccione una Compañia</option>";
                    for (i = 0; i < data.company.length; i++) {
                        html_1 += "<option value=" + data.company[i].id + ">" + data.company[i].name + "</option>";
                    }
                    $("#company_employee").html(html_1);

                    $("#modal-create-employee").modal("show");

                },
                complete: function() {
                    $("#country_location_current_employee").prop("disabled", false);
                    $("#company_employee").prop("disabled", false);
                },
                error: function(result) {
                    console.log(result);
                    alert("Ooop ocurrio un error");
                }
            });

        });
    }
    function _selectCountryGMT() {
        $("#country_location_current_employee").change(function() {

            var request = $.ajax({
                type: 'GET',
                url: "/Sys/City/GetCityAndGmt",
                data: {"country_id": $("#country_location_current_employee").val()},
                beforeSend: function() {
                    $("#gmt_location_current_employee").select2('val', 'empty');
                    $("#gmt_location_current_employee").prop("disabled", true);
                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    html = "<option value='empty'>Seleccione un GMT</option>";
                    for (i = 0; i < data.length; i++) {
                        html += "<option value=" + data[i].id + ">" + data[i].name + "</option>";
                    }
                    $("#gmt_location_current_employee").html(html);

                },
                complete: function(msg) {
                    $("#gmt_location_current_employee").prop("disabled", false);
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });

//            request.done(function(msg){
//                alert(msg);
//            });
//            request.fail(function(msg){
//                console.log(msg.response);
//            });

        });
    }
    function  _saveEmployeeHr() {
        $("#save-employee").on('click', function() {

            var request = $.ajax({
                type: 'GET',
                url: "/Hr/employee/SaveEmployee",
                data: {
                    "dni": $("#dni_employee").val(),
                    "name": $("#name_employee").val(),
                    "second_name": $("#second_name_employee").val(),
                    "surname": $("#surname_employee").val(),
                    "second_surname": $("#second_surname_employee").val(),
                    "country_location_current": $("#country_location_current_employee").val(),
                    "gmt_location_current": $("#gmt_location_current_employee").val(),
                    "start_date": $("#start_date_employee").val(),
                    "contract_type": $("#contract_type_employee").val(),
                    "end_date": $("#end_date_employee").val(),
                    "company": $("#company_employee").val(),
                    "department": $("#department_employee").val(),
                    "position": $("#position_employee").val()

                },
                beforeSend: function() {

                },
                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);

                    if (data.response == "true")
                    {
//                        $('.cont-mensaje-edit').removeClass('ocultar');
                        $(".cont-mensaje-edit").show('true');
                        $('.cont-mensaje-edit').removeClass('alert-danger');
                        $('.cont-mensaje-edit').addClass('alert-success');
                        $('#mensaje-editar-empleado').html("Registro Exitoso");

                        $('#div_tabla_employee').load('/Hr/employee/RefreshEmployee', '#table_employee', function() {
                            Metronic.init();
                            _initDataTable('#table_employee', 1, 10);

                        });


                        setTimeout('$("#modal-create-employee").modal("hide");', 1500);

                    }

                    if (data.response == "false")
                    {
                        if (data.message == "Faltan algunos campos por llenar.")
                        {
                            $(".cont-mensaje-edit").show('true');
//                            $('.cont-mensaje-edit').removeClass('ocultar');
                            $('.cont-mensaje-edit').removeClass('alert-success');
                            $('.cont-mensaje-edit').addClass('alert-danger');
                            $('#mensaje-editar-empleado').html("Faltan algunos campos por llenar.");
                        }

                        else
                        {
                            console.log("Ooops a ocurrido un error.");
                        }
                    }



                },
                complete: function(msg) {

                },
                error: function(result) {
                    alert(result.responseText);
                }
            });

//            request.done(function(msg){
//                alert(msg);
//            });
//            request.fail(function(msg){
//                console.log(msg.response);
//            });

        });
    }

    function _selectCompanyHr() {
        $("#company_employee").change(function() {

            var request = $.ajax({
                type: 'GET',
                url: "/Hr/Department/DepartmentByCompany",
                data: {"id": $("#company_employee").val()},
                beforeSend: function() {
                    $("#department_employee").select2('val', 'empty');
                    $("#department_employee").prop("disabled", true);
                    $("#position_employee").select2('val', 'empty');
                    $("#position_employee").prop("disabled", true);
                },
                success: function(data, textStatus, jqXHR) {
                    //console.log(data);
                    data = JSON.parse(data);
                    html = "<option value='empty'>Seleccione un Departamento</option>";
                    if (data.response != "false") {
                        for (i = 0; i < data.departments.length; i++) {
                            html += "<option value=" + data.departments[i].id + ">" + data.departments[i].name + "</option>";
                        }
                    }
                    $("#department_employee").html(html);

                },
                complete: function(msg) {
                    $("#department_employee").prop("disabled", false);
                    $("#position_employee").prop("disabled", false);
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });

//            request.done(function(msg){
//                alert(msg);
//            });
//            request.fail(function(msg){
//                console.log(msg.response);
//            });

        });
    }

    function _selectDepartmentHr() {
        $("#department_employee").change(function() {

            var request = $.ajax({
                type: 'GET',
                url: "/Hr/position/PositionByCompanyAndDepartment1",
                data: {"id_company": $("#company_employee").val(), "id_department": $("#department_employee").val()},
                beforeSend: function() {

                    $("#position_employee").select2('val', 'empty');
                    $("#position_employee").prop("disabled", true);
                },
                success: function(data, textStatus, jqXHR) {
                    // console.log(data);
                    data = JSON.parse(data);
                    html = "<option value='empty'>Seleccione un Cargo</option>";
                    for (i = 0; i < data.length; i++) {
                        html += "<option value=" + data[i].id + ">" + data[i].name + "</option>";
                    }
                    $("#position_employee").html(html);

                },
                complete: function(msg) {

                    $("#position_employee").prop("disabled", false);
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });

//            request.done(function(msg){
//                alert(msg);
//            });
//            request.fail(function(msg){
//                console.log(msg.response);
//            });

        });
    }
    
    
        function _validateField() {

//        $('#title, #title_mod, #obstacle_mod').validCampoFranz(' abcdefghijklmnÃƒÂ±opqrstuvwxyzÃƒÂ¡ÃƒÂ©ÃƒÂ­ÃƒÂ³ÃƒÂº0123456789()-.,áéíóúÁÉÍÓÚñÑ');
//        $('#description, #description_mod, #observacion_mod').validCampoFranz(' abcdefghijklmnÃƒÂ±opqrstuvwxyzÃƒÂ¡ÃƒÂ©ÃƒÂ­ÃƒÂ³ÃƒÂº0123456789()-.,áéíóúÁÉÍÓÚñÑ');
        $('#surname_employee, #name_employee, #second_name_employee, #second_surname_employee, #edit-name-department, #Department_name, #Position_name').validCampoFranz(' abcdefghijklmnÃƒÂ±opqrstuvwxyzÃƒÂ¡ÃƒÂ©ÃƒÂ­ÃƒÂ³ÃƒÂºÃ¡Ã©áéíóúÁÉÍÓÚñÑ');
//        $('.only_number_tel').validCampoFranz(' 0123456789-.()');
//        $('.only_number').validCampoFranz(' 0123456789.');
//        $('.alfa_number').validCampoFranz(' abcdefghijklmnÃ±opqrstuvwxyz­Ã³Ãº0123456789-.,ÂñÑáéíóúÁÉÍÓÚ');

    }


    return {
        init: init,
        successPass: successPass,
        viewEmployeeModal: viewEmployeeModal,
        rolCreate: rolCreate,
        viewActionController: viewActionController,
        createPosition: createPosition,
        createDivision: createDivision,
        createCargo: createCargo,
        viewPositionCode: viewPositionCode,
        genNameFile: genNameFile,
        messageCp: messageCp,
        updateTable: updateTable,
        successAsigPayRoll: successAsigPayRoll,
        successDeleteEmployeePayRoll: successDeleteEmployeePayRoll,
        successUpdateayRoll: successUpdateayRoll,
        successModicationPayRoll: successModicationPayRoll,
        successFinalityPayRoll: successFinalityPayRoll,
        successSendeEmail: successSendeEmail,
        successDeleteDivision: successDeleteDivision,
        successEditDivision: successEditDivision,
        successEditPosition: successEditPosition,
        successDeleteCodigoPosicion: successDeleteCodigoPosicion,
        successUpdatePositionCode: successUpdatePositionCode,
        successDeletePositionCp: successDeletePositionCp,
        successActivePositionCp: successActivePositionCp,
        successAsignacionHorarioEmployee: successAsignacionHorarioEmployee,
        successNewHorario: successNewHorario,
        successDeleteHours: successDeleteHours,
        successSaveEmployeeByHour: successSaveEmployeeByHour,
        successSaveEmployeeBreakHour: successSaveEmployeeBreakHour,
        successAsignacionBreak: successAsignacionBreak,
        successAsigTelf: successAsigTelf,
        successDeleteTelfAsig: successDeleteTelfAsig,
        successSaveProof: successSaveProof

    };
})();    
(function(a) {
    a.fn.validCampoFranz = function(b) {
        a(this).on({keypress: function(a) {
                var c = a.which, d = a.keyCode, e = String.fromCharCode(c).toLowerCase(),
                        f = b;
                (-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || a.preventDefault()
            }})
    }
})(jQuery);
