<?php
require_once('Dashboard.php');
require_once('Helper.php');

class DashboardCHAI extends Dashboard
{
	protected $_primary = 'id';

	public function fetchConsumptionDetails($dataName = null, $id, $where = null, $group = null, $useName = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $subSelect = new Zend_Db_Expr("(select facility_id, month(max(date)) as C_monthDate from commodity group by facility_id)");

		switch ($dataName) {
		    case 'geo':
		        $select = $db->select()
		        ->from('location');
		        if ($where) // comma seperated string for sql
		            $select = $select->where($where)
		            ->order('location_name');
		        $result = $db->fetchAll($select);
		        
		        foreach ($result as $row){
		        
		          $output[] = array(
		            "id" => $row['id'],
		            "name" => $row['location_name'],
		            "tier" => $row['tier'],
		            "parent_id" => $row['parent_id'],
		            "consumption" => 0,
		            "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
		            "type" => 1
		           );
		        }
		    break;
		    case 'location':
		        $select = $db->select()
		        ->from(array('f' => 'facility'), 
		          array(
		          'f.id as F_id', 
		          'f.facility_name as F_facility_name', 
		          'f.location_id as F_location_id', 
		          'l1.id as L1_id',
		          'l1.location_name as L1_location_name', 
		          'l2.id as L2_id', 
		          'l2.location_name as L2_location_name', 
		          'l2.parent_id as L2_parent_id', 
		          'l3.location_name as L3_location_name', 
		          'cno.commodity_name as CNO_commodity_name',
		          'ifnull(sum(c.consumption),0) as C_consumption' ))
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
		        ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinInner(array('mc' => $subSelect), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
		        ->where($where)
		        ->group(array($group))
                ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
		        
		        $result = $db->fetchAll($select);
		        
		      foreach ($result as $row){
		          
		        $_child_name = ($id ==  "") ? $row['L2_location_name'] : $row['L1_location_name'] ;
		        
		 	    $output[] = array(
		 	        "id" => $row[$group],
		 	        "name" => $row[$useName],
		 	        "tier" => $row['tier'],
		 	        "parent_id" => $row['L2_parent_id'],
		 	        "child_name" => $_child_name,
		 	        "commodity_name" => $row[CNO_commodity_name],
		 	        "consumption" => $row['C_consumption'],
		 	        "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['l2.parent_id'],
		 	        "type" => 1
		 	     );
		 	    

		 	    	
		 	    
		      }
		    break;
		    case 'facility':
		          $select = $db->select()
		          ->from(array('f' => 'facility'), 
		          array(
		          'f.id as F_id', 
		          'f.facility_name as F_facility_name', 
		          'f.location_id as F_location_id', 
		          'l1.id as L1_id',
		          'l1.location_name as L1_location_name', 
		          'l2.id as L2_id', 
		          'l2.location_name as L2_location_name', 
		          'l2.parent_id as L2_parent_id', 
		          'l3.location_name as L3_location_name', 
		          'cno.commodity_name as CNO_commodity_name',
		          'ifnull(c.consumption,0) as C_consumption' ))
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->joinLeft(array('c' => "commodity"), 'f.id = c.facility_id')
		        ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinInner(array('mc' => $subSelect), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
		        ->where($where)
                ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
		        
		        $result = $db->fetchAll($select);
		        
		      foreach ($result as $row){
		 	    $output[] = array(
		 	        "id" => $row[$group],
		 	        "name" => $row[$useName],
		 	        "tier" => $row['tier'],
		 	        "parent_id" => $row['L3_parent_id'],
		 	        "child_name" => $row['F_facility_name'],
		 	        "commodity_name" => $row[CNO_commodity_name],
		 	        "consumption" => $row['C_consumption'],
		 	        "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['l2.parent_id'],
		 	        "type" => 1
		 	     );
		      }
		        break;		    
		}
		return $output;
	}
	
	public function fetchTier( $id ) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $select = $db->select()
	    ->from('location')
        ->where('id = ' . $id);
	    
	    $result = $db->fetchRow($select);
	    
	    return $result['tier'];
	}
	
	public function fetchTitleData() {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	     
	    $select = $db->select()
	    ->from(array('l' => 'lc_view'),
	        array( 
	            'monthName(l.C_date) as month_name',
	            'year(l.C_date) as year'
	        ));
	     
	    $result = $db->fetchRow($select);
	     
	    return $result;
	    
	}
	
	public function fetchCLNDetails($dataName = null, $id = null, $where = null, $group = null, $useName = null) {
	    
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	
	    switch ($dataName) {
	        case 'geo':
	            $select = $db->select()
	            ->from('location');
	            if ($where) // comma seperated string for sql
	                $select = $select->where($where)
	                ->order('location_name');
	            $result = $db->fetch($select);
	
	            foreach ($result as $row){
	
	                $output[] = array(
	                    "id" => $row['id'],
	                    "name" => $row['location_name'],
	                    "tier" => $row['tier'],
	                    "parent_id" => $row['parent_id'],
	                    "consumption" => 0,
	                    "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
	                    "type" => 1
	                );
	            }
	            break;
	        case 'location':

	    	    $create_view = $db->select()
	           ->from(array('f' => 'facility'),
	           array(
	            'f.id as F_id',
	            'f.facility_name as F_facility_name',
	            //"replace(f.facility_name, '\'', '\\\'') as F_facility_name',",
	            'f.location_id as F_location_id',
	            'l1.id as L1_id',
	            'l1.location_name as L1_location_name',
	            'l2.id as L2_id',
	            'l2.location_name as L2_location_name',
	            'l2.parent_id as L2_parent_id',
	            'l3.location_name as L3_location_name',
	            'cno.id as CNO_id',
	            'c.date as C_date',
	         	'ifnull(sum(c.consumption),0) as C_consumption' ))
	         	->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	    	    ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
	    	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
	    	    ->joinInner(array('mc' => 'lc_view_subselect'), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
	    	    ->where($where)
	    	    ->group(array($group))
	    	    ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
	    
	    $sql = $create_view->__toString();
	    $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
	    $sql = str_replace('`l1`.*,', '', $sql);
	    $sql = str_replace('`l2`.*,', '', $sql);
	    $sql = str_replace('`l3`.*,', '', $sql);
	    $sql = str_replace('`c`.*,', '', $sql);
	    $sql = str_replace('`cno`.*,', '', $sql);
	    $sql = str_replace('`mc`.*', '', $sql);
	    
	    try{
	        $sql='create or replace view lc_view as ('.$sql.')';
	        $db->fetchOne( $sql );
	    }
	    catch (Exception $e) {
	        //echo $e->getMessage();
	    }

	    $select = $db->select()
	    ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	        array(
	            'L1_location_name',
	            'L2_location_name',
	            'L3_location_name',
	        'ifnull(sum(cv.implanon_consumption),0) as implanon_consumption',
	        'ifnull(sum(cv.jadelle_consumption),0) as jadelle_consumption',
	        'ifnull(sum(cv.condom_consumption),0) as condom_consumption', ))
	    	->group(array($useName))
	    	->order(array('L3_location_name', 'L2_location_name', 'L1_location_name'));
	    
	    $result = $db->fetchAll($select);
	        
	    foreach ($result as $row){
	    
	        $output[] = array(
	            "L1_location_name" => $row['L1_location_name'],
	            "L2_location_name" => $row['L2_location_name'],
	            "L3_location_name" => $row['L3_location_name'],
	           // "location_name" => str_replace(' ', '', $row[$useName]),
	           // "location_name" => substr($row[$useName], 0, 16),
	            "location_name" => $row[$useName],
	            "implanon_consumption" => $row['implanon_consumption'],
	            "jadelle_consumption" => $row['jadelle_consumption'],
	            "condom_consumption" => $row['condom_consumption'],
	            "type" => 1
	        );
	    }
	            break;
	            
	        case 'facility':
	            
	            $create_view = $db->select()
	            ->from(array('f' => 'facility'),
	                array(
	                    'f.id as F_id',
	                    'f.facility_name as F_facility_name',
	                    //"replace(f.facility_name, '\'', '\\\'') as F_facility_name',",
	                    'f.location_id as F_location_id',
	                    'l1.id as L1_id',
	                    'l1.location_name as L1_location_name',
	                    'l2.id as L2_id',
	                    'l2.location_name as L2_location_name',
	                    'l2.parent_id as L2_parent_id',
	                    'l3.location_name as L3_location_name',
	                    'cno.id as CNO_id',
	                    'c.date as C_date',
                        'ifnull(sum(c.consumption),0) as C_consumption' ))	            	         	
                        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	            	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	            	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	            	    ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
	            	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
	            	    ->joinInner(array('mc' => 'lc_view_subselect'), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
	            	    ->where($where)
	            	    ->group(array($group))
	            	    ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
	            	    
	            	    $sql = $create_view->__toString();
	            	    $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
	            	    $sql = str_replace('`l1`.*,', '', $sql);
	            	    $sql = str_replace('`l2`.*,', '', $sql);
	            	    $sql = str_replace('`l3`.*,', '', $sql);
	            	    $sql = str_replace('`c`.*,', '', $sql);
	            	    $sql = str_replace('`cno`.*,', '', $sql);
	            	    $sql = str_replace('`mc`.*', '', $sql);
	            	    
	            	    try{
	            	        $sql='create or replace view lc_view as ('.$sql.')';
	            	        $db->fetchOne( $sql );
	            	    }
	            	    catch (Exception $e) {
	            	        //echo $e->getMessage();
	            	    }
	            	    
	            	    
	                   if($useName == 'F_facility_name'){
	                       $select = $db->select()
	                       ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	                           array(
	                               'F_facility_name',
	                               'L1_location_name',
	                               'L2_location_name',
	                               'L3_location_name',
	                               'implanon_consumption',
	                               'jadelle_consumption',
	                               'condom_consumption', ));
	                   }
	                   else {
	            	    $select = $db->select()
	            	    ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	            	        array(
	            	            'F_facility_name',
	            	            'L1_location_name',
	            	            'L2_location_name',
	            	            'L3_location_name',
	            	        'ifnull(sum(cv.implanon_consumption),0) as implanon_consumption',
	            	        'ifnull(sum(cv.jadelle_consumption),0) as jadelle_consumption',
	            	        'ifnull(sum(cv.condom_consumption),0) as condom_consumption', ))
	            	    	->group(array('L1_location_name'))
	            	    	->order(array('L3_location_name', 'L2_location_name', 'L1_location_name'));
	                   }
	            	    
	            	    $result = $db->fetchAll($select);
	            	    
	            	    foreach ($result as $row){
	            	    
	            	        $output[] = array(
	            	            "L1_location_name" => $row['L1_location_name'],
	            	            "L2_location_name" => $row['L2_location_name'],
	            	            "L3_location_name" => $row['L3_location_name'],
	            	            "location_name" => $row[$useName], 0, 16,
	            	            "implanon_consumption" => $row['implanon_consumption'],
	            	            "jadelle_consumption" => $row['jadelle_consumption'],
	            	            "condom_consumption" => $row['condom_consumption'],
	            	            "type" => 1
	            	        );
	            	    }
	            break;
	    }
	    return $output;
	}
	
