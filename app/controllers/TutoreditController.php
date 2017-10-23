<?php
require_once ('ITechController.php');
require_once ('models/table/Peopleadd.php');
require_once ('models/table/Tutoredit.php');
require_once ('models/table/Helper.php');
require_once ('views/helpers/FormHelper.php');

class TutoreditController extends ITechController
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
    }

    public function init()
    {}

    public function preDispatch()
    {
        parent::preDispatch();
        
        if (! $this->isLoggedIn())
            $this->doNoAccessError();
    }

    public function tutoreditAction()
    {
        
        if(! $this->hasACL('edit_studenttutorinst')){
            $instid = $this->getSanParam('id');
            $this->_redirect("tutoredit/tutorview/id/" . $instid);
        }
         
        $params = $this->getAllParams();
        
        if (isset($params['addpeople'])) {
            // ADDING PERSON RECORD
            $peopleadd = new Peopleadd();
            $tutorid = $peopleadd->Peopleadd($params);
            /*
             * # ADDING ADDRESS RECORD
             * $query = "INSERT INTO addresses SET
             * address1 = '" . addslashes($params[address1]) . "',
             * address2 = '" . addslashes($params[address2]) . "',
             * city = '" . addslashes($params[zip]) . "',
             * postalcode = '" . addslashes($params[address1]) . "',
             * id_addresstype = '" . addslashes(1) . "',
             * id_geog1 = '" . addslashes($params[province_id] ? $params[province_id] : 0) . "',
             * id_geog2 = '" . addslashes($params[district_id] ? $params[district_id] : 0) . "',
             * id_geog3 = '" . addslashes($params[region_c_id] ? $params[region_c_id] : 0) . "'";
             * die($query);
             * $db = $this->dbfunc()->query($query);
             * $id = $db->lastInsertId();
             *
             *
             * # ADDING ADDRESS RECORD
             * $query = "INSERT INTO link_tutor_addresses SET
             * id_address = '" . addslashes($id) . "',
             * id_tutor = '" . addslashes($tutorid) . "'";
             * die($query);
             *
             * $db = $this->dbfunc()->query($query);
             * $id = $db->lastInsertId();
             * # LINKING TO TUTOR RECORD
             * $db = $this->dbfunc();
             * $linkrec = array(
             * 'id_address'		=>	$id,
             * 'id_tutor'			=>	$tutorid,
             * );
             * $rowArray = $db->insert("link_tutor_addresses",$linkrec);
             * $id = $db->lastInsertId();
             */
            $this->_redirect("/tutoredit/tutoredit/id/" . $tutorid);
        }

//TA:51 10/05/2015
        if (isset($params['delete'])) {
             $tutoredit = new Tutoredit();
             $tutoredit->DeleteTutor($params);
            ValidationContainer::instance()->setStatusMessage(t('The tutor was deleted.'));
            $_SESSION['status'] = t('The tutor was deleted');
            $this->_redirect(Settings::$COUNTRY_BASE_URL . '/people/people');
        }
        
        if (isset($params['update'])) {
            $tutoredit = new Tutoredit();
            $updateperson = $tutoredit->UpdatePerson($params);
            //TA:#417
            if(!isset($params['province_id'])){
                $params['province_id'] = $params['permanent_geo1'];
            }
            if(!isset($params['district_id'])){
                $params['district_id'] = $params['permanent_geo2'];
            }
            if(!isset($params['region_c_id'])){
                $params['region_c_id'] = $params['permanent_geo3'];
            }
            //
            $updateperson = $tutoredit->UpdateTutor($params);
            
            // STORE LANGUAGES ON EDIT
            
            // $updateperson=$tutoredit->UpdatePermanentAddress($params);
            
            // $studentupdate = new Tutoredit();
            // $update = $studentupdate->UpdatePerson($params); # STORING PERSON RECORD
            // $update = $studentupdate->UpdateStudent($params); # STORING STUDENT RECORD
            // $update = $studentupdate->UpdateStudentCohort($params); # STORING COHORT LINK
            // $update = $studentupdate->UpdatePermanentAddress($params); # STORING PERMANENT ADDRESS
            
            $tutorid = $params['id'];
            $this->view->assign('newid', $tutorid);
            
            $status = ValidationContainer::instance();
            
            if ($tutorid) { // sucess
                $status->setStatusMessage(t('The person was saved.'));
                $_SESSION['status'] = t('The person was saved.');
                $this->_redirect("/tutoredit/tutoredit/id/" . $tutorid);//TA:#417
            }
        }
        
        $request = $this->getRequest();
        $tutorid = $request->getParam('id');
        
        $this->view->assign('id', $tutorid);
        
        $this->viewAssignEscaped('locations', Location::getAll());
        $Tutoredit = new Tutoredit();
        $details = $Tutoredit->EditTutor($tutorid);
        
        $dob = formHelperDate($details['person'][0]['birthdate']);
        
        $this->view->assign('id', $tutorid);
        $this->view->assign('selid', $details['person'][0]['title_option_id']);
        $this->view->assign('firstname', $details['person'][0]['first_name']);
        $this->view->assign('middlename', $details['person'][0]['middle_name']);
        $this->view->assign('lastname', $details['person'][0]['last_name']);
        $this->view->assign('nationalid', $details['person'][0]['national_id']);
        $this->view->assign('gender', $details['person'][0]['gender']);
        $this->view->assign('cell', $details['person'][0]['phone_mobile']);
        // TA: there is no 'phone_mobile_2' column in PERSON table -> link it 'phone_home' column
        // $this->view->assign('cell2',$details['person'][0]['phone_mobile_2']);
        $this->view->assign('cell2', $details['person'][0]['phone_home']);
        $this->view->assign('dob', $dob);
        $this->view->assign('tutor_not_active', $details['person'][0]['active']);//TA:#254
        
        // TA:6: added 8/8/2014 - 8/10/2014
        $dc = strtotime($details['person'][0]['timestamp_created']);
        $dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y", $dc) : t("N/A");
        $this->view->assign('dateCreated', $dateCreated);
        $dm = strtotime($details['person'][0]['timestamp_updated']);
        $dateModified = $dm != '' && $dm > 0 ? date("d-m-Y", $dm) : t("N/A");
        $this->view->assign('dateModified', $dateModified);
        $this->view->assign('uuid', $details['person'][0]['uuid']);
        //TA:#384
        require_once ('models/table/User.php');
        $personObj = new User();
        $created_by = $details['person'][0]['created_by'] ? $personObj->getUserFullName($details['person'][0]['created_by']) : t("N/A");
        $this->viewAssignEscaped('creator', $created_by);
        $update_by = $details['person'][0]['modified_by'] ? $personObj->getUserFullName($details['person'][0]['modified_by']) : t("N/A");
        $this->viewAssignEscaped('updater', $update_by);
        //
        
        $helper = new Helper();
        
        $this->view->assign('lookupnationalities', $helper->getNationalities());
        $this->view->assign('lookupfacilities', $helper->getNationalities());
        $this->view->assign('lookupdegrees', $helper->getDegrees());
        $this->view->assign('lookupdegreeinst', $helper->getDegreeInst());
        $this->view->assign('tutorlanguages', $helper->getLanguages());
        
        $known = array();
        $tl = $helper->getTutorLanguages($details['tutor'][0]['id']);
        foreach ($tl as $_tl) {
            $known[] = $_tl['id_language'];
        }
        $this->view->assign('knownlanguages', $known);
        
        $this->view->assign('lookuptutortypes', $helper->getTutorTypes());
        
        $tt = array();
        $types = $helper->getLinkedTutorTypes($details['tutor'][0]['id']);
        foreach ($types as $type) {
            $tt[] = $type['id'];
        }
        $this->view->assign('tutortypes', $tt);
        
        $this->view->assign('students', $helper->getTutorStudents($tutorid));
        $this->view->assign('classes', $helper->getTutorClasses($tutorid));
        
        // GET ALL STUDENTS WHERE ADVISOR = THIS ID
        // GET ALL COURSES LINKED TO THIS ID
        
        /*
         * $this->view->assign('localgeo1',$details['student'][0]['geog1']);
         * $this->view->assign('localgeo2',$details['student'][0]['geog2']);
         * $this->view->assign('localgeo3',$details['student'][0]['geog3']);
         * $this->view->assign('address1',$details['person'][0]['home_address_1']);
         * $this->view->assign('address2',$details['person'][0]['home_address_2']);
         * $this->view->assign('city',$details['person'][0]['city']);
         * $this->view->assign('zip',$details['person'][0]['home_postal_code']);
         * $this->view->assign('enroll',$details['person'][0]['home_address_2']);
         * $this->view->assign('city',$details['person'][0]['city']);
         * $this->view->assign('email',$details['person'][0]['email']);
         * $this->view->assign('email2',$details['person'][0]['email_secondary']);
         * $this->view->assign('titid',$details['person'][0]['title_option_id']);
         * $this->view->assign('phone',$details['person'][0]['phone_work']);
         * $this->view->assign('cell',$details['person'][0]['phone_mobile']);
         * $this->view->assign('cadre',$details['person'][0]['cadre']);
         * $this->view->assign('graduated',$details['student'][0]['isgraduated']);
         * $this->view->assign('cohortid',$details['link_cohort'][0]['id_cohort']);
         */
        
        // FACILITY INFORMATION GEO
        // $this->view->assign('localgeo1',$details['tutor'][0]['geog1']);
        // $this->view->assign('localgeo2',$details['tutor'][0]['geog2']);
        // $this->view->assign('localgeo3',$details['tutor'][0]['geog3']);
        
        //TA:#417
        require_once('views/helpers/Location.php');
        $facility_loc_arr = locationIDTo3TierCriteriaArray($details['tutor'][0]['facility_location_id'], '');
         $this->view->assign('localgeo1',$facility_loc_arr['province_id']);
         $this->view->assign('localgeo2',$facility_loc_arr['district_id']);
         $this->view->assign('localgeo3',$facility_loc_arr['region_c_id']);
        ///
        
        $this->view->assign('facilityid', $details['tutor'][0]['facilityid']);
        $facility = $helper->getFacilities();
        $this->view->assign('facilities', $facility);
        
        // TA: added 7/24/2014
        $this->view->assign('spid', $details['tutor'][0]['specialty']);
        $this->view->assign('ctid', $details['tutor'][0]['contract_type']);
        // $this->view->assign('cadre',$details['tutor'][0]['cadre']);
        
        $this->view->assign('institutionid', $details['tutor'][0]['institutionid']);
        $institutions = $helper->getInstitutions();
        $this->view->assign('institutions', $institutions);
        $this->view->assign('tutorsince', $details['tutor'][0]['tutorsince']);
        $this->view->assign('tutortimehere', $details['tutor'][0]['tutortimehere']);
        $this->view->assign('degree', $details['tutor'][0]['degree']);
        $this->view->assign('degreeinst', $details['tutor'][0]['degreeinst']);
        $this->view->assign('degreeyear', $details['tutor'][0]['degreeyear']);
        $this->view->assign('languagesspoken', $details['tutor'][0]['languagesspoken']);
        $this->view->assign('positionsheld', $details['tutor'][0]['positionsheld']);
        $this->view->assign('comments', $details['tutor'][0]['comments']);
        $this->view->assign('nationalityid', $details['tutor'][0]['nationalityid']);
        
        // PERMANENT ADDRESS
        $this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
        $this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
        $this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
        $this->view->assign('address1', $details['permanent_address'][0]['address1']);
        $this->view->assign('address2', $details['permanent_address'][0]['address2']);
        $this->view->assign('city', $details['permanent_address'][0]['city']);
        $this->view->assign('postalcode', $details['permanent_address'][0]['postalcode']);
        
        /*
         * # STUDENT VIEW
         * $this->view->assign('studentid',$details['student'][0]['studentid']);
         * $this->view->assign('studenttype',$details['student'][0]['studenttype']);
         *
         * if ($details['student'][0]['enrollmentdate'] == "0000-00-00"){
         * $enrolldate = "";
         * } else {
         * $enrolldate = date("d-m-Y", strtotime($details['student'][0]['enrollmentdate']));
         * }
         * $this->view->assign('enrollmentdate',$enrolldate);
         * $this->view->assign('enrollmentreason',$details['student'][0]['enrollmentreason']);
         *
         * if ($details['student'][0]['separationdate'] == "0000-00-00"){
         * $separationdate = "";
         * } else {
         * $separationdate = date("d-m-Y", strtotime($details['student'][0]['separationdate']));
         * }
         * $this->view->assign('separationdate',$separationdate);
         * $this->view->assign('separationreason',$details['student'][0]['separationreason']);
         * $this->view->assign('cadre',$details['student'][0]['cadre']);
         * $this->view->assign('advisor',$details['student'][0]['advisorid']);
         * $this->view->assign('yearofstudy',($details['student'][0]['yearofstudy'] != 0 ? $details['student'][0]['yearofstudy'] : ""));
         */
        
        // For Title List
        $listtitle = $Tutoredit->ListTitle();
        $this->view->assign('gettitle', $listtitle);
        
        // TA: added 7/24/2014
        // For Specialty List
        $listsp = $Tutoredit->ListSpecialty();
        $this->view->assign('getspecialty', $listsp);
        // For contract type List
        $listct = $Tutoredit->ListContractType();
        $this->view->assign('getcontracts', $listct);
        
        //
        // # GETTING COHORTS
        // $listcohort = $Tutoredit->ListCohort();
        // $this->view->assign('getcohorts',$listcohort);
        //
        // # GETTING CADRES
        // $listcadre = $Tutoredit->listCadre();
        // $this->view->assign('getcadres',$listcadre);
        //
        // # GETTING TUTORS
        // $listtutors = $Tutoredit->ListTutors();
        // $this->view->assign('gettutors',$listtutors);
        
        $this->view->assign('status', ValidationContainer::instance());
        
        // TA: added 7/22/2014 - 7/23/2014
        $sysTable = new System();
        $sysRows = $sysTable->fetchAll()->toArray();
        foreach ($sysRows as $row) {
            foreach ($row as $column => $value) {
                if ($column == 'ps_display_custom_field1' && $value != '0') {
                    $this->view->assign('label_custom_field1', $this->view->translation['ps custom field 1']);
                    $this->view->assign('custom_field1', $details['person'][0]['custom_field1']);
                } else 
                    if ($column == 'ps_display_custom_field2' && $value != '0') {
                        $this->view->assign('label_custom_field2', $this->view->translation['ps custom field 2']);
                        $this->view->assign('custom_field2', $details['person'][0]['custom_field2']);
                    } else 
                        if ($column == 'ps_display_custom_field3' && $value != '0') {
                            $this->view->assign('label_custom_field3', $this->view->translation['ps custom field 3']);
                            $this->view->assign('custom_field3', $details['person'][0]['custom_field3']);
                        } else 
                            if ($column == 'ps_display_marital_status' && $value != '0') {
                                $this->view->assign('label_marital_status', $this->view->translation['ps marital status']);
                                $this->view->assign('marital_status', $details['person'][0]['marital_status']);
                            } else 
                                if ($column == 'ps_display_spouse_name' && $value != '0') {
                                    $this->view->assign('label_spouse_name', $this->view->translation['ps spouse name']);
                                    $this->view->assign('spouse_name', $details['person'][0]['spouse_name']);
                                } else 
                                    if ($column == 'ps_display_specialty' && $value != '0') {
                                        $this->view->assign('label_specialty', $this->view->translation['ps specialty']);
                                        $this->view->assign('specialty', $details['tutor'][0]['specialty']);
                                    } else 
                                        if ($column == 'ps_display_contract_type' && $value != '0') {
                                            $this->view->assign('label_contract_type', $this->view->translation['ps contract type']);
                                            $this->view->assign('contract_type', $details['tutor'][0]['contract_type']);
                                        } else 
                                            if ($column == 'ps_display_nationality' && $value != '0') {
                                                $this->view->assign('label_ps_nationality', $this->view->translation['ps nationality']);
                                            } else 
                                                if ($column == 'ps_display_local_address' && $value != '0') {
                                                    $this->view->assign('label_ps_local_address', $this->view->translation['ps local address']);
                                                } else 
                                                    if ($column == 'ps_display_permanent_address' && $value != '0') {
                                                        $this->view->assign('label_ps_permanent_address', $this->view->translation['ps permanent address']);
                                                    }
                $this->view->assign('label_ps_zip_code', $this->view->translation['ps zip code']);
            }
        }
    }

    public function tutorviewAction()
    {
        $params = $this->getAllParams();
        
        if (isset($params['addpeople'])) {
            // ADDING PERSON RECORD
            $peopleadd = new Peopleadd();
            $tutorid = $peopleadd->Peopleadd($params);
            /*
             * # ADDING ADDRESS RECORD
             * $query = "INSERT INTO addresses SET
             * address1 = '" . addslashes($params[address1]) . "',
             * address2 = '" . addslashes($params[address2]) . "',
             * city = '" . addslashes($params[zip]) . "',
             * postalcode = '" . addslashes($params[address1]) . "',
             * id_addresstype = '" . addslashes(1) . "',
             * id_geog1 = '" . addslashes($params[province_id] ? $params[province_id] : 0) . "',
             * id_geog2 = '" . addslashes($params[district_id] ? $params[district_id] : 0) . "',
             * id_geog3 = '" . addslashes($params[region_c_id] ? $params[region_c_id] : 0) . "'";
             * die($query);
             * $db = $this->dbfunc()->query($query);
             * $id = $db->lastInsertId();
             *
             *
             * # ADDING ADDRESS RECORD
             * $query = "INSERT INTO link_tutor_addresses SET
             * id_address = '" . addslashes($id) . "',
             * id_tutor = '" . addslashes($tutorid) . "'";
             * die($query);
             *
             * $db = $this->dbfunc()->query($query);
             * $id = $db->lastInsertId();
             * # LINKING TO TUTOR RECORD
             * $db = $this->dbfunc();
             * $linkrec = array(
             * 'id_address'		=>	$id,
             * 'id_tutor'			=>	$tutorid,
             * );
             * $rowArray = $db->insert("link_tutor_addresses",$linkrec);
             * $id = $db->lastInsertId();
             *
             */
            $this->_redirect("/tutoredit/tutorview/id/" . $tutorid);
        }
        
        if (isset($params['update'])) {
            $tutoredit = new Tutoredit();
            $updateperson = $tutoredit->UpdatePerson($params);
            $updateperson = $tutoredit->UpdateTutor($params);
            
            // STORE LANGUAGES ON EDIT
            
            // $updateperson=$tutoredit->UpdatePermanentAddress($params);
            
            // $studentupdate = new Tutoredit();
            // $update = $studentupdate->UpdatePerson($params); # STORING PERSON RECORD
            // $update = $studentupdate->UpdateStudent($params); # STORING STUDENT RECORD
            // $update = $studentupdate->UpdateStudentCohort($params); # STORING COHORT LINK
            // $update = $studentupdate->UpdatePermanentAddress($params); # STORING PERMANENT ADDRESS
            
            $tutorid = $params['id'];
            $this->view->assign('newid', $tutorid);
            
            $status = ValidationContainer::instance();
            
            if ($tutorid) { // sucess
                $status->setStatusMessage(t('The person was saved.'));
                $_SESSION['status'] = t('The person was saved.');
            }
        }
        
        $request = $this->getRequest();
        $tutorid = $request->getParam('id');
        
        $this->view->assign('id', $tutorid);
        
        $this->viewAssignEscaped('locations', Location::getAll());
        $Tutoredit = new Tutoredit();
        $details = $Tutoredit->EditTutor($tutorid);
        
        // print_r ($details);
        
        $dob = formHelperDate($details['person'][0]['birthdate']);
        
        $this->view->assign('id', $tutorid);
        $this->view->assign('selid', $details['person'][0]['title_option_id']);
        $this->view->assign('firstname', $details['person'][0]['first_name']);
        $this->view->assign('middlename', $details['person'][0]['middle_name']);
        $this->view->assign('lastname', $details['person'][0]['last_name']);
        $this->view->assign('nationalid', $details['person'][0]['national_id']);
        $this->view->assign('gender', $details['person'][0]['gender']);
        $this->view->assign('cell', $details['person'][0]['phone_mobile']);
        // TA: there is no 'phone_mobile_2' column in PERSON table -> link it 'phone_home' column
        // $this->view->assign('cell2',$details['person'][0]['phone_mobile_2']);
        $this->view->assign('cell2', $details['person'][0]['phone_home']);
        $this->view->assign('dob', $dob);
        
        // TA:6: added 8/8/2014 - 8/10/2014
        $dc = strtotime($details['person'][0]['timestamp_created']);
        $dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y", $dc) : t("N/A");
        $this->view->assign('dateCreated', $dateCreated);
        $dm = strtotime($details['person'][0]['timestamp_updated']);
        $dateModified = $dm != '' && $dm > 0 ? date("d-m-Y", $dm) : t("N/A");
        $this->view->assign('dateModified', $dateModified);
        $this->view->assign('uuid', $details['person'][0]['uuid']);
        require_once ('models/table/Person.php');
        $personObj = new Person();
        $created_by = $details['person'][0]['created_by'] ? $personObj->getPersonName($details['person'][0]['created_by']) : t("N/A");
        $this->viewAssignEscaped('creator', $created_by);
        $update_by = $details['person'][0]['modified_by'] ? $personObj->getPersonName($details['person'][0]['modified_by']) : t("N/A");
        $this->viewAssignEscaped('updater', $update_by);
        
        $helper = new Helper();
        
        $this->view->assign('lookupnationalities', $helper->getNationalities());
        $this->view->assign('lookupfacilities', $helper->getNationalities());
        $this->view->assign('lookupdegrees', $helper->getDegrees());
        $this->view->assign('tutorlanguages', $helper->getLanguages());
        
        $known = array();
        $tl = $helper->getTutorLanguages($details['tutor'][0]['id']);
        foreach ($tl as $_tl) {
            $known[] = $_tl['id_language'];
        }
        $this->view->assign('knownlanguages', $known);
        
        $this->view->assign('lookuptutortypes', $helper->getTutorTypes());
        
        $tt = array();
        $types = $helper->getLinkedTutorTypes($details['tutor'][0]['id']);
        foreach ($types as $type) {
            $tt[] = $type['id'];
        }
        $this->view->assign('tutortypes', $tt);
        
        $this->view->assign('students', $helper->getTutorStudents($tutorid));
        $this->view->assign('classes', $helper->getTutorClasses($tutorid));
        
        // GET ALL STUDENTS WHERE ADVISOR = THIS ID
        // GET ALL COURSES LINKED TO THIS ID
        
        /*
         * $this->view->assign('localgeo1',$details['student'][0]['geog1']);
         * $this->view->assign('localgeo2',$details['student'][0]['geog2']);
         * $this->view->assign('localgeo3',$details['student'][0]['geog3']);
         * $this->view->assign('address1',$details['person'][0]['home_address_1']);
         * $this->view->assign('address2',$details['person'][0]['home_address_2']);
         * $this->view->assign('city',$details['person'][0]['city']);
         * $this->view->assign('zip',$details['person'][0]['home_postal_code']);
         * $this->view->assign('enroll',$details['person'][0]['home_address_2']);
         * $this->view->assign('city',$details['person'][0]['city']);
         * $this->view->assign('email',$details['person'][0]['email']);
         * $this->view->assign('email2',$details['person'][0]['email_secondary']);
         * $this->view->assign('titid',$details['person'][0]['title_option_id']);
         * $this->view->assign('phone',$details['person'][0]['phone_work']);
         * $this->view->assign('cell',$details['person'][0]['phone_mobile']);
         * $this->view->assign('cadre',$details['person'][0]['cadre']);
         * $this->view->assign('graduated',$details['student'][0]['isgraduated']);
         * $this->view->assign('cohortid',$details['link_cohort'][0]['id_cohort']);
         */
        
        // FACILITY INFORMATION GEO
        // $this->view->assign('localgeo1',$details['tutor'][0]['geog1']);
        // $this->view->assign('localgeo2',$details['tutor'][0]['geog2']);
        // $this->view->assign('localgeo3',$details['tutor'][0]['geog3']);
        
        $this->view->assign('facilityid', $details['tutor'][0]['facilityid']);
        $facility = $helper->getFacilities();
        $this->view->assign('facilities', $facility);
        
        // TA: added 7/24/2014
        $this->view->assign('spid', $details['tutor'][0]['specialty']);
        $this->view->assign('ctid', $details['tutor'][0]['contract_type']);
        // $this->view->assign('cadre',$details['tutor'][0]['cadre']);
        
        $this->view->assign('institutionid', $details['tutor'][0]['institutionid']);
        $institutions = $helper->getInstitutions();
        $this->view->assign('institutions', $institutions);
        $this->view->assign('tutorsince', $details['tutor'][0]['tutorsince']);
        $this->view->assign('tutortimehere', $details['tutor'][0]['tutortimehere']);
        $this->view->assign('degree', $details['tutor'][0]['degree']);
        $this->view->assign('degreeinst', $details['tutor'][0]['degreeinst']);
        $this->view->assign('degreeyear', $details['tutor'][0]['degreeyear']);
        $this->view->assign('languagesspoken', $details['tutor'][0]['languagesspoken']);
        $this->view->assign('positionsheld', $details['tutor'][0]['positionsheld']);
        $this->view->assign('comments', $details['tutor'][0]['comments']);
        $this->view->assign('nationalityid', $details['tutor'][0]['nationalityid']);
        
        // PERMANENT ADDRESS
        $this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
        $this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
        $this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
        $this->view->assign('address1', $details['permanent_address'][0]['address1']);
        $this->view->assign('address2', $details['permanent_address'][0]['address2']);
        $this->view->assign('city', $details['permanent_address'][0]['city']);
        $this->view->assign('postalcode', $details['permanent_address'][0]['postalcode']);
        
        /*
         * # STUDENT VIEW
         * $this->view->assign('studentid',$details['student'][0]['studentid']);
         * $this->view->assign('studenttype',$details['student'][0]['studenttype']);
         *
         * if ($details['student'][0]['enrollmentdate'] == "0000-00-00"){
         * $enrolldate = "";
         * } else {
         * $enrolldate = date("d-m-Y", strtotime($details['student'][0]['enrollmentdate']));
         * }
         * $this->view->assign('enrollmentdate',$enrolldate);
         * $this->view->assign('enrollmentreason',$details['student'][0]['enrollmentreason']);
         *
         * if ($details['student'][0]['separationdate'] == "0000-00-00"){
         * $separationdate = "";
         * } else {
         * $separationdate = date("d-m-Y", strtotime($details['student'][0]['separationdate']));
         * }
         * $this->view->assign('separationdate',$separationdate);
         * $this->view->assign('separationreason',$details['student'][0]['separationreason']);
         * $this->view->assign('cadre',$details['student'][0]['cadre']);
         * $this->view->assign('advisor',$details['student'][0]['advisorid']);
         * $this->view->assign('yearofstudy',($details['student'][0]['yearofstudy'] != 0 ? $details['student'][0]['yearofstudy'] : ""));
         *
         */
        
        // For Title List
        $listtitle = $Tutoredit->ListTitle();
        $this->view->assign('gettitle', $listtitle);
        
        // TA: added 7/24/2014
        // For Specialty List
        $listsp = $Tutoredit->ListSpecialty();
        $this->view->assign('getspecialty', $listsp);
        // For contract type List
        $listct = $Tutoredit->ListContractType();
        $this->view->assign('getcontracts', $listct);
        
        //
        // # GETTING COHORTS
        // $listcohort = $Tutoredit->ListCohort();
        // $this->view->assign('getcohorts',$listcohort);
        //
        // # GETTING CADRES
        // $listcadre = $Tutoredit->listCadre();
        // $this->view->assign('getcadres',$listcadre);
        //
        // # GETTING TUTORS
        // $listtutors = $Tutoredit->ListTutors();
        // $this->view->assign('gettutors',$listtutors);
        
        $this->view->assign('status', ValidationContainer::instance());
        
        // TA: added 7/22/2014 - 7/23/2014
        $sysTable = new System();
        $sysRows = $sysTable->fetchAll()->toArray();
        foreach ($sysRows as $row) {
            foreach ($row as $column => $value) {
                if ($column == 'ps_display_custom_field1' && $value != '0') {
                    $this->view->assign('label_custom_field1', $this->view->translation['ps custom field 1']);
                    $this->view->assign('custom_field1', $details['person'][0]['custom_field1']);
                } else 
                    if ($column == 'ps_display_custom_field2' && $value != '0') {
                        $this->view->assign('label_custom_field2', $this->view->translation['ps custom field 2']);
                        $this->view->assign('custom_field2', $details['person'][0]['custom_field2']);
                    } else 
                        if ($column == 'ps_display_custom_field3' && $value != '0') {
                            $this->view->assign('label_custom_field3', $this->view->translation['ps custom field 3']);
                            $this->view->assign('custom_field3', $details['person'][0]['custom_field3']);
                        } else 
                            if ($column == 'ps_display_marital_status' && $value != '0') {
                                $this->view->assign('label_marital_status', $this->view->translation['ps marital status']);
                                $this->view->assign('marital_status', $details['person'][0]['marital_status']);
                            } else 
                                if ($column == 'ps_display_spouse_name' && $value != '0') {
                                    $this->view->assign('label_spouse_name', $this->view->translation['ps spouse name']);
                                    $this->view->assign('spouse_name', $details['person'][0]['spouse_name']);
                                } else 
                                    if ($column == 'ps_display_specialty' && $value != '0') {
                                        $this->view->assign('label_specialty', $this->view->translation['ps specialty']);
                                        $this->view->assign('specialty', $details['tutor'][0]['specialty']);
                                    } else 
                                        if ($column == 'ps_display_contract_type' && $value != '0') {
                                            $this->view->assign('label_contract_type', $this->view->translation['ps contract type']);
                                            $this->view->assign('contract_type', $details['tutor'][0]['contract_type']);
                                        } else 
                                            if ($column == 'ps_display_nationality' && $value != '0') {
                                                $this->view->assign('label_ps_nationality', $this->view->translation['ps nationality']);
                                            } else 
                                                if ($column == 'ps_display_local_address' && $value != '0') {
                                                    $this->view->assign('label_ps_local_address', $this->view->translation['ps local address']);
                                                } else 
                                                    if ($column == 'ps_display_permanent_address' && $value != '0') {
                                                        $this->view->assign('label_ps_permanent_address', $this->view->translation['ps permanent address']);
                                                    }
                $this->view->assign('label_ps_zip_code', $this->view->translation['ps zip code']);
            }
        }
    }
}

?>