<?php

class transformavel_json {
	
	/**
	* Convert an object into an associative array
	*
	* This function converts an object into an associative array by iterating
	* over its public properties. Because this function uses the foreach
	* construct, Iterators are respected. It also works on arrays of objects.
	*
	* @return array
	*/
	private function object_to_array($var) {
		$result = array();
		$references = array();
	
		// loop over elements/properties
		foreach ($var as $key => $value) {
			// recursively convert objects
			if (is_object($value) || is_array($value)) {
				// but prevent cycles
				if (!in_array($value, $references)) {
					$result[$key] = $this->object_to_array($value);
					$references[] = $value;
				}
			} else {
				// simple values are untouched
				$result[$key] = $value;
			}
		}
		return $result;
	}
	
	public function get_json() {
		return json_encode($this->object_to_array($this));
	}
	
}

?>