	public function fetchAMCDetails($where = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $orderClause = new Zend_Db_Expr("`c`.`date` desc limit 12");
	    
	    $select = $db->select()
	    ->from(array('cv' => 'amc_view_extended_pivot_non_null'),
	        array(
	            'monthname(cv.date) as month',
	            'sum(cv.implant_consumption) as implant_consumption',
	            'sum(cv.injectable_consumption) as injectable_consumption'))
	            ->group(array('monthname(cv.date)'))
	            ->order(array('cv.date'));
	    
	    
	    $result = $db->fetchAll($select);
	    
	    foreach ($result as $row){
	    
	        $output[] = array(
	            "month" => $row['month'],
	            "implant_consumption" => $row['implant_consumption'],
	            "injectable_consumption" => $row['injectable_consumption']
	        );
	    }
	
	    return $output;
	}
	
	public function fetchHCWTDetails($where = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	     
	    $select = $db->select()
	    ->from(array('hv' => 'hcwt_view_extended_pivot_non_null'),
	        array(
	            'year(hv.date) as year',
	            'sum(hv.fp_trained) as fp_trained',
                'sum(hv.larc_trained) as larc_trained'))
                ->group(array('year(hv.date)'))
		        ->order(array('hv.date'));

		    $result = $db->fetchAll($select);
		    
		    foreach ($result as $row){
		    
		        $output[] = array(
		            "year" => $row['year'],
		            "fp_trained" => $row['fp_trained'],
		            "larc_trained" => $row['larc_trained']
		        );
		    }
		
		    return $output;
		}
		
		
		
