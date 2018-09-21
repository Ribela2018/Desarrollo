<?php
ini_set('max_execution_time', -1);
/**
 *
 */
class EmployeeController extends Controller {

    /**
     *
     */
    public function filters() {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl', /// perform access control for CRUD operations
            array(
                'application.filters.UserLoginFilter + infoEmployee, firstView, searchEmployee, directoryPhone', /* cuando no estas logeado */
            ),
            array(
                'application.filters.UserUpdateFilter + infoEmployee, searchEmployee, directoryPhone, birthDate, signatureInfo ',
            )
        );
    }

    /**
     *
     */
//    public function accessRules()
//    {
//        return array(
//            array(
//                'allow',
//                'actions'=>Rol::getActions('Employee', Yii::app()->user->id),
//                'users'=>array(
//                    Yii::app()->user->name
//                    )
//                ),
//            array(
//                'deny', // deny all users
//                'users'=>array('*'),
//                ),
//            );
//    }

    /**
     *
     */
    public function actionInfoEmployee() {
        $Employee = Employee::getEmployee(Yii::app()->user->id);

        if (is_null($Employee)) {
            $Employee = new Employee;
            $Address = new Address;
        } else {
            $Address = Address::getAddressByEmployee($Employee->id);
        }

        if (isset($_POST['Employee'])) {
            $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

            $Employee->attributes = $_POST['Employee'];
            $date_birth = date('Y-m-d', strtotime(str_replace($arraFrom, $arraTo, $_POST['Employee']['date_birth'])));
            $Employee->date_birth = $date_birth;
            if ($Employee->save()) {
                Users::assignEmployee(Yii::app()->user->id, $Employee->id, $_POST['Employee']['email_company']);

                if (Address::validAddressForm($_POST['Address'])) {
                    $checkAddress = Address::checkAddress($_POST['Address']);

                    if (is_null($checkAddress)) {
                        $NewAddress = new Address;
                        $NewAddress->address_line_1 = $_POST['Address']['address_line_1'];
                        $NewAddress->address_line_2 = $_POST['Address']['address_line_2'];
                        $NewAddress->zip = $_POST['Address']['zip'];
                        $NewAddress->id_city = $_POST['Address']['id_city'];
                        if ($NewAddress->save())
                            $idAddress = $NewAddress->id;
                    }
                    else {
                        $idAddress = $checkAddress;
                    }

                    $checkAddressEmployee = AddressEmployee::checkAddressByEmployee($Employee->id, $idAddress);
                    /* si es */
                    if (is_null($checkAddressEmployee)) {
                        if ($Address->id != NULL) {
                            $OldAddressEmployee = AddressEmployee::model()->find('id_address =:address', array(':address' => $Address->id));
                            $OldAddressEmployee->end_date = date("Y-m-d");
                            $OldAddressEmployee->save();
                        }
                        $AddressEmployee = new AddressEmployee;
                        $AddressEmployee->id_employee = $Employee->id;
                        $AddressEmployee->id_address = $idAddress;
                        $AddressEmployee->start_date = date("Y-m-d");
                        if ($AddressEmployee->save()) {
                            
                        }
                        $this->redirect(array('infoEmployee', 'id' => $Employee->id));
                    } else {
                        
                    }
                }
            }
        }
        $this->render('infoEmployee', array('Employee' => $Employee, 'Address' => $Address));
    }

    /**
     *
     */
    public function actionStateByCountry() {
        $dato = '<option value="">Seleccione uno</option>';
        $data = State::getListStateCountry($_POST['Employee']['country']);
        foreach ($data as $value => $name) {
            $dato.= "<option value='$value'>" . CHtml::encode($name) . "</option>";
        }
        echo $dato;
    }

    public function actionSignatureInfo() {
//        exit();
        $model = Employee::getEmployee(Yii::app()->user->id);
        $query = "SELECT (SUBSTRING(UPPER (e.first_name), 1, 1) || SUBSTRING (LOWER (e.first_name), 2,char_length(e.first_name)) || (CASE WHEN e.id=160 THEN ' '||e.second_name ELSE '' END) ||' '||
                         SUBSTRING(UPPER (e.last_name), 1, 1) || SUBSTRING (LOWER (e.last_name), 2,char_length(e.last_name)) ) as nombre,  
                         e.identity_card as cedula, p.name as position, pc.start_date as fecha,d.name as division, e.cellphone, e.extension_numeric, 
                         e.skype, e.email_company, e.id, pc.position_code, e.corporation_phone AS corporation_phone, e.office_phone, 
                         (CASE WHEN strpos(e.email_company, 'etelix') > 0 THEN 'ETELIX'::text ELSE 'SACET'::text  END) AS signature_email,e.id_gender
                 FROM employee e  
                 INNER JOIN position_code pc ON e.id = pc.id_employee
                 INNER JOIN position p ON p.id = pc.id_position
                 INNER JOIN division d ON d.id = pc.id_division
                 WHERE pc.end_date IS NULL AND e.first_name!='Vacante' AND e.id=" . $model->id . "
                 ORDER BY pc.position_code ASC;";
        $model = Employee::model()->findBySql($query);
        $params = array();
        $countStr = 0;
        $position = explode(" ", $model->position);
        $params["last_position"] = $params["position"] = NULL;
        foreach ($position as $key => $value) {
            $countStr+=strlen($value);
            if ($countStr <= 35)
                $params["position"].=" " . $value;
            else
                $params["last_position"].=" " . $value;
        }
        $params["username"] = Yii::app()->user->name;
        $params["name"] = ucwords(strtolower($model->nombre));
        $params["division"] = $model->division;
        $params["email"] = $model->email_company;
        $params["cellphone"] = $model->cellphone;
        $params["corporation_phone"] = str_replace("_", "", (isset($model->corporation_phone) ? $model->corporation_phone : "indefinite"));
        $params["extension_numeric"] = str_replace("_", "", $model->extension_numeric);
        $params["office_phone"] = $model->office_phone;
        $params["signature_email"] = $model->signature_email;
        $params["skype"] = $model->skype;
        $params["id_gender"] = $model->id_gender;

        echo json_encode($params);
    }

    /**
     *
     */
    public function actionCityByState() {
        $dato = '<option value="">Seleccione uno</option>';
        $data = City::getListCityState($_POST['Employee']['state']);
        foreach ($data as $value => $name) {
            $dato.= "<option value='$value'>" . CHtml::encode($name) . "</option>";
        }
        echo $dato;
    }

    /**
     *
     * funcion para guardar la imagen del empleado
     */
    public function actionPhoto() {
        $Employee = Employee::getEmployee(Yii::app()->user->id);
        if ($Employee != NULL) {
            $direccion = "themes/metronic/img/profile/" . Yii::app()->user->name . "/";

            if (file_exists($direccion)) {
                if (isset($_FILES["myfile"])) {
                    $ret = array();
                    $error = $_FILES["myfile"]["error"];
                    if (!is_array($_FILES["myfile"]["name"])) { //single file
                        $fileName = uniqid() . '-' . $_FILES["myfile"]["name"];
                        move_uploaded_file($_FILES["myfile"]["tmp_name"], $direccion . $fileName);
                        $Employee->image_rute = $direccion . $fileName;
                        if ($Employee->save()) {
                            $namephoto[] = 'successUpdate';
                            $namephoto[] = $direccion . $fileName;
                            echo json_encode($namephoto);
                        } else {
                            $namephoto[] = 'imageRuteFail';
                            $namephoto[] = $direccion . $fileName;
                            echo json_encode($namephoto);
                        }
                    }
                }
            } else {
                mkdir($direccion, 0755);
                if (isset($_FILES["myfile"])) {
                    $ret = array();
                    $error = $_FILES["myfile"]["error"];
                    if (!is_array($_FILES["myfile"]["name"])) { //single file
                        $fileName = uniqid() . '-' . $_FILES["myfile"]["name"];
                        move_uploaded_file($_FILES["myfile"]["tmp_name"], $direccion . $fileName);
                        $Employee->image_rute = $direccion . $fileName;
                        if ($Employee->save()) {
                            $namephoto[] = 'successNew';
                            $namephoto[] = $direccion . $fileName;
                            echo json_encode($namephoto);
                        } else {
                            $namephoto[] = 'imageRuteFail';
                            $namephoto[] = $direccion . $fileName;
                            echo json_encode($namephoto);
                        }
                    }
                }
            }
        } else {
            echo json_encode('dataFail');
        }
    }

    /**
     *
     */
    public function actionDeletejquery() {
        $output_dir = "themes/metronic/img/profile/";
        if (isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name'])) {
            $fileName = $_POST['name'];
            $filePath = $output_dir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo "Deleted File " . $fileName . "<br>";
        }
    }

    /*
     * vista para actualizar los datos de numeros de telefonos para empleados
     * 
     */

    public function actionUpdatePhone() {
        $model = new Employee;
        $Address = new Address;
        if (isset($_POST['Employee'])) {
            $model = Employee::getEmployee(Yii::app()->user->id);
            $model->attributes = $_POST['Employee'];
            Users::updateStatus(Yii::app()->user->id);
            if ($model->save()) {
                $this->redirect(array('infoEmployee', 'id' => $model->id));
            }
        }

        $this->render('UpdatePhone', array('model' => $model, 'address' => $Address));
    }

    /**
     * 
     * Vista de carga de datos basicos del empleado con estatus 3
     */
    public function actionFirstView() {
        $model = Employee::getEmployee(Yii::app()->user->id);
        $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        if (is_null($model)) {
            $model = new Employee;
            $Address = new Address;
            if (isset($_POST['Employee'])) {

                $model->id_nationality = $_POST['Employee']['nationality'];
                $model->attributes = $_POST['Employee'];
                $date_birth = date('Y-m-d', strtotime(str_replace($arraFrom, $arraTo, $_POST['Employee']['date_birth'])));
                $model->date_birth = $date_birth;
                //$model->office_phone="+58(212)740-1112";

                if ($model->save()) {


                    Users::updateStatus(Yii::app()->user->id);
                    Users::assignEmployee(Yii::app()->user->id, $model->id, $_POST['Employee']['email_company']);

                    if (Address::validAddressForm($_POST['Address'])) {
                        $checkAddress = Address::checkAddress($_POST['Address']);

                        if (is_null($checkAddress)) {
                            $idAddress = Address::newAddress($_POST['Address']['address_line_1'], $_POST['Address']['address_line_2'], $_POST['Address']['zip'], $_POST['Address']['id_city']);
                        } else {
                            $idAddress = $checkAddress;
                        }

                        $checkAddressEmployee = AddressEmployee::checkAddressByEmployee($model->id, $idAddress);
                        /* si esdddddd */
                        if (is_null($checkAddressEmployee)) {
                            if ($Address->id != NULL) {
                                $OldAddressEmployee = AddressEmployee::model()->find('id_address =:address', array(':address' => $Address->id));
                                $OldAddressEmployee->end_date = date("Y-m-d");
                                $OldAddressEmployee->save();
                            }
                            $AddressEmployee = new AddressEmployee;
                            $AddressEmployee->id_employee = $model->id;
                            $AddressEmployee->id_address = $idAddress;
                            $AddressEmployee->start_date = date("Y-m-d");
                            if ($AddressEmployee->save()) {
                                
                            }
                            $this->redirect(array('infoEmployee', 'id' => $model->id));
                        } else {
                            return false;
                        }
                    }
                }
            }
        } else {


            $addressModel = AddressEmployee::loadAddressByEmployee($model->id);
            if (is_null($addressModel))
                $Address = new Address;
            else
                $Address = Address::employee($addressModel->id_address);

            if (isset($_POST['Employee'])) {

                $model->id_nationality = $_POST['Employee']['nationality'];
                $model->attributes = $_POST['Employee'];
                //$model->office_phone="+58(212)740-1112";
                $model->save();


                Users::updateStatus(Yii::app()->user->id);
                Users::assignEmployee(Yii::app()->user->id, $model->id);

                if (Address::validAddressForm($_POST['Address'])) {
                    $checkAddress = Address::checkAddress($_POST['Address']);

                    if (is_null($checkAddress)) {
                        $idAddress = Address::newAddress($_POST['Address']['address_line_1'], $_POST['Address']['address_line_2'], $_POST['Address']['zip'], $_POST['Address']['id_city']);
                    } else {
                        $idAddress = $checkAddress;
                    }

                    $checkAddressEmployee = AddressEmployee::checkAddressByEmployee($model->id, $idAddress);
                    /* si esdddddd */
                    if (is_null($checkAddressEmployee)) {
                        if ($Address->id != NULL) {
                            $OldAddressEmployee = AddressEmployee::model()->find('id_address =:address', array(':address' => $Address->id));
                            $OldAddressEmployee->end_date = date("Y-m-d");
                            $OldAddressEmployee->save();
                        }
                        $AddressEmployee = new AddressEmployee;
                        $AddressEmployee->id_employee = $model->id;
                        $AddressEmployee->id_address = $idAddress;
                        $AddressEmployee->start_date = date("Y-m-d");
                        if ($AddressEmployee->save()) {
                            
                        }
                        $this->redirect(array('infoEmployee', 'id' => $model->id));
                    } else {
                        $this->redirect(array('infoEmployee', 'id' => $model->id));
                    }
                }
            }
        }

        $this->render('viewfirstemployee', array('model' => $model, 'Address' => $Address));
    }

    /**
     * 
     * funcion para mostrar los usuarios
     */
    public function actionSearchEmployee() {

        $consulta = "SELECT e.id, eee.id_type_event, eee.date as date_event, eee.hour_event, (e.first_name || ' ' || e.last_name) as employee, d.name, p.name as position, te.name as name_event
                        FROM employee e
                        INNER JOIN position_code pc ON pc.id_employee = e.id
                        INNER JOIN division d ON d.id = pc.id_division
                        INNER JOIN position p ON p.id = pc.id_position
                        INNER JOIN event_employee eee ON eee.id_employee = e.id
                        INNER JOIN 
                         (SELECT ee.id_employee, MAX(ee.id) as id 
                          FROM event_employee ee 
                          INNER JOIN employee e ON e.id = ee.id_employee 
                          GROUP BY ee.id_employee
                          ORDER BY ee.id_employee) as ee
                        ON ee.id = eee.id 
                        INNER JOIN users u ON u.id_employee = e.id
                        INNER JOIN type_event te ON te.id=eee.id_type_event
                        WHERE e.id NOT IN(SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No')
                        AND u.id_status NOT IN(2,3) AND pc.end_date IS NULL;";

        $model = Employee::model()->findAllBySql($consulta);
        $tablaActive = '';
        $tablaInactive = '';
        if ($model != Null) {

            foreach ($model as $key => $value) {
                $estilo = EventEmployee::getStilo($value->id_type_event);
                $filtrar = EventEmployee::getfiltro($value->id, $value->date_event, $value->hour_event);
                if ($filtrar == false && $value->id_type_event != 4 && $value->id_type_event != 5) {

                    $new_event_employee = new EventEmployee;
                    $new_event_employee->id_employee = $value->id;
                    $new_event_employee->date = $value->date_event;
                    $new_event_employee->hour_event = $value->hour_event;
                    $new_event_employee->id_type_event = 5;
                    $new_event_employee->save();
                }

                if ($value->id_type_event == 1 || $value->id_type_event == 3) {

                    $tablaActive.="<tr>"
                            . "<td class='ocultar'>$value->id</td>"
                            . "<td>$value->employee</td>"
                            . "<td>$value->position</td>"
                            . "<td><span class='label label-sm $estilo'>$value->name_event</span></td>"
                            . "</tr>";
                } else {
                    $tablaInactive.="<tr>"
                            . "<td class='ocultar'>$value->id</td>"
                            . "<td>$value->employee</td>"
                            . "<td>$value->position</td>"
                            . "<td><span class='label label-sm $estilo'>$value->name_event</span></td>"
                            . "</tr>";
                }
            }
        }


//        $filtroactivo= Employee::getfiltro("active");
//        $filtroinactivo= Employee::getfiltro("inactive");
//        $employeeActive = '';
//        $employeeInactive = '';
//        $filtroactivo =array_filter($filtroactivo);
////        $filtroinactivo =array_filter($filtroinactivo);
////        echo "<pre>";
////        print_r($filtroactivo);
////        echo "</pre>";
//        foreach($filtroactivo as $key => $value)
//        {
//            foreach($value as $key1 => $value1)
//            {
//                 $estilo=EventEmployee::getStilo($value1['tipo_evento']);
//                 $employeeActive.="<tr>"
//                       . "<td style='display:none'>".$value1['id']."</td>"
//                       . "<td>".$value1['nombre']."</td>"
//                       . "<td>".$value1['apellido']."</td>"
//                       . "<td>".$value1['cargo']."</td>"
//                       . "<td><span class='label label-sm $estilo'>inicio</span></td>"
//                       . "</tr>";
//            }
//              
//                    
//         
//        }
//        foreach($filtroinactivo as $key => $value)
//        {
//            foreach($value as $key1 => $value1)
//            {
//                   $estilo=EventEmployee::getStilo($value1['tipo_evento']);
//                   $employeeInactive.="<tr>"
//                       . "<td style='display:none'>".$value1['id']."</td>"
//                       . "<td>".$value1['nombre']."</td>"
//                       . "<td>".$value1['apellido']."</td>"
//                       . "<td>".$value1['cargo']."</td>"
//                       . "<td><span class='label label-sm $estilo'>inicio</span></td>"
//                       . "</tr>";
//            }
//            
//                    
//         
//        }
//       
//        print_r($employeeInactive);
//        print_r($filtroactivo);
//        print_r($filtroinactivo);
//        if ($filtroactivo!=NULL){ 
//
//            foreach ($filtroactivo as $value) {
//             if (is_null($value->image_rute)){ $photoemployee="themes/metronic/img/profile/profile.jpg";} else {/**$photoemployee=$value->image_rute;**/} 
//
//                         $status=EventEmployee::getSearchStatus($value->id);
//                         $estilo=EventEmployee::getStilo($status['id_type_event']);
//                         if (Yii::app()->user->getState('rol')==1){$opc="<a id='detalle' class='btn default btn-xs green-stripe'><div id='id_employ' style='display:none;'>$value->id</div>Detalle</a>";} else { $opc="<a id='detalle' class='btn default btn-xs blue-stripe'><div id='id_employ' style='display:none;'>$value->id</div>Contactos</a>";}
//             $employeeActive.="
//                     <tr>
//                  
//                     <td>$value->first_name</td>  
//                     <td>$value->last_name</td>
//                     <td>".Position::getNamePositionByEmployee($value->id)."</td>
//                     <td><span class='label label-sm $estilo' > $status[name]</span></td>
//                     <td>$opc</td>
//                     </tr>
//                     ";
//             } 
//
//        }
//        
//        if ($filtroinactivo!=NULL){ 
//        
//          foreach ($filtroinactivo as $value) {
//               if (is_null($value->image_rute)){ $photoemployee="themes/metronic/img/profile/profile.jpg";} else {/**$photoemployee=$value->image_rute;**/} 
//
//                           $status=EventEmployee::getSearchStatus($value->id);
//                           $estilo=EventEmployee::getStilo($status['id_type_event']);
//                           if (Yii::app()->user->getState('rol')==1){$opc="<a id='detalle' class='btn default btn-xs green-stripe'><div id='id_employ' style='display:none;'>$value->id</div>Detalle</a>";} else { $opc="<a id='detalle' class='btn default btn-xs blue-stripe'><div id='id_employ' style='display:none;'>$value->id</div>Contactos</a>";}
//               $employeeInactive.="
//                       <tr>
//                   
//                       <td>$value->first_name</td>  
//                       <td>$value->last_name</td>
//                       <td>".Position::getNamePositionByEmployee($value->id)."</td>
//                       <td><span class='label label-sm $estilo' > $status[name]</span></td>
//                       <td>$opc</td>
//                       </tr>
//                       ";
//          } 
//           
//        }

        $this->render('SearchEmployee', array('tablaActive' => $tablaActive, 'tablaInactive' => $tablaInactive));
    }

    public function actionDynamicEmployee() {

        $model = Employee::getDynamicEmployee($_GET['id_employee']);
        $Address = Address::getAddressByEmployee($_GET['id_employee']);
        $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        if ($model != null) {
            $date_birth = date('Y-m-d', strtotime(str_replace($arraTo, $arraFrom, $model->date_birth)));
            $datos[] = array(
                'name' => $model->first_name,
                'second_name' => $model->second_name,
                'last_name' => $model->last_name,
                'second_last_name' => $model->second_last_name,
                'date_birth' => $date_birth,
                'identity_card' => $model->identity_card,
                'email_personal' => $model->email_personal,
                'email_company' => $model->email_company,
                'cellphone' => $model->cellphone,
                'skype' => $model->skype,
                'homephone' => $model->home_phone,
                'extension_numeric' => $model->extension_numeric,
                'nationality' => $model->idNationality->name,
                'maritalstatus' => $model->idMaritalStatus->name,
                'imagen_rute' => $model->image_rute,
            );

            if ($Address != NULL) {
                if ($Address->id_city != NULL) {
                    $city = $Address->idCity->name;
                    $state = $Address->idCity->idState->name;
                    $country = $Address->idCity->idState->idCountry->name;
                } else {
                    $city = "";
                    $state = "";
                    $country = "";
                }


                $datos[] = array(
                    'address_line_1' => $Address->address_line_1,
                    'address_line_2' => $Address->address_line_2,
                    'zip' => $Address->zip,
                    'country' => $country,
                    'state' => $state,
                    'city' => $city,
                );
            } else {
                return false;
            }

            echo json_encode($datos);
        } else {
            return false;
        }
    }

    public function actionAdmiControllers() {
        $rol = Rol::getRol();
        $this->render('AdminControllers', array('rol' => $rol));
    }

    public function actionAdminPermit($id) {
//        $idrol=$_GET['idRol'];
        $rol = Rol::getRol();
        $model = ActionRol::getActionRol($id);

        $controllers = Controllers::getControllers();
        $this->render('AdminPermit', array('model' => $model, 'controllers' => $controllers, 'rol' => $rol, 'idrol' => $id));
    }

    public function actionPayRoll() {
        $this->render('prueba');
    }

    public function actionBirthDate() {
        $mes = date("m");
        $model = Employee::getBirthDate($mes);
        $this->render('birthDate');
    }

    public function actionBirthDateAjax() {
        $mes = $_GET['birthMont'];
        $year = $_GET['birthYear'];
        $model = Employee::getBirthDate($mes);
        $cont = 0;

        if ($model != null) {
            foreach ($model as $key => $value) {
                $porciones = explode("-", $value->date_birth);
                $datos[$cont] = array(
                    'id' => $value->id,
                    'title' => $value->first_name . " " . $value->last_name,
                    'start' => $year . "-" . $porciones[1] . "-" . $porciones[2],
                    'end' => $year . "-" . $porciones[1] . "-" . $porciones[2],
                    'allDay' => false,
                );
                $cont++;
            }
        } else {
            $datos[0] = NULL;
        }
        echo json_encode($datos);
    }

    public function actionHoliday() {
        $this->render('holiday');
    }

    public function actionDirectoryPhone() {
        $modelCountry = new Country();
        $modelEmployee = Employee::getAllEmployeeByDirectory();
        $this->render('directoryPhone', array('modelEmployee' => $modelEmployee, 'modelCountry' => $modelCountry));
    }

    public function actionSaveDirectoryTelf() {
        $idEmployee = $_GET['idEmployee'];
        $cellphoneCorp = $_GET['cellphoneCorp'];
        $officePhone = $_GET['homephone'];
        $cont = 0;

        if ($idEmployee != 'Empty') {
            $consultaSql = "SELECT * FROM employee e WHERE e.id=" . $idEmployee . ";";
            $model = Employee::model()->findBySql($consultaSql);
            $arrayTelft = '';
            $arrayTelftOffice = '';
            if ($model != null) {
                if ($model->corporation_phone != null) {
                    $arrayTelf = $model->corporation_phone;
                    $porciones = explode(",", $arrayTelf);
                    $contador = count(explode(",", $arrayTelf));
                    for ($i = 0; $contador > $i; $i++) {
                        $arrayTelft.=$porciones[$i] . ',';
                    }

                    if ($cellphoneCorp != 'Empty') {
                        $arrayTelft.=$cellphoneCorp;
                        $model->corporation_phone = $arrayTelft;
                        $model->save();
                        $cont++;
                    }
                } else {
                    if ($cellphoneCorp != 'Empty') {
                        $model->corporation_phone = $cellphoneCorp;
                        $model->save();
                        $cont++;
                    }
                }

                if ($model->office_phone != null) {
                    $arrayTelfOffice = $model->office_phone;
                    $porcionesOffice = explode(",", $arrayTelfOffice);
                    $contadorOffice = count(explode(",", $arrayTelfOffice));
                    for ($i = 0; $contadorOffice > $i; $i++) {
                        $arrayTelftOffice.=$porcionesOffice[$i] . ',';
                    }

                    if ($officePhone != 'Empty') {
                        $arrayTelftOffice.=$officePhone;
                        $model->office_phone = $arrayTelftOffice;
                        $model->save();
                        $cont++;
                    }
                } else {
                    if ($officePhone != 'Empty') {
                        $model->office_phone = $officePhone;
                        $model->save();
                        $cont++;
                    }
                }


                if ($cont > 0) {
                    echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }
    }

    public function actionDeleteAsigTelf() {
        $positionTelf = $_GET['positionTelf'];
        $typeTelf = $_GET['typeTelf'];
        $idEmployee = $_GET['idEmployee'];
        $model = Employee::model()->findByPk($idEmployee);
        if ($model) {
            if ($model->corporation_phone != NULL && $typeTelf == "corporativo") {
                $arrayTelf = $model->corporation_phone;
                $phone = $this->DeleteTelephone($positionTelf, $typeTelf, $arrayTelf, $idEmployee);

                echo json_encode($phone);
            }
            if ($model->office_phone != NULL && $typeTelf == "oficina") {
                $arrayTelf = $model->office_phone;
                $phone = $this->deleteTelephone($positionTelf, $typeTelf, $arrayTelf, $idEmployee);
                echo json_encode($phone);
            }
        }
    }

    public function DeleteTelephone($positionTelf, $typeTelf, $arrayTelf, $idEmployee) {
        $arrayTelft = '';
        $arrayTelfty = '';
        $cont = 0;
        $porciones = explode(",", $arrayTelf);
        $contador = count(explode(",", $arrayTelf));
        $model = Employee::model()->findByPk($idEmployee);
        if ($contador >= $positionTelf) {
            for ($i = 0; $contador > $i; $i++) {
                $a = 0;
                if ($i == $positionTelf) {
                    $porciones[$positionTelf] = '';
                    $a = 1;
                    $cont++;
                }
                if ($a == 0) {
                    $arrayTelft.=$porciones[$i] . ',';
                }
            }
            $arrayTelfty = trim($arrayTelft, ',');

            if ($typeTelf == "corporativo") {


                $model->corporation_phone = $arrayTelfty;

                if ($model->save()) {
                    return "true";
                } else {
                    return "false";
                }
            }
            if ($typeTelf == "oficina") {
                $model->office_phone = $arrayTelfty;
                if ($model->save()) {
                    return "true";
                } else {
                    return "false";
                }
            }
        }
    }

    public function actionRefreshGetAllEmployeeByDirectory() {
        $sql = " SELECT (e.first_name||' '||e.last_name) as nombre, p.name as position,d.name as division, e.cellphone, e.extension_numeric, e.skype, e.email_company, e.id, pc.position_code, e.corporation_phone, e.office_phone FROM employee e  
						INNER JOIN position_code pc ON e.id = pc.id_employee
						INNER JOIN position p ON p.id = pc.id_position
						INNER JOIN division d ON d.id = pc.id_division
                        WHERE pc.end_date IS NULL AND e.first_name!='Vacante'
                        ORDER BY pc.position_code ASC;";

        $idRol = Yii::app()->user->getState('rol');
        if ($idRol == 7 || $idRol == 5 || $idRol == 6) {
            $tituloAccion = 'Opc.';
            $optTelf = "<a href='#' id='optionDirectori' name='optionDirectori' class='tooltips' data-original-title='Asignar Telefóno Corporativo'><i class='fa fa-phone-square '></i></a>";
            $opctionDelete = "<i class='fa fa-times rojo tooltips' data-original-title='Eliminar número telefónico'></i>";
        } else {
            $tituloAccion = ' ';
            $optTelf = " ";
            $opctionDelete = " ";
        }

        $allPhone = '';
        $modelPositionCodeActives = Employee::model()->findAllBySql($sql);
        $tabla = '';
        $tabla.="<div class='table-responsive'> <table class='table table-striped table-bordered table-hover dataTable no-footer table-export  tableDirectoryPhonecla' id='tableDirectoryPhone' name='tableDirectoryPhone'>
                                    <thead class='flip-content'>
                                        <tr>
                                            <th class='fondotablesgris ocultar'>Ids</th>
                                            <th class='fondotablesgris'>Colaborador</th>
                                            <th class='fondotablesgris'>Posición/División</th>
                                            <th class='fondotablesgris'>Telf. Corp(Móvil)</th>
                                            <th class='fondotablesgris'>Telf. Oficina</th>
                                            <th class='fondotablesgris'>Ext.</th>
                                            <th class='fondotablesgris'>Telf. Personal</th>
                                            <th class='fondotablesgris'>Skype</th>
                                            <th class='fondotablesgris'>Correo</th>
                                            <th class='fondotablesgris'>" . $tituloAccion . "</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

        foreach ($modelPositionCodeActives as $key => $value) {
            $arrayTelfOffice = Array();
            $arrayTelfCorp = Array();
            if ($value->corporation_phone != NULL) {
                $employeePhoneCorp = $value->corporation_phone;
                $porcionesCorp = explode(",", $employeePhoneCorp);
                $contadorCorp = count(explode(",", $employeePhoneCorp));
                for ($i = 0; $contadorCorp > $i; $i++) {
                    $arrayTelfCorp[$i] = $porcionesCorp[$i] . '<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">corporativo</div><div class="positionPhone ocultar">' . $i . '</div><i class="fa fa-times rojo tooltips" data-original-title="Eliminar número telefónico"></i></a>';
                }

                $allPhone = implode("\n", $arrayTelfCorp);
            } else {
                $allPhone = '';
            }


            if ($value->office_phone != NULL) {
                $employeePhoneOffice = $value->office_phone;
                $porcionesOffice = explode(",", $employeePhoneOffice);
                $contadorOffice = count(explode(",", $employeePhoneOffice));
                for ($i = 0; $contadorOffice > $i; $i++) {
                    $arrayTelfOffice[$i] = $porcionesOffice[$i] . '<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">oficina</div><div class="positionPhone ocultar">' . $i . '</div><i class="fa fa-times rojo tooltips" data-original-title="Eliminar número telefónico"></i></a>';
                }

                $allPhoneOffice = implode("\n", $arrayTelfOffice);
            } else {
                $allPhoneOffice = '';
            }

            if ($value->extension_numeric != NULL) {
                $extensionNum = trim($value->extension_numeric, '_');
            } else {
                $extensionNum = '';
            }

            $tabla.= "<tr>"
                    . "<td class='ocultar'>$value->position_code"
                    . "</td>"
                    . "<td>$value->nombre"
                    . "<div class='ocultar'>"
                    . "<div id='nameModalModification'>$value->nombre</div>"
                    . "<div id='idEmployee'>$value->id</div>"
                    . "<div id='position'>$value->position</div>"
                    . "</div>"
                    . "</td>"
                    . "<td>$value->position/$value->division</td>"
                    . "<td>$allPhone</td>"
                    . "<td style='width: 150px;'>$allPhoneOffice</td>"
                    . "<td>$extensionNum</td>"
                    . "<td>$value->cellphone</td>"
                    . "<td>$value->skype</td>"
                    . "<td>$value->email_company</td>"
                    . "<td>" . $optTelf . "</td>"
                    . "</tr>";
        }
        $tabla.= '</tbody>
                        </table></div>';

        echo $tabla;
    }

    public function actionDirectoryPark() {
        $modelEmployee = Employee::getAllEmployeeByDirectoryPark();
        $this->render('directoryPark', array('modelEmployee' => $modelEmployee));
    }

    public function actionSoon() {
        $this->render('proofEmployee');
    }

    public function actionProofEmployee() {
        $model = Employee::getEmployee(Yii::app()->user->id);
        $model = Employee::model()->findBySql("SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, e.identity_card as cedula, p.name as position, pc.start_date as fecha,d.name as division, e.cellphone, e.extension_numeric, e.skype, e.email_company, e.id, pc.position_code, e.corporation_phone, e.office_phone, n.name as nationality, e.id 
                                                FROM employee e  
                                                INNER JOIN position_code pc ON e.id = pc.id_employee
                                                INNER JOIN position p ON p.id = pc.id_position
                                                INNER JOIN division d ON d.id = pc.id_division
                                                INNER JOIN nationality n ON e.id_nationality=n.id
                                                WHERE pc.end_date IS NULL AND e.first_name!='Vacante' AND e.id=" . $model->id);
        $modelTable = Employee::model()->findAllBySql("SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                                e.identity_card as cedula, p.name as position, pc.start_date as fecha, d.name as division, 
                                                e.email_company, (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,
                                                c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, 
                                                c.information->>'dateAdmission' AS dateadmission,
                                                c.information->'status'->8->>'observation' as observation,
                                                c.id as id_solicitude, n.name as nationality, e.id
                                                FROM employee e
                                                INNER JOIN constancy c ON e.id=c.id_employee
                                                INNER JOIN position_code pc ON e.id = pc.id_employee
                                                INNER JOIN position p ON p.id = pc.id_position
                                                INNER JOIN division d ON d.id = pc.id_division
                                                INNER JOIN users u ON u.id_employee = e.id
                                                INNER JOIN nationality n ON n.id=e.id_nationality
                                                WHERE pc.end_date IS NULL AND e.first_name!='Vacante'  AND u.id=" . Yii::app()->user->id); //AND c.information->'status'->7->>'active'!='true'


        $val = $model->cedula;

        $rest = strlen($val) / 3;
        $value1 = "";
        for ($i = 1; $i <= strlen($val); $i++) {
            if ($i % 3 == 0 && $i != strlen($val)) {
                $value1 = "." . substr($val, -$i, 1) . $value1;
            } else {
                $value1 = substr($val, -$i, 1) . $value1;
            }
        }
        $model->cedula = $value1;

        foreach ($modelTable as $value) {
            $val = $value->cedula;

            $rest = strlen($val) / 3;
            $value1 = "";
            for ($i = 1; $i <= strlen($val); $i++) {
                if ($i % 3 == 0 && $i != strlen($val)) {
                    $value1 = "." . substr($val, -$i, 1) . $value1;
                } else {
                    $value1 = substr($val, -$i, 1) . $value1;
                }
            }
            $value->cedula = $value1;
        }
        $this->render('proofEmployee', array('model' => $model, 'modelTable' => $modelTable));
    }

    public function actionProofEmployeeSave() {


        $model = new Constancy();
        $employee = Employee::getEmployee(Yii::app()->user->id);
        $idEmployee = Employee::getEmployee(Yii::app()->user->id)->id; //$_GET['idEmployee'];

        $min = date('Y-m') . '-01';
        $max = date('Y-m', strtotime("+1 month", strtotime($min))) . "-01";
        $max = date('Y-m-d', strtotime("-1", strtotime($max)));

        $attribs = ["id_employee" => $idEmployee];
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('date_solicitude', $min, $max);
        $num = Constancy::model()->findAllByAttributes($attribs, $criteria);
        $bool = true;
        if (count($num) >= 3) {
            $bool = false;
        }

        $date = date('Y-m-d H:i');
        $information = [];
        $information['addressed'] = strtoupper($_GET['addressed']); // ucwords(strtolower($_GET['addressed']))
        $information['reason'] = $_GET['reason'];
        $information['dateAdmission'] = null;

        $information['status'] = [
            ['name' => 'Abierta', 'active' => true, 'date_active' => $date],
            ['name' => 'En Proceso', 'active' => false, 'date_active' => null],
            ['name' => 'Confirmada', 'active' => false, 'date_active' => null, 'contratacion' => null],
            ['name' => 'Impresa', 'active' => false, 'date_active' => null],
            ['name' => 'Pendiente Por Firmar', 'active' => false, 'date_active' => null],
            ['name' => 'Por Retirar', 'active' => false, 'date_active' => null],
            ['name' => 'Entregada', 'active' => false, 'date_active' => null],
            ['name' => 'No Entregada', 'active' => false, 'date_active' => null],
            ['name' => 'No Procede', 'active' => false, 'date_active' => null, 'observation' => "null"],
        ];

        $model->id_employee = $idEmployee;
        $model->date_solicitude = $date;
        $model->information = json_encode($information);
        if ($bool && $model->save()) {
            $asunto= "Solicitud de Constancia de Trabajo";
            $html = $this->LayoutConstancia($employee, $date, $_GET); 
            
             Yii::app()->email->sendEmail($html, $employee['email_company'], $asunto, null, ["rrhh@sacet.biz"]); 
//             Yii::app()->email->sendEmail($html, $employee['email_company'], $asunto, null, ["josefinam@sacet.biz", "angelos@sacet.biz", "rrhh@sacet.biz","carlosr@sacet.biz"]); 
            echo json_encode(["response" => "true", "val" => "0"]);          
            // request true val
        } elseif ($bool) {
            echo json_encode(["response" => "false", "val" => "0"]);
        } else {
            echo json_encode(["response" => "false", "val" => "1"]);
        }
    }

    public function actionSetObservation() {


        if (isset($_GET['id_constancy']) && !empty($_GET['id_constancy'])) {
            $constancy = Constancy::model()->findByPk($_GET['id_constancy']);

            if ($constancy != null) {

                $objInformation = json_decode($constancy->information);

                if (isset($_GET['observation']) && !empty($_GET['observation'])) {

                    $objInformation->status[8]->observation = $_GET['observation'];
                    $constancy->information = json_encode($objInformation);
                    if ($constancy->save()) {
                        //se guardo la observacion
                        echo json_encode(["response" => "true", "val" => "0"]);
                    } else {
                        // no se guardo la observacion
                        echo json_encode(["response" => "false", "val" => "0"]);
                    }
                } else {

                    echo json_encode(['request' => "false", 'val' => "1"]);
                }
            } else {
                //la constancia es nula
                echo json_encode(["response" => "false", "val" => "2"]);
            }
        } else {
            echo json_encode(['request' => "false", 'val' => "0"]);
        }
    }

    public function actionGetObservation() {


        if (isset($_GET['id_constancy']) && !empty($_GET['id_constancy'])) {
            $constancy = Constancy::model()->findByPk($_GET['id_constancy']);

            if ($constancy != null) {

                $objInformation = json_decode($constancy->information);
                echo json_encode(["response" => "true", "val" => "0", "observation" => $objInformation->status[8]->observation]);
            } else {
                //la constancia es nula
                echo json_encode(["response" => "false", "val" => "1"]);
            }
        } else {
            echo json_encode(['request' => "false", 'val' => "0"]);
        }
    }

    public function actionChangeStatusEnProceso() {
          $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "En Proceso"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }
    public function actionChangeStatusConfirmada() {
            $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "Confirmada"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }

    public function actionChangeStatusImpresa() {
         $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "Impresa"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }

    public function actionChangeStatusPorFirmar() {
           $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "Por Firmar"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }

    public function actionChangeStatusPorRetirar() {
        $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        $employee = Employee::model()->findByPk($model->id_employee);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "Por Retirar"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                $asunto= "Constancia de Trabajo Por Retirar";
                $html = $this->LayoutConstanciaPorRetirar($model->information, $employee); 

                 Yii::app()->email->sendEmail($html, $employee['email_company'], $asunto, null, ["rrhh@sacet.biz"]); 
//                echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }
        
    
    

    public function actionChangeStatusEntregada() {
        $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "Entregada"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }

    public function actionChangeStatusNoEntregada() {
           $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "No Entregada"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }


    public function actionChangeStatusNoProcede() {
              $idConstancy = $_GET['id'];
        $model = Constancy::model()->findByPk($idConstancy);
        if ($model != null) {
            $information = json_decode($model->information);
            foreach ($information->status as $key => $value ) {
                $information->status[$key]->active=false;
                if($information->status[$key]->name == "No Procede"){
                    $information->status[$key]->active=true;
                    $information->status[$key]->date_active=date('Y-m-d H:i');
                }
            }
            $model->information = json_encode($information);
            if ($model->save(false)) {
                    echo json_encode(['response' => "true", "val" => 0]);
            } else {
                echo json_decode(['response' => "false", "val" => 0]);
            }
        } else {
            echo json_encode(['request' => "false"]);
        }
    }

    public function actionMontConstaNumPay() {

        $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $arraTo = Array("January", "February", "March", "Abril", "May", "June", "July", "August", "September", "October", "November", "December");

        $mes = date('F', strtotime(str_replace($arraFrom, $arraTo, "May")));
        $sueldo = Yii::app()->numerosLetras->numtoletras(str_replace(",", ".", str_replace(".", "", $_GET['sueldo'])));

        $array['mes'] = $mes;
        $array['sueldo'] = $sueldo;
        echo json_encode($array);
    }

    public function actionAdminProofEmployee() {
        //$model = Employee::getEmployee(Yii::app()->user->id);
        $model = Users::model()->findByAttributes(['id' => Yii::app()->user->id]);
        $signersList = Employee::getSignersList();
        $positionSigner = json_encode(Psigner::getSignerPosition());
//        var_dump($position);exit();
        if ($model->id_rol == 4) {
            $query = "SELECT ('[' || string_agg((row_to_json(tt))::text,',') || ']')::json AS constancy
                    FROM 
                    (SELECT 
                        (SELECT
                             ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS abierta
                        FROM(
                           select tt.* from(
                                 SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                                 e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                                 e.email_company, 
                                                 (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                                 c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                                 c.information->>'dateAdmission' AS dateadmission,
                                                 c.information->'status'->8->>'observation' as observation,
                                                 c.id as id_solicitude, n.name as nationality, e.id,
                                                 c.information->'status'->1->>'contratacion' AS contratacion 
                                                 FROM employee e
                                                 INNER JOIN constancy c ON e.id=c.id_employee
                                                 INNER JOIN position_code pc ON e.id = pc.id_employee
                                                 INNER JOIN position p ON p.id = pc.id_position
                                                 INNER JOIN division d ON d.id = pc.id_division
                                                 INNER JOIN users u ON u.id_employee = e.id
                                                 INNER JOIN nationality n ON n.id=e.id_nationality
                                                 WHERE pc.end_date IS NULL AND e.first_name!='Vacante' 
                                                 ORDER BY date_solicitude desc
                                                 )tt
                                where status='Abierta')t)AS abierta,
                        (SELECT
                                ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS por_retirar
                         FROM(
                            select tt.* from(
                                SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                             e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                             e.email_company, 
                                             (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                             c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                             c.information->>'dateAdmission' AS dateadmission,
                                             c.information->'status'->8->>'observation' as observation,
                                             c.id as id_solicitude, n.name as nationality, e.id,
                                             c.information->'status'->1->>'contratacion' AS contratacion 
                                             FROM employee e
                                             INNER JOIN constancy c ON e.id=c.id_employee
                                             INNER JOIN position_code pc ON e.id = pc.id_employee
                                             INNER JOIN position p ON p.id = pc.id_position
                                             INNER JOIN division d ON d.id = pc.id_division
                                             INNER JOIN users u ON u.id_employee = e.id
                                             INNER JOIN nationality n ON n.id=e.id_nationality
                                             WHERE pc.end_date IS NULL AND e.first_name!='Vacante' 
                                             ORDER BY date_solicitude desc
                                             )tt
                                where status='Por Retirar')t
                        )AS por_retirar,
                        (SELECT
                                ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS entregado
                         FROM(
                            select tt.* from(
                                SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                             e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                             e.email_company, 
                                             (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                             c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                             c.information->>'dateAdmission' AS dateadmission,
                                             c.information->'status'->8->>'observation' as observation,
                                             c.id as id_solicitude, n.name as nationality, e.id,
                                             c.information->'status'->1->>'contratacion' AS contratacion 
                                             FROM employee e
                                             INNER JOIN constancy c ON e.id=c.id_employee
                                             INNER JOIN position_code pc ON e.id = pc.id_employee
                                             INNER JOIN position p ON p.id = pc.id_position
                                             INNER JOIN division d ON d.id = pc.id_division
                                             INNER JOIN users u ON u.id_employee = e.id
                                             INNER JOIN nationality n ON n.id=e.id_nationality
                                             WHERE pc.end_date IS NULL AND e.first_name!='Vacante' 
                                             ORDER BY date_solicitude desc
                                             )tt
                                where status='Entregada')t
                                        )AS entregada
                    )tt";
            $modelConstancy = Employee::model()->findBySql($query);

            $model = Employee::model()->findAllBySql("SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                                e.identity_card as cedula, p.spanish_name as position, e.date_entered as fechaingreso,e.id as id_employee , pc.start_date as fecha, d.name as division, 
                                                e.email_company, (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,
                                                c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude,
                                                c.information->>'dateAdmission' AS dateadmission,
                                                c.information->'status'->8->>'observation' as observation,
                                                c.id as id_solicitude, n.name as nationality, e.id,
                                                c.information->'status'->1->>'contratacion' AS contratacion 
                                                FROM employee e
                                                INNER JOIN constancy c ON e.id=c.id_employee
                                                INNER JOIN position_code pc ON e.id = pc.id_employee
                                                INNER JOIN position p ON p.id = pc.id_position
                                                INNER JOIN division d ON d.id = pc.id_division
                                                INNER JOIN nationality n ON n.id=e.id_nationality
                                                WHERE pc.end_date IS NULL AND e.first_name!='Vacante'"); // AND c.information->'status'->7->>'active'!='true'"


            foreach ($model as $value) {
                $val = $value->cedula;

                $rest = strlen($val) / 3;
                $value1 = "";
                for ($i = 1; $i <= strlen($val); $i++) {
                    if ($i % 3 == 0 && $i != strlen($val)) {
                        $value1 = "." . substr($val, -$i, 1) . $value1;
                    } else {
                        $value1 = substr($val, -$i, 1) . $value1;
                    }
                }
                $value->cedula = $value1;
            }
            
            
            $this->render('adminProofEmployee', array('model' => $model, "constancy" => json_decode($modelConstancy->constancy)[0], "signersList" => $signersList,'positionSigner' => json_decode($positionSigner)));
        } else {
            $query = "SELECT ('[' || string_agg((row_to_json(tt))::text,',') || ']')::json AS constancy
                    FROM 
                    (SELECT 
                        (SELECT
                             ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS abierta
                        FROM(
                           select tt.* from(
                                 SELECT u.id, (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                                 e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                                 e.email_company, 
                                                 (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                                 c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                                 c.information->>'dateAdmission' AS dateadmission,
                                                 c.information->'status'->8->>'observation' as observation,
                                                 c.id as id_solicitude, n.name as nationality, e.id,
                                                 c.information->'status'->1->>'contratacion' AS contratacion 
                                                 FROM employee e
                                                 INNER JOIN constancy c ON e.id=c.id_employee
                                                 INNER JOIN position_code pc ON e.id = pc.id_employee
                                                 INNER JOIN position p ON p.id = pc.id_position
                                                 INNER JOIN division d ON d.id = pc.id_division
                                                 INNER JOIN users u ON u.id_employee = e.id
                                                 INNER JOIN nationality n ON n.id=e.id_nationality
                                                 WHERE pc.end_date IS NULL AND e.first_name!='Vacante'  AND u.id=" . Yii::app()->user->id."
                                                 ORDER BY date_solicitude desc
                                                 )tt
                                where status='Abierta')t)AS abierta,
                        (SELECT
                                ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS por_retirar
                         FROM(
                            select tt.* from(
                                SELECT u.id, (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                             e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                             e.email_company, 
                                             (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                             c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                             c.information->>'dateAdmission' AS dateadmission,
                                             c.information->'status'->8->>'observation' as observation,
                                             c.id as id_solicitude, n.name as nationality, e.id,
                                             c.information->'status'->1->>'contratacion' AS contratacion 
                                             FROM employee e
                                             INNER JOIN constancy c ON e.id=c.id_employee
                                             INNER JOIN position_code pc ON e.id = pc.id_employee
                                             INNER JOIN position p ON p.id = pc.id_position
                                             INNER JOIN division d ON d.id = pc.id_division
                                             INNER JOIN users u ON u.id_employee = e.id
                                             INNER JOIN nationality n ON n.id=e.id_nationality
                                             WHERE pc.end_date IS NULL AND e.first_name!='Vacante'  AND u.id=" . Yii::app()->user->id."
                                             ORDER BY date_solicitude desc
                                             )tt
                                where status='Por Retirar')t
                        )AS por_retirar,
                        (SELECT
                                ('[' || string_agg((row_to_json(t))::text,',') || ']')::json AS entregado
                         FROM(
                            select tt.* from(
                                SELECT u.id,  (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                             e.identity_card as cedula, p.spanish_name as position,e.date_entered as fechaingreso, pc.start_date as fecha, d.name as division, 
                                             e.email_company, 
                                             (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,

                                             c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, e.id as id_employee,
                                             c.information->>'dateAdmission' AS dateadmission,
                                             c.information->'status'->8->>'observation' as observation,
                                             c.id as id_solicitude, n.name as nationality, e.id,
                                             c.information->'status'->1->>'contratacion' AS contratacion 
                                             FROM employee e
                                             INNER JOIN constancy c ON e.id=c.id_employee
                                             INNER JOIN position_code pc ON e.id = pc.id_employee
                                             INNER JOIN position p ON p.id = pc.id_position
                                             INNER JOIN division d ON d.id = pc.id_division
                                             INNER JOIN users u ON u.id_employee = e.id
                                             INNER JOIN nationality n ON n.id=e.id_nationality
                                             WHERE pc.end_date IS NULL AND e.first_name!='Vacante' AND u.id=" . Yii::app()->user->id." 
                                             ORDER BY date_solicitude desc
                                             )tt
                                where status='Entregada')t
                                        )AS entregada
                    )tt";
            $modelConstancy = Employee::model()->findBySql($query);
            $model = Employee::model()->findAllBySql("SELECT (initcap(e.first_name)||' '||initcap(e.second_name) ||' '|| initcap(e.last_name) ||' '|| initcap(e.second_last_name)) as nombre, 
                                                e.identity_card as cedula,e.id as id_employee ,p.spanish_name as position, pc.start_date as fecha, d.name as division, 
                                                e.email_company, (SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') AS status,
                                                c.information->>'addressed' AS addressed, c.information->>'reason' AS reason, c.date_solicitude AS date_solicitude, 
                                                c.information->>'dateAdmission' AS dateadmission,
                                                c.information->'status'->8->>'observation' as observation,
                                                c.id as id_solicitude, n.name as nationality, e.id,
                                                c.information->'status'->1->>'contratacion' AS contratacion 
                                                FROM employee e
                                                INNER JOIN constancy c ON e.id=c.id_employee
                                                INNER JOIN position_code pc ON e.id = pc.id_employee
                                                INNER JOIN position p ON p.id = pc.id_position
                                                INNER JOIN division d ON d.id = pc.id_division
                                                INNER JOIN users u ON u.id_employee = e.id
                                                INNER JOIN nationality n ON n.id=e.id_nationality
                                                WHERE pc.end_date IS NULL AND e.first_name!='Vacante' AND u.id=" . Yii::app()->user->id." --AND c.information->'status'->7->>'active'!='true'
                                                ORDER BY date_solicitude desc");
//            exit();
            foreach ($model as $value) {
                $val = $value->cedula;

                $rest = strlen($val) / 3;
                $value1 = "";
                for ($i = 1; $i <= strlen($val); $i++) {
                    if ($i % 3 == 0 && $i != strlen($val)) {
                        $value1 = "." . substr($val, -$i, 1) . $value1;
                    } else {
                        $value1 = substr($val, -$i, 1) . $value1;
                    }
                }
                $value->cedula = $value1;
            }
           
            $this->render('adminProofEmployee', array('model' => $model, "constancy" => json_decode($modelConstancy->constancy)[0],'positionSigner' => json_decode($positionSigner)));
        }
    }

    public function actionSessionPayRollEmployee() {
//       $id=$_GET['id'];
        $id = $_GET['id'] . "_session";
        Yii::app()->getSession()->add($id, $_GET['sueldo']);
//       $variableSession= Yii::app()->session[$id]=$_GET['sueldo'];

        echo json_encode($id);
    }

    public function actionCountSolicitude() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $contancys = Constancy::model()->findAllBySql("SELECT c.* FROM constancy c INNER JOIN employee e ON e.id=c.id_employee WHERE c.information->'status'->7->>'active'='false' AND e.id=" . $_GET['id']);
            echo json_encode(['response' => 'true', 'val' => '0', 'count' => count($contancys)]);
        } else {
            echo json_encode(['request' => 'false', 'val' => '0']);
        }

//(SELECT status0->>'name' FROM json_array_elements(c.information->'status') AS status0 WHERE status0->>'active'='true') IN('Abierta','En Proceso','Confirmada','Impresa','Por Retirar','Entregada','No Entregada')
    }
    
   public function actionAdminPlanillas()
    {
         $this->render('adminPlanillas');
    }

    public function actionGetEmployees(){
        
        $array = Employee::getEmployees();
        $array2 = [];
        foreach ($array as $value) {
            $employee["id"] = $value->id;
            $employee["text"] =  $value->first_name ." ". $value->last_name;      
            $array2[] = $employee;
        }
        echo json_encode($array2);
    }
    public function LayoutConstancia($employee, $date, $get)
    {
        $html= "<h4>El Colaborador: ".$employee['first_name'] ." ". $employee['last_name']."</h4>
                    <p>Realizó una solicitud de Constancia de Trabajo, con los siguientes datos:</p>
                    <table style = 'border-collapse:collapse;border-spacing:0;border-color:#999;'>
                        <tr style = 'border-style:solid;border-width:0px;border-color:#999;color:#fff;background-color:#3598dc;border-bottom-width:1px;'>
                            <th style = 'font-family:Arial, sans-serif; font-size:14px; font-weight:normal; padding:10px 15px;'>
                                Cédula
                            </th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>
                                Fecha de Solicitud
                            </th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>
                                Motivo
                            </th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>
                                A quien va Dirigida
                            </th>
                        </tr>
                        <tr style= 'border-style:solid;border-width:0px;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;'>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>"
                               .$get['identity_email']."
                            </th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>"
                                . $date .
                            "</th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>"
                                . $get['reason_email']."
                            </th>
                            <th style = 'font-family:Arial, sans-serif;font-size:14px; font-weight:normal; padding:10px 15px;'>"
                                . $get['addressed'] ."
                            </th>
                        </tr>
                    </table>";
        return $html;
    }
        public function LayoutConstanciaPorRetirar($constancyInformation , $employee)
    {   
            $constancyInformation = json_decode($constancyInformation);
//            var_dump (date("Y-m-d",strtotime($constancyInformation->status[0]->date_active))); exit();
          $html= "<h4>El Colaborador: ".$employee['first_name'] ." ". $employee['last_name']."</h4>
                    <p>Buen día apreciado Colaborador,</p>
                    <p>Después de saludarte, hago de tu conocimiento que ya  puedes pasar por el Departamento de RRHH a retirar las Constancias Laborales solicitadas.</p>
                    <p>Saludos.-</p>";
                  
        return $html;
}
    public function actionSaveSelectionSigner()
    {  
        $model = Psigner::model()->findBySql("SELECT * FROM psigner WHERE end_date IS NULL");

        if ($model != null) {
            $model->end_date = date('Y-m-d');
            if ($model->save()) {
                $modelNew = new Psigner();
                $modelNew->id_employee = $_GET['idEmployee'];
                $modelNew->start_date =date('Y-m-d') ;
                if($modelNew-> save()){
                    $resultSigner = Psigner::getSignerPosition (); 
                    echo json_encode($resultSigner);
                }
            }
        }else{
            $model = new Psigner();
            $model->id_employee = $_GET['idEmployee'];
            $model->start_date = date('Y-m-d');
            if($model->save()){
                $resultSignerNew = Psigner::getSignerPosition ();
                echo json_encode($resultSignerNew);
            }
        }
    } 
    public function actionGetDataSigner()
    {  
        $resultSignerNew = Psigner::getSignerPosition ();
        echo json_encode($resultSignerNew);
    } 
    
}


