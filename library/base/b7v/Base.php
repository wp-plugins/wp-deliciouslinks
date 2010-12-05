<?php
if (! class_exists ( 'b7v_Base' )) :
	abstract class b7v_Base {
		protected static $_instance = null;
		public function __construct($application = null) {
			$this->_application = $application;
		}
		private $_application = null;
		public function set_application($application = null) {
			$this->_application = $application;
		}
		public function application() {
			if (null === $this->_application) {
				throw new Exception ( "Application not set \n" );
			}
			return $this->_application;
		}
		public function debug($show) {
			b7v_Debug::show ( $show );
		}
	}

endif;