    public function fetchPercentFacHWTrainedDetails($where = null, $group = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $subSelect = new Zend_Db_Expr("(select count(*) as cnt
  from facility f
  left join location l1 ON f.location_id = l1.id
  left join location l2 ON l1.parent_id = l2.id
  left join location l3 ON l2.parent_id = l3.id
  where l2.location_name = outer_state
  group by l2.location_name)");

		
		        $select = $db->select()
		        ->from(array('pt' => 'person_to_training'), 
		          array(
		          'l2.location_name as outer_state', 
		          "count(*) / $subSelect as percentage" ))
		         
		        ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		        ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        
		        ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
		        ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
		        ->where($where)
		        ->group(array('outer_state'))
                ->order(array('percentage'));
		        
		        $result = $db->fetchAll($select);

		      $cnt = 0;
		      $length = sizeof($result);
		      
		      foreach ($result as $row){
		        $cnt += 1;
		        $color = ($cnt <  6) ? 'red' : 'blue' ;

		        if ($cnt < 6 or $cnt > $length - 5) 
		        {   $output[] = array(
		 	        "state" => $row['outer_state'],
		 	        "percentage" => $row['percentage'],
		 	        "color" => $color,
		 	      );
		        }
		      }
		      
		      $subSelect = new Zend_Db_Expr("(select count(*) from facility)");
		      
		      
		      $select = $db->select()
		      ->from(array('pt' => 'person_to_training'),
		          array(
		              "count(*) / $subSelect as percentage" ))
		               
		              ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		                  ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		                      ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		                      ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		                      ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		      
		                  ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
		                  ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
		                  ->where($where);
		      
		              $result = $db->fetchAll($select);
		      
		      foreach ($result as $row){
		        $output[] = array(
		          "state" => 'National',
		          "percentage" => $row['percentage'],
		          "color" => 'black',
		          );
		      }   
		

		return $output;
	}
				    

public function fetchPercentProvidingDetails($where = null, $group = null) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();
     
