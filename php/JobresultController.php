<?php
/*
 * Modified by powya on August 1,2013.
 * email : powya.p@contus.in
 * Page is used to view the results
 * */
require_once 'Zend/Feed.php';
class JobresultController extends Zend_Controller_Action
{
    public function multiboxAction()
    {
    }
    public function indexAction()
    {  // error_reporting(E_ALL|E_STRICT);
       // ini_set('display_errors', 1);
        $url	= SITE_URL;
        $name	= SITE_NAME;
                
        $this->_helper->layout()->disableLayout();//disable layout.phtml page
        
        $obj		= new Common_Class();
        $getvalue 	= new Model_DbTable_jobresult();
        
        $form 				= new Form_jobresult();
        $this->view->form 	= $form;
        
        $authNamespace 			= new Zend_Session_Namespace('Zend_Auth');
        $filter 				= new Zend_Filter_StripTags();        
        $Seeker_Reg_Id 			= $authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
        $this->view->seeker_id 	= $Seeker_Reg_Id;

        $pageURL 				= $_SERVER["REQUEST_URI"];
        $this->view->pageURL	= $pageURL;
        $rssurl					= explode('?',$pageURL); 
        if(!empty($rssurl) && !empty($rssurl[1])) { 
        	$this->view->rssurl		= $rssurl[1];
        }		      
        $this->view->RemoveURL	= $_SERVER['REQUEST_URI'];
        
        $getpayment_details		= new Model_DbTable_cart();
        $currency				= $getpayment_details->getpaymodedetails();
        $currency_symbol 		= $currency[0]['Currency'];
        $curr_symbol			= $getpayment_details->getcurrencysymbol($currency_symbol);
        $symbol 				= $curr_symbol[0]['Currency_symbol'];
        $symbol_currency 		= htmlentities($symbol, ENT_QUOTES,'ISO-8859-1' );
        $this->view->currency	= $symbol_currency;
        
        $obj=new Common_Class();
        $getvalue = new Model_DbTable_jobresult();
        
        $Category =trim($this->_request->get('Category'));
        $categorydata = $getvalue->getCategorydata($Category);
        $this->view->categorydata = $categorydata;
        
        $form = new Form_jobresult();
        $this->view->form = $form;
        
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $filter = new Zend_Filter_StripTags();
        $authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];
        if($Seeker_Reg_Id!='')
        {
            $Email_Id=$getvalue->getEmailId($Seeker_Reg_Id);
            $data=array('Myemail'=>$Email_Id[0]['Email']);
            $form->populate($data);
        }

        $ordername =  $this->_request->get('filter_order_Dir'); //get the asc or desc value
       
        if($ordername=='desc')//get the order name
        {
            $this->view->imgname='/public/images/up-arrow.gif';//assign the images
            $assign_ordername='asc';//assign the order name
        }
        else
        {
            $this->view->imgname='/public/images/down-arrow.gif';
            $assign_ordername='desc';
        }
      
        $descriptionview=$this->_request->get('descriptionview');//get the rows per page
        
        if($descriptionview==''){
            $descriptionview='detail';//default rows per page
        }
        
        $this->view->descriptionview=$descriptionview;//pass the rows per page
        $this->view->ordername=$assign_ordername;//assign the asc or desc value
        $orderby =  trim($filter->filter($this->_request->get('filter_order')));//get the field name
        $this->view->orderby=$orderby;//assign the field name

        $logolist=$getvalue->getLogoList();//Get the values from database for Logo
        $this->view->logolist=$logolist;//show the values in index page
        
        $rowsperpage=$this->_request->get('sortvalue');//get the rows per page
        
        if($rowsperpage=='') {
            $rowsperpage=15;//default rows per page
        }
        
        $this->view->rowsperpage=$rowsperpage;//pass the rows per page
        $page= $this->_request->get('pagevalue');//get the page value
        if($page=='') {
            $page=1;//assign the default page value
        }
        
        $this->view->page=$page;//assign the page value
        
        $offset = $obj->offsetCalculate($page,$rowsperpage);
        $this->view->offset=$offset;

        //Quick Job Search Input Starts here
        $_COOKIE['Keyword']='';
        // $Keywords =  trim($this->_request->get('Keywords'));
        $Keywords =  trim($this->_request->get('Headerkeywords'));

        if(trim($this->_request->get('Headerkeywords'))=='') {
            $Keywords =  trim($this->_request->get('Keywords'));
        }
        
        if($this->_request->get('Keyword_result')== "'") {
             $this->view->Keywords='';
             $Keywords='';            
        }
        
        if($Keywords=='Clinical Project Manager' || $Keywords==''|| $Keywords=="'") {                    
            $this->view->Keywords='';
            $Keywords='';
        } else {
            $this->view->Keywords=$Keywords;
          //  setcookie("Keyword", "", time()-(60*60*24*365), "/");//clear the cookies values
           // setcookie("Keyword",$Keywords, time()+(60*60*24*365),'/'); //Store The values in Cookies
        }
        
        if($this->_request->get('Keyword_result')!='Clinical Project Manager' && $this->_request->get('Keyword_result')!='')
        {
            if($this->_request->get('Keyword_result')== "'") {
	            $this->view->Keywords='';
	        	$Keywords='';
	        } else {
	            $Keywords = trim($this->_request->get('Keyword_result'));
	            $this->view->Keywords=$Keywords;
	         //   setcookie("Keyword", "", time()-(60*60*24*365), "/");//clear the cookies values
	         //   setcookie("Keyword",$Keywords, time()+(60*60*24*365),'/'); 
	            ////Store The values in Cookies
	        }
        }
       
        if($this->_request->get('category_id')=='') {
            //$Category = trim($this->_request->get('Category'));
            $Category = trim($this->_request->get('Headercategory'));
            if(trim($this->_request->get('Headercategory'))=='') {
            	$Category = trim($this->_request->get('Category'));
        	}
        } else {
            $Category =trim($this->_request->get('category_id'));
        }
        
        $this->view->Category=$Category;
        //$Location = trim($this->_request->get('Location'));
        $Location = trim($this->_request->get('Headerlocation'));

        if(trim($this->_request->get('Headerlocation'))=='') {
            $Location = trim($this->_request->get('Location'));
            if($Location=="'") {
               $Location=""; 
            }
        }
        
//         if($this->_request->get('state')!='') {
//             $Location =trim($this->_request->get('state'));
//         } 
        
        if($Location=='City' || $Location =="'") {
            $this->view->Location='';
            $Location='';
        } else {
            $this->view->Location=$Location;
        }
        
        if($this->_request->get('Category_result')!='' && $this->_request->get('Category_result')!='City, State, Zipcode' && $this->_request->get('Category_result')!="'" && $this->_request->get('Category_result')!='City') 
        {
            $Location = trim($this->_request->get('Category_result'));
            $this->view->Location=$Location;
        }
        //Quick Job Search Input Ends here
        
        //Refine Job Search Input Starts here
        $Skill = trim($this->_request->get('Skill'));
        $this->view->Skill=$Skill;
        $Refine_Country_Id = trim($this->_request->get('Refine_Country_Id'));
        $this->view->Refine_Country_Id=$Refine_Country_Id;
        $Refine_Country_Value = trim($this->_request->get('Refine_Country_Value'));
        $this->view->Refine_Country_Value=$Refine_Country_Value;

//         $Refine_State_Id = trim($this->_request->get('Refine_State_Id'));
//         $this->view->Refine_State_Id=$Refine_State_Id;
//         $Refine_State_Value = trim($this->_request->get('Refine_State_Value'));
//         $this->view->Refine_State_Value=$Refine_State_Value;
        
        $Refine_City = trim($this->_request->get('Refine_City'));
        $this->view->Refine_City=$Refine_City;
        $Refine_Company_Type = trim($this->_request->get('Refine_Company_Type'));
        $this->view->Refine_Company_Type=$Refine_Company_Type;
        $Refine_Company_Type_Value= trim($this->_request->get('Refine_Company_Type_Value'));
        $this->view->Refine_Company_Type_Value=$Refine_Company_Type_Value;
        $Refine_Travel= trim($this->_request->get('Refine_Travel'));
        $this->view->Refine_Travel=$Refine_Travel;
        $Refine_Travel_Value= trim($this->_request->get('Refine_Travel_Value'));
        $this->view->Refine_Travel_Value=$Refine_Travel_Value;
        $Refine_Companyname= trim($this->_request->get('Refine_Companyname'));
        $this->view->Refine_Companyname=$Refine_Companyname;
        $Refine_Companyid= trim($this->_request->get('Refine_Companyid'));
        $this->view->Refine_Companyid=$Refine_Companyid;
        $Refine_Job_Type= trim($this->_request->get('Refine_Job_Type'));
        $this->view->Refine_Job_Type=$Refine_Job_Type;
        $Refine_Job_Id= trim($this->_request->get('Refine_Job_Id'));
        $this->view->Refine_Job_Id=$Refine_Job_Id;
        //Refine Job Search Input Ends here
        
        //Advance Job Search Input Starts here
        $Date_Posted = trim($this->_request->get('Date_Posted'));
        $this->view->Date_Posted=$Date_Posted;
        $current_date=date('Y-m-d');
        $twoday=date("Y-m-d", strtotime("-1 month", strtotime($current_date)));
        $Advance_Keywords = trim($this->_request->get('Advance_Keywords'));
        if($Advance_Keywords=='Clinical Project Manager' || $Advance_Keywords=='' ||  $Advance_Keywords=="'") {           
            $this->view->Advance_Keywords='';
            $Advance_Keywords='';
        } else {            
            $this->view->Advance_Keywords=$Advance_Keywords;
            setcookie("Keyword", "", time()-(60*60*24*365), "/");//clear the cookies values
            setcookie("Keyword",$Advance_Keywords, time()+(60*60*24*365),'/'); //Store The values in Cookies             
        }
        
        $Keywords_Select = trim($this->_request->get('Keywords_Select'));
        $this->view->Keywords_Select=$Keywords_Select;
        $Job_Title = trim($this->_request->get('Job_Title'));
        $this->view->Job_Title=$Job_Title;
        $Advance_Location = trim($this->_request->get('Advance_Location'));
         
