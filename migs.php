<?php 
class ModelPaymentMigs extends Model {
    public function getMethod($address, $total) {
        $this->language->load('payment/migs');
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('migs_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
    
        if ($this->config->get('migs_total') > 0 && $this->config->get('migs_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('migs_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
        
        $method_data = array();
    
        if ($status) {  
            $method_data = array( 
                'code'       => 'migs',
                'img'        => "",
                'title'      => $this->language->get('text_title'),
                'sort_order' => $this->config->get('migs_sort_order')
            );
        }
   
        return $method_data;
    }
        public function getResponseDescription($responseCode) {
        
            switch ($responseCode) {
                case "0" : $result = "Transaction Successful"; break;
                case "?" : $result = "Transaction status is unknown"; break;
                case "1" : $result = "Unknown Error"; break;
                case "2" : $result = "Bank Declined Transaction"; break;
                case "3" : $result = "No Reply from Bank"; break;
                case "4" : $result = "Expired Card"; break;
                case "5" : $result = "Insufficient funds"; break;
                case "6" : $result = "Error Communicating with Bank"; break;
                case "7" : $result = "Payment Server System Error"; break;
                case "8" : $result = "Transaction Type Not Supported"; break;
                case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
                case "A" : $result = "Transaction Aborted"; break;
                case "C" : $result = "Transaction Cancelled"; break;
                case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
                case "F" : $result = "3D Secure Authentication failed"; break;
                case "I" : $result = "Card Security Code verification failed"; break;
                case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
                case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
                case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
                case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
                case "S" : $result = "Duplicate SessionID (OrderInfo)"; break;
                case "T" : $result = "Address Verification Failed"; break;
                case "U" : $result = "Card Security Code Failed"; break;
                case "V" : $result = "Address Verification and Card Security Code Failed"; break;
                default  : $result = "Unable to be determined"; 
            }
            return $result;
        }
        
	public function get_cc_price($price){
		
		
			return $price;
			
			 $interest = $this->config->get('migs_interest'); 
			 $interest =0;
			 if(!empty($interest) and $this->config->get('migs_status')){
			 			$xfraudfee =0;
			 		
					/*
						$xfraudper = $this->config->get('migs_xfraudfee');
			 			$interest = 0.4;
				 		$xfraudper = 0.0035;
						if(is_numeric($xfraudper) and !empty($xfraudper)){
							$xfraudfee =$xfraudper;
							
						} 
					*/	 
			 		
			 	 
			 	return  ceil($price + ($price * $interest/100) + ($price * $xfraudfee/100));
			 }
			 
	
	}




}
?>
