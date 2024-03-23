if($tnow < $limitT){ // check if now is less than b4
	if(($tnow + $tb4) <= $limitT){ //check now plus  and if duration is total den allow once and not do the following cal
		if($datenow < $next_end_date || $next_end_date == "0"){ //check if current date is less than next or if next is zero
			echo "Permit Transaction \n";
			echo "Set tb4 to tnow + tb4";
			if($next_end_date == "0"){
				echo "set next_end_date to datenow + limit";
			}
		}
		elseif($datenow >= $next_end_date){
			echo "set next_end_date to next_end_date + limit duration \n";
			echo "Permit Transaction";
		}
	}
	elseif(($tnow + $tb4) > $limitT){
		
		if($datenow < $next_end_date ){
			echo "END TRANSACTION WITH A MESSAGE";
		}
		elseif($datenow >= $next_end_date){
			echo "set next_end_date to next_end_date + limit duration \n";
			echo "Permit Transaction";
		}
		
	}
}
else{
	
	echo "verify Account To Permit This Transaction";
}