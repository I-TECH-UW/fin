<?php
require_once ('ITechController.php');
require_once ('models/table/Peopleadd.php');
require_once ('models/table/Helper.php');

class PeopleaddController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ($request, $response, $invokeArgs );
	}

	public function init() {
	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();

	}

	public function peopleaddAction(){
		$params = $this->getAllParams();
		$status = ValidationContainer::instance();
        $peopleadd = new Peopleadd();

		if ( $this->getRequest()->isPost() ){
			if (isset ($params['addpeople'])){
			    
			    //TA:87 !!!! DO NOT REMOVE: in PS add people for duplicat chjeck used first_name and last_name
			    $params['firstname'] = $params['first_name'];
			    $params['lastname'] = $params['last_name'];
			    
				$tutorid = $peopleadd->addTutor($params);

				if ($tutorid) {
					$status->setStatusMessage ( t ( 'The person was saved.' ) );
					$_SESSION['status'] = t ( 'The person was saved.' );
				}

				switch ($params['type']){
					case "key":
					case "tutor":
						$this->_redirect(Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/" . $tutorid);
					break;
				}
			}
			exit;
		}
		$this->viewAssignEscaped ('locations', Location::getAll () );
		$this->view->assign('action','../studentedit/studentedit/');
		$this->view->assign('title', $this->view->translation['Application Name']);

		$result = $peopleadd->Peopletitle();

		$this->view->assign('fetchtitle',$result);

		// For Facility
		$result = $peopleadd->PeopleFacility();

		$this->view->assign('fetchfacility',$result);

		$result = $peopleadd->PeopleCity();

		$this->view->assign('fetchcity',$result);

		# CREATING HELPER
		$helper = new Helper();
		$this->view->assign('institutions',$helper->getInstitutions(false));
	}

    /**
     * this action is only expected to be called for SkillSMART CHW (pre-service)
     */
    public function skillsmartChwAddAction() {

        require_once('views/helpers/DropDown.php');
        require_once('views/helpers/Location.php');
        require_once('controllers/ReportFilterHelpers.php');

        $this->view->assign('action', '/studentedit/skillsmart-chw-student-edit/');
        $this->view->assign('title', $this->view->translation['Application Name']);
        $this->view->assign('required_fields', array('last_name', 'first_name', 'primary_qualification_option_id'));


        $this->view->assign('locations', Location::getAll());
        $this->view->assign('personCriteria', getCriteriaValues(array(), 'person'));
        $this->view->assign('workplaceCriteria', getCriteriaValues(array(), 'workplace'));
        $this->view->assign('employerCriteria', getCriteriaValues(array(), 'employer'));
	    $this->view->assign('formType', 'add');

        $this->view->assign('nationality_dropdown',
            DropDown::generateSelectionFromQuery(
                'select id, nationality as val from lookup_nationalities order by val',
                array('name' => 'nationalityid')
            )
        );

        $this->view->assign('primary_qualification',
            DropDown::generateSelectionFromQuery(
                'select id, qualification_phrase as val from person_qualification_option order by val',
                array('name' => 'primary_qualification_option_id')
            )
        );

	    $this->view->assign('race_options',
		    DropDown::generateSelectionFromQuery(
			    'select id, race_phrase as val from person_race_option order by val',
			    array('name' => 'race_option_id')
		    )
	    );

        // do we need a prior learning yes/no when we have a way to select it?
        //$this->view->assign('nationality_dropdown', DropDown::generateSelectionFromQuery('select id, nationality as value from lookup_nationalities', array('name' => 'nationalityid')));

        $this->view->assign('class_modules', $this->dbfunc()->fetchAll('select id, external_id, title from class_modules order by id'));
        $this->view->assign('prior_modules', array());
        $this->view->assign('qualification_name',
            DropDown::generateSelectionFromQuery(
                "select id, reason as val from lookup_reasons where reasontype = 'join' order by val",
                array('name' => 'joinreason', 'id' => 'joinreason')
            )
        );

		$db = $this->dbfunc();

        // doc says this field is for SAQA ID but I don't understand how that can be a dropdown
        //$this->view->assign('nationality_dropdown', DropDown::generateSelectionFromQuery('select id, nationality as value from lookup_nationalities', array('name' => 'nationalityid')));

	    $this->view->assign('cohorts',
		    DropDown::generateSelectionFromQuery(
			    "select id, cohortname as val from cohort order by val",
			    array('name' => 'cohort', 'id' => 'cohort')
		    )
	    );

        $this->view->assign('level',
            DropDown::generateSelectionFromQuery(
                "select id, reason as val from lookup_reasons where reasontype = 'drop' order by val",
                array('name' => 'dropreason', 'id' => 'dropreason')
            )
        );

        $this->view->assign('cadre',
            DropDown::generateSelectionFromQuery(
                'select id, cadrename as val from cadres order by val',
                array('name' => 'cadre')
            )
        );

        $this->view->assign('student_employed',
            DropDown::generateSelectionFromQuery(
                'select id, studenttype as val from lookup_studenttype order by val',
                array('name' => 'studenttype', 'id' => 'studenttype')
            )
        );
		$s = $db->select()->from('certificate_issuers', array('id', 'val' => 'issuer_name'));

		$this->view->assign('certificate_issuer',
			DropDown::generateSelectionFromQuery($s,
				array('name' => 'certificate_issuer', 'id' => 'certificate_issuer')
			)
		);

	}
}