        if($Advance_Location=='City' || $Advance_Location=="'") {
            $this->view->Advance_Location='';
            $Advance_Location='';
        } else {
            $this->view->Advance_Location=$Advance_Location;
        }

        $Advance_Location1 = trim($this->_request->get('Advance_Location1'));

        if($Advance_Location1=='City') {
            $this->view->Advance_Location1='';
            $Advance_Location1='';
        } else {
            $this->view->Advance_Location1=$Advance_Location1;
        }

        $Advance_Location2 = trim($this->_request->get('Advance_Location2'));
        if($Advance_Location2=='City') {
            $this->view->Advance_Location2='';
            $Advance_Location2='';
        } else {
            $this->view->Advance_Location2=$Advance_Location2;
        }

        $Advance_Category = trim($this->_request->get('Advance_Category'));
        $this->view->Advance_Category=$Advance_Category;
        $Advance_Category1 = trim($this->_request->get('Advance_Category1'));
        $this->view->Advance_Category1=$Advance_Category1;
        $Advance_Category2 = trim($this->_request->get('Advance_Category2'));
        $this->view->Advance_Category2=$Advance_Category2;
        $Experience = trim($this->_request->get('Experience'));
        $this->view->Experience=$Experience;
        $Salary = trim($this->_request->get('Salary'));
        $this->view->Salary=$Salary;
        $Company_Name = trim($this->_request->get('Company_Name'));

		if($Company_Name == "'") {
          $this->view->Company_Name="";
          $Company_Name="";
        }
        
        $this->view->Company_Name=$Company_Name;
        $Job_Type = trim($this->_request->get('Job_Type'));
        $this->view->Job_Type=$Job_Type;
        $Education = trim($this->_request->get('Education'));
        $this->view->Education=$Education;
        $Travel = trim($this->_request->get('Travel'));
        $this->view->Travel=$Travel;
        $Company_Type = trim($this->_request->get('Company_Type'));
        $this->view->Company_Type=$Company_Type;
        $Advance_Country_Id = trim($this->_request->get('Advance_Country_Id'));
        $this->view->Advance_Country_Id=$Advance_Country_Id;

//         $State_Id_List =$this->_request->get('State_Id_List');
//         $this->view->State_Id_List='';
        
//         if($State_Id_List!='') {
//             $value.=implode(',',$State_Id_List);
//         } else {
//             $value=trim($this->_request->get('State_Id_New'));
//         }
        //Advance Job Search Input Ends here
        
        //Company Job Search Input Starts here
        $Search_Company = trim($this->_request->get('Search_Company'));
        // $this->view->Search_Company=$Search_Company;
        if($Search_Company=='City' || $Search_Company == "'") {
        	$this->view->Search_Company='';
           	$Search_Company='';
        } else {
              $this->view->Search_Company=$Search_Company;
        }
        
        $Industry_Type_Id = trim($this->_request->get('Industry_Type_Id'));
        $this->view->Industry_Type_Id=$Industry_Type_Id;
        $Search_Location = trim($this->_request->get('Search_Location'));
       
        if($Search_Location=='City' || $Search_Location == "'") {
            $this->view->Search_Location='';
            $Search_Location='';
        } else {
            $this->view->Search_Location=$Search_Location;
        }
        
        $No_Employee_Id = trim($this->_request->get('No_Employee_Id'));
        $this->view->No_Employee_Id=$No_Employee_Id;
        //Company Job Search Input Ends here
        
        //Location Job Search Input Starts here
        $Country_Id = trim($this->_request->get('Country_Id'));
        $this->view->Country_Id=$Country_Id;
        
//         $State = $this->_request->get('State');
//         $this->view->State='';
//         if($State!='') {
//             $value_new.=implode(',',$State);
//         } else {
//             $value_new=trim($this->_request->get('State_New'));
//         }

        $City = trim($this->_request->get('City'));
        if($City == "'"){
        	$this->view->City='';
            $City='';        
        } else {
        	$this->view->City=$City;
        }
        
        $Location_Keywords = trim($this->_request->get('Location_Keywords'));
        if($Location_Keywords=='Clinical Project Manager' || $Location_Keywords=='' || $Location_Keywords=="'") {
            $this->view->Location_Keywords='';
            $Location_Keywords='';
        } else {
            $this->view->Location_Keywords=$Location_Keywords;
            setcookie("Keyword", "", time()-(60*60*24*365), "/");//clear the cookies values
            setcookie("Keyword",$Location_Keywords, time()+(60*60*24*365),'/'); //Store The values in Cookies           
        }
        $this->view->Location_Keywords=$Location_Keywords;
        //Location Job Search Input Ends here
        
        //Search Company Name by Alphabetical order
        $Alpha_Company = trim($this->_request->get('Alpha_Company'));
        $this->view->Alpha_Company=$Alpha_Company;
        //Search Location by Alphabetical order
        $Alpha_Location = trim($this->_request->get('Alpha_Location'));
        $this->view->Alpha_Location=$Alpha_Location;

        //Run the saved search coding Starts here
        if($this->_request->get['searchid']!='') {
            $searchlist=$getvalue->getsavedsearch($this->_request->get['searchid']);//Get the values from database for Logo
            $Keywords_Select=$searchlist['Keywords_Select'];
            $this->view->Keywords_Select=$searchlist['Keywords_Select'];
            $Job_Title =$searchlist['Job_Title'];
            $this->view->Job_Title =$searchlist['Job_Title'];
            
            //Quick Job Search Input Starts here
            if($searchlist['Keywords']!='') {
                $Keywords=$searchlist['Keywords'];
                $this->view->Keywords=$searchlist['Keywords'];
            }
            
            if($searchlist['Category']!='0') {
                $Category=$searchlist['Category'];
                $this->view->Category=$searchlist['Category'];
            } else {
                $Category='';
            }
            
            if($searchlist['Location']!='') {
                $Location=$searchlist['Location'];
                $this->view->Location=$searchlist['Location'];
            }
            //Quick Job Search Input Ends here
            
            //Advance Job Search Input Starts here
            if($searchlist['Date_Posted']!='') {
                $Date_Posted=$searchlist['Date_Posted'];
                $this->view->Date_Posted=$searchlist['Date_Posted'];
            }
            
            if($searchlist['Advance_Keywords']!='') {
                $Advance_Keywords=$searchlist['Advance_Keywords'];
                $this->view->Advance_Keywords=$searchlist['Advance_Keywords'];
            }
            
            if($searchlist['Advance_Location']!='') {
                $Advance_Location=$searchlist['Advance_Location'];
                $this->view->Advance_Location=$searchlist['Advance_Location'];
            }
            
            if($searchlist['Advance_Location1']!='') {
                $Advance_Location1=$searchlist['Advance_Location1'];
                $this->view->Advance_Location1=$searchlist['Advance_Location1'];
            }
            
            if($searchlist['Advance_Location2']!='') {
                $Advance_Location2=$searchlist['Advance_Location2'];
                $this->view->Advance_Location2=$searchlist['Advance_Location2'];
            }

            if($searchlist['Advance_Category']!='0') {
                $Advance_Category=$searchlist['Advance_Category'];
                $this->view->Advance_Category=$searchlist['Advance_Category'];
            } else {
                $Advance_Category='';
                $this->view->Advance_Category='';
            }
            
            if($searchlist['Advance_Category1']!='0') {
                $Advance_Category1=$searchlist['Advance_Category1'];
                $this->view->Advance_Category1=$searchlist['Advance_Category1'];
            } else {
                $Advance_Category1='';
                $this->view->Advance_Category1='';
            }
            
            if($searchlist['Advance_Category2']!='0') {
                $Advance_Category2=$searchlist['Advance_Category2'];
                $this->view->Advance_Category2=$searchlist['Advance_Category2'];
            } else {
                $Advance_Category2='';
                $this->view->Advance_Category2='';
            }
            
            if($searchlist['Experience']!='0') {
                $Experience=$searchlist['Experience'];
                $this->view->Experience=$searchlist['Experience'];
            } else {
                $Experience='';
                $this->view->Experience='';
            }

            if($searchlist['Salary']!='0') {
                $Salary=$searchlist['Salary'];
                $this->view->Salary=$searchlist['Salary'];
            } else {
                $Salary='';
                $this->view->Salary='';
            }

            if($searchlist['Job_Type']!='0') {
                $Job_Type=$searchlist['Job_Type'];
                $this->view->Job_Type=$searchlist['Job_Type'];
            } else {
                $Job_Type='';
                $this->view->Job_Type='';
            }

            if($searchlist['Education']!='0') {
                $Education=$searchlist['Education'];
                $this->view->Education=$searchlist['Education'];
            } else {
                $Education='';
                $this->view->Education='';
            }

            if($searchlist['Travel']!='0') {
                $Travel=$searchlist['Travel'];
                $this->view->Travel=$searchlist['Travel'];
            } else {
                $Travel='';
                $this->view->Travel='';
            }

            if($searchlist['Company_Type']!='0') {
                $Company_Type=$searchlist['Company_Type'];
                $this->view->Company_Type=$searchlist['Company_Type'];
            } else {
                $Company_Type='';
                $this->view->Company_Type='';
            }

            if($searchlist['Advance_Country_Id']!='0') {
                $Advance_Country_Id=$searchlist['Advance_Country_Id'];
                $this->view->Advance_Country_Id=$searchlist['Advance_Country_Id'];
            } else {
                $Advance_Country_Id='';
                $this->view->Advance_Country_Id='';
            }

//             if($searchlist['Advance_State_Id']!='0' && $searchlist['Advance_State_Id']!='') {
//                 $Advance_State_Id=$searchlist['Advance_State_Id'];
//                 $value=$searchlist['Advance_State_Id'];
//             } else {
//                 $value='';
//             }
            //Advance Job Search Input Ends here
            
            //Company Job Search Input Starts here
            if($searchlist['Search_Company']!='') {
                $Search_Company=$searchlist['Search_Company'];
                $this->view->Search_Company=$searchlist['Search_Company'];
            }
            
            if($searchlist['Industry_Type_Id']!='0') {
                $Industry_Type_Id=$searchlist['Industry_Type_Id'];
                $this->view->Industry_Type_Id=$searchlist['Industry_Type_Id'];
            } else {
                $Industry_Type_Id='';
                $this->view->Industry_Type_Id='';
            }
            
            if($searchlist['Search_Location']!='') {
                $Search_Location=$searchlist['Search_Location'];
                $this->view->Search_Location=$searchlist['Search_Location'];
            }
            
            if($searchlist['No_Employee_Id']!='0') {
                $No_Employee_Id=$searchlist['No_Employee_Id'];
                $this->view->No_Employee_Id=$searchlist['No_Employee_Id'];
            } else {
                $No_Employee_Id='';
                $this->view->No_Employee_Id='';
            }
            //Company Job Search Input Starts here
            
            //Location Job Search Input Starts here
//             if($searchlist['State']!='' && $searchlist['State']!='0') {
//                 $value_new=$searchlist['State'];
//             }
            
            if($searchlist['Country_Id']!='0') {
                $Country_Id=$searchlist['Country_Id'];
                $this->view->Country_Id=$searchlist['Country_Id'];
            } else {
                $Industry_Type_Id='';
                $this->view->Industry_Type_Id='';
            }
            
            if($searchlist['City']!='') {
                $City=$searchlist['City'];
                $this->view->City=$searchlist['City'];
            }
            
            if($searchlist['Location_Keywords']!='') {
                $Location_Keywords=$searchlist['Location_Keywords'];
                $this->view->Location_Keywords=$searchlist['Location_Keywords'];
            }
            //Location Job Search Input Ends here

            //Search Company Name by Alphabetical order
            if($searchlist['Alpha_Company']!='') {
                $Alpha_Company=$searchlist['Alpha_Company'];
                $this->view->Alpha_Company=$searchlist['Alpha_Company'];
            }

            //Search Location by Alphabetical order
            if($searchlist['Alpha_Location']!='') {
                $Alpha_Location=$searchlist['Alpha_Location'];
                $this->view->Alpha_Location=$searchlist['Alpha_Location'];
            }

            //Search Location by Keyskill
            if($searchlist['Keyskill']!='') {
                $Skill=$searchlist['Keyskill'];
                $this->view->Skill=$searchlist['Keyskill'];
            }
        }
        //Run the saved search coding Ends here
        
