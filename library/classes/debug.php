<?php
class dc_debug_1_0_0  extends dc_base_2_4_0{
	function test($var=null,$echo=true,$name='Call to test',$condition=true){
		$return = '';
		if ($condition) {
			ob_start();
			print_r($var);
			$output = ob_get_contents();
			ob_end_clean();
			$output = split("\n",$output);
			ob_start();
?>

	<table style = 'margin:0;width:100%;background-color:white;border:4px solid darkblue;'>
		<thead style='margin:0;padding:5px;background-color:darkblue;color:white'>
			<tr>
				<th style='width:50px;text-align:left' scope = 'row'>
					Class
				</th>
				<td style='text-align:left'>
					<?php echo get_class($this); ?>
				</td>
			</tr>
			<tr>
				<th style='text-align:left' scope = 'row'>
					Time
				</th>
				<td style='text-align:left'>
					<?php echo date("Y-m-d H:i",time()); ?>
				</td>
			</tr>
			<tr>
				<th style='text-align:left' scope = 'row'>
					Name
				</th>
				<td style='text-align:left'>
					<?php echo $name; ?>
				</td>
			</tr>
		</thead>
		<tbody>
		<?php
				$cnt = 1;
				$alternate = "";
				foreach($output as $row)
				{
					$row = htmlentities($row);
					$row = str_replace(" ","<em style='color:red'>.</em>",$row);
					if (is_null($var))
					{
						$row = "<span style='color:red'>null</span>";
					};
					if (($cnt%2) == 0) {
						$alternate = " style='background-color:aliceblue' ";
					} else {
						$alternate = "";
					}
					?>
			<tr <?php echo $alternate; ?>>
				<th  style='text-align:right;padding-right:10px;' scope = 'row'>
					<?php echo $cnt++; ?>
				</th>
				<td style='text-align:left'>
					<?php echo $row; ?>
				</td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>

<?php
			$return = ob_get_contents();
			ob_end_clean();
		}
		if ($echo)
			echo $return;
		return $return;
	}
}

?>