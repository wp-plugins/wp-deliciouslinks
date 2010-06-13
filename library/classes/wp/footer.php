<?php
class dc_wp_footer_1_0_0 extends dc_base_2_4_0 {
	function init() {
		add_action('wp_footer', $this->callback);
	}
}
?>