        // Checking the inputs are valid or not  for Quick job search
       
        if($Keywords!='') {
            $All_Values_Array = $this->view->Keywords;
            $all_values=explode(',',$All_Values_Array);
            
            if(!isset($all_values[1])) {
            	$all_values=explode(' ',$All_Values_Array);
            }

            $totalrow_key=count($all_values);
            $Keywords='';
            for($i=0;$i<$totalrow_key;$i++) {
                if($i==0) {
                    $condition='AND';
                } else {
                    $condition='OR';
                }
                
                $Keywords.=" $condition (getdetails.Post_Job_Title LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills1 LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills2  LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills3 LIKE '%".$all_values[$i]."%' OR company.Company_Name LIKE '%".$all_values[$i]."%' OR getdetails.Hiring_For LIKE '".$all_values[$i]."%' OR category.Category_Name like '%".$all_values[$i]."%')";
            }
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Keywords='';
        }

//         if($Location!='') {
//             $All_Values_Array = $this->view->Location;
//             $all_values_zipcode=explode('-',$All_Values_Array);
            
//             if(is_numeric(trim($all_values_zipcode[0]))) {
//                 $zipcode_res=$getvalue->zipcodecheck($all_values_zipcode[0]);
//                 if($zipcode_res['City_Zipcode']!='') {

//                     $miles_value=$getvalue->mileslimit();
//                     $zipMilesHigh=$miles_value['Miles_Limit']; 
                    
//                     if($miles_value['Miles_Limit']=='' || $miles_value['Miles_Limit']=='0') {
//                         $zipMilesHigh='30';
//                     }

//                     $related_zipcode_count='';
//                     $radius_zipcode=$getvalue->zipRadiusSQL("", $zipcode_res['City_Zipcode'], $zipcode_res['City_Latitude'], $zipcode_res['City_Longitude'], $zipMilesHigh);
//                     $related_zipcode_count=count($radius_zipcode);
//                     if($related_zipcode_count!='' && $related_zipcode_count!='0') {
//                         $zipcode_value='';
//                         for($j=0;$j<$related_zipcode_count;$j++) {
//                             //if($radius_zipcode[$j]['City_Zipcode']!=$zipcode_res['City_Zipcode'])
//                            // {
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==4) {
//                                     $radius_zipcode[$j]['City_Zipcode']='0'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==3) {
//                                     $radius_zipcode[$j]['City_Zipcode']='00'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
//                                 if($j==0) {
//                                     if($all_values_zipcode[1]=='') {
//                                         $condition='AND';
//                                     } else {
//                                         $condition='OR';
//                                     }                                    
//                                 } else {
//                                     $condition='OR';
//                                 }
                                
//                                 $zipcode_value.=" $condition (getdetails.Location LIKE '%".$radius_zipcode[$j]['City_Zipcode']."%')";
//                            // }
//                         }
//                     } else {
//                         $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                     }
//                 } else {
//                     $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                 }
//             } else {
//                 $all_values_zipcode[1]=$all_values_zipcode[0];
//             }
            
//             if($all_values_zipcode[1]!='') {
//                 $all_values=explode(',',$all_values_zipcode[1]);
//                 $totalrow_key=count($all_values);
//                 $Location='';
//                 for($i=0;$i<$totalrow_key;$i++) {
//                     if($i==0) {
//                         $condition='AND';
//                     } else {
//                         $condition='OR';
//                     }
//                     $Location.=" $condition (getdetails.Location LIKE '%".trim($all_values[$i])."%')".$zipcode_value;
//                 }
//             } else {
//                 $Location=$zipcode_value;
//             }
//             $this->view->Advance_Country_Id='';
//             $Advance_Country_Id='';
//         } else {
//             $Location='';
//         }
        
        if($Category!='') {
           // $Category=" AND (getdetails.Category_Id='".$Category."' OR  getdetails.Sub_Category_Id='".$Category."')";
            $Category=" AND (multicategory.Cat_Id='".$Category."')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Category='';
        }
        // Checking the inputs are valid or not  for Refine job search

        if($Skill!='') {
            $Skill="AND (getdetails.Key_Skills1 LIKE '%".$Skill."%')";
            //  $Skill="AND (getdetails.Key_Skills1 LIKE '%".$Skill."%' OR  getdetails.Key_Skills2  LIKE '%".$Skill."%' OR  getdetails.Key_Skills3 LIKE '%".$Skill."%')";
        } else {
            $Skill='';
        }
        
        if($Refine_Country_Id!='' ) {
            $Refine_Country_Id=" AND (getdetails.Country_Id1='".$Refine_Country_Id."' OR getdetails.Country_Id2='".$Refine_Country_Id."' OR getdetails.Country_Id3='".$Refine_Country_Id."')";
        } else {
            $Refine_Country_Id="";
        }
        
//         if($Refine_State_Id!='' ) {
//             $Refine_State_Id=" AND (getdetails.State_Id1 LIKE '%".$Refine_State_Id."%' OR getdetails.State_Id2 LIKE '%".$Refine_State_Id."%' OR getdetails.State_Id3 LIKE '%".$Refine_State_Id."%')";
//         } else {
//             $Refine_State_Id="";
//         }
        
        if($Refine_City!='' ) {
            $Refine_City=" AND (getdetails.City1 LIKE '%".$Refine_City."%' OR getdetails.City2 LIKE '%".$Refine_City."%' OR getdetails.City3 LIKE '%".$Refine_City."%')";
        } else {
            $Refine_City="";
        }
        
        if($Refine_Company_Type!='' ) {
            $Refine_Company_Type=" AND (recdetails.Company_Type_Id='".$Refine_Company_Type."')";
        } else {
            $Refine_Company_Type="";
        }
        
        if($Refine_Travel!='' ) {
            $Refine_Travel=" AND (getdetails.Travel_Amount_Id='".$Refine_Travel."')";
        } else {
            $Refine_Travel="";
        }
        
        if($Refine_Companyname!='' ) {
            $Refine_Companyname=" AND (company.Company_Name LIKE '".$Refine_Companyname."%' OR getdetails.Hiring_For LIKE '".$Refine_Companyname."%')";
        } else {
            $Refine_Companyname="";
        }
        
        if($Refine_Job_Id!='' ) {
            $Refine_Job_Id= " AND (getdetails.Job_Type_Id='".$Refine_Job_Id."')";
        } else {
            $Refine_Job_Id="";
        }
        // Checking the inputs are valid or not  for Advance job search

        if($Advance_Keywords=='') {
            $Advance_Keywords='';
        } else {
            if($Keywords_Select=='This exact phrase' && $Job_Title=='0' ) {
                $Advance_Keywords=" AND (getdetails.Post_Job_Title='".$Advance_Keywords."' OR  getdetails.Key_Skills1='".$Advance_Keywords."' OR  getdetails.Key_Skills2='".$Advance_Keywords."' OR  getdetails.Key_Skills3='".$Advance_Keywords."'  OR company.Company_Name LIKE '".$Advance_Keywords."%' OR getdetails.Hiring_For LIKE '".$Advance_Keywords."%' OR category.Category_Name like '%".$Advance_Keywords."%' )";
            } elseif($Keywords_Select=='This exact phrase' && $Job_Title=='1') {
                $Advance_Keywords=" AND (getdetails.Post_Job_Title='".$Advance_Keywords."')";
            } elseif($Keywords_Select=='All these words' && $Job_Title=='0' ) {
                $Advance_Keywords=" AND (getdetails.Post_Job_Title='".$Advance_Keywords."' OR  getdetails.Key_Skills1='".$Advance_Keywords."' OR  getdetails.Key_Skills2='".$Advance_Keywords."' OR  getdetails.Key_Skills3='".$Advance_Keywords."'  OR company.Company_Name LIKE '".$Advance_Keywords."%' OR getdetails.Hiring_For LIKE '".$Advance_Keywords."%' OR category.Category_Name like '%".$Advance_Keywords."%')";
            } elseif($Keywords_Select=='All these words' && $Job_Title=='1') {
                $Advance_Keywords=" AND (getdetails.Post_Job_Title LIKE'%".$Advance_Keywords."%' OR category.Category_Name like '%".$Advance_Keywords."%')";
            } elseif($Keywords_Select=='Any of these words' && $Job_Title=='0' ) {
                $All_Values_Array = $this->view->Advance_Keywords;
                $all_values=explode(',',$All_Values_Array);
                if(!isset($all_values[1])) {
                	$all_values=explode(' ',$All_Values_Array);
                }
               
                $totalrow_key=count($all_values);
                $Advance_Keywords='';
                for($i=0;$i<$totalrow_key;$i++) { 	
                    if($i==0) {
                        $condition='AND';
                    } else {
                        $condition='OR';
                    }
                    $Advance_Keywords.=" $condition (getdetails.Post_Job_Title LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills1 LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills2  LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills3 LIKE '%".$all_values[$i]."%' OR company.Company_Name LIKE '".$all_values[$i]."%' OR getdetails.Hiring_For LIKE '".$all_values[$i]."%' OR category.Category_Name like '%".$all_values[$i]."%')";
                } 
            }
            elseif($Keywords_Select=='Any of these words' && $Job_Title=='1') {
                $All_Values_Array = $this->view->Advance_Keywords;
                
                $all_values=explode(',',$All_Values_Array);
                if(!isset($all_values[1])) {
                	$all_values=explode(' ',$All_Values_Array);
                }
                
                
                $totalrow_key=count($all_values);
                $Advance_Keywords='';
                for($i=0;$i<$totalrow_key;$i++) {
                    if($i==0) {
                        $condition='AND';
                    } else {
                        $condition='OR';
                    }
                    $Advance_Keywords.=" $condition (getdetails.Post_Job_Title LIKE'%".$all_values[$i]."%')";
                }
            }
        }
        
        
       
//         if($Advance_Location!='') {
//             $All_Values_Array = $this->view->Advance_Location;
//             $all_values_zipcode=explode('-',$All_Values_Array);
            
//             if(is_numeric(trim($all_values_zipcode[0]))) {
//                 $zipcode_res=$getvalue->zipcodecheck($all_values_zipcode[0]);

//                 if($zipcode_res['City_Zipcode']!='') {
//                     $miles_value=$getvalue->mileslimit();
//                     $zipMilesHigh=$miles_value['Miles_Limit'];
//                     if($miles_value['Miles_Limit']=='' || $miles_value['Miles_Limit']=='0') {
//                         $zipMilesHigh='30';
//                     }

//                     $related_zipcode_count='';
//                     $radius_zipcode=$getvalue->zipRadiusSQL("", $zipcode_res['City_Zipcode'], $zipcode_res['City_Latitude'], $zipcode_res['City_Longitude'], $zipMilesHigh);
//                     $related_zipcode_count=count($radius_zipcode);
                    
//                     if($related_zipcode_count!='' && $related_zipcode_count!='0') {
//                         $zipcode_value='';
//                         for($j=0;$j<$related_zipcode_count;$j++) {
//                             //if($radius_zipcode[$j]['City_Zipcode']!=$zipcode_res['City_Zipcode'])
//                            // {
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==4) {
//                                     $radius_zipcode[$j]['City_Zipcode']='0'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==3) {
//                                     $radius_zipcode[$j]['City_Zipcode']='00'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if($j==0){
//                                     if($all_values_zipcode[1]=='') {
//                                         $condition='AND';
//                                     } else {
//                                         $condition='OR';
//                                     }
//                                 } else {
//                                     $condition='OR';
//                                 }
                                
//                                 $zipcode_value.=" $condition (getdetails.Location LIKE '%".$radius_zipcode[$j]['City_Zipcode']."%')";                              
//                            // }
//                         }
//                     } else {
//                         $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                     }
//                 } else {
//                     $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                 }
//             } else {
//                 $all_values_zipcode[1]=$all_values_zipcode[0];
//             }
            
//             if($all_values_zipcode[1]!='') {
//                 $all_values=explode(',',$all_values_zipcode[1]);
//                 $totalrow_key=count($all_values);
//                 $Advance_Location='';
                
//                 for($i=0;$i<$totalrow_key;$i++) {
//                     if($i==0) {
//                         $condition='AND';
//                     } else {
//                         $condition='OR';
//                     }
//                     $Advance_Location.=" $condition (getdetails.Location LIKE '%".trim($all_values[$i])."%')".$zipcode_value;
//                 }
//             } else {
//                 $Advance_Location=$zipcode_value;
//             }
//         } else {
//             $Advance_Location='';
//         }
        
//         if($Advance_Location1!='City' && $Advance_Location1!='') {
//             $All_Values_Array = $this->view->Advance_Location1;
//             $all_values_zipcode=explode('-',$All_Values_Array);
            
//             if(is_numeric(trim($all_values_zipcode[0]))) {
//                 $zipcode_res=$getvalue->zipcodecheck($all_values_zipcode[0]);
                
//                 if($zipcode_res['City_Zipcode']!='') {
//                     $miles_value=$getvalue->mileslimit();
//                     $zipMilesHigh=$miles_value['Miles_Limit'];
                    
//                     if($miles_value['Miles_Limit']=='' || $miles_value['Miles_Limit']=='0'){
//                         $zipMilesHigh='30';
//                     }

//                     $related_zipcode_count='';
//                     $radius_zipcode=$getvalue->zipRadiusSQL("", $zipcode_res['City_Zipcode'], $zipcode_res['City_Latitude'], $zipcode_res['City_Longitude'], $zipMilesHigh);
//                     $related_zipcode_count=count($radius_zipcode);
                    
//                     if($related_zipcode_count!='' && $related_zipcode_count!='0') {
//                         $zipcode_value='';
                        
//                         for($j=0;$j<$related_zipcode_count;$j++) {
//                             //if($radius_zipcode[$j]['City_Zipcode']!=$zipcode_res['City_Zipcode'])
//                             //{
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==4) {
//                                     $radius_zipcode[$j]['City_Zipcode']='0'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==3) {
//                                     $radius_zipcode[$j]['City_Zipcode']='00'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if($j==0) {
//                                     if($all_values_zipcode[1]=='') {
//                                         $condition='AND';
//                                     } else {
//                                         $condition='OR';
//                                     }
//                                 } else {
//                                     $condition='OR';
//                                 }
//                                 $zipcode_value.=" $condition (getdetails.Location LIKE '%".$radius_zipcode[$j]['City_Zipcode']."%')";
//                            // }
//                         }
//                     } else {
//                         $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                     }
//                 } else {
//                     $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                 }
//             } else {
//                 $all_values_zipcode[1]=$all_values_zipcode[0];
//             }
            
//             if($all_values_zipcode[1]!='') {
//                 $all_values=explode(',',$all_values_zipcode[1]);
//                 $totalrow_key=count($all_values);
//                 $Advance_Location1='';
//                 for($i=0;$i<$totalrow_key;$i++) {
//                     if($i==0) {
//                         $condition='AND';
//                     } else {
//                         $condition='OR';
//                     }
//                     $Advance_Location1.=" $condition (getdetails.Location LIKE '%".trim($all_values[$i])."%')".$zipcode_value;
//                 }
//             } else {
//                 $Advance_Location1=$zipcode_value;
//             }
//         } else {
//             $Advance_Location1='';
//         }
        
//         if($Advance_Location2!='City' && $Advance_Location2!='') {
//             $All_Values_Array = $this->view->Advance_Location2;
//             $all_values_zipcode=explode('-',$All_Values_Array);
            
//             if(is_numeric(trim($all_values_zipcode[0]))) {
//                 $zipcode_res=$getvalue->zipcodecheck($all_values_zipcode[0]);
//                 if($zipcode_res['City_Zipcode']!='') {

//                     $miles_value=$getvalue->mileslimit();
//                     $zipMilesHigh=$miles_value['Miles_Limit'];
                    
//                     if($miles_value['Miles_Limit']=='' || $miles_value['Miles_Limit']=='0') {
//                         $zipMilesHigh='30';
//                     }

//                     $related_zipcode_count='';
//                     $radius_zipcode=$getvalue->zipRadiusSQL("", $zipcode_res['City_Zipcode'], $zipcode_res['City_Latitude'], $zipcode_res['City_Longitude'], $zipMilesHigh);
//                     $related_zipcode_count=count($radius_zipcode);
                    
//                     if($related_zipcode_count!='' && $related_zipcode_count!='0') {
//                         $zipcode_value='';
//                         for($j=0;$j<$related_zipcode_count;$j++) {
//                             //if($radius_zipcode[$j]['City_Zipcode']!=$zipcode_res['City_Zipcode'])
//                            // {
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==4) {
//                                     $radius_zipcode[$j]['City_Zipcode']='0'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==3) {
//                                     $radius_zipcode[$j]['City_Zipcode']='00'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if($j==0) {
//                                     if($all_values_zipcode[1]=='') {
//                                         $condition='AND';
//                                     } else {
//                                         $condition='OR';
//                                     }
//                                 } else {
//                                     $condition='OR';
//                                 }
                                
//                                 $zipcode_value.=" $condition (getdetails.Location LIKE '%".$radius_zipcode[$j]['City_Zipcode']."%')";
//                             //}
//                         }
//                     } else {
//                         $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                     }
//                 } else {
//                     $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                 }
//             } else {
//                 $all_values_zipcode[1]=$all_values_zipcode[0];
//             }
            
//             if($all_values_zipcode[1]!='') {
//                 $all_values=explode(',',$all_values_zipcode[1]);
//                 $totalrow_key=count($all_values);
//                 $Advance_Location2='';
                
//                 for($i=0;$i<$totalrow_key;$i++) {
//                     if($i==0) {
//                         $condition='AND';
//                     } else {
//                         $condition='OR';
//                     }
//                     $Advance_Location2.=" $condition (getdetails.Location LIKE '%".trim($all_values[$i])."%')".$zipcode_value;
//                 }
//             } else {
//                 $Advance_Location2=$zipcode_value;
//             }
//         } else {
//             $Advance_Location2='';
//         }
        
        if($Advance_Category!='' ) {
           // $Advance_Category=" AND (getdetails.Category_Id='".$Advance_Category."' OR  getdetails.Sub_Category_Id='".$Advance_Category."')";
            $Advance_Category=" AND (multicategory.Cat_Id='".$Advance_Category."')";
        } else {
            $Advance_Category='';
        }
        
        if($Advance_Category1!='' ) {
            //$Advance_Category1=" AND (getdetails.Category_Id='".$Advance_Category1."' OR  getdetails.Sub_Category_Id='".$Advance_Category1."')";
            $Advance_Category1=" AND (multicategory.Cat_Id='".$Advance_Category1."')";
        } else {
            $Advance_Category1='';
        }
        
        if($Advance_Category2!='' ) {
           // $Advance_Category2=" AND (getdetails.Category_Id='".$Advance_Category2."' OR  getdetails.Sub_Category_Id='".$Advance_Category2."')";
            $Advance_Category2=" AND (multicategory.Cat_Id='".$Advance_Category2."')";
        } else {
            $Advance_Category2='';
        }
        
        if($Experience!='' ) {
            $Experience=" AND (getdetails.Experience_Id='".$Experience."')";
        } else {
            $Experience="";
        }
        
        if($Education!='' ) {
            $Education=" AND (getdetails.Qualification_Id='".$Education."')";
        } else {
            $Education="";
        }
        
        if($Travel!='' ) {
            $Travel=" AND (getdetails.Travel_Amount_Id='".$Travel."')";
        } else {
            $Travel="";
        }
        
        if($Job_Type!='' ) {
            $Job_Type=" AND (getdetails.Job_Type_Id='".$Job_Type."')";
        } else {
            $Job_Type="";
        }
        
        if($Company_Type!='' ) {
            $Company_Type=" AND (recdetails.Company_Type_Id='".$Company_Type."')";
        } else {
            $Company_Type="";
        }
        
        if($Company_Name!='' ) {
            $Company_Name=" AND (company.Company_Name LIKE '".$Company_Name."%' OR getdetails.Hiring_For LIKE '".$Company_Name."%')";
        } else {
            $Company_Name="";
        }
        
        if($Salary!='' ) {
            $Salary=" AND (getdetails.Salary <='".$Salary."')";
        } else {
            $Salary="";
        }
        
        if($Date_Posted!='') {
            if($Date_Posted=='Today') {
                // date_format(savedsearch.Added_On,"%d %b, %Y") as Added_On');
                $Date_Posted=" AND (DATE_FORMAT(getdetails.Added_On,'%Y-%m-%d') ='".$current_date."')";
            } elseif($Date_Posted=='twodays') {
                $twodays=date("Y-m-d", strtotime("-2 day", strtotime($current_date)));
                $Date_Posted=" AND (getdetails.Added_On >='".$twodays."')";
            } elseif($Date_Posted=='week') {
                $week=date("Y-m-d", strtotime("-7 day", strtotime($current_date)));
                $Date_Posted=" AND (getdetails.Added_On >='".$week."')";
            } elseif($Date_Posted=='month') {
                $month=date("Y-m-d", strtotime("-1 month", strtotime($current_date)));
                $Date_Posted=" AND (getdetails.Added_On >='".$month."')";
            } elseif($Date_Posted=='sixtydays') {
                $twomonth=date("Y-m-d", strtotime("-2 month", strtotime($current_date)));
                $Date_Posted=" AND (getdetails.Added_On >='".$twomonth."')";
            } elseif($Date_Posted=='anytime') {
                $Date_Posted="";
            }
        } else {
            $Date_Posted='';
        }
        
        if($Advance_Country_Id!='' ) {
             $Advance_Country_Id=" AND (getdetails.Country_Id1='".$Advance_Country_Id."' OR getdetails.Country_Id2='".$Advance_Country_Id."' OR getdetails.Country_Id3='".$Advance_Country_Id."')";
        } else {
            $Advance_Country_Id="";
        }
        
//         if($value!='' ) { 
//         	$all_values=explode(',',$value);
//             $totalrow=count($all_values);
//             $State_Id_Search='';
            
//             for($i=0;$i<$totalrow;$i++) {
//                 if($i==0) {
//                     $connector='AND';
//                 } else {
//                     $connector='OR';
//                 }

//                 $State_Id_Search.=' '.$connector." (getdetails.State_Id1='".$all_values[$i]."' OR getdetails.State_Id2='".$all_values[$i]."' OR getdetails.State_Id3='".$all_values[$i]."')";
//                 $this->view->State_Id_New=$value;
//             }
//         } else {
//             $State_Id_Search="";
//         }

        // Checking the inputs are valid or not  for Company job search
        if($Search_Company!='' ) {
            $Search_Company=" AND (company.Company_Name LIKE '".$Search_Company."%' OR getdetails.Hiring_For LIKE '".$Search_Company."%')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Search_Company="";
        }
        
        if($Industry_Type_Id!='' ) {
            $Industry_Type_Id=" AND (company.Industry_Type_Id='".$Industry_Type_Id."')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Industry_Type_Id="";
        }
        
//         if($Search_Location!='' && $Search_Location!='City') {
//             $All_Values_Array = $this->view->Search_Location;
//             $all_values_zipcode=explode('-',$All_Values_Array);
            
//             if(is_numeric(trim($all_values_zipcode[0]))) {
//                 $zipcode_res=$getvalue->zipcodecheck($all_values_zipcode[0]);
                
//                 if($zipcode_res['City_Zipcode']!='') {

//                     $miles_value=$getvalue->mileslimit();
//                     $zipMilesHigh=$miles_value['Miles_Limit'];
                    
//                     if($miles_value['Miles_Limit']=='' || $miles_value['Miles_Limit']=='0') {
//                         $zipMilesHigh='30';
//                     }

//                     $related_zipcode_count='';
//                     $radius_zipcode=$getvalue->zipRadiusSQL("", $zipcode_res['City_Zipcode'], $zipcode_res['City_Latitude'], $zipcode_res['City_Longitude'], $zipMilesHigh);
//                     $related_zipcode_count=count($radius_zipcode);
                    
//                     if($related_zipcode_count!='' && $related_zipcode_count!='0') {
//                         $zipcode_value='';
//                         for($j=0;$j<$related_zipcode_count;$j++)  {
//                             //if($radius_zipcode[$j]['City_Zipcode']!=$zipcode_res['City_Zipcode'])
//                            // {
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==4) {
//                                     $radius_zipcode[$j]['City_Zipcode']='0'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if(strlen($radius_zipcode[$j]['City_Zipcode'])==3) {
//                                     $radius_zipcode[$j]['City_Zipcode']='00'.$radius_zipcode[$j]['City_Zipcode'];
//                                 }
                                
//                                 if($j==0) {
//                                     if($all_values_zipcode[1]=='') {
//                                         $condition='AND';
//                                     } else {
//                                         $condition='OR';
//                                     }
//                                 } else {
//                                     $condition='OR';
//                                 }
//                                 $zipcode_value.=" $condition (getdetails.Location LIKE '%".$radius_zipcode[$j]['City_Zipcode']."%')";
//                             //}
//                         }
//                     } else {
//                         $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                     }
//                 } else {
//                     $zipcode_value=" AND (getdetails.Location LIKE '%".$all_values_zipcode[0]."%')";
//                 }
//             } else {
//                 $all_values_zipcode[1]=$all_values_zipcode[0];
//             }
            
//             if($all_values_zipcode[1]!='') {
//                 $all_values=explode(',',$all_values_zipcode[1]);
//                 $totalrow_key=count($all_values);
//                 $Search_Location='';
//                 for($i=0;$i<$totalrow_key;$i++) {
//                     if($i==0) {
//                         $condition='AND';
//                     } else {
//                         $condition='OR';
//                     }
//                     $Search_Location.=" $condition (getdetails.Location LIKE '%".trim($all_values[$i])."%')".$zipcode_value;
//                 }
//             } else {
//                 $Search_Location=$zipcode_value;
//             }
//             $this->view->Advance_Country_Id='';
//             $Advance_Country_Id='';
//         } else {
//             $Search_Location='';
//         }
        
        if($No_Employee_Id!='' ) {
            $No_Employee_Id=" AND (company.No_Employee_Id='".$No_Employee_Id."')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $No_Employee_Id="";
        }

        // Checking the inputs are valid or not  for Location job search
        if($Country_Id!='' ) {
            $Country_Id=" AND (getdetails.Country_Id1='".$Country_Id."' OR getdetails.Country_Id2='".$Country_Id."' OR getdetails.Country_Id3='".$Country_Id."')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Country_Id="";
        }
        
//         if($value_new!='' ) {
//             $all_values=explode(',',$value_new);
//             $totalrow=count($all_values);
//             $State='';
//             for($i=0;$i<$totalrow;$i++) {
//                 if($i==0) {
//                     $connector='AND';
//                 } else {
//                     $connector='OR';
//                 }

//                 $State.=' '.$connector." (getdetails.State_Id1='".$all_values[$i]."' OR getdetails.State_Id2='".$all_values[$i]."' OR getdetails.State_Id3='".$all_values[$i]."')";
//                 $this->view->State_New=$value_new;
//                 $this->view->Advance_Country_Id='';
//                 $Advance_Country_Id='';
//             }
//         } else {
//             $State="";
//         }
        
        if($City!='' ) {
            $City=" AND (getdetails.City1 LIKE '%".$City."%' OR  getdetails.City2 LIKE '%".$City."%'  OR  getdetails.City3 LIKE '%".$City."%')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        }  else  {
            $City="";
        }
        
        if($Location_Keywords!='') {
            $All_Values_Array = $this->view->Location_Keywords;
            $all_values=explode(',',$All_Values_Array);
            $totalrow_key=count($all_values);
            $Keywords='';
            for($i=0;$i<$totalrow_key;$i++) {
                if($i==0) {
                    $condition='AND';
                } else {
                    $condition='OR';
                }                
              $Location_Keywords =" $condition (getdetails.Post_Job_Title LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills1 LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills2  LIKE '%".$all_values[$i]."%' OR  getdetails.Key_Skills3 LIKE '%".$all_values[$i]."%' OR company.Company_Name LIKE '".$all_values[$i]."%' OR getdetails.Hiring_For LIKE '".$all_values[$i]."%' category.Category_Name like '%".$all_values[$i]."%')";
            }
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Location_Keywords='';
        }

        // Checking the inputs are valid or not  for Location job search by Alphabetical order
        if($Alpha_Location!='') {
            $Alpha_Location="AND (getdetails.City1 LIKE '".$Alpha_Location."%'  OR   getdetails.City2 LIKE '".$Alpha_Location."%'  OR   getdetails.City3 LIKE '".$Alpha_Location."%')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id='';
        } else {
            $Alpha_Location='';
        }

        // Checking the inputs are valid or not  for Company job search by Alphabetical order
        if($Alpha_Company!='') {
            $Alpha_Company=" AND (company.Company_Name LIKE '".$Alpha_Company."%' OR getdetails.Hiring_For LIKE '".$Alpha_Company."%')";
            $this->view->Advance_Country_Id='';
            $Advance_Country_Id=''; 
        } else {
            $Alpha_Company='';
        }
        
//         $searchArray = array($Keywords, $Location, $Category, $Skill,
//         		$Advance_Keywords, $Advance_Location, $Advance_Location1, $Advance_Location2, $Advance_Category, 
//         		$Advance_Category1, $Advance_Category2, $Experience, $Education, $Travel, $Job_Type, $Company_Type, 
//         		$Company_Name, $Salary, $Date_Posted, $Advance_Country_Id, $State_Id_Search, $Country_Id, $State, $City,
//         		$Location_Keywords, $Alpha_Location, $Alpha_Company, $Refine_Travel, $Refine_Company_Type, $Refine_City, 
//         		$Refine_State_Id, $Refine_Country_Id, $Refine_Companyname, $Refine_Job_Id
//         );
		
        $City_Location = NULL;
        if($Location){
        $City_Location=" AND (getdetails.City1 LIKE '%".$Location."%')";
        }
        if($Advance_Location){
        	$Advance_Location=" AND (getdetails.City1 LIKE '%".$Advance_Location."%')";
        }
        if($Search_Location){
        	$Search_Location=" AND (getdetails.City1 LIKE '%".$Search_Location."%')";
        }

        //$search_category = " AND (category.Category_Name like '%".$All_Values_Array."%')";
        //error_reporting(E_ALL|E_STRICT);
        //ini_set('display_errors', 'on');
        $searchArray = array($Keywords, $Category, $Skill,
        		$Advance_Keywords,  $Advance_Location2, $Advance_Category, 
        		$Advance_Category1, $Advance_Category2, $Experience, $Education, $Travel, $Job_Type, $Company_Type, 
        		$Company_Name, $Salary, $Date_Posted, $City,$Search_Location,
        		$Location_Keywords, $Alpha_Location, $Alpha_Company, $Refine_Travel, $Refine_Company_Type, $Refine_City, 
        		$Refine_Country_Id, $Refine_Companyname, $Refine_Job_Id
        );

        $searchby = '';
        for($inc = 0; $inc < count($searchArray); $inc++) {
        	if($searchArray[$inc] != '') {
	        	if($inc > 0){
	        		$searchby .= ' ';
	        	}
	        	$searchby .= $searchArray[$inc];
        	}
        }
       // $searchby="$Keywords $Location $Category $Skill";
//         $searchby.="$Advance_Keywords $Advance_Location $Advance_Location1 $Advance_Location2 $Advance_Category $Advance_Category1 $Advance_Category2 $Experience $Education $Travel $Job_Type $Company_Type $Company_Name $Salary $Date_Posted $Advance_Country_Id $State_Id_Search";
//         $searchby.="$Search_Company $Industry_Type_Id $Search_Location $No_Employee_Id";
//         $searchby.="$Country_Id $State $City $Location_Keywords";
//         $searchby.="$Alpha_Location";
//         $searchby.="$Alpha_Company";
//         $searchby.="$Refine_Travel $Refine_Company_Type $Refine_City $Refine_State_Id $Refine_Country_Id $Refine_Companyname $Refine_Job_Id";
        
        $isdeleted="(getdetails.Is_Deleted=0 AND company.Is_Deleted=0 AND company.Is_Blocked=0)";
               
        $select=$getvalue->getQuickjobsearch('jz_post_jobs',$rowsperpage,$offset,$isdeleted,$searchby,$orderby,$assign_ordername,'Featured_Employer',$Advance_Location, $City_Location);
        $paginator=$getvalue->getPagination($select,$page,$rowsperpage);//view page query executed
        
        $queryres=$getvalue->getCountpagination($select);//view page query executed
        $queryvalue=$getvalue->getCount('jz_post_jobs',$isdeleted,$searchby);//view page query executed
        $this->view->queryvalue=count($queryvalue);
        $this->view->queryres=count($queryres);
        $stop=$this->view->queryvalue%$this->view->rowsperpage;
        if($stop==0)  {
            $this->view->stop=0+$this->view->rowsperpage;
        } else {
            $this->view->stop=$offset+$this->view->rowsperpage;
        }
        
        $this->view->remaining=$this->view->stop;
        //$start=
        $this->view->finalresult=$paginator;//assigning for paginaton values        
        $this->view->searchby=$searchby;
        $this->view->obj=$getvalue;
        $this->view->deleted=$isdeleted;
        $this->view->searchby=$searchby;
        $select=$getvalue->getrefinesearchlocation('jz_post_jobs',$isdeleted,$searchby);
        $this->view->refineresultloc=$select;//Result for refine search location
        $select=$getvalue->getrefinesearchkeyskill('jz_post_jobs',$isdeleted,$searchby);
        $this->view->refineresultskill=$select;//Result for refine search Keyskill
        $select=$getvalue->getrefinesearchcountry('jz_post_jobs',$isdeleted,$searchby);
        $this->view->refineresultcountry=$select;//Result for refine search Country
        $select=$getvalue->getrefinesearchcompany('jz_post_jobs',$isdeleted,$searchby);
        $this->view->companytype=$select;//Result for refine search company type
        $select=$getvalue->getrefinesearchtravel('jz_post_jobs',$isdeleted,$searchby);
        $this->view->refinetravel=$select;//Result for refine search travle
        $select=$getvalue->getcompanyname('jz_post_jobs',$isdeleted,$searchby);
        $this->view->companyname=$select;//Result for refine search company name
        $select=$getvalue->getjobtype('jz_post_jobs',$isdeleted,$searchby);
        $this->view->jobtype=$select;//Result for refine search company name
//         $select=$getvalue->getstatename('jz_post_jobs',$isdeleted,$searchby);
//         $this->view->refineresultstate=$select; //Result for refine search state name
       
             /*---------------------Save the Jobs Coding Starts here-------------------*/

        $savejob=trim($this->_request->get('savejob'));//get the copy values to insert the record in database
        if($savejob!='') {
            $storage = new Zend_Auth_Storage_Session();//directliy store the value in session
            $data = $storage->read();//read session value
            
            if(!$data)  {
                $this->_redirect('users/login');
            }
            $authNamespace = new Zend_Session_Namespace('Zend_Auth');
            $Seeker_Reg_Id=$authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
            $current_date=date('Y-m-d H:m:s');
            $saved_date=date('Y-m-d');
            $savejob=trim($this->_request->get('savejob'));//get the copy values to insert the record in database
            $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Job_Id'=>$savejob,'Added_On'=>$current_date,'Saved_Date'=>$saved_date);
            if($getvalue->add_Savejobs($data)) {
            //insert the values in database            
                $this->view->msg='Job has been successfully saved';
            } else {
                $this->view->msg='Sorry! Please try again';
            }
        }
            /*---------------------Save the Jobs Coding Ends here-------------------*/
            /*---------------------Apply Jobs Coding Starts here-------------------*/

        $applyjob=trim($this->_request->getPost('applyjob'));//get the copy values to insert the record in database
        if($applyjob!='') {
            $storage = new Zend_Auth_Storage_Session();//directliy store the value in session
            $authNamespace = new Zend_Session_Namespace('Zend_Auth');
            $data = $storage->read();//read session value
            $Seeker_Reg_Id=$authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
            
            if($Seeker_Reg_Id=='') {
                $this->_redirect('users/login');
            }
            
            $Resume_Id=$getvalue->getEmailId($Seeker_Reg_Id);
            $Seeker_Resume_Id=$Resume_Id[0]['Seeker_Resume_Id'];
            $current_date=date('Y-m-d H:m:s');
            $saved_date=date('Y-m-d');
            $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Job_Id'=>$applyjob,'Added_On'=>$current_date,'Apply_On'=>$saved_date,'Status_Code'=>4);
            
            if($getvalue->applyjob($data)) {
                if($getvalue->jobsapplied($Seeker_Resume_Id)) {
                    $this->view->msg='You has been successfully applied for this job';
                }
            } else {
                $this->view->msg='Sorry! Please try again';
            }
        }

              /*---------------------Apply Jobs Coding Ends here-------------------*/
             /*---------------------Save the view jobs Count Starts-------------------*/
        if($this->_request->get('searchnewname')!='savejobs') {

            $Count_view_jobs=$getvalue->Saveviewjobs('jz_post_jobs',$rowsperpage,$offset,$isdeleted,$searchby);
            for($i=0;$i<count($Count_view_jobs);$i++) {
                $data=array('Post_Jobs_Id'=>$Count_view_jobs[$i]['Post_Jobs_Id']);
                $getvalue->savejobs($data);
            }
            $this->view->searchnewname='savejobs';
        }
            /*---------------------Save the view jobs Count Ends-------------------*/
            /*---------------------Similar  job Coding Starts here-------------------*/
        $similarjob = trim($this->_request->get('similarjob'));//get the copy values to insert the record in database
        $searchname = trim($this->_request->get('searchname'));
        if($searchname == 'similarjob') {
            $Keywords = trim($this->_request->getPost('Keywords'));

            $this->view->Keywords=$Keywords;
            if($Keywords!='') {
                $Keywords="AND (getdetails.Post_Job_Title  LIKE '%".$Keywords."%')";
            } else {
                $Keywords='';
            }
            
            $searchby="$Keywords";
            $isdeleted="(getdetails.Is_Deleted=0)";
            $select=$getvalue->getQuickjobsearch('jz_post_jobs',$rowsperpage,$offset,$isdeleted,$searchby,$orderby,$ordername,'Featured_Employer');
            $paginator=$getvalue->getPagination($select,$page,$rowsperpage);//view page query executed
            $this->view->finalresult=$paginator;//assigning for paginaton values
        }
             /*---------------------Similar  job Coding Ends here-------------------*/
             /*---------------------Save job alert from jobsearch Coding Starts here-------------------*/
        $All_Values=$_POST['savejobalert'];
        $All_Values =$this->_request->get('savejobalert');
        if($All_Values=='2') { 
            $storage = new Zend_Auth_Storage_Session();//directliy store the value in session
            $data = $storage->read();//read session value
            
            if(!$data) {
                $this->_redirect('users/login');
            }
            
            $Advance_Keywords = $this->_request->get('Advance_Keywords');
            
            if($Advance_Keywords=='Clinical Project Manager') {
                $this->view->Advance_Keywords='';
                $Advance_Keywords='';
            } else {
                $this->view->Advance_Keywords=$Advance_Keywords;
            }
                        
            $Keywords_Select = $this->_request->get('Keywords_Select');
            $this->view->Keywords_Select=$Keywords_Select;
            $Job_Title = $this->_request->get('Job_Title');
            $this->view->Job_Title=$Job_Title;
            $Date_Posted = $this->_request->get('Date_Posted');
            $this->view->Date_Posted=$Date_Posted;
            $Advance_Location = trim($this->_request->get('Advance_Location'));
            
            if($Advance_Location=='City') {
                $this->view->Advance_Location='';
                $Advance_Location='';
            } else {
                $this->view->Advance_Location=$Advance_Location;
            }
            $Advance_Location1 = trim($this->_request->get('Advance_Location1'));

            if($Advance_Location1=='City') {
                $this->view->Advance_Location1='';
                $Advance_Location1='';
            } else {
                $this->view->Advance_Location1=$Advance_Location1;
            }

            $Advance_Location2 = trim($this->_request->get('Advance_Location2'));
            if($Advance_Location2=='City')  {
                $this->view->Advance_Location2='';
                $Advance_Location2='';
            } else {
                $this->view->Advance_Location2=$Advance_Location2;
            }
            $Advance_Category = $this->_request->get('Advance_Category');
            $this->view->Advance_Category=$Advance_Category;
            $Advance_Category1 = $this->_request->get('Advance_Category1');
            $this->view->Advance_Category1=$Advance_Category1;
            $Advance_Category2 = $this->_request->get('Advance_Category2');
            $this->view->Advance_Category2=$Advance_Category2;
            $Experience = $this->_request->get('Experience');
            $this->view->Experience=$Experience;
            $Salary = $this->_request->get('Salary');
            $this->view->Salary=$Salary;
            $Company_Name = $this->_request->get('Company_Name');
            $this->view->Company_Name=$Company_Name;
            $Job_Type = $this->_request->get('Job_Type');
            $this->view->Job_Type=$Job_Type;
            $Education = $this->_request->get('Education');
            $this->view->Education=$Education;
            $Travel = $this->_request->get('Travel');
            $this->view->Travel=$Travel;
            $Company_Type = $this->_request->get('Company_Type');
            $this->view->Company_Type=$Company_Type;
            $Title = $this->_request->get('Title');
            $this->view->Title=$Title;
            $this->view->Title_View=$Title;
            $Sendalert_Id = $this->_request->get('Sendalert');
            $this->view->Sendalert_Id=$Sendalert_Id;
            $Myemail = $this->_request->get('Myemail');
            $this->view->Myemail=$Myemail;
            $current_date=date('Y-m-d H:m:s');
            
            if($this->_request->get('Advance_Country_Id')!='') {
                $Advance_Country_Id = $this->_request->get('Advance_Country_Id');
            }
            
//             if($this->view->State_Id_New!='') {
//                 $Advance_State_Id=$this->view->State_Id_New;
//             }

//             $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Sendalert_Id'=>$Sendalert_Id,'Myemail'=>$Myemail,'Keywords'=>$Keywords,'Category'=>$Category,
//                     'Location'=>$Location,'Advance_Keywords'=>$Advance_Keywords,'Keywords_Select'=>$Keywords_Select,'Job_Title'=>$Job_Title,'Date_Posted'=>$Date_Posted,
//                     'Advance_Location'=>$Advance_Location,'Advance_Location1'=>$Advance_Location1,'Advance_Location2'=>$Advance_Location2,
//                     'Advance_Category'=>$Advance_Category,'Advance_Category1'=>$Advance_Category1,'Advance_Category2'=>$Advance_Category2,'Experience'=>$Experience,
//                     'Salary'=>$Salary,'Company_Name'=>$Company_Name,'Job_Type'=>$Job_Type,'Education'=>$Education,'Travel'=>$Travel,
//                     'Company_Type'=>$Company_Type,'Advance_Country_Id'=>$Advance_Country_Id,'Advance_State_Id'=>$Advance_State_Id,'Search_Company'=>$Search_Company,'Industry_Type_Id'=>$Industry_Type_Id,'Search_Location'=>$Search_Location,
//                     'No_Employee_Id'=>$No_Employee_Id,'Country_Id'=>$Country_Id,'City'=>$City,'State'=>$State,'Location_Keywords'=>$Location_Keywords,
//                     'Alpha_Location'=>$Alpha_Location,'Alpha_Company'=>$Alpha_Company,'Keyskill'=>$Skillset,'Added_On'=>$current_date,'Title'=>$Title);
            
            $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Sendalert_Id'=>$Sendalert_Id,'Myemail'=>$Myemail,'Keywords'=>$Keywords,'Category'=>$Category,
                    'Location'=>$Location,'Advance_Keywords'=>$Advance_Keywords,'Keywords_Select'=>$Keywords_Select,'Job_Title'=>$Job_Title,'Date_Posted'=>$Date_Posted,
                    'Advance_Location'=>$Advance_Location,'Advance_Location1'=>$Advance_Location1,'Advance_Location2'=>$Advance_Location2,
                    'Advance_Category'=>$Advance_Category,'Advance_Category1'=>$Advance_Category1,'Advance_Category2'=>$Advance_Category2,'Experience'=>$Experience,
                    'Salary'=>$Salary,'Company_Name'=>$Company_Name,'Job_Type'=>$Job_Type,'Education'=>$Education,'Travel'=>$Travel,
                    'Company_Type'=>$Company_Type,'Advance_Country_Id'=>$Advance_Country_Id,'Search_Company'=>$Search_Company,'Industry_Type_Id'=>$Industry_Type_Id,'Search_Location'=>$Search_Location,
                    'No_Employee_Id'=>$No_Employee_Id,'Country_Id'=>$Country_Id,'City'=>$City,'Location_Keywords'=>$Location_Keywords,
                    'Alpha_Location'=>$Alpha_Location,'Alpha_Company'=>$Alpha_Company,'Keyskill'=>$Skillset,'Added_On'=>$current_date,'Title'=>$Title);

            if($getvalue->add_Jobalert($data)) {
            ////insert the values in database            
                $this->view->msg='Your job alert has been successfully saved';
            }
        }
            /*---------------------Save job alert from jobsearch Coding Ends here-------------------*/
            /*---------------------Save job alert from jobresult Coding Starts here-------------------*/
        if($this->_request->get('Sendalert_Id')!='')  { 
            $storage = new Zend_Auth_Storage_Session();//directliy store the value in session
            $data = $storage->read();//read session value
            
            if(!$data) {
                $this->_redirect('users/login');
            }
            //                $authNamespace = new Zend_Session_Namespace('Zend_Auth');
            //                $Seeker_Reg_Id=$authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
            //                $data_work=array('MyEmail'=>$authNamespace->auth_seekerdetails[0]['Email']);
            //                $form->populate($data_work);
            $current_date=date('Y-m-d H:m:s');
            $Title = $this->_request->get('Title');
            $this->view->Title=$Title;
            $Sendalert_Id = $this->_request->get('Sendalert_Id');
            $this->view->Sendalert_Id=$Sendalert_Id;
            $Myemail = $this->_request->get('Myemail');
            $this->view->Myemail=$Myemail;
            
            if($this->_request->get('Advance_Country_Id')!='') {
                $Advance_Country_Id = $this->_request->get('Advance_Country_Id');
            } else {
                $Advance_Country_Id='';
            }
            
            if($Job_Type=='' && $this->_request->get('Refine_Job_Id')!='') {
                $Job_Type = $this->_request->get('Refine_Job_Id');
            }

            if($Company_Type=='' && $this->_request->get('Refine_Company_Type')!='') {
                $Company_Type = $this->_request->get('Refine_Company_Type');
            }
            
            if($Country_Id=='' && $this->_request->get('Refine_Country_Id')!='') {
                $Country_Id = $this->_request->get('Refine_Country_Id');
            }
            
            if($City=='' && $this->_request->get('Refine_City')!='') {
                $City = $this->_request->get('Refine_City');
            }
            
            if($this->_request->get('Skill')!='') {
                $Skillset = $this->_request->get('Skill');
            }

//             if($State=='' && $this->_request->get('Refine_State_Id')!='') {
//                 $State = $this->_request->get('Refine_State_Id');
//             } else {
//                 $State=$this->view->State_New;
//             }
            
            if($Travel=='' && $this->_request->get('Refine_Travel')!='') {
                $Travel = $this->_request->get('Refine_Travel');
            }
            
            if($Search_Company=='' && $this->_request->get('Refine_Companyname')!='') {
                $Search_Company = $this->_request->get('Refine_Companyname');
            }
            
//             $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Sendalert_Id'=>$Sendalert_Id,'Myemail'=>$Myemail,'Keywords'=>$this->view->Keywords,'Category'=>$this->view->Category,
//                     'Location'=>$this->view->Location,'Advance_Keywords'=>$this->view->Advance_Keywords,'Keywords_Select'=>$this->view->Keywords_Select,'Job_Title'=>$Job_Title,'Date_Posted'=>$this->view->Date_Posted,
//                     'Advance_Location'=>$this->view->Advance_Location,'Advance_Location1'=>$this->view->Advance_Location1,'Advance_Location2'=>$this->view->Advance_Location2,
//                     'Advance_Category'=>$this->view->Advance_Category,'Advance_Category1'=>$this->view->Advance_Category1,'Advance_Category2'=>$this->view->Advance_Category2,'Experience'=>$this->view->Experience,
//                     'Salary'=>$this->view->Salary,'Company_Name'=>$this->view->Company_Name,'Job_Type'=>$this->view->Job_Type,'Education'=>$this->view->Education,'Travel'=>$this->view->Travel,
//                     'Company_Type'=>$this->view->Company_Type,'Advance_Country_Id'=>$Advance_Country_Id,'Advance_State_Id'=>$this->view->State_Id_New,'Search_Company'=>$Search_Company,'Industry_Type_Id'=>$Industry_Type_Id,'Search_Location'=>$Search_Location,
//                     'No_Employee_Id'=>$this->view->No_Employee_Id,'Country_Id'=>$Country_Id,'City'=>$this->view->City,'State'=>$State,'Location_Keywords'=>$this->view->Location_Keywords,
//                     'Alpha_Location'=>$this->view->Alpha_Location,'Alpha_Company'=>$this->view->Alpha_Company,'Keyskill'=>$Skillset,'Added_On'=>$current_date,'Title'=>$this->view->Title);
            
            $data=array('Seeker_Id'=>$Seeker_Reg_Id,'Sendalert_Id'=>$Sendalert_Id,'Myemail'=>$Myemail,'Keywords'=>$this->view->Keywords,'Category'=>$this->view->Category,
                    'Location'=>$this->view->Location,'Advance_Keywords'=>$this->view->Advance_Keywords,'Keywords_Select'=>$this->view->Keywords_Select,'Job_Title'=>$Job_Title,'Date_Posted'=>$this->view->Date_Posted,
                    'Advance_Location'=>$this->view->Advance_Location,'Advance_Location1'=>$this->view->Advance_Location1,'Advance_Location2'=>$this->view->Advance_Location2,
                    'Advance_Category'=>$this->view->Advance_Category,'Advance_Category1'=>$this->view->Advance_Category1,'Advance_Category2'=>$this->view->Advance_Category2,'Experience'=>$this->view->Experience,
                    'Salary'=>$this->view->Salary,'Company_Name'=>$this->view->Company_Name,'Job_Type'=>$this->view->Job_Type,'Education'=>$this->view->Education,'Travel'=>$this->view->Travel,
                    'Company_Type'=>$this->view->Company_Type,'Advance_Country_Id'=>$Advance_Country_Id,'Search_Company'=>$Search_Company,'Industry_Type_Id'=>$Industry_Type_Id,'Search_Location'=>$Search_Location,
                    'No_Employee_Id'=>$this->view->No_Employee_Id,'Country_Id'=>$Country_Id,'City'=>$this->view->City,'Location_Keywords'=>$this->view->Location_Keywords,
                    'Alpha_Location'=>$this->view->Alpha_Location,'Alpha_Company'=>$this->view->Alpha_Company,'Keyskill'=>$Skillset,'Added_On'=>$current_date,'Title'=>$this->view->Title);

            if($getvalue->add_Jobalert($data)) {
            ////insert the values in database 
                $this->view->msg='Your job alert has been successfully saved';
            }
        }
    }    
     /*---------------------Save job alert from jobresult Coding Ends here-------------------*/
    
    public function countryAction()
    {
        //   $this->_helper->viewRenderer->setNoRender('index.phtml');//disable add.phtml page
        $this->_helper->layout()->disableLayout();//disable layout.phtml page
    }
    
    public function emailAction()
    {
        $url=SITE_URL;
     	$name=SITE_NAME;
        $this->_helper->layout()->disableLayout();//disable layout.phtml page
        $form = new Form_jobresult();
        $this->view->form = $form;
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $Seeker_Reg_Id=$authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
        $Post_Job_Id=$this->_getParam('postid','');
        $get_details = new Model_DbTable_savedjobs();
        $emaillist=$get_details->getEmailList($Post_Job_Id);//Get the values from database for Logo
        
        if($authNamespace->auth_seekerdetails[0]['Email']!='') {
            $data=array('EmailAddress'=>$authNamespace->auth_seekerdetails[0]['Email'],'Yourname'=>$authNamespace->auth_seekerdetails[0]['First_Name'].' '.$authNamespace->auth_seekerdetails[0]['Last_Name']);
            $form->populate($data);
        }
        
        $email_details = new Model_DbTable_seeker();
        $send_email=$email_details->getadminemail();
        $admin_email=$send_email[0]['Contact_Email'];

        $this->view->jobtitle=$emaillist[0]['Post_Job_Title'];//show the values in index page
        $this->view->jobloc=$emaillist[0]['City1'];
        if($emaillist[0]['City2']!='') {
            $this->view->jobloc.= ",".$emaillist[0]['City2'];
        }
        
        if($emaillist[0]['City3']!='') {
            $this->view->jobloc.=",".$emaillist[0]['City3'];
        }
        
        if ($this->getRequest()->isPost())//get the values from form
        {
            $formData = $this->getRequest()->getPost();
            //if ($form->isValid($formData))//checking form values are valid or not
           // {
                $To =$this->_request->getPost('To');
                $Fullname = $this->_request->getPost('Fullname');
                $EmailAddress =$this->_request->getPost('EmailAddress');
                $Yourname =$this->_request->getPost('Yourname');
                $Copy = $this->_request->getPost('Copy');
                $Message = $this->_request->getPost('Message');
                $url=SITE_URL;
                $name=SITE_NAME;
                
                if($To!='') {
                    $link='<a href="'.$url.'/'.$emaillist[0]["jobs_slug"].'">this link</a>';
                    $Subject = $this->_request->getPost('Subject');
                    Zend_Loader::loadClass('Zend_Mail');                    
                                        
                    $bodyhtml = file_get_contents($url."securepharm/mailtemplates/jobresult.html");
                    
                   // $bodyhtml = str_replace("{@BANNER}", '<img src="'.$url.'/public/images/image_new.jpg" width="675" height="118" alt="banner"/>', $bodyhtml);
                    
                    $bodyhtml = str_replace ( "{@BASEURL}", $url, $bodyhtml );
                    $bodyhtml = str_replace ( "{@SUBJECT}", "Contact Form", $bodyhtml );                    
                    
                    $bodyhtml = str_replace("{@FNAME}", $Fullname, $bodyhtml);
                    $bodyhtml = str_replace("{@SNAME}", $Yourname, $bodyhtml);
                    $bodyhtml = str_replace("{@EMAIL}", $EmailAddress, $bodyhtml);
                    
                    if($Seeker_Reg_Id=='') {
                    	$bodyhtml =str_replace("{@VERIFIED}", "(Email address has not been verified.)", $bodyhtml);
                    }
                    else {
                    	$bodyhtml =str_replace("{@VERIFIED}", "", $bodyhtml);
                    }
                    
                    $bodyhtml = str_replace("{@MESSAGE}", $Message, $bodyhtml);
                    $bodyhtml = str_replace("{@JOBTITLE}", $this->view->jobtitle, $bodyhtml);
                    $bodyhtml = str_replace("{@JOBLOC}", $this->view->jobloc, $bodyhtml);
                    $bodyhtml = str_replace("{@THISLINK}",$link , $bodyhtml);
                //    $bodyhtml = str_replace("{@COMINFOID}", $emaillist[0]['Company_Info_Id'], $bodyhtml);
                //    $bodyhtml = str_replace("{@POSTJOBID}", $emaillist[0]['Post_Jobs_Id'], $bodyhtml);
                    
                    $mail = new Zend_Mail();
                    $mail->setFrom ($admin_email,$name);
                    
                    $mail->addHeader ( 'MIME-Version', '1.0' );
                    $mail->addHeader ( 'Content-Transfer-Encoding', '8bit' );
                    $mail->addHeader ( 'X-Mailer:', 'PHP/' . phpversion () );
                    
                    $mail->addTo ($To,ucfirst($Fullname));
                    $mail->setSubject($Subject);
                    $mail->setBodyHtml ($bodyhtml);
                   
                    if($mail->send())//forward the new password to the mail
                    {
                        $this->view->successmsg='Your message has been sent';
                    }
                    if($Copy==1)
                    {
                        $mail = new Zend_Mail();
                       // $mail->setFrom ($EmailAddress,ucfirst($Yourname));
                        $mail->setFrom ($admin_email,$name);
                        $mail->addTo ($EmailAddress,ucfirst($Yourname));
                        $mail->setSubject ($Subject);
                        $mail->setBodyHtml (nl2br($bodyhtml));
                        $mail->send();
                    }

                }

            //}
        }
    }
    
    public function applyAction()
    {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $Seeker_Reg_Id=$authNamespace->auth_seekerdetails[0]['Seeker_Reg_Id'];//get the role id from the session
        if($Seeker_Reg_Id=='') {
            $this->_redirect('users/login');
        }
        
        if($this->_request->getPost('qstring')!='') {
            $this->view->qstring=$this->_request->getPost('qstring');
        } else {
            $this->view->qstring=$_SERVER['HTTP_REFERER'];
        }
        
        $form = new Form_jobapply();
        $this->view->form = $form;
        $getvalue = new Model_DbTable_jobresult();
        $applydetails = new Model_DbTable_jobapply();
         $getprofile = new Model_DbTable_jobprofile();
        $this->view->obj=$getprofile;
        $Email_Id=$getvalue->getEmailId($Seeker_Reg_Id);
        $data=array('Myemail'=>$Email_Id[0]['Email'],'Name'=>$Email_Id[0]['First_Name'].' '.$Email_Id[0]['Last_Name']);
        $form->populate($data);
        
        $resume_title=$applydetails->getpersonaldetails($Seeker_Reg_Id);
        $this->view->resume_title=$resume_title;
        $obj=new Common_Class();
        $isdeleted='Is_Deleted=0 And Seeker_Reg_Id='.$Seeker_Reg_Id;
        $result=$obj->getCommonrecordcount('jz_seeker_coverletter',$isdeleted);//get the record count
        $this->view->countresult=$result;
        $select=$obj->getQueryresultwithoutpagination('jz_seeker_coverletter','Coverletter_Id','desc',$isdeleted,'3','0');
        $this->view->queryresult=$select;
        
        if($this->_request->getPost('applyjobid')!='') {
            $this->view->jobid=$this->_request->getPost('applyjobid');
        } else {
            $this->view->jobid=$this->_request->get('applyjobid');
        }

        if($this->_request->getPost('applyjobid')!=0 && $this->_request->getPost('applyjobid')!='') {
            $jobid=$this->view->jobid;
            $Job_Details=$applydetails->getjobdetails($jobid);
            $cover_body='';
            $bodyhtml='';
            $this->view->companyid=$Job_Details['Company_Id'];
            $this->view->slug=$Job_Details['jobs_slug'];
        }        
    }
    
    public function sorryAction() {}
}