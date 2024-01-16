<?php



Kirby::plugin('animaux/devkit', [

	'hooks' => [
			'page.render:before' => function (string $contentType, array $data, Kirby\Cms\Page $page) {
			
				$user = $this->user();
				$site = $this->site();
				$content = $page->content();
					
				/* Check for ?devkit and logged in user */
				if ($user && $user->isLoggedIn() && isset($_GET['dev'])) {

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

						ul.debug li {
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

						ul.debug li > b {
						flex: 0 0 16em;
						}
						
						ul.debug li > span {
						flex: 1 0 0;
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
	
						/* Page */
						echoVar('$page->slug()', $page->slug());
						foreach($content->data() as $key => $val) {
							echo '<li><b>$page->' . $key . '()</b><span>';
							if (is_array($val)) {
								foreach ($val as $subKey => $subVal) {
									echo '<b>' . $subKey . '</b>: ';
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
									if (is_string($val)) {
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








