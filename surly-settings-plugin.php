<div class="wrapper-surly">
	<div class="ps-window">
		<div class="ps-central-content">
			<div class="ps-rows-settings">
				<form id="surly-save-settings-form">
					<input type="hidden" name="action" value="surly_save_settings"/>
					<div id="surly-replace-urls" class="ps-row-box">
						<div class="ps-inner-box">
							<div class="ps-title-box">
								<p>Replace URLs</p>
							</div>
							<div class="ps-table-box">
								<div class="ps-cell">
									<div class="w280">
										<div class="ps-list-in<?php if (in_array(0, get_option('surly_replace_urls', array(0)))): ?> field-error<?php endif; ?>">
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
									</div>
								</div>
								<div class="ps-cell">
									<div class="surly-field-error" data-field="surly-replace-urls">
										<div <?php if (in_array(0, get_option('surly_replace_urls', array(0)))): ?>class="ps-title-cell red" style="display:block"<?php else: ?>style="display:none;"<?php endif; ?>>
											<p>Your website and visitors are not yet protected by Sur.ly. Be sure to enable it for your outbound links.</p>
										</div>
									</div>
									<div class="ps-title-cell">
										<p>Sur.ly can work for links in Comments, Posts, or for all outbound links on your site.<br />Important: ’Everywhere’ covers all links, including third-party plugins and forum software.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ps-row-box">
						<div class="ps-inner-box">
							<div class="ps-title-box">
								<p>Shorten URLs</p>
							</div>
							<div class="ps-cell">
								<div class="w280">
									<div class="ps-cell">
										<div class="ps-select-in">
											<select name="surly_shorten_urls">
												<option value="0"<?php if (!get_option('surly_shorten_urls', false)): ?> selected="selected"<?php endif; ?>>Disable</option>
												<option value="1"<?php if (get_option('surly_shorten_urls', false)): ?> selected="selected"<?php endif; ?>>Enable</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="ps-cell">
								<div class="ps-title-cell">
									<p>Enable URL shortening (optionally): all links replaced by Sur.ly will be shortened and formatted like http://sur.ly/o/bN/<?php echo get_option('surly_toolbar_id', SURLY_DEFAULT_TOOLBAR_ID); ?>.</p>
								</div>
							</div>
						</div>
					</div>
					<div id="surly-subdomain" class="ps-row-box">
						<div class="ps-inner-box">
							<div class="ps-title-box">
								<p>Use your subdomain</p>
							</div>
							<div class="ps-table-box">
								<div class="ps-cell">
									<div class="w280">
										<div class="ps-type-in">
											<input name="surly_subdomain" value="<?php echo get_option('surly_subdomain', ''); ?>" type="text" placeholder="URL"/>
										</div>
									</div>
								</div>
								<div class="ps-cell">
									<div class="surly-field-error" data-field="surly-subdomain"></div>
									<div class="ps-title-cell">
										<p>If you have a subdomain set up according to <a href="https://surdotly.com/setting_subdomain#dns">instructions</a>, just enter its name to allow viewing external pages via it.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ps-row-box">
						<div class="ps-inner-box">
							<div class="ps-title-box">
								<p>Trusted groups</p>
							</div>
							<div class="ps-table-box">
								<div class="ps-cell">
									<div class="w280">
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
									</div>
								</div>
								<div class="ps-cell">
									<div class="ps-title-cell">
										<p>Select the trusted user groups whose links should stay untouched.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				<form id="surly-trusted-domains">
					<div class="ps-row-box">
						<div class="ps-inner-box">
							<div class="ps-title-box">
								<p>Trusted domains</p>
							</div>
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
									<div class="ps-title-cell">
										<p>List your project’s link building partners (or other trusted websites) here and keep all the outbound linking to their domains & subdomains untouched.</p>
									</div>
								</div>
							</div>
							<div class="ps-central-box">
								<div class="ps-table-line">
									<ul>
										<li class="first">
											<span class="ps-type-check">
												<input type="checkbox" value="1" id="surly_trusted_domains"/>
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
			</div>
			<div class="ps-type-buttons">
				<a id="surly-save-settings" href="#" class="ps-type-button blue">Save changes</a>
			</div>
		</div>
	</div>
</div>
