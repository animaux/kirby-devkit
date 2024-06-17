<?php

$debug = '';

Kirby::plugin(
  name: 'animaux/devkit',
  version: '0.4.3',
  info: [
			'description' => 'Get easily accessible info on variables and data in frontend for template development.',
			'homepage' => 'https://github.com/animaux/kirby-devkit',
			'license' => 'MIT',
	],
	extends: [
		'hooks' => [
			'page.render:after' => function (string $contentType, array $data, $html, Kirby\Cms\Page $page) {
			
				$kirby = $this;
				$user = $this->user();
				$site = $this->site();
				$files = $page->files();
				$content = $page->content();
				global $debug;
	
				function addVar($name, $value) {
					global $debug;
					$debug .= '<li><b>' . $name . '</b><span>' . $value . '</span></li>';
				}
				
				/* Check for ?devkit or ?dev, debug mode and logged in user */
				if ($kirby->option('debug') === true && $user && $user->isLoggedIn() && (isset($_GET['dev']) or isset($_GET['devkit']))) {
	
					$debug .= '<style>
						ul.debug {
						display: block;
						color: white;
						background: rgb(0,40,60);
						padding: 2em;
						white-space: pre-line;
						width: 100%;
						max-width: 100%;
						margin: 0 0 3em 0;
						}
	
						ul.debug > li {
						display: flex;
						flex-flow: row wrap;
						gap: 1em;
						font-family: monospace;
						font-size: 12px;
						line-height: 1.4em;
						padding: .25em;
						}
					
						ul.debug li:hover {
						background: rgba(255,255,255,.2);
						}
	
						ul.debug > li > b {
						flex: 0 0 16em;
						}
					
						ul.debug > li > span {
						flex: 1 0 0;
						}
						
						ul.files li {
						display: flex;
						flex-flow: row wrap;
						gap: 1em;
						}
						
						ul.files li + li {
						margin: .5em 0 0 0;
						}
						
						ul.files li * {
						flex: 1;
						}
						
						ul.files li img {
						max-width: 46px;
						height: 46px;
						}
						
						ul.files div.file {
						display: flex;
						align-items: center;
						justify-content: center;
						font-weight: bold;
						text-transform: uppercase;
						letter-spacing: .05em;
						max-width: 46px;
						height: 46px;
						color: black;
						background: rgba(255,255,255,.5);
						}
					</style>';
					$debug .= '<ul class="debug">';
					
						/* System */					
						addVar('kirby', Kirby::version());
						addVar('php', phpversion());
	
						$debug .= '<br/>';
						
						/* Basics */
						addVar('$site->title()', $site->title());
						addVar('$site->root()', $site->root());
						addVar('$site->url()', $site->url());
	
						$debug .= '<br/>';
	
						/* User */
						addVar('$user', $user);
						addVar('$user->name()', $user->name());						
						$debug .= '<br/>';
					
	
						/* PageTemplates */
						addVar('$page->slug()', $page->slug());
						addVar('$page->intendedTemplate()', $page->intendedTemplate());
						addVar('$page->template()', $page->template());
						$debug .= '<br/>';
						
						/* Fields/Data */
						foreach($content->data() as $key => $val) {
							$debug .= '<li><b>$page->' . $key . '()</b><span>';
							if (is_array($val)) {
								foreach ($val as $subKey => $subVal) {
									$debug .= '<b>' . $subKey . '</b>: ';
										/* Subarrays */
										if (is_array($subVal)) {
											foreach ($subVal as $subSubKey => $subSubVal) {
												$debug .= '[' . array_search($subSubKey,array_keys($subVal)) . '] ';
												if (is_string($subSubVal[0])) {
													$debug .= $subSubVal[0] . ' —> ' . $subSubVal[1];
												}
												if ($subSubKey != array_key_last($subVal)) {$debug .= '<br/>';}
											}
										} else {
											$debug .= $subVal;
										}
									}
									$debug .= '<br/>';
								} else {
									/* Files */
									if (is_string($val) && str_starts_with($val, '- file://')) {
										$files = $page->$key()->sortBy('sort','desc')->toFiles();
										$debug .= '<ul class="files">';
											foreach ($files as $file) {
												$debug .= '<li>';
													if ($file->type() == 'image') {
														$debug .= $file->thumb([
															'crop' => true,
															'width'   => 46,
															'height'  => 46,
															'quality' => 80,
														])->html();
													} else {
														$debug .= '<div class="file">' . $file->extension() . '</div>';
													}
													$debug .= '<p>';	    
														$debug .= '<b>' . $file->uuid() . '</b><br/>';
														$debug .= '»' . $file->title() . '«';
														$debug .= '<br/>';
														$debug .= $file->root();
													$debug .= '</p>';
												$debug .= '</li>';
											}
										$debug .= '</ul>';
									} else if (is_string($val)) {
										$debug .= $val;
									}
								}
								$debug .= '</span></li>';
							}
							$debug .= '</ul>';
						}
						
						/* Page Controllers abgrasen? */
						//$debug .= $kirby->controller($page->intendedTemplate()->name());
						
						
						/* Output */
						return $debug . $html;
						
				}
		]
	]

);