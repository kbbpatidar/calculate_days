<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Datediff extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://questionmark-test.loc/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/datediff/<method_name>
	 *
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		$data ['title'] = 'Calculate Days | Home';

		$data['active'] = 'home';
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_validation' );
		
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'date_diff', $data );
		$this->load->view ( 'templates/footer' );
	}
	
	/**
	 * Validate and return back appropriate response in return.
	 *
	 * @see user_login_form_submit()
	 */
	public function validate() {
		if (! $this->input->is_ajax_request ()) {
			exit ( 'no valid req.' );
		}
		
		$this->load->library ( 'form_validation' );
		
		$FormRules = array (
				array (
						'field' => 'start_date',
						'label' => 'Start Date',
						'rules' => 'required' 
				),
				array (
						'field' => 'end_date',
						'label' => 'End Date',
						'rules' => 'required' 
				) 
		);
		
		$this->form_validation->set_rules ( $FormRules );
		
		if ($this->form_validation->run () == TRUE) {
			$include_day = $_POST['include_day'] ? $_POST['include_day'] : false;
			
			/* ************  METHOD 1 ************ */
			$date1 = $this->convertDateToTime($_POST['start_date']);
			$date2 = $this->convertDateToTime($_POST['end_date']);
			$dateDifference = $this->findDateDiff($date2, $date1);
			
			$methodOneResult = array();
			if ($dateDifference < 0) {
				$dateDifference = abs($dateDifference);
				$methodOneResult['invert'] = true;
			}
						
			if ($include_day == true) {
				$dateDifference = $dateDifference + 1;
			}
			$methodOneResult['days'] = $dateDifference;
			
			$data['result'] = $methodOneResult;
			
			/* ************ METHOD 1 END ************ */ 
			
			
			
			
			/* ************ METHOD 2 ************ */
			$methodTwoResult = $this->_date_diff ( $_POST['start_date'], $_POST['end_date'], $_POST['start_date_timestamp'], $_POST['end_date_timestamp'], $include_day);
			
			// UNCOMMENT BELOW LINE OF CODE TO CHECK THE RESULT FROM METHOD 2
			
// 			$data['result'] = $methodTwoResult;
			/* ************ METHOD 2 END ************ */
			

			// Set status and days included if checkmboxark selected
			$data['status'] = true;
			$data['include_day'] = $include_day;			 
			
			echo json_encode($data);
		} else {
			echo '<div class="errors">' . validation_errors () . '</div>';
		}
	}
	
	

	/*  ************************************************************************
	 *
	 * ****************** BELOW FUNCTIONS ARE FOR METHOD 1 *********************
	 *
	 * *************************************************************************/
	
	/**
	 * Convert date with Format (y-m-d) to unix timestamp without using PHP date/time functions.
	 * @param date $date
	 * @return number
	 */
	public function convertDateToTime($date) {
		
		// Predefined days per month of the year
		$monthWiseDays = [ 
				1 => 31,
				2 => 28,
				3 => 31,
				4 => 30,
				5 => 31,
				6 => 30,
				7 => 31,
				8 => 31,
				9 => 30,
				10 => 31,
				11 => 31,
				12 => 31 
		];
	
		// Explode date with - to get Year, Month and Date in array.
		$dateArray = explode("-", $date);
	
		// As per PHP we are taking base year as 1970.
		$baseYear = "1970";
	
		// Assign associated values to variable.
		$currentYear = $dateArray[0];
		$currentMonth = $dateArray[1];
		$currentDay = $dateArray[2];
	
		// Calculate second in one day.
		$oneDaySecond = 24 * 60 * 60;
	
		
		$seconds = 0;
	
		for( $i = $baseYear; $i <= $currentYear; $i++) {
			// Check if year if Leap year or not. If yes then seconds will be different for leap year.
			// Else seconds for year will be 31536000 and add those as per the year increase.
			if($i%4 == 0) {
				$seconds += "31622400";
			}
			else {
				$seconds += "31536000";
			}
		}
	
		for( $j = 1; $j < $currentMonth; $j++) {
			// Check if current year is leap year or not.
			if (($currentYear % 4) == 0 and $j==2) {
				$seconds += $monthWiseDays[$j] * $oneDaySecond;
			}
			else {
				$seconds += $monthWiseDays[$j] * $oneDaySecond;
			}
		}
		
		$seconds += $currentDay * $oneDaySecond;
	
		// Return final result which will be unix timestamp as PHP returns with function strtotime()
		return $seconds;
	}
	
	/**
	 * Count Difference between two dates.
	 * @param timestamp $date2
	 * @param timestamp $date1
	 * @return number
	 */
	public function findDateDiff($date2, $date1) {
		$oneDaySecond = 24 * 60 * 60;
		$dateDiff = $date2 - $date1;
		$totalDays = ($dateDiff / $oneDaySecond);
		return $totalDays;
	}
	
	
	/*  ************************************************************************
	 * 
	 * ****************** BELOW FUNCTIONS ARE FOR METHOD 2 *********************
	 * 
	 * *************************************************************************/
	
	/**
	 * 
	 * ACCEPTS TWO DATE AND SAME UNIX TIMESTAMPS FOR THOSE TWO DATES.
	 * 
	 * @param date $start_date
	 * @param date $end_date
	 * @param timestamp $start_date_timestamp
	 * @param timestamp $end_date_timestamp
	 * @param boolean $include_day
	 * @return multitype:number 
	 */
	function _date_diff($start_date, $end_date, $start_date_timestamp, $end_date_timestamp, $include_day) {
		
		$sdt = $start_date_timestamp;
		$edt = $end_date_timestamp;

		// Format will become like 2016-05-30-00-00-00 (Y-m-d-h-i-s)
		$start_date = $start_date . '-00-00-00';
		$end_date = $end_date . '-00-00-00';
		
		// Check if end date is less than start date.
		// In this case days will be in minus but we will tell user that dates has been switched to calculate total days.
		$invert = false;
		if ($sdt > $edt) {
			list ( $sdt, $edt ) = array ($edt, $sdt);
			$invert = true;
		}
		
		$key = array ("y", "m", "d", "h", "i", "s" );
		$start_date_array = array_combine($key, array_map("intval", explode ("-", $start_date)));
		$end_date_array = array_combine($key, array_map("intval", explode("-", $end_date )));
		
		
		$result = array ();
		$result ["y"] = $end_date_array["y"] - $start_date_array["y"];
		$result ["m"] = $end_date_array["m"] - $start_date_array["m"];
		$result ["d"] = $end_date_array["d"] - $start_date_array["d"];
		$result ["h"] = $end_date_array["h"] - $start_date_array["h"];
		$result ["i"] = $end_date_array["i"] - $start_date_array["i"];
		$result ["s"] = $end_date_array["s"] - $start_date_array["s"];
		$result ["invert"] = $invert ? 1 : 0;
		$result ["days"] = intval ( abs ( ($sdt - $edt) / 86400 ) );
		
		if ($include_day == true) {
			$result["days"] = $result ["days"] + 1;
		}
		
		if ($invert) {
			$this->date_normalize($start_date_array, $result);
		} else {
			$this->date_normalize($end_date_array, $result );
		}
		return $result;
	}
	
	
	/**
	 * SEPRATE OUT YEAR MONTH AND DAYS WITH HOUR MINUTES AND SECOND IF REQUIRED.
	 * @param unknown $base
	 * @param unknown $result
	 * @return number
	 */
	function date_normalize($base, $result) {
		$result = $this->_date_range_limit ( 0, 60, 60, "s", "i", $result );
		$result = $this->_date_range_limit ( 0, 60, 60, "i", "h", $result );
		$result = $this->_date_range_limit ( 0, 24, 24, "h", "d", $result );
		$result = $this->_date_range_limit ( 0, 12, 12, "m", "y", $result );
		
		$result = $this->_date_range_limit_days ($base, $result );
		
		$result = $this->_date_range_limit ( 0, 12, 12, "m", "y", $result );
		
		return $result;
	}
	

	/**
	 * Function to get date rang between two dates using passed month/year/day
	 * @param int $start
	 * @param int $end
	 * @param int $adj
	 * @param char $a
	 * @param char $b
	 * @param array $result
	 * @return number
	 */
	function _date_range_limit($start, $end, $adj, $a, $b, $result){
		if ($result[$a] < $start) {
			$result[$b] -= intval(($start - $result[$a] - 1) / $adj) + 1;
			$result[$a] += $adj * intval(($start - $result[$a] - 1) / $adj + 1);
		}
	
		if ($result[$a] >= $end) {
			$result[$b] += intval($result[$a] / $adj);
			$result[$a] -= $adj * intval($result[$a] / $adj);
		}
		return $result;
	}
	
	/**
	 * Function to return date range
	 * @param array $base
	 * @param array $result
	 * @return number
	 */
	function _date_range_limit_days($base, $result) {
		$days_in_month_leap = array(31, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$days_in_month = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
		$this->_date_range_limit(1, 13, 12, "m", "y", $base);
	
		$year = $base["y"];
		$month = $base["m"];
	
		// CHECK IF INVERT DATE IS THERE OR NOT
		
		if (!$result["invert"]) {
			while ($result["d"] < 0) {
				$month--;
				if ($month < 1) {
					$month += 12;
					$year--;
				}
	
				// CHECK FOR LEAPYEAR
				$leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
				$days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];
	
				$result["d"] += $days;
				$result["m"]--;
			}
		} else {
			while ($result["d"] < 0) {
				
				// CHECK FOR LEAPYEAR
				$leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
				$days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];
	
				$result["d"] += $days;
				$result["m"]--;
	
				$month++;
				if ($month > 12) {
					$month -= 12;
					$year++;
				}
			}
		}
		return $result;
	}
	
	
	
	/**
	 * Function to call contact page content.
	 */
	public function information() {
		$data['title'] = 'Calculate Days | Information';
		$data['active'] = 'information';
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'information', $data );
		$this->load->view ( 'templates/footer' );
	}
	
	/**
	 * CUSTOM FUNCTION TO PRINT/DEBUG DATA. CREATED FOR BETTER UI IN DEBUG MODE. NOT RELATED TO CALCULATE DATE DIFFERENCE.
	 * @param array $data
	 * @param string $exit
	 */
	public function debug($data, $exit = false) {
		print '<pre>';
		print_r ( $data );
		print '</pre>';
		
		if ($exit == false) {
			exit ();
		}
	}
}
