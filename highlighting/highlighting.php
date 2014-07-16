<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="./assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="./assets/css/reset.css" />
	</head>
	<body id="">
	<style>
/*
		body{
			margin: 0;
			background: #000;
		}
		ul{
			background-color: #F6F6F6;
		}
		ul li{
			list-style: none;
		}
		.json{
			font-family: monospace;
			line-height: 18px;
		}
		.json ul {
			border-left: 1px solid #e6e6e6;
		}

		li .expand{
			margin-left: -15px;
		}
		.expand{
			padding: 0px 5px 0px 0px;
		}
*/
	</style>
		<?php
			require_once 'class.Highlighting.php';
			$arr = array(
				'one' => 1,
				'two' => 'apple',
				'three' => array(
					0,
					9
				),
				'four' => array(
					'one' => 1,
					'two' => 'apple',
				),
				0 => true,
				1 => 1.0546,
				2 => array(
					'one' => 1,
					'two' => 'apple',
					'three' => array(
						0,
						9
					),
					'four' => array(
						'one' => 1,
						'two' => 'apple',
					),
					0 => true,
					1 => 1.0546,
					2 => array(
						'one' => 1,
						'two' => 'apple',
						'three' => array(
							0,
							9
						),
						'four' => array(
							'one' => 1,
							'two' => 'apple',
						),
						0 => true,
						1 => 1.0546,
						2 => array( ),
					),
				),
				10 => false,
				11 => null
			);
//			Highlighting::renderJson( $arr );
			/*
$html = '<div class="wrapper clearfix">
	<ul class="nav">
		<li><a href="http://itunes.apple.com/app/troy-lee-designs/id496883338?mt=8" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_appstore.png" alt="" /></a>
		</li>
		<li><a href="http://www.facebook.com/troyleedesigns.tld" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_facebook.png" alt="" /></a>
		</li>
		<li><a href="http://twitter.com/troyleedesigns" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_twitter.png" alt="" /></a>
		</li>
		<li><a href="http://instagram.com/troyleedesigns#" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_instagram.png" alt="" /></a>
		</li>
		<li><a href="https://plus.google.com/b/108972167110076793221/108972167110076793221/about" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_google.png" alt="" /></a>
		</li>
		<li><a href="http://www.youtube.com/troyleedesigns" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_youtube.png" alt="" /></a>
		</li>
		<li><a href="http://www.linkedin.com/company/1637125" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_linkedin.png" alt="" /></a>
		</li>
		<li><a href="http://troyleesportswear.blogspot.com/" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_blogger.png" alt="" /></a>
		</li>
		<li><a href="http://troyleedesigns.com/omniweaver/pages/rss/article" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_rss.png" alt="" /></a>
		</li>
		<li><a class="verisign_popout" href="https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&dn=www.troyleedesigns.com&lang=en" target="_blank"><img src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_verisign.png" alt="" /></a>
		</li>
	</ul>
	<form action="http://192.168.6.98/troyleedesigns/mailing-list" method="post" class="floatright">
		<label for="footer_mailinglist"><span>Join our Mailing List</span>
		</label>
		<div class="clear"></div>
		<div>
			<input type="text" name="email" value="Email@Example.com" id="footer_mailinglist" class="email" />
			<input id="footer_email_submit" type="image" class="submit" src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_submit.png" />
		</div>
	</form>
	<form class="floatright" action="http://192.168.6.98/troyleedesigns/store-locator" method="get">
		<label for="footer_locator_zip">	<span>Find a Store</span>

		</label>
		<div class="clear"></div>
		<div class="relative">
			<input type="text" value="Zip Code" class="zip" id="footer_locator_zip" name="locator_zip" />
			<input id="footer_zip_submit" type="image" name="z" src="http://192.168.6.98/troyleedesigns/assets/img/ui_footer_submit.png" />
		</div>
	</form>
</div>';
			//*/

$css = ".json, .html, .css {
	margin-left: 15px;
	font-family: monospace;
	line-height: 18px;

	#wtf, #123456789fg, #aabbcc {
		derp: 105em;
	}
	ul{
		border-left: 1px solid #e6e6e6;

		&:after{ content: \"}\"; }
	}

	li {
		padding-left: 20px;
		list-style: none;
	}


	li .expand{
		margin-left: -15px;
		padding: 0px 5px 0px 0px;
	}

	span{
		&.string {
			color: #85AF3A;
			&:after, &:before { content: '\"';}
		}

		&.object {
			color: #d7893d;

			&:before { content: 'Object('; }
			&:after  { content: ')'; }
		}

		&.integer, &.double {
			color: #66a3c2 !important;
		}

		&.boolean {
			color: #d25716;
		}

		&.null, &.empty {
			color: #A6A79A;
		}

		&.htmlTag, &.selector{
			color: #d7893d;
		}

		&.attribute{
			color: #85AF3A;
		}

		&.property{
			color: #69457C;
		}
	}
}";
			Highlighting::renderCss( $css );

		?>
		<script src="./assets/js/core.js"></script>
		<script src="./assets/js/ui.js"></script>
	</body>
</html>