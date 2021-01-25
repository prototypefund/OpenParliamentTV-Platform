<header>
	<nav class="navbar justify-content-between navbar-light">
		<div>
			<a href="<?= $config["dir"]["root"] ?>/" class="breadcrumb-page navbar-text large brand">
				<img src="<?= $config["dir"]["root"] ?>/content/client/images/optv-logo_klein.png"><span class="<?=($page != "media") ? "d-none d-sm-inline" : "d-none d-lg-inline"?>"><?php echo L::brand; ?></span>
			</a>
			<?php if ($pageType == "admin" && $page != "dashboard") { ?>
				<div class="breadcrumb-page">
					<span class="navbar-text breadcrumb-separator">/</span><a href="<?= $config["dir"]["root"] ?>/dashboard" class="navbar-text pl-0 pr-0"><?php echo L::dashboard; ?></a>
				</div>
			<?php } ?>
			<div class="breadcrumb-page <?=($page != "media" && $page != "main") ? "" : "d-none"?>">
				<span class="navbar-text breadcrumb-separator">/</span><span href="" class="navbar-text pl-0 pr-0"><?= $pageTitle ?></span>
			</div>
		</div>
		<div class="navbarCenterOptions">
			<?php
			if ($_REQUEST["a"] == "media" && $isResult) {
				$autoplayResultsClass = ($autoplayResults) ? "active" : "";
				$backParamStr = preg_replace('/(&playresults=[0-1])/', '', ltrim($paramStr, '&'));
			?>
				<a href='<?= $config["dir"]["root"]."/search".$backParamStr ?>' class="btn btn-primary btn-sm"><span class="icon-left-open-big"></span><span class="icon-search"></span><span class="sr-only"><?php echo L::backToResults; ?></span></a>
				<div id="prevResultSnippetButton" class="btn btn-primary btn-sm"><span class="icon-left-open-big"></span><span class="sr-only"><?php echo L::previousSpeech; ?></span></div>
				<div id="nextResultSnippetButton" class="btn btn-primary btn-sm"><span class="icon-right-open-big"></span><span class="sr-only"><?php echo L::nextSpeech; ?></span></div>
				<div id="toggleAutoplayResults" class="navbar-text switch-container <?=$autoplayResultsClass?>">
					<span class="switch">
						<span class="slider round"></span>
					</span><span class="d-none d-md-inline"><?php echo L::autoplayResults; ?></span>
				</div>
			<?php
			}
			?>
		</div>
		<div class="navbarRightOptions">
			<?php if ($pageType != "admin" && $page != "login" && $page != "logout" && $page != "register") { ?>
				<button class="btn btn-primary btn-sm d-inline" type="button">
					<span class="icon-share"></span>
					<span class="sr-only"><?php echo L::share; ?></span>
				</button>
			<?php } ?>
			<div class="dropdown d-inline">
				<button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="icon-torso"></span></button>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item <?= ($page == "dashboard") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/dashboard">Dashboard</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item <?= ($page == "login") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/login"><?php echo L::login; ?></a>
					<a class="dropdown-item <?= ($page == "register") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/register"><?php echo L::registerNewAccount; ?></a>
					<a class="dropdown-item <?= ($page == "logout") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/logout"><?php echo L::logout; ?></a>
				</div>
			</div>
			<div class="dropdown d-inline">
				<button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="icon-menu"></span></button>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item <?= ($page == "about") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/about"><?php echo L::about; ?></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item <?= ($page == "datapolicy") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/datapolicy"><?php echo L::dataPolicy; ?></a>
					<a class="dropdown-item <?= ($page == "imprint") ? "active" : "" ?>" href="<?= $config["dir"]["root"] ?>/imprint"><?php echo L::imprint; ?></a>
				</div>
			</div>
		</div>
	</nav>
</header>