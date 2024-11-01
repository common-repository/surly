<div class="wrapper-surly">
	<div class="ps-window">
		<div class="ps-central-content">
			<div class="ps-top-title">
				<p>Sur.ly</p>
			</div>
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
				</form>
			</div>
			<div class="ps-type-buttons">
				<a id="surly-save-settings" href="#" class="ps-type-button blue">Save changes</a>
			</div>
			<div class="ps-rows-settings">
				<div class="ps-row-box">
					<div class="ps-inner-box">
						<div class="ps-title-box">
							<p>Customize Sur.ly for maximum effect</p>
						</div>
						<div class="ps-show-info">
							<a id="surly-set-up-plugin-img" href="#"><div class="toolbar-img"></div></a>
						</div>
					</div>
					<div class="ps-type-buttons ps-mess">
						<a id="surly-set-up-plugin" href="#" class="ps-type-button blue">Configure Sur.ly</a>
						<p class="ps-start">and get the most out of outbound links with fine tuning.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>