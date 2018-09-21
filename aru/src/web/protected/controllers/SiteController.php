<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $navegador = self::Detect();
        $navi = $navegador["browser"];
        //VALIDACION PARA LOS NAVEGADORES
        if($navi == 'CHROME' || $navi == 'SAFARI')
        {
            $this->layout = '//layouts/main';
            if(!Yii::app()->user->isGuest)
            {
                $this->redirect('eventEmployee/Create');
            }
            else
            {   
                //header('Location: http://etelixribela.local/');
                header('Location: http://'.SERVER_NAME_RIBELA.'/');
            }
        }
        else
        {
            $this->layout = '//layouts/emptyLayout';
            $this->render('browserInvalido');
        }
    }
 
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error = Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes = $_POST['ContactForm'];
            if($model->validate())
            {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        
        if(!Yii::app()->user->isGuest)
        {
            $this->render('index');
        }
        else
        {
            //header('Location: http://etelixribela.local/');
            header('Location: http://'.SERVER_NAME_RIBELA.'/');
        }
    }
       public function actionAutoLogin(){
            $id=$_GET['id'];
            
            $model = new LoginForm();
            
            $user = Users::model()->findBySql("SELECT u.* FROM users u WHERE (SELECT s.data->>'username' FROM sys_session s WHERE s.id=$id AND s.data->>'status'='true')=u.username");
            
            if ($model->loginAutoLogin($user)){
                $this->redirect('/eventEmployee/Create');
            }else{
                //header('Location: http://etelixribela.local/');
                header('Location: http://'.SERVER_NAME_RIBELA.'/');
            }   
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        //header('Location: http://etelixribela.local/site/logout');
        header('Location: http://'.SERVER_NAME_RIBELA.'/site/logout');
        //$this->redirect(Yii::app()->homeUrl);
    }

    public function actionBlockScreen()
    {
        $model = new LoginForm;
        if(isset($_POST['Users']))
        {
            $model->attributes = $_POST['Users'];
            if($model->validate() && $model->login())
                $this->redirect('/EventEmployee/Create');
        }

        $datos = $_GET['idUser'];
        if($datos != NULL)
        {
            Yii::app()->user->logout();
            $user = Users::getUsers($datos);
            $model = Employee::getEmployee($datos);
            $this->render('lockEmployee', array('model' => $model, 'user' => $user));
        }
        else
        {
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->homeUrl);
        }
    }

    public static function CreateMenu($id_rol)
    {
        
        $Employee=Employee::getEmployee(Yii::app()->user->id);
        $idEmployee=$Employee->id;

        if($idEmployee=='356' || $idEmployee=='429' ){$aproConsta="<li><a href='/employee/adminProofEmployee'>Aprobación de Constancias</a></li>";} else{$aproConsta='';}
        switch($id_rol)
        {
            case 1:
                $option_menu = "<li id='create' name='create'>
                                                   <a href='/EventEmployee/Create'>
                                                   <i class='icon-pointer'></i> 
                                                   <span class='title'>Declarar</span>
                                                   <span class='selected'></span>
                                                   </a>
                          </li>
                           <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                                 <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <li>
                                                <a href='/Employee/holiday'>
                                                Ver Días Festivos</a>
                                              </li>
                                              
                                             <!--<li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>-->
                                            
                                             
                                       </ul>
                         </li>
                        
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         <!--<li id='directorioTefl' name='directorioTefl'>
                                                   <a href='/Employee/AdmiControllers'>
                                                   <i class='icon-map-marker'></i> 
                                                   <span class='title'>Administrar</span>
                                                   </a>
                          </li>-->
                          <li id='department' name='department'>
                                                   <a href='/Hr/department/index'>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Departamento</span>
                                                   </a>
                          </li>
                          <li id='position' name='position'>
                                                   <a href='/Hr/position'>
                                                   <i class='icon-briefcase'></i> 
                                                   <span class='title'>Cargo</span>
                                                   </a>
                          </li>
                         <!--<li id='codigoposicion' name='codigoposicion'>
                                                   <a href=''>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Código de Posición</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/positionCode/'>
                                                 Crear Cp</a>
                                              </li>
                                              <li>
                                                 <a href='/positionCode/AdminPositionCode'>
                                                Administrar Cp</a>
                                              </li>
                                              
                                       </ul>                  
                          </li>-->
                          
                         <!--<li id='division' name='division'>
                                                   <a href=''>
                                                   <i class='fa fa-th-large'></i> 
                                                   <span class='title'>División</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/division/viewDivision'>
                                                 Divisiones</a>
                                              </li>
                                              
                                              
                                       </ul>                  
                          </li>-->
                           <!--<li id='cargoemploye' name='cargoemployee'>
                                   <a href=''>
                                   <i class='icon-briefcase'></i> 
                                   <span class='title'>Cargo</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                         <a href='/position/viewPosition'>
                                         Cargos</a>
                                      </li>
                               </ul>                  
                          </li>-->
                          </li>
                           <!-- <li id='AdminPay' name='AdminPay'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Remuneración</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                      </li>
                                     
                               </ul>
                          </li>-->
                           <!-- <li id='horariosEmpleados' name='horariosEmpleados'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Horarios</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                   
                               <ul class='sub-menu'>
                                       <li>
                                          <a href=''>Creación de Horarios</a>
                                      </li>
                                       <li>
                                          <a href=''>Asignación de Horarios</a>
                                      </li>
                                       <li>
                                          <a href=''>Horarios Especiales/Guardías</a>
                                      </li>
                                    
                               </ul>
                          </li>-->
                          
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                            <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                            </li>
                                            <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                            </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <li>
                                                <a href='/Users/Create'>Crear Usuario</a>
                                              </li>
                                       </ul>
                            </li>
                            ";
                return $option_menu;
                break;
            case 2: case 12: case 13: case 14: case 15: case 16: case 17:
                $option_menu = "
                        <li id='create' name='create'>
                           <a href='/EventEmployee/Create'>
                           <i class='icon-map-marker'></i> 
                           <span class='title'>Declarar</span>
                           <span class='selected'></span>
                           </a>
                        </li>
                         <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                              <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <!-- <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Días Festivos</a>
                                              </li>-->
                                            
                                               
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         
                        <!-- <li id='AdminPay' name='AdminPay'>
                               <a href=''>
                               <i class='fa fa-money'></i> 
                               <span class='title'>Remuneración</span> 
                               <span class='arrow '></span>
                               <span class='selected'></span>
                               </a>
                              
                            <ul class='sub-menu'>
                                  <li>
                                      <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                  </li>
                            </ul>  
                           
                        </li>-->
                             
                        <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                                Mis constancias</a>
                                              </li>
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                                Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                            ";
                return $option_menu;

                break;

            case 3:
                $option_menu = "
                        <li id='create' name='create'>
                           <a href='/EventEmployee/Create'>
                           <i class='icon-map-marker'></i> 
                           <span class='title'>Declarar</span>
                           <span class='selected'></span>
                           </a>
                        </li>
                        
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <!--<a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <!--<ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>-->
                            </li>

                            ";
                return $option_menu;
                break;

            case 4:
                $option_menu = "<li id='create' name='create'>
                                                   <a href='/EventEmployee/Create'>
                                                   <i class='icon-pointer'></i> 
                                                   <span class='title'>Declarar</span>
                                                   <span class='selected'></span>
                                                   </a>
                          </li>
                           <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                               <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                             <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                               Ver Cumpleaños</a>
                                              </li>
                                            <li>
                                                <a href='/Employee/holiday'>
                                                Ver Días Festivos</a>
                                              </li>
                                              
                                             <li>
                                                <a href='/Hr/employee'>
                                                Agregar Colaborador <span style='font-size:65%'>(V. Beta)</span></a>
                                              </li>
                                             
                                              
                                             
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         <!--<li id='controladores' name='controladores'>
                                                   <a href='/Employee/AdmiControllers'>
                                                   <i class='icon-map-marker'></i> 
                                                   <span class='title'>Administrar</span>
                                                   
                                                   </a>
                          </li>-->
                          <li id='department' name='department'>
                                                   <a href='/Hr/department/index'>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Departamento <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                          <li id='position' name='position'>
                                                   <a href='/Hr/position'>
                                                   <i class='icon-briefcase'></i> 
                                                   <span class='title'>Cargo <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                         <!--<li id='codigoposicion' name='codigoposicion'>
                                                   <a href=''>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Código de Posición</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/positionCode/'>
                                                 Crear Cp</a>
                                              </li>
                                              <li>
                                                 <a href='/positionCode/AdminPositionCode'>
                                                Administrar Cp</a>
                                              </li>
                                              
                                       </ul>                  
                          </li>-->
                          
                            <!--<li id='division' name='division'>
                                                   <a href=''>
                                                   <i class='fa fa-th-large'></i> 
                                                   <span class='title'>División</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/division/viewDivision'>
                                                 Divisiones</a>
                                              </li>
                                              
                                              
                                       </ul>                  
                          </li>-->
                            <!--<li id='cargoemploye' name='cargoemployee'>
                                   <a href=''>
                                   <i class='icon-briefcase'></i> 
                                   <span class='title'>Cargo</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                         <a href='/position/viewPosition'>
                                         Cargos</a>
                                      </li>
                               </ul>                  
                          </li>-->

                         <!--
                          <li id='AdminPay' name='AdminPay'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Remuneración</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                      </li>
                                      <li>
                                         <a href='/PayRoll/AdminPayRoll'>
                                         Nómina Empleado</a>
                                      </li>
                                    
                               </ul>
                          </li>-->
                            <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa fa-file-text-o'></i> 
                                   <span class='title'>Reportes</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                        <li>
                                          <a href='/report/employee/allHourEmployee'>Tablero de Horas Trabajadas</a>
                                      </li>
                                       <li>
                                          <a href='/report/employee/reportDeclareDay'>Reporte de Jornada Laboral</a>
                                      </li>
                               </ul>
                          </li>
                              <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa  fa-clock-o'></i> 
                                   <span class='title'>Horarios</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/EmployeeHour/EmployeeHours'>Asignación de horarios</a>
                                      </li>
                               </ul>
                          </li>
                          
                          

                              <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                              ".$aproConsta."
                                              <li>
                                                <a href='/employee/adminPlanillas'>
                                               Formatos</a>
                                              </li>
                                       </ul>
                            </li>
                          <!-- <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <li>
                                                <a href='/Users/Create'>Crear Usuario</a>
                                              </li>
                                       </ul>
                            </li>-->
                       
                            ";
                return $option_menu;
                break;
            case 5:
                $option_menu = "<li id='create' name='create'>
                                                   <a href='/EventEmployee/Create'>
                                                   <i class='icon-pointer'></i> 
                                                   <span class='title'>Declarar</span>
                                                   <span class='selected'></span>
                                                   </a>
                          </li>
                           <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                               <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                             <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                                <!--<li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Días Festivos</a>
                                              </li>-->
                                               <li>
                                                <a href='/Hr/employee'>
                                                Agregar Colaborador <span style='font-size:65%'>(V. Beta)</span></a>
                                              </li>
                                            
                                             
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         <!--<li id='controladores' name='controladores'>
                                                   <a href='/Employee/AdmiControllers'>
                                                   <i class='icon-map-marker'></i> 
                                                   <span class='title'>Administrar</span>
                                                   
                                                   </a>
                          </li>-->
                          <li id='department' name='department'>
                                                   <a href='/Hr/department/index'>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Departamento <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                          <li id='position' name='position'>
                                                   <a href='/Hr/position'>
                                                   <i class='icon-briefcase'></i> 
                                                   <span class='title'>Cargo <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                         <!--<li id='codigoposicion' name='codigoposicion'>
                                                   <a href=''>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Código de Posición</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/positionCode/'>
                                                 Crear Cp</a>
                                              </li>
                                              <li>
                                                 <a href='/positionCode/AdminPositionCode'>
                                                Administrar Cp</a>
                                              </li>
                                              
                                       </ul>                  
                          </li>-->
                          
                            <!--<li id='division' name='division'>
                                                   <a href=''>
                                                   <i class='fa fa-th-large'></i> 
                                                   <span class='title'>División</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/division/viewDivision'>
                                                 Divisiones</a>
                                              </li>
                                              
                                              
                                       </ul>                  
                          </li>-->
                            <!--<li id='cargoemploye' name='cargoemployee'>
                                   <a href=''>
                                   <i class='icon-briefcase'></i> 
                                   <span class='title'>Cargo</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                         <a href='/position/viewPosition'>
                                         Cargos</a>
                                      </li>
                               </ul>                  
                          </li>-->
                    <!--
                            <li id='AdminPay' name='AdminPay'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Remuneración</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                      </li>
                                      <li>
                                         <a href='/PayRoll/AdminPayRoll'>
                                         Nómina Empleado</a>
                                      </li>
                               </ul>
                             
                          </li>-->
                          <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa fa-file-text-o'></i> 
                                   <span class='title'>Reportes</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/report/employee/allHourEmployee'>Tablero de Horas Trabajadas</a>
                                      </li>
                                       <li>
                                          <a href='/report/employee/reportDeclareDay'>Reporte de Jornada Laboral</a>
                                      </li>
                               </ul>
                          </li>
                            <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa  fa-clock-o'></i> 
                                   <span class='title'>Horarios</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/EmployeeHour/EmployeeHours'>Asignación de horarios</a>
                                      </li>
                                      
                               </ul>
                          </li>
                       



                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                             <li>
                                                <a href='/employee/adminProofEmployee'>
                                                Aprobación de Constancia</a>
                                              </li>
                                             <li>
                                                <a href='/employee/adminPlanillas'>
                                                Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                          <!-- <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>
                            </li>-->
                            
                       
                            ";
                return $option_menu;
                break;
            case 6:
                $option_menu = "<li id='create' name='create'>
                                                   <a href='/EventEmployee/Create'>
                                                   <i class='icon-pointer'></i> 
                                                   <span class='title'>Declarar</span>
                                                   <span class='selected'></span>
                                                   </a>
                          </li>
                           <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                               <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                             <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                               Ver Cumpleaños</a>
                                              </li>
                                               <li>
                                                <a href='/Hr/employee'>
                                                Agregar Colaborador <span style='font-size:65%'>(V. Beta)</span></a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         <!--<li id='controladores' name='controladores'>
                                                   <a href='/Employee/AdmiControllers'>
                                                   <i class='icon-map-marker'></i> 
                                                   <span class='title'>Administrar</span>
                                                   
                                                   </a>
                          </li>-->
                          <li id='department' name='department'>
                                                   <a href='/Hr/department/index'>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Departamento <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                          <li id='position' name='position'>
                                                   <a href='/Hr/position/create'>
                                                   <i class='icon-briefcase'></i> 
                                                   <span class='title'>Cargo <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                         <!--<li id='codigoposicion' name='codigoposicion'>
                                                   <a href=''>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Código de Posición</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/positionCode/'>
                                                 Crear Cp</a>
                                              </li>
                                              <li>
                                                 <a href='/positionCode/AdminPositionCode'>
                                                Administrar Cp</a>
                                              </li>
                                              
                                       </ul>                  
                          </li>-->
                          
                            <!--<li id='division' name='division'>
                                                   <a href=''>
                                                   <i class='fa fa-th-large'></i> 
                                                   <span class='title'>División</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/division/viewDivision'>
                                                 Divisiones</a>
                                              </li>
                                              
                                              
                                       </ul>                  
                          </li>-->
                            <!--<li id='cargoemploye' name='cargoemployee'>
                                   <a href=''>
                                   <i class='icon-briefcase'></i> 
                                   <span class='title'>Cargo</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                         <a href='/position/viewPosition'>
                                         Cargos</a>
                                      </li>
                               </ul>                  
                          </li>-->
                    <!--
                            <li id='AdminPay' name='AdminPay'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Remuneración</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                   <!--
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                      </li>
                                      <li>
                                         <a href='/PayRoll/AdminPayRoll'>
                                         Nómina Empleado</a>
                                      </li>
                                    
                               </ul>
                               -->
                          </li>
                            <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa fa-file-text-o'></i> 
                                   <span class='title'>Reportes</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                          <a href='/report/employee/allHourEmployee'>Tablero de Horas Trabajadas</a>
                                      </li>
                                       <li>
                                          <a href='/report/employee/reportDeclareDay'>Reporte de Jornada Laboral</a>
                                      </li>
                               </ul>
                          </li>
                            <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa  fa-clock-o'></i> 
                                   <span class='title'>Horarios</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                           <a href='/EmployeeHour/EmployeeHours'>Asignación de horarios</a>
                                      </li>
                               </ul>
                          </li>
                          

                          <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                               Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                          <!-- <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>
                            </li>-->

                            ";
                return $option_menu;
                break;

            case 7:
                $option_menu = "<li id='create' name='create'>
                                                   <a href='/EventEmployee/Create'>
                                                   <i class='icon-pointer'></i> 
                                                   <span class='title'>Declarar</span>
                                                   <span class='selected'></span>
                                                   </a>
                          </li>
                           <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                                 <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <li>
                                                <a href='/Employee/holiday'>
                                                Ver Días Festivos</a>
                                              </li>
                                              
                                            <!-- <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             <li>
                                                <a href='/Employee/DirectoryPark'>
                                                Directorio de Estacionamiento</a>
                                              </li>-->
                                            
                                             
                                       </ul>
                         </li>
                          <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         <!--<li id='controladores' name='controladores'>
                                                   <a href='/Employee/AdmiControllers'>
                                                   <i class='icon-map-marker'></i> 
                                                   <span class='title'>Administrar</span>
                                                   
                                                   </a>
                          </li>-->
                          <li id='department' name='department'>
                                                   <a href='/Hr/department/index'>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Departamento <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                          <li id='position' name='position'>
                                                   <a href='/Hr/position/create'>
                                                   <i class='icon-briefcase'></i> 
                                                   <span class='title'>Cargo <span style='font-size:65%'>(V. Beta)</span></span>
                                                   </a>
                          </li>
                         <!--<li id='codigoposicion' name='codigoposicion'>
                                                   <a href=''>
                                                   <i class='fa fa-users'></i> 
                                                   <span class='title'>Código de Posición</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/positionCode/'>
                                                 Crear Cp</a>
                                              </li>
                                              <li>
                                                 <a href='/positionCode/AdminPositionCode'>
                                                Administrar Cp</a>
                                              </li>
                                              
                                       </ul>                  
                          </li>-->
                          
                         <!--<li id='division' name='division'>
                                                   <a href=''>
                                                   <i class='fa fa-th-large'></i> 
                                                   <span class='title'>División</span> 
                                                   <span class='arrow '></span>
                                                   <span class='selected'></span>
                                                   </a>
                                                   
                                               <ul class='sub-menu'>
                                              <li>
                                                 <a href='/division/viewDivision'>
                                                 Divisiones</a>
                                              </li>
                                              
                                              
                                       </ul>                  
                          </li>-->
                           <!--<li id='cargoemploye' name='cargoemployee'>
                                   <a href=''>
                                   <i class='icon-briefcase'></i> 
                                   <span class='title'>Cargo</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                         <a href='/position/viewPosition'>
                                         Cargos</a>
                                      </li>
                               </ul>                  
                          </li>
                          </li>-->
                           <!-- <li id='AdminPay' name='AdminPay'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Remuneración</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                       <li>
                                          <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                      </li>
                                     
                               </ul>
                          </li>-->
                           <!-- <li id='horariosEmpleados' name='horariosEmpleados'>
                                   <a href=''>
                                   <i class='fa fa-money'></i> 
                                   <span class='title'>Horarios</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                   
                               <ul class='sub-menu'>
                                       <li>
                                          <a href=''>Creación de Horarios</a>
                                      </li>
                                       <li>
                                          <a href=''>Asignación de Horarios</a>
                                      </li>
                                       <li>
                                          <a href=''>Horarios Especiales/Guardías</a>
                                      </li>
                                    
                               </ul>
                          </li>-->
                          
                          <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                               Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                          <!-- <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>
                            </li>-->
                            ";
                return $option_menu;
                break;
            
            case 8:
                $option_menu = "
                        <li id='create' name='create'>
                           <a href='/EventEmployee/Create'>
                           <i class='icon-map-marker'></i> 
                           <span class='title'>Declarar</span>
                           <span class='selected'></span>
                           </a>
                        </li>
                         <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                              <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <!-- <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Días Festivos</a>
                                              </li>-->
                                             <!-- <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>-->
                                               
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         
                        <!-- <li id='AdminPay' name='AdminPay'>
                               <a href=''>
                               <i class='fa fa-money'></i> 
                               <span class='title'>Remuneración</span> 
                               <span class='arrow '></span>
                               <span class='selected'></span>
                               </a>
                              
                            <ul class='sub-menu'>
                                  <li>
                                      <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                  </li>
                            </ul>  
                           
                        </li>-->
                        
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                               Mis constancias</a>
                                              </li>
                                              
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                              Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                          <!-- <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>
                            </li>-->

                            ";
                return $option_menu;

                break;
             case 9:
                $option_menu = "
                        <li id='create' name='create'>
                           <a href='/EventEmployee/Create'>
                           <i class='icon-map-marker'></i> 
                           <span class='title'>Declarar</span>
                           <span class='selected'></span>
                           </a>
                        </li>
                         <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                              <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <!-- <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Días Festivos</a>
                                              </li>-->
                                             <!-- <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>-->
                                               
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         
                        <!-- <li id='AdminPay' name='AdminPay'>
                               <a href=''>
                               <i class='fa fa-money'></i> 
                               <span class='title'>Remuneración</span> 
                               <span class='arrow '></span>
                               <span class='selected'></span>
                               </a>
                              
                            <ul class='sub-menu'>
                                  <li>
                                      <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                  </li>
                            </ul>  
                           
                        </li>-->
                        
                           <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                              <!-- <li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/soon'>
                                               Mis constancias</a>
                                              </li>
                                             
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                               Formatos</a>
                                              </li>
                                             
                                       </ul>
                            </li>
                               <li id='report' name='report'>
                                   <a href=''>
                                   <i class='fa fa-file-text-o'></i> 
                                   <span class='title'>Reportes</span> 
                                   <span class='arrow '></span>
                                   <span class='selected'></span>
                                   </a>
                                <ul class='sub-menu'>
                                      <li>
                                          <a href='/report/employee/allHourEmployee'>Tablero de Horas Trabajadas</a>
                                      </li>
                                       <li>
                                          <a href='/report/employee/reportDeclareDay'>Reporte de Jornada Laboral</a>
                                      </li>
                               </ul>
                            </li>

                           <!--<li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-gears'></i> 
                                           <span class='title'>Configuración</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                               <!--<li>
                                                <a href='/employee/soon'></a>
                                              </li>-->
                                       </ul>
                            </li>-->
                            ";
                return $option_menu;
                break;
            
             case 10:
                $option_menu = "
                        <li id='create' name='create'>
                           <a href='/EventEmployee/Create'>
                           <i class='icon-map-marker'></i> 
                           <span class='title'>Declarar</span>
                           <span class='selected'></span>
                           </a>
                        </li>
                         <li id ='employee'>
                                   <a href='javascript:;'>
                                           <i class='icon-user'></i> 
                                           <span class='title'>Empleados</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              <li>
                                                 <a href='/Employee/infoEmployee'>
                                                 Mi Perfil</a>
                                              </li>
                                              <li>
                                                 <a href='/report/Employee/employeeHours'>
                                                Mi Jornada</a>
                                              </li>
                                              <li>
                                                 <a href='/Employee/SearchEmployee'>
                                                Ver Empleados</a>
                                              </li>
                                                <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Cumpleaños</a>
                                              </li>
                                               <!-- <li>
                                                 <a href='/Employee/birthDate'>
                                                Ver Días Festivos</a>
                                              </li>-->
                                             <!-- <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>-->
                                               
                                       </ul>
                         </li>
                            <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa fa-book'></i> 
                                           <span class='title'>Directorios</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <li>
                                                <a href='/Employee/DirectoryPhone'>
                                                Directorio Telef&oacute;nico</a>
                                              </li>
                                             
                                       </ul>
                         </li>
                         
                        <!-- <li id='AdminPay' name='AdminPay'>
                               <a href=''>
                               <i class='fa fa-money'></i> 
                               <span class='title'>Remuneración</span> 
                               <span class='arrow '></span>
                               <span class='selected'></span>
                               </a>
                              
                            <ul class='sub-menu'>
                                  <li>
                                      <a href='/PayRoll/employeePayRoll'>Mi Nómina</a>
                                  </li>
                            </ul>  
                           
                        </li>-->
                             
                        <li id ='directorioTefl'>
                                   <a href='javascript:;'>
                                           <i class='fa fa-folder-open-o'></i> 
                                           <span class='title'>Solicitudes</span>
                                           <span class='arrow '></span>
                                           <span class='selected'></span>
                                   </a>
                                       <ul class='sub-menu'>
                                              
                                               <!--<li>
                                                <a href='/employee/soon'>
                                                Permisos</a>
                                              </li>
                                               <li>
                                                <a href='/employee/soon'>
                                                Vacaciones</a>
                                              </li>-->
                                              
                                               <li>
                                                <a href='/employee/proofEmployee'>
                                                Mis constancias</a>
                                              </li>
                                               <li>
                                                <a href='/employee/adminPlanillas'>
                                                Formatos</a>
                                              </li>
                                              
                                             
                                       </ul>
                            </li>
                            ";
                return $option_menu;

                break;
        }
    }

    public function actionExcel()
    {
        $files = array();
        if($_GET['table'] == 'adminPositionCodeActive' || $_GET['table'] == 'adminPositionCodeInactives')
        {
            $files['positionCode']['name'] = $_GET['name'];
            $files['positionCode']['body'] = Yii::app()->report->newPositionCode($_GET['name'],NULL);
        }
        

        foreach($files as $key => $file)
        {
            $this->genExcel($file['name'], $file['body'], true);
        }
    }

    public function actionSendEmail()
    {
        $correo = UserIdentity::getEmail();
        $topic = $_GET['name'];
        $files = array();
        if($_GET['table'] == 'adminPositionCodeActive' || $_GET['table'] == 'adminPositionCodeInactives')
        {
            $files['positionCode']['name'] = $_GET['name'];
            $files['positionCode']['body'] = Yii::app()->report->adminPositionCode($_GET['ids'], $_GET['name'], false);
            $files['positionCode']['excel'] = Yii::app()->report->adminPositionCode($_GET['ids'], $_GET['name'], true);
            $files['positionCode']['dir'] = Yii::getPathOfAlias('webroot.adjuntos') . DIRECTORY_SEPARATOR . $files['positionCode']['name'] . ".xls";
        }

        foreach($files as $key => $file)
        {
            $this->genExcel($file['name'], utf8_encode($file['excel']), false);
            Yii::app()->email->sendEmail($file['body'], $correo, $topic, $file['dir']);
        }
    }

    public function actionPrint()
    {
        if($_GET['table'] == 'adminPositionCodeActive' || $_GET['table'] == 'adminPositionCodeInactives')
        {
            echo Yii::app()->report->adminPositionCode($_GET['ids'], $_GET['name'], false);
        }
    }

    public function genExcel($name, $html, $salida = true)
    {
        if($salida)
        {
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename={$name}.xls");
            header("Pragma: cache");
            header("Expires: 0");
            echo $html;
        }
        else
        {
            $ruta = Yii::getPathOfAlias('webroot.adjuntos') . DIRECTORY_SEPARATOR;
            $fp = fopen($ruta . "$name.xls", "w+");
            $cuerpo = "<!DOCTYPE html>
                            <html>
                                <head>
                                    <meta charset='utf-8'>
                                    <meta http-equiv='Content-Type' content='application/vnd.ms-excel charset=utf-8'>
                                </head>
                                <body>";
            $cuerpo.=$html;
            $cuerpo.="</body>
                </html>";
            fwrite($fp, $cuerpo);
        }
    }

    public function actionExcelJornadaDeclare()
    {
        $files = array();
        $_GET['start_date'];
        $_GET['end_date'];
        $_GET['position'];
        $_GET['employee'];
        $_GET['division'];
        $_GET['dependency'];
//            if($_GET['table']=='adminPositionCodeActive' || $_GET['table']=='adminPositionCodeInactives')
//            {
//                $files['positionCode']['name']=$_GET['name'];
        $files['positionCode']['body'] = Yii::app()->report->ReportDeclareDay($_GET['ids'], $_GET['name'], true);
//            }
//
//            foreach($files as $key => $file)
//            {
//                $this->genExcel($file['name'],$file['body'],true);
//            }
    }

    public function Detect()
    {
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
        $os = array("WIN", "MAC", "LINUX");

        # definimos unos valores por defecto para el navegador y el sistema operativo
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";

        # buscamos el navegador con su sistema operativo
        foreach($browser as $parent)
        {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if($s)
            {
                $info['browser'] = $parent;
                $info['version'] = $version;
            }
        }

        # obtenemos el sistema operativo
        foreach($os as $val)
        {
            if(strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
                $info['os'] = $val;
        }

        # devolvemos el array de valores
        return $info;
    }
    
    public function getBrowser() 
    { 
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    } 

}
