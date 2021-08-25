<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

//TODO: Check AUTH - dont allow direct access to this page - just if its included

include_once(__DIR__ . '/../../../modules/utilities/auth.php');

$auth = auth($_SESSION["userdata"]["id"], "requestPage", "results");

if ($auth["meta"]["requestStatus"] != "success") {
    echo "Not authorized";
} else {

	if (!function_exists("L")) {
		require_once(__DIR__."/../../../i18n.class.php");
		$i18n = new i18n(__DIR__.'/../../../lang/lang_{LANGUAGE}.json', __DIR__.'/../../../langcache/', 'de');
		$i18n->init();
	}

	// ELSE FINISHES AT END OF FILE
?>
<div class="row no-gutters">
	<div id="selectParliament" class="col-6 col-sm-auto">
		<select class="form-control form-control-sm" name="parliament">
			<option value="all" <?php if (!isset($_REQUEST['parliament'])) { echo 'selected'; } ?>><?php echo L::showAll; ?> <?php echo L::parliaments; ?></option>
			<?php
			foreach($config["parliament"] as $k=>$v) {
				$selectedString = '';
				if (isset($_REQUEST['parliament']) && $_REQUEST['parliament'] == $k) {
					$selectedString = ' selected';
				}
				
				//TODO: Remove once all parliaments should be listed
				if ($k == 'DE') {
					echo '<option value="'.$k.'"'.$selectedString.'>'.$v["label"].'</option>';
				}

				//echo '<option value="'.$k.'"'.$selectedString.'>'.$v["label"].'</option>';
			}
			?>
		</select>
	</div>
	<?php 
	if (isset($_REQUEST['parliament'])) {
	?>
	<div id="selectElectoralPeriod" class="col-2 col-sm-auto">
		<select class="form-control form-control-sm" name="electoralPeriod">
			<option value="all" <?php if (!isset($_REQUEST['electoralPeriod'])) { echo 'selected'; } ?>><?php echo L::showAll; ?> <?php echo L::electoralPeriods; ?></option>
			<?php
			$selectedString = '';
			if (isset($_REQUEST['electoralPeriod']) && $_REQUEST['electoralPeriod'] == '19') {
				$selectedString = ' selected';
			}
			echo '<option value="19"'.$selectedString.'>19. '. L::electoralPeriod .'</option>';
			?>
		</select>
	</div>
	<?php 
	}
	if (isset($_REQUEST['parliament']) && isset($_REQUEST['electoralPeriod'])) {
	?>
	<div id="selectSession" class="col-4 col-sm-auto">
		<select class="form-control form-control-sm" name="sessionNumber">
			<option value="all" <?php if (!isset($_REQUEST['sessionNumber'])) { echo 'selected'; } ?>><?php echo L::showAll; ?> <?php echo L::sessions; ?></option>
			<?php
			for ($i=1; $i <= 237; $i++) { 
			 	$selectedString = '';
				if (isset($_REQUEST['sessionNumber']) && $_REQUEST['sessionNumber'] == $i) {
					$selectedString = ' selected';
				}
			 	echo '<option value="'.$i.'"'.$selectedString.'>'.$i.'. '. L::session .'</option>';
			} 
			?>
		</select>
	</div>
	<?php 
	}
	?>
</div>
<?php 
}
?>