    $subSelect = new Zend_Db_Expr("(select count(*) as cnt
  from facility f
  left join location l1 ON f.location_id = l1.id
  left join location l2 ON l1.parent_id = l2.id
  left join location l3 ON l2.parent_id = l3.id
  where l2.location_name = outer_state
  group by l2.location_name)");
	
	
	    $select = $db->select()
	    ->from(array('c' => 'commodity'),
	        array(
	            'l2.location_name as outer_state',
	            "count(*) / $subSelect as percentage" ))

	            ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
	            ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
	            ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
	            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	            ->where($where)
	            ->group(array('outer_state'))
	            ->order(array('percentage'));
	
	                $result = $db->fetchAll($select);
	
	                $cnt = 0;
	                $length = sizeof($result);
	
	                foreach ($result as $row){
	                $cnt += 1;
	                $color = ($cnt <  6) ? 'red' : 'blue' ;
	
	                    if ($cnt < 6 or $cnt > $length - 5)
	                    {   $output[] = array(
	                        "state" => $row['outer_state'],
	                        "percentage" => $row['percentage'],
	                            "color" => $color,
	                        
	                        );
	                    }
	                }
	
	                $subSelect = new Zend_Db_Expr("(select count(*) from facility)");
	
	
	                $select = $db->select()
	                ->from(array('c' => 'commodity'),
	                   array("count(*) / $subSelect as percentage" ))
		       
	                ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
	                ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
	                ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
	                ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	                ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	                ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	                ->where($where);
	
		          $result = $db->fetchAll($select);
	
			      foreach ($result as $row){
			      $output[] = array(
			      "state" => 'National',
		          "percentage" => $row['percentage'],
			          "color" => 'black',
		            );
			      }

			return $output;
		}
		
		public function fetchDashboardData($chart = null) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $output = array();

		    $where = "chart = '$chart' and datetime = (select max(datetime) from dashboard_refresh where chart = '$chart')";
		    $subSelect = new Zend_Db_Expr("(select max(datetime) from dashboard_refresh where chart = $chart)");
		
		    $select = $db->select()
		    ->from(array('dr' => 'dashboard_refresh'),
		        array(
		            'id',
		            'datetime',
		            'chart',
		            'data0','data1','data2','data3','data4','data4',
		        ))
		            ->where($where)
		            ->order(array('id'));
		
		    $result = $db->fetchAll($select);
		    
            switch ($chart) {
                case 'percent_facilities_hw_trained_larc':
                case 'percent_facilities_hw_trained_fp':
                case 'percent_facilities_providing_larc':
                case 'percent_facilities_providing_fp':
                    
                    foreach ($result as $row) {
                        $output[] = array(
                            "state" => $row['data0'],
                            "percentage" => $row['data1'],
                            "color" => $row['data2']
                        );
                    }
                    break;
                    
                case 'average_monthly_consumption':
                
                    foreach ($result as $row) {
                        $output[] = array(
                            "month" => $row['data0'],
                            "injectable_consumption" => $row['data1'],
                            "implant_consumption" => $row['data2']
                        );
                    }
                    break;
            }

		    return $output;
		}
	
		public function insertDashboardData($details, $chart) {

		    $dateTime = date("Y-m-d H:i:s");
		    $dashboard_refresh = new ITechTable(array('name' => 'dashboard_refresh'));
		    
		    switch ($chart) {
		        case 'percent_facilities_hw_trained_larc':
		        case 'percent_facilities_hw_trained_fp':
		        case 'percent_facilities_providing_larc':
		        case 'percent_facilities_providing_fp':
		  		    
        		    foreach($details as $row){
        		        $data = array(
        		            'datetime'  => $dateTime,
        		            'chart'  => $chart,
        		            'data0'  => $row['state'],
        		            'data1'  => $row['percentage'],
        		            'data2'  => $row['color'],
        		        );
        		        
        		        $insert_result = $dashboard_refresh->insert($data);
        		    }
		          break;
		          
		        case 'average_monthly_consumption':
		            
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['month'],
		                    'data1'  => $row['injectable_consumption'],
		                    'data2'  => $row['implant_consumption'],
		                );
		            
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		    }
		    
		}
}
?>
