<?php

Kirby::plugin('animaux/devkit', [

	'hooks' => [
		'page.render:before' => function (string $contentType, array $data, Kirby\Cms\Page $page) {
		
			$kirby = $this;
			$user = $this->user();
			$site = $this->site();
			$files = $page->files();
			$content = $page->content();
			
			/* Check for ?devkit and logged in user */
			if ($kirby->option('debug') === true && $user && $user->isLoggedIn() && isset($_GET['dev'])) {

				function echoVar($key, $val) {
					echo '<li><b>' . $key . '</b><span>' . $val . '</span></li>';
				}

				echo '<style>
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
					
					ul.files div.file {
					display: flex;
					align-items: center;
					justify-content: center;
					font-weight: bold;
					text-transform: uppercase;
					letter-spacing: .05em;
					width: 46px;
					height: 46px;
					color: black;
					background: rgba(255,255,255,.5);
					}
				</style>';
				echo '<ul class="debug">';
				
					/* System */
					echoVar('kirby', Kirby::version());
					echoVar('php', phpversion());

					echo '<br/>';

					/* Basics */
					echoVar('$site->title()', $site->title());
					echoVar('$site->root()', $site->root());
					echoVar('$site->url()', $site->url());

					echo '<br/>';

					/* User */
					echoVar('$user', $user);
					echoVar('$user->name()', $user->name());						
					echo '<br/>';
				

					/* PageTemplates */
					echoVar('$page->slug()', $page->slug());
					echoVar('$page->intendedTemplate()', $page->intendedTemplate());
					echoVar('$page->template()', $page->template());
					echo '<br/>';

					/* Fields/Data */
					foreach($content->data() as $key => $val) {
						echo '<li><b>$page->' . $key . '()</b><span>';
						if (is_array($val)) {
							foreach ($val as $subKey => $subVal) {
								echo '<b>' . $subKey . '</b>: ';
									/* Subarrays */
									if (is_array($subVal)) {
										foreach ($subVal as $subSubKey => $subSubVal) {
											echo '[' . array_search($subSubKey,array_keys($subVal)) . '] ';
											if (is_string($subSubVal[0])) {
												echo $subSubVal[0] . ' —> ' . $subSubVal[1];
											}
											if ($subSubKey != array_key_last($subVal)) {echo '<br/>';}
										}
									} else {
										echo $subVal;
									}
								}
								echo '<br/>';
							} else {
								/* Files */
								if (is_string($val) && str_starts_with($val, '- file://')) {
									$files = $page->$key()->sortBy('sort','desc')->toFiles();
									echo '<ul class="files">';
										foreach ($files as $file) {
											echo '<li>';
												if ($file->type() == 'image') {
													echo $file->thumb([
														'crop' => true,
														'width'   => 46,
														'height'  => 46,
														'quality' => 80,
													])->html();
												} else {
												  echo '<div class="file">' . $file->extension() . '</div>';
												}
												echo '<p>';	    
													echo '<b>' . $file->uuid() . '</b><br/>';
													echo '»' . $file->title() . '«';
													echo '<br/>';
													echo $file->root();
												echo '</p>';
											echo '</li>';
										}
									echo '</ul>';
								} else if (is_string($val)) {
									echo $val;
								}
							}
							echo '</span></li>';
						}
						echo '</ul>';
					}
			}
	]

]);








