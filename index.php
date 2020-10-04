<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Tools</title>
	<base href="">
	<link rel="icon" href='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y=".9em" font-size="90">🔧</text></svg>'>
	<meta name="description" content="">
	<meta name="keywords" content="bitfertig, tools, datebobjs, wmlang, jobposting, justdatatablevue, 0to255">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script data-ad-client="ca-pub-3809977409157715" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<link rel="stylesheet" href="./style.css">
</head>
<body>


	<header>
		<div class="container">
			<h1>Tools</h1>
			<p>Tools, Scripts, Generators, Packages, Helpers.</p>
		</div>
	</header>


	<main>
		<div class="container">

			<?php
			$file_content = file_get_contents(__DIR__.'/tools.json');
			$categories = [];
			$tools = json_decode($file_content);
			foreach ($tools as $tool) {
				$tool = (object) $tool;
				if ( !isset($categories[ $tool->category ]) ) $categories[ $tool->category ] = [];
				$categories[ $tool->category ][] = $tool;
			}
			?>
			<?php foreach ($categories as $category => $tools) { ?>
				<h2><?= $category ?></h2>
				<ul>
					<?php foreach ($tools as $tool) { ?>
						<li>
							<a href="<?= $tool->path ?>"><?= $tool->label ?></a>
							<?php if ( !empty($tool->description) ) { ?><small class="text-muted"><?= $tool->description ?></small><?php } ?>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>

		</div>
	</main>


	<footer>
		<div class="container">
			<a href="http://www.bitfertig.de/impressum">Impress</a>
			&middot;
			<a href="http://www.bitfertig.de/datenschutzerklaerung">Privacy</a>
		</div>
	</footer>





	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-164640273-2"></script>
	<script>
	//document.querySelector('base').href = 'http://tools.bitfertig.de/';
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'UA-164640273-2', { 'anonymize_ip': true });
	</script>
	

</body>
</html>
