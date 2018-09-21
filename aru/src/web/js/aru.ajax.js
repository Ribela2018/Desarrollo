/**
 * Modulo encargado dela interaccion con servidor a traves de AJAX
 */
$ARU.AJAX=(function()
{
    /**
     * @param type
     * @param action
     * @param formulario
     */    
    function sendEvent(type, action, formulario)
    {
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                id=result.event;
//                if (id==2)
//                {
//                    console.log (result.hour);
//                }
                $('div#tab'+id+' label').html(result.hour +" <span style='color:#B3B3B3'>("+result.date+")</span>");
                
            }
         });

    }
    function SaveDirectoryTelfByEmployee(type, action, formulario)
    {
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data); 
                console.log(result);
                $ARU.UI.successAsigTelf(result);
            }
         });

    }
    
    
    function sendPass(type,action, formulario){
        
         $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
               console.log(data);
                result=JSON.parse(data);
                $ARU.UI.successPass(result);
            }
         });
    }
    
    
    
    /**
     * funcion para buscar los detalles de los empleados
     * 
     */
    
    function searchEmployee(type,action, formulario)
    {
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data); 
                console.log(result);
                $ARU.UI.viewEmployeeModal(result);
            }
        });
    }
    
    
    function createRol(type,action, formulario)
    {
        
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.rolCreate(result);
            }
        });
    }
    
    
  
    
    /**
     * 
     * @param {type} type
     * @param {type} action
     * @param {type} formulario
     * @returns {undefined}
     */
     function idRol(type,action, formulario)
    {
        
     $.ajax({
            type:type,
            url:action,
            data:formulario,
          
            success:function(data)
            { 
               //console.log(data[0]);
                result= JSON.parse(data);
                $ARU.UI.viewActionController(result); 
            
            }
            });      
    }
    
      /**
     * funcion para crear la posicion de empleado en la organizacion
     * @param {type} type
     * @param {type} action
     * @param {type} formulario
     * @returns {undefined}
     */
    
    function crearPosicion(type,action, formulario)
    {
        
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.createPosition(result);
            }
        });
    }
    function createPositionCode(type,action, formulario)
    {
        
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.createPosition(result);
            }
        });
    }
    
    
    /**
     * funcion para crear una nueva division en la organizacion
     */
    
    function crearDivision(type,action, formulario)
    {
        
       var idDivision= $.ajax({
            type:type,
            url:action,
            async: false,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.createDivision(result);
      
            }
        }).responseText;
        
       return idDivision;
    }
    /**
     * funcion para crear una nueva division en la organizacion
     */
    
    function montPay(type,action, formulario)
    {
        
       var idMontPay= $.ajax({
            type:type,
            url:action,
            async: false,
            data:formulario,
            success:function(data)
            {
            }
        }).responseText;
        
       return idMontPay;
    }
    
    
    /**
     * funcion para crear nuevos cargos en la organizacion
     */
    
    
     function crearCargo(type,action, formulario)
    {
        
        var idPosition = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.createCargo(result);
            }
        }).responseText;
        
        return idPosition;
    }
     function leaderExist(type,action, formulario)
    {
        
        var leader = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
//                result=JSON.parse(data);
//                $ARU.UI.createCargo(result);
            }
        }).responseText;
        
        return leader;
    }
     function employeeExist(type,action, formulario)
    {
        
        var employee = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
//                result=JSON.parse(data);
//                $ARU.UI.createCargo(result);
            }
        }).responseText;
        
        return employee;
    }
    
    
    
    
       function ExistDependency(type,action, formulario)
    {
        
        var ExistDependen = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
//                result=JSON.parse(data);
//                $ARU.UI.createCargo(result);
            }
        }).responseText;
        
        return ExistDependen;
    }
    
    /**
     * 
     * 
     */
     function posicion(type,action, formulario)
    {
        
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.viewPositionCode(result);
            }
        });
    }
    
    
    /**
     * 
     * 
     */
    
     function excelCp(type,action, formulario, ids, idTable, name)
    {
        
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                 setTimeout("window.open('/site/excel?ids="+ids+"&name="+name+"&table="+idTable+"','_top');",500);
                 //Mostramos los Mensajes y despues de la Descarga se Ocultan Automaticamente.
                 setTimeout('$("#complete").html("<h3>Archivo Excel Generado... !!</h3>");',1800 );
                 setTimeout('$("#administrarPosicion").modal("hide");',2500 );
            }
        });
    }
    
    /**
     * funcion para enviar por email la tabla cp
     * 
     */
     function emailCp(type,action, formulario)
    {
       
        $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
             setTimeout('$("#complete").html("<h3>Correo Enviado con Exito... !!</h3>");',1800 );
             setTimeout('$("#administrarPosicion").modal("hide");',2500 );   
            }
        });
    }
    
    
    /**
     * funcion para eliminar (quitar de la tabla cp) al empleado con su cp
     */
    
    function endDate(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.messageCp(result);
            }
        });
        
    }
    function confirmarDelete(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                
                result=JSON.parse(data);
                console.log(result);
                $ARU.UI.successDeleteCodigoPosicion(result);
            }
        });
        
    }
    
   function payRoll(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.successAsigPayRoll(result);  
            }
        });
    }
    
   function idPayRoll(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                $ARU.UI.updateTable(result);
            }
        });

    }
    
    function idPayRollRefresh(type,action, formulario){

        var PayRollEmployee = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
            }
        }).responseText;
        
        return PayRollEmployee;
        
        
    }
    function getEmployeePayRoll(type,action, formulario){

        var dataEmployeePayRoll = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
            }
        }).responseText;
        
        return dataEmployeePayRoll;
        
        
    }
    
    function getAllPayRoll(type,action, formulario){

        var dataPayRoll = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
            }
        }).responseText;
        
        return dataPayRoll;
        
        
    }
    
   function deletePayRoll(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
    
                result=JSON.parse(data);
                $ARU.UI.successDeleteEmployeePayRoll(result);

            }
        });
        
    }
   function updateNominas(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
      
                result=JSON.parse(data);
                $ARU.UI.successUpdateayRoll(result);

            }
        });
        
    }
    
    
      function newPayRollEmployee(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
//      console.log (data);
                result=JSON.parse(data);
                $ARU.UI.successUpdateayRoll(result);

            }
        });
        
    }
      function modificationPayRollEmployee(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                 
                result=JSON.parse(data);
                $ARU.UI.successModicationPayRoll(result);

            }
        });
        
    }
    
      function finalizarNominaEmployee(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                result=JSON.parse(data);
                console.log (result);
                $ARU.UI.successFinalityPayRoll(result);

            }
        });
        
    }
      function getFiltroReportRRHH(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                console.log (data);
//                result=JSON.parse(data);
//                console.log (result);
//                $ARU.UI.successFinalityPayRoll(result);

            }
        });
        
    }
      function sentEmailDeclare(type,action, formulario){
          $.ajax({
            type:type,
            url:action,
            data:formulario,
            success:function(data)
            {
                setTimeout('$("#enviandoCorreo").html("<h3>Correo Enviado con Exito... !!</h3>");',1800 );
                setTimeout('$("#ModalGeneral").modal("hide");',2500 );  
                

            }
        });
        
    }
      function sentDeleteIdDivision(type, action, formulario) {
        $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successDeleteDivision(result);

            }
        });

    }
      function sentUpdateIdDivision(type, action, formulario) {
        $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successEditDivision(result);

            }
        });

    }
      function sentUpdateIdPosition(type, action, formulario) {
        $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successEditPosition(result);

            }
        });

    }
      function actualizarCodigoPosicion(type, action, formulario) {
        $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
           
                result = JSON.parse(data);
                $ARU.UI.successUpdatePositionCode(result);

            }
        });

    }
      function borrarPosicionCp(type, action, formulario) {
        $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
           
                result = JSON.parse(data);
                $ARU.UI.successDeletePositionCp(result);

            }
        });

    }
    
    function confirmarActivarCpEmployee(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
           
                result = JSON.parse(data);
                $ARU.UI.successActivePositionCp(result);

            }
        });
    }
    
    
     function birthDate(type,action, formulario){

        var databirthDate = $.ajax({
            type:type,
            url:action,
            data:formulario,
            async: false,
            success:function(data)
            {
            }
        }).responseText;
        
        return databirthDate;
        
        
    }
    
     function asignacionHorarios(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successAsignacionHorarioEmployee(result);

            }
        });
    }
     function createNewListHours(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successNewHorario(result);
            }
        });
    }
     function deleteHourEmployee(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                
                result = JSON.parse(data);
                $ARU.UI.successDeleteHours(result);
               
            }
        });
    }
     function saveHourEmployeeByDay(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
              
                result = JSON.parse(data);
                $ARU.UI.successSaveEmployeeByHour(result);
            }
        });
    }
    
     function asignacionDescansoHour(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
              
                result = JSON.parse(data);
                $ARU.UI.successSaveEmployeeBreakHour(result);
            }
        });
    }
     function asignacionDescanso(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
                result = JSON.parse(data);
                $ARU.UI.successAsignacionBreak(result);
            }
        });
    }
     function deleteAsigTelfAjax(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
               
                result = JSON.parse(data);
                $ARU.UI.successDeleteTelfAsig(result);
            }
        });
    }
    
    
    
    function calcularHoraEvento(type,action, formulario)
    {
        
       var validateHour= $.ajax({
            type:type,
            url:action,
            async: false,
            data:formulario,
            success:function(data)
            {
//               console.log(data);
      
            }
        }).responseText;
        
       return validateHour;
    }
    
    
     function SaveProofEmployee(type, action, formulario)
    {
         $.ajax({
            type: type,
            url: action,
            data: formulario,
            success: function(data)
            {
               
                result = JSON.parse(data);
                $ARU.UI.successSaveProof(result);
            }
        });
    }
    
    
        function sessionPayRollEmployee(type,action, formulario)
    {
        
       var validateHour= $.ajax({
            type:type,
            url:action,
            async: false,
            data:formulario,
            success:function(data)
            {
               console.log(data);
      
            }
        }).responseText;
        
       return validateHour;
    }
  
    
    
    
   
    
    
     return {
        sendEvent:sendEvent,
        sendPass:sendPass,
        searchEmployee:searchEmployee,
        createRol:createRol,
        idRol:idRol,
        crearPosicion:crearPosicion,
        crearDivision:crearDivision,
        crearCargo:crearCargo,
        createPositionCode:createPositionCode,
        posicion:posicion,
        employeeExist:employeeExist,
        leaderExist:leaderExist,
        ExistDependency:ExistDependency,
        excelCp:excelCp,
        emailCp:emailCp,
        endDate:endDate,
        payRoll:payRoll,
        idPayRoll:idPayRoll,
        idPayRollRefresh:idPayRollRefresh,
        deletePayRoll:deletePayRoll,
        getEmployeePayRoll:getEmployeePayRoll,
        updateNominas:updateNominas,
        newPayRollEmployee:newPayRollEmployee,
        getAllPayRoll:getAllPayRoll,
        modificationPayRollEmployee:modificationPayRollEmployee,
        finalizarNominaEmployee:finalizarNominaEmployee,
        getFiltroReportRRHH:getFiltroReportRRHH,
        sentEmailDeclare:sentEmailDeclare,
        sentDeleteIdDivision:sentDeleteIdDivision,
        sentUpdateIdDivision:sentUpdateIdDivision,
        sentUpdateIdPosition:sentUpdateIdPosition,
        confirmarDelete:confirmarDelete,
        actualizarCodigoPosicion:actualizarCodigoPosicion,
        borrarPosicionCp:borrarPosicionCp,
        confirmarActivarCpEmployee:confirmarActivarCpEmployee,
        birthDate:birthDate,
        asignacionHorarios:asignacionHorarios,
        createNewListHours:createNewListHours,
        deleteHourEmployee:deleteHourEmployee,
        saveHourEmployeeByDay:saveHourEmployeeByDay,
        asignacionDescansoHour:asignacionDescansoHour,
        asignacionDescanso:asignacionDescanso,
        SaveDirectoryTelfByEmployee:SaveDirectoryTelfByEmployee,
        deleteAsigTelfAjax:deleteAsigTelfAjax,
        calcularHoraEvento:calcularHoraEvento,
        montPay:montPay,
        SaveProofEmployee:SaveProofEmployee,
        sessionPayRollEmployee:sessionPayRollEmployee
        
        
    };
    
})();