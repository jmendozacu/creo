<?php


require_once dirname(__FILE__).'/SoapController.php';


class Progos_Creomob_UserSoapController extends Progos_Creomob_SoapController {
    
    
    protected function getCustomer($sessionId,$email,$hash){
        $proxy = new SoapClient($this->soapURLv2);
        $filter = array(
            'complex_filter' => array(
                array(
                    'key' => 'email',
                    'value' => array('key' => 'eq', 'value' => $email)
                ),
                array(
                    'key' => 'password_hash',
                    'value' => array('key' => 'eq', 'value' => $hash)
                )
            )
        );
        return $proxy->customerCustomerList($sessionId, $filter);
    }


    public function loginAction() {
        $sessionId = $this->getRequest()->getParam('sid');
        //$sessionId = 'fd5112c2a64ddc4eaa0a391a39258783';
        $login_data = json_decode(file_get_contents('php://input'),true);
        
        $email = $login_data['email'];
        $password = $login_data['password'];
//        $email = 'chkashif167@gmail.com';
//        $password = '1234567a';
        
        /** @var $session Mage_Customer_Model_Session */
        $session = Mage::getSingleton( 'customer/session' );

        $response = array('success'=>0,'message'=>'','customer'=>array());
        try
        {
            $session->login( $email, $password );
            //$session->setCustomerAsLoggedIn( $session->getCustomer() );
            $customer = $session->getCustomer();
            
            try{
                $res = $this->getCustomer($sessionId,$customer->getEmail(),$customer->getPasswordHash());
                $response['success'] = 1;
                $response['message'] = 'Login successful';
                $response['customer'] = $res;
                
            }catch(Exception $e){
                $response['message'] = $e->getMessage();
            }
            
        }
        catch( Exception $e )
        {
            $response['message'] = $e->getMessage();
        }
        
        header("Content-Type: application/json");
        echo json_encode($response);
        die;
    }
}