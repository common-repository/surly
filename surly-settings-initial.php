<div class="wrapper-surly">
	<div class="ps-window">
		<div class="ps-top-title">
			<p>Sur.ly plugin settings</p>
		</div>
		<div class="ps-window-content">
			<div class="ps-left-side">
				<div class="ps-left-content">
					<div class="ps-step-menu">
						<ul id="initial-steps" class="ps-menu">
							<li>
								<div class="ps-num-item"><span>1</span></div>
								<div class="ps-name-item"><p><span>Registration</span></p></div>
							</li>
							<li>
								<div class="ps-num-item"><span>2</span></div>
								<div class="ps-name-item"><p><span>Domains</span></p></div>
								<div class="ps-sub-menu">
									<ul>
										<li>
											<span class="sline"></span>
											<div class="ps-sub-name-item"><p><span>Use your subdomain</span></p></div>
										</li>
										<li>
											<span class="sline hlast"></span>
											<div class="ps-sub-name-item"><p><span>Trusted domains</span></p></div>
										</li>
									</ul>
								</div>
							</li>
							<li>
								<div class="ps-num-item"><span>3</span></div>
								<div class="ps-name-item"><p><span>Trusted groups</span></p></div>
							</li>
							<li>
								<div class="ps-num-item"><span>4</span></div>
								<div class="ps-name-item"><p><span>URL processing</span></p></div>
								<div class="ps-sub-menu">
									<ul>
										<li>
											<div class="ps-sub-name-item"><p><span>Shorten URLs</span></p></div>
										</li>
										<li>
											<div class="ps-sub-name-item"><p><span>Replace URLs</span></p></div>
										</li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="ps-right-side">
				<div class="ps-right-content">
					<div id="surly-initial-step-1-0" style="display:none">
						<?php if (!get_option('surly_toolbar_id')): ?>
							<iframe scrolling="no" class="surly-auth" src="https://surdotly.com/settings/auth/?<?php echo http_build_query(array('url' => get_bloginfo('url'), 'cmsId' => 1, 'meta' => array('cms_version' => get_bloginfo('version')))); ?>"></iframe>
						<?php endif; ?>
					</div>
					<form id="surly-initial-step-2-1" style="display:none">
						<input type="hidden" name="action" value="surly_save_subdomain"/>
						<div class="ps-info-text">
							<p>If you have a subdomain set up according to <a href="https://surdotly.com/setting_subdomain#dns">instructions</a>, just enter its name to allow viewing external pages via it.</p>
						</div>
						<div class="ps-cell">
							<div class="surly-field-error" data-field="surly-subdomain"></div>
						</div>
						<div class="ps-next-form">
							<div class="ps-left-row">
								<div class="ps-list">
									<ul>
										<li>
											<div class="ps-type-in">
												<input name="surly_subdomain" value="<?php echo get_option('surly_subdomain', ''); ?>" type="text" placeholder="URL"/>
											</div>
										</li>
										<li>
											<div class="ps-type-buttons">
												<a id="surly-save-subdomain" href="#" class="ps-type-button ps-icon blue">Next<span> &rarr;</span></a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</form>
					<div id="surly-initial-step-2-2" style="display:none">
						<div class="ps-info-text">
							<p>List your project’s link building partners (or other trusted websites) here and keep all the outbound linking to their domains & subdomains untouched.</p>
						</div>
						<div class="ps-next-form">
							<div class="ps-list">
								<ul>
									<li>
										<form id="surly-trusted-domains">
											<div class="ps-adding">
												<div class="ps-inner-box">
													<div class="ps-table-box">
														<div class="ps-cell">
															<div class="w430">
																<div class="ps-type-in">
																	<input id="surly-trusted-domain" name="surly_trusted_domain" value="" placeholder="Domain"/>
																	<div class="ps-type-buttons">
																		<a id="surly-save-trusted-domain" href="#" class="ps-type-button blue">Add domain</a>
																	</div>
																</div>
															</div>
														</div>
														<div class="ps-cell">
															<div class="surly-field-error" data-field="surly-trusted-domain"></div>
														</div>
													</div>
													<div class="ps-central-box">
														<div class="ps-table-line">
															<ul>
																<li class="first">
																	<span class="ps-type-check">
																		<input type="checkbox" id="surly_trusted_domains">
																	</span>
																	<label for="surly_trusted_domains">Domain</label>
																	<span class="num-item">
																		<?php if (count(get_option('surly_trusted_domains', array())) == 1): ?>
																			1 item
																		<?php else: ?>
																			<?php echo count(get_option('surly_trusted_domains', array())); ?> items
																		<?php endif; ?>
																	</span>
																</li>
																<?php foreach (get_option('surly_trusted_domains', array()) as $key => $value): ?>
																	<li class="inner">
																		<span class="ps-type-check">
																			<input
																				id="surly_trusted_domains-<?php echo $key; ?>"
																				name="surly_trusted_domains[]"
																				value="<?php echo $value; ?>"
																				type="checkbox"/>
																		</span>
																		<label for="surly_trusted_domains-<?php echo $key; ?>"><?php echo $value; ?></label>
																	</li>
																<?php endforeach ;?>
																<?php if (get_option('surly_trusted_domains', array())): ?>
																	<li class="empty" style="display:none;"><label>No items found</label></li>
																<?php else: ?>
																	<li class="empty"><label>No items found</label></li>
																<?php endif; ?>
															</ul>
															<div class="ps-type-buttons">
																<a id="surly-delete-trusted-domains" href="#" class="ps-type-button ps-border lred pad30">Delete</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
									</li>
									<li>
										<div class="ps-type-buttons">
											<a id="surly-save-trusted-domains" href="#" class="ps-type-button ps-icon blue">Next<span> &rarr;</span></a>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<form id="surly-initial-step-3-0" style="display:none">
						<input type="hidden" name="action" value="surly_save_trusted_groups"/>
						<div class="ps-info-text">
							<p>Select the trusted user groups whose links should stay untouched.</p>
						</div>
						<div class="ps-next-form">
							<div class="ps-left-row">
								<div class="ps-list">
									<ul>
										<li>
											<div class="ps-list-in trusted-groups">
												<ul>
													<?php foreach(surly_get_roles() as $key => $value): ?>
														<li>
															<label for="surly_trusted_groups-<?php echo $key; ?>">
																<?php echo $value['name']; ?>
															</label>
															<input
																id="surly_trusted_groups-<?php echo $key; ?>"
																name="surly_trusted_groups[]"
																value="<?php echo $key; ?>"
																type="checkbox"
																<?php if (in_array($key, get_option('surly_trusted_groups', array()))): ?>checked="checked"<?php endif; ?>/>
														</li>
													<?php endforeach; ?>
												</ul>
											</div>
										</li>
										<li>
											<div class="ps-type-buttons">
												<a id="surly-save-trusted-groups" href="#" class="ps-type-button ps-icon blue">Next<span> &rarr;</span></a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</form>
					<form id="surly-initial-step-4-1" style="display:none">
						<input type="hidden" name="action" value="surly_save_shorten_urls"/>
						<div class="ps-info-text">
							<p>Enable URL shortening (optionally): all links replaced by Sur.ly will be shortened and formatted like http://sur.ly/o/bN/<?php echo get_option('surly_toolbar_id', SURLY_DEFAULT_TOOLBAR_ID); ?>.</p>
						</div>
						<div class="ps-next-form">
							<div class="ps-left-row">
								<div class="ps-list">
									<ul>
										<li>
											<div class="ps-select-in">
												<select name="surly_shorten_urls">
													<option value="0"<?php if (!get_option('surly_shorten_urls', false)): ?> selected="selected"<?php endif; ?>>Disable</option>
													<option value="1"<?php if (get_option('surly_shorten_urls', false)): ?> selected="selected"<?php endif; ?>>Enable</option>
												</select>
											</div>
										</li>
										<li>
											<div class="ps-type-buttons">
												<a id="surly-save-shorten-urls" href="#" class="ps-type-button ps-icon blue">Next<span> &rarr;</span></a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</form>
					<form id="surly-initial-step-4-2" style="display:none">
						<input type="hidden" name="action" value="surly_save_replace_urls"/>
						<div class="ps-info-text">
							<p>Sur.ly can work for links in Comments, Posts, or for all outbound links on your site.<br />Important: ’Everywhere’ covers all links, including third-party plugins and forum software.</p>
						</div>
						<div class="ps-next-form">
							<div class="ps-left-row">
								<div class="ps-list">
									<ul>
										<li>
											<div class="ps-list-in">
												<ul>
													<li>
														<label for="surly_replace_urls_nowhere">Nowhere</label>
														<input
															id="surly_replace_urls_nowhere"
															name="surly_replace_urls[]"
															value="0"
															type="checkbox"
															<?php if (in_array(0, get_option('surly_replace_urls', array(0)))): ?>checked="checked"<?php endif; ?>/>
													</li>
													<li>
														<label for="surly_replace_urls_posts">Posts</label>
														<input
															id="surly_replace_urls_posts"
															name="surly_replace_urls[]"
															value="1"
															type="checkbox"
															<?php if (in_array(1, get_option('surly_replace_urls', array(0)))): ?>checked="checked"<?php endif; ?>/>
													</li>
													<li>
														<label for="surly_replace_urls_comments">Comments</label>
														<input
															id="surly_replace_urls_comments"
															name="surly_replace_urls[]"
															value="2"
															type="checkbox"
															<?php if (in_array(2, get_option('surly_replace_urls', array(0)))): ?>checked="checked"<?php endif; ?>/>
													</li>
													<li>
														<label for="surly_replace_urls_everywhere">Everywhere</label>
														<input
															id="surly_replace_urls_everywhere"
															name="surly_replace_urls[]"
															value="3"
															type="checkbox"
															<?php if (in_array(3, get_option('surly_replace_urls', array(0)))): ?>checked="checked"<?php endif; ?>/>
													</li>
												</ul>
											</div>
										</li>
										<li>
											<div class="ps-type-buttons">
												<a id="surly-save-replace-urls" href="<?php echo admin_url( 'options-general.php?page=surly.php' ); ?>" class="ps-type-button blue">Finish</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		surly.initialStep('<?php echo get_option('surly_initial', '1-0'); ?>');
	});
</script>