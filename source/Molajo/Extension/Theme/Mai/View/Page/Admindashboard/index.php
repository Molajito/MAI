<?php
use Molajo\Service\Services;

/**
 * @package     Molajo
 * @copyright   2012 Babs Gösgens. All rights reserved.
 * @license     GNU GPL v 2, or later and MIT, see License folder
 */
defined('MOLAJO') or die;

$pageURL = Services::Registry()->get('Triggerdata', 'full_page_url');
?>

	<nav role="navigation">
		<include:template name=Adminnavigationbar/>
	</nav>

	<section role="main">
		<include:message/>

		<a href="<?php echo $_baseUri ?>#focus" id="expander"><span>Expand working area</span></a>
		

						<fieldset>
						"offline":"0",
						</fieldset>

						<ul>
							<li>
								<a href="<?php echo $pageURL ?>#application">Application</a>

								<ul id="application">

									<li>
										<a href="<?php echo $pageURL ?>#applicationApplication">Application</a>
										<dl id="applicationApplication">
											<dt><?php echo Services::Language()->translate('application_name'); ?></dt>
											<dd><input type="text" name="application_name" value="Site 2" placeholder="The name of this application" /></dd>

											<dt><?php echo Services::Language()->translate('application_home_catalog_id'); ?></dt>
											<dd><select name="application_home_catalog_id"><option value="423"></option></select></dd>

											<dt><?php echo Services::Language()->translate('application_logon_requirement'); ?></dt>
											<dd>
												<select name="application_logon_requirement">
													<option value="1" selected="selected"><?php echo Services::Language()->translate('yes'); ?></option>
													<option value="0"><?php echo Services::Language()->translate('no'); ?></option>
												</select>
											</dd>
										</dl>
									</li>

									<li>
										<a href="#applicationUrl"><?php echo Services::Language()->translate('URL'); ?></a>
										<dl id="applicationUrl">
											<dt><?php echo Services::Language()->translate('url_sef'); ?></dt>
											<dd>yn (1)</dd>	

											<dt><?php echo Services::Language()->translate('url_unicode_slugs'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('url_force_ssl'); ?></dt>
											<dd>yn (0)</dd>	
										</dl>
									</li>

									<li>
										<a href="#applicationLanguage"><?php echo Services::Language()->translate('Language'); ?></a>
										<dl id="applicationLanguage">
											<dt><?php echo Services::Language()->translate('language'); ?></dt>
											<dd>en-GB</dd>	

											<dt><?php echo Services::Language()->translate('language_direction'); ?></dt>
											<dd>ltr</dd>	

											<dt><?php echo Services::Language()->translate('language_multilingual'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('language_utc_offset'); ?></dt>
											<dd>UTC</dd>	
										</dl>
									</li>

								</ul>

							</li>
							<li>
								<a href="<?php echo $pageURL ?>#development"><?php echo Services::Language()->translate('Development'); ?></a>

								<ul id="development">
									<li>
										<a href="#developmentProfiler"><?php echo Services::Language()->translate('Profiler'); ?></a>

										<dl id="developmentProfiler">
											<dt><?php echo Services::Language()->translate('profiler'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('profiler_verbose'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('profiler_start_with'); ?></dt>
											<dd>Initialise</dd>	

											<dt><?php echo Services::Language()->translate('profiler_end_with'); ?></dt>
											<dd>Response</dd>	

											<dt><?php echo Services::Language()->translate('profiler_output'); ?></dt>
											<dd>Actions,Application,Authorisation,Queries,Rendering,Routing,Services,Triggers</dd>	

											<dt><?php echo Services::Language()->translate('profiler_output_queries_table_registry'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('profiler_output_queries_sql'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('profiler_output_queries_query_results'); ?></dt>
											<dd>yn (0)</dd>	

											<dt><?php echo Services::Language()->translate('profiler_console_template_view_id'); ?></dt>
											<dd>1385</dd>	

											<dt><?php echo Services::Language()->translate('profiler_console_wrap_view_id'); ?></dt>
											<dd>2090</dd>	
										</dl>

									</li>
									<li>
										<a href="#developmentProfiler"><?php echo Services::Language()->translate('Profiler'); ?></a>

									</li>
								</ul>

							</li>

							<li>
								<a href="#theme"><?php echo Services::Language()->translate('Design'); ?></a>

								<ul id="theme">
									<li>
										<a href="#themeView"><?php echo Services::Language()->translate('Views'); ?></a>

										<fieldset>
											<legend><?php echo Services::Language()->translate('Head'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('head_template_view_id'); ?></dt>
												<dd>1340</dd>	

												<dt><?php echo Services::Language()->translate('head_wrap_view_id'); ?></dt>
												<dd>2090</dd>	
											</dl>
										</fieldset>

										<fieldset>
											<legend><?php echo Services::Language()->translate('Defer'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('defer_template_view_id'); ?></dt>
												<dd>1240</dd>	

												<dt><?php echo Services::Language()->translate('defer_wrap_view_id'); ?></dt>
												<dd>2090</dd>	
											</dl>
										</fieldset>

										<fieldset>
											<legend><?php echo Services::Language()->translate('Message'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('message_template_view_id'); ?></dt>
												<dd>1350</dd>	

												<dt><?php echo Services::Language()->translate('message_wrap_view_id'); ?></dt>
												<dd>2030</dd>	
											</dl>
										</fieldset>

										<fieldset>
											<legend><?php echo Services::Language()->translate('Offline'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('offline_theme_id'); ?></dt>
												<dd>9000</dd>

												<dt><?php echo Services::Language()->translate('offline_page_view_id'); ?></dt>
												<dd>260</dd>

												<dt><?php echo Services::Language()->translate('offline_message'); ?></dt>
												<dd>This site is not available</dd>
											</dl>
										</fieldset>

										<fieldset>
											<legend><?php echo Services::Language()->translate('Error'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('error_page_view_id'); ?></dt>
												<dd>9000</dd>	

												<dt><?php echo Services::Language()->translate('head_wrap_view_id'); ?></dt>
												<dd>250</dd>	

												<dt><?php echo Services::Language()->translate('error_404_message'); ?></dt>
												<dd>Page not found</dd>	

												<dt><?php echo Services::Language()->translate('error_403_message'); ?></dt>
												<dd>Not authorised</dd>	
											</dl>
										</fieldset>

									</li>

									<li>
										<a href="#themeView"><?php echo Services::Language()->translate('Theme'); ?></a>
									</li>

								</ul>

							</li>

						</ul>

						
						<div>   
						    "criteria_title":"UNL Integrated Data System",
						    "criteria_display_view_on_no_results":"0",
						    "criteria_snippet_length":"200",
						    "criteria_status":"",
						    "criteria_content_catalog_type_id":"",
						    "criteria_menuitem_catalog_type_id":"",
						    "criteria_content_extension_instance_id":"",
						    "criteria_menuitem_extension_instance_id":"",

						    "mustache":"0",

						    "menuitem_type":"",

						    "menuitem_source_id":" ",
						    "menuitem_source_catalog_type_id":" ",
						    "menuitem_extension_instance_id":" ",

						    "menuitem_theme_id":"9050",
						    "menuitem_page_view_id":"200",
						    "menuitem_page_view_css_id":"",
						    "menuitem_page_view_css_class":"",
						    "menuitem_template_view_id":"1030",
						    "menuitem_template_view_css_id":"",
						    "menuitem_template_view_css_class":"",
						    "menuitem_wrap_view_id":"2030",
						    "menuitem_wrap_view_css_id":"",
						    "menuitem_wrap_view_css_class":"",
						    "menuitem_model_name":"Content",
						    "menuitem_model_type":"Menuitem",
						    "menuitem_model_query_object":"List",

						    "list_theme_id":"9050",
						    "list_page_view_id":"220",
						    "list_page_view_css_id":"",
						    "list_page_view_css_class":"",
						    "list_template_view_id":"1050",
						    "list_template_view_css_id":"",
						    "list_template_view_css_class":"",
						    "list_wrap_view_id":"2030",
						    "list_wrap_view_css_id":"",
						    "list_wrap_view_css_class":"",
						    "list_model_name":"Content",
						    "list_model_type":"Table",
						    "list_model_query_object":"List",
						    "list_model_ordering":"start_publishing_datetime",
						    "list_model_direction":"DESC",
						    "list_model_offset":"0",
						    "list_model_count":"5",

						    "list_select_archived_content":"1",
						    "list_select_featured_content":"1",
						    "list_select_stickied_content":"1",
						    "list_select_published_date_begin":"",
						    "list_select_published_date_end":"",
						    "list_select_category_list":"0",
						    "list_select_tag_list":"0",
						    "list_select_author_list":"0",

						    "parent_menuitem":"0",
						    "item_parent_menu_id":"0",

						    "item_theme_id":"9050",
						    "item_page_view_id":"200",
						    "item_page_view_css_id":"",
						    "item_page_view_css_class":"",
						    "item_template_view_id":"1130",
						    "item_template_view_css_id":"",
						    "item_template_view_css_class":"",
						    "item_wrap_view_id":"2030",
						    "item_wrap_view_css_id":"",
						    "item_wrap_view_css_class":"",
						    "item_model_name":"Content",
						    "item_model_type":"Table",
						    "item_model_query_object":"Item",

						    "form_theme_id":"9050",
						    "form_page_view_id":"210",
						    "form_page_view_css_id":"",
						    "form_page_view_css_class":"",
						    "form_template_view_id":"1260",
						    "form_template_view_css_id":"",
						    "form_template_view_css_class":"",
						    "form_wrap_view_id":"2030",
						    "form_wrap_view_css_id":"",
						    "form_wrap_view_css_class":"",
						    "form_model_name":"Content",
						    "form_model_type":"Table",
						    "form_model_query_object":"Item",
						</div>
						<div>
						    "enable_draft_save":"1",
						    "enable_version_history":"1",
						    "enable_retain_versions_after_delete":"1",
						    "enable_maximum_version_count":"5",
						    "enable_hit_counts":"1",
						    "enable_comments":"1",
						    "enable_spam_protection":"1",
						    "enable_ratings":"1",
						    "enable_notifications":"1",
						    "enable_tweets":"1",
						    "enable_ping":"1",
						</div>
						<div>
						    "cache":"0",
						    "cache_time":"5",
						    "cache_handler":"file",
						</div>
						<div>
						    "log_user_activity_create":"1",
						    "log_user_activity_read":"1",
						    "log_user_activity_update":"1",
						    "log_user_activity_delete":"1",

						    "log_catalog_update_activity":"1",
						    "log_catalog_view_activity":"1",
						</div>
						<div>
						    "asset_priority_site":"100",
						    "asset_priority_application":"200",
						    "asset_priority_user":"300",
						    "asset_priority_extension":"400",
						    "asset_priority_request":"500",
						    "asset_priority_category":"600",
						    "asset_priority_menu_item":"700",
						    "asset_priority_source":"800",
						    "asset_priority_theme":"900",
						</div>
						<div>
						    "image_xsmall":"50",
						    "image_small":"75",
						    "image_medium":"150",
						    "image_large":"300",
						    "image_xlarge":"500",
						    "image_folder":"Images",
						    "image_thumb_folder":"Thumbs",
						</div>
						<div>
						    "gravatar":"1",
						    "gravatar_size":"50",
						    "gravatar_type":"mm",
						    "gravatar_rating":"pg",
						    "gravatar_image":"1"
						</div>
	</section>

