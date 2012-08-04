<?php
use Molajo\Service\Services;
/**
 * @package       Molajo
 * @copyright     2012 Babs Gösgens. All rights reserved.
 * @license       GNU GPL v 2, or later and MIT, see License folder
 */
defined('MOLAJO') or die;

$pageURL = Services::Registry()->get('Triggerdata', 'full_page_url');

$title = Services::Registry()->get('Triggerdata', 'PageTitle');
if ($title == '') {
    $title = $this->row->criteria_title;
} else {
    $title .= '-' . $this->row->criteria_title;
}
$homeURL = Services::Registry()->get('Configuration', 'application_base_url');
$pageURL = Services::Registry()->get('Triggerdata', 'full_page_url');
?>
    <header role="banner">
		<div>
			<h1><a href="<?php echo $homeURL ?>"><i>"</i><span><strong>Molajo</strong> Admin Interface</span></a></h1>
			<nav>

				<ul class="suckerfish settings">
					<li class="search">
						<a href="<?php echo $pageURL ?>#search"><i>=</i><span>Search</span></a>
						<form role="search">
							<input type="search" placeholder="Search Resources">
						</form>
					</li
					><li class="user align-right">
						<a href="<?php echo $pageURL ?>#user">
							<img src="/source/Molajo/Extension/Theme/Mai/Images/smile.png" alt="" width="40" height="40" />
							<span>Babs G&ouml;sgens</span>
						</a>
						<div>
							<h2>User Settings</h2>
							<ul id="user">
								<li><a href="<?php echo $pageURL ?>#"><?php echo Services::Language()->translate('User Settings'); ?></a></li>
								<li><a href="<?php echo $pageURL ?>#">Mail <small>no&nbsp;new&nbsp;messages</small></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $pageURL ?>#">Last Item</a></li>
							</ul>
						</div>
					</li
					><li class="config last align-right">
						<a href="<?php echo $pageURL ?>#aplication-config"><i>a</i><span>Settings</span></a>
						<div>
							<h2><?php echo Services::Language()->translate('Site Settings'); ?></h2>
							<ul>
								<li><a href="<?php echo $pageURL ?>" data-reveal-id="application-config"><?php echo Services::Language()->translate('Application&nbsp;Configuration'); ?></a></li>
								<li><a href="<?php echo $pageURL ?>" data-reveal-id="application-options"><?php echo Services::Language()->translate('Applications&hellip;'); ?></a></li>
								<li class="divider"></li>
								<li class="switch">
									<form class="custom">
										<input type="hidden" name="offline" value="1" />
										<button class="tiny alert radius button"><?php echo Services::Language()->translate('Offline'); ?></button>
									</form>
								</li>
							</ul>
						</div>
					</li>
				</ul>

			</nav>
		</div>
    </header>

	<div id="application-config" class="reveal-modal">

		<form class="custom row">

			<dl class="vertical tabs three columns">
				<dd class="active"><a href="<?php echo $pageURL ?>#application"><?php echo Services::Language()->translate('Application'); ?></a></dd>
				<dd><a href="<?php echo $pageURL ?>#development"><?php echo Services::Language()->translate('Development'); ?></a></dd>
				<dd><a href="<?php echo $pageURL ?>#design"><?php echo Services::Language()->translate('Design'); ?></a></dd>
			</dl>

			<ul class="contained tabs-content fifteen columns">
				<li class="active" id="applicationTab">
					
					<ul class="accordion">
						<li class="active">
							<h5 class="title"><?php echo Services::Language()->translate('Site'); ?></h5>
							<div class="content">

								<div class="row">
									<div class="five columns">
										<label class="right inline" for="application_name"><?php echo Services::Language()->translate('application_name'); ?></label>
									</div>
									<div class="ten columns">
										<input type="text" placeholder="<?php echo Services::Language()->translate('A name for your website'); ?>" name="application_name" id="application_name" class="eight" value="Site 2" /> 
									</div>
									<div class="three columns">
										<a href="<?php echo $pageURL ?>" data-reveal-id="application_name_info" class="helper-info"><i>`</i><small><?php echo Services::Language()->translate('More info&hellip;'); ?></small></a>
										<div id="application_name_info" class="reveal-modal">
											This is the name of your website. It will be put in the browser title of your site and Google will use it in its search results.
										</div>
									</div>
								</div>

								<div class="row">
									<div class="five columns">
										<label class="right inline" for="application_home_catalog_id"><?php echo Services::Language()->translate('application_home_catalog_id'); ?></label>
									</div>
									<div class="ten columns">
										<select name="application_home_catalog_id" id="application_home_catalog_id"><option value="423"></option></select>
									</div>
									<div class="three columns">
										<a href="<?php echo $pageURL ?>" data-reveal-id="application_name_info" class="helper-info"><i>`</i><small><?php echo Services::Language()->translate('More info&hellip;'); ?></small></a>
										<div id="application_name_info" class="reveal-modal">
											This is the name of your website. It will be put in the browser title of your site and Google will use it in its search results.
										</div>
									</div>
								</div>

								<div class="row">
									<div class="five columns">
										<label class="right inline" for="application_logon_requirement"><?php echo Services::Language()->translate('application_logon_requirement'); ?></label>
									</div>
									<div class="ten columns">
										<select name="application_logon_requirement" id="application_logon_requirement">
											<option value="1" selected="selected"><?php echo Services::Language()->translate('yes'); ?></option>
											<option value="0"><?php echo Services::Language()->translate('no'); ?></option>
										</select>
									</div>
									<div class="three columns">
										<a href="<?php echo $pageURL ?>" data-reveal-id="application_name_info" class="helper-info"><i>`</i><small><?php echo Services::Language()->translate('More info&hellip;'); ?></small></a>
										<div id="application_name_info" class="reveal-modal">
											This is the name of your website. It will be put in the browser title of your site and Google will use it in its search results.
										</div>
									</div>
								</div>

							</div>

						</li>
						<li>
							<h5 class="title"><?php echo Services::Language()->translate('Url'); ?></h5>
							<dl class="content">
								<dt><?php echo Services::Language()->translate('url_sef'); ?></dt>
								<dd>yn (1)</dd>	

								<dt><?php echo Services::Language()->translate('url_unicode_slugs'); ?></dt>
								<dd>yn (0)</dd>	

								<dt><?php echo Services::Language()->translate('url_force_ssl'); ?></dt>
								<dd>yn (0)</dd>	
							</dl>
						</li>
						<li>
							<h5 class="title"><?php echo Services::Language()->translate('Language'); ?></h5>
							<dl class="content">
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
				<li id="developmentTab">

					<ul class="accordion">
						<li class="active">
							<h5 class="title"><?php echo Services::Language()->translate('Profiler'); ?></h5>
							<dl class="content">
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
					</ul>

				</li>
				<li id="designTab">

					<ul class="accordion">
						<li class="active">
							<h5 class="title"><?php echo Services::Language()->translate('Mustache'); ?></h5>
							<div class="content">
								"mustache":"0",
							</div>
						</li>
						<li>
							<h5 class="title"><?php echo Services::Language()->translate('Views'); ?></h5>
							<div class="content">
								<ul class="block-grid two-up">
									<li>
										<fieldset>
											<legend><?php echo Services::Language()->translate('Head'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('head_template_view_id'); ?></dt>
												<dd>1340</dd>	

												<dt><?php echo Services::Language()->translate('head_wrap_view_id'); ?></dt>
												<dd>2090</dd>	
											</dl>
										</fieldset>
									</li>
									<li>
										<fieldset>
											<legend><?php echo Services::Language()->translate('Defer'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('defer_template_view_id'); ?></dt>
												<dd>1240</dd>	

												<dt><?php echo Services::Language()->translate('defer_wrap_view_id'); ?></dt>
												<dd>2090</dd>	
											</dl>
										</fieldset>
									</li>
									<li>
										<fieldset>
											<legend><?php echo Services::Language()->translate('Message'); ?></legend>
											<dl>
												<dt><?php echo Services::Language()->translate('message_template_view_id'); ?></dt>
												<dd>1350</dd>	

												<dt><?php echo Services::Language()->translate('message_wrap_view_id'); ?></dt>
												<dd>2030</dd>	
											</dl>
										</fieldset>
									</li>
									<li>
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
									</li>
									<li>
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
								</ul>
							</div>
						</li>
						<li>

							<h5 class="title"><?php echo Services::Language()->translate('Theme'); ?></h5>
							<div class="content">

									<ul class="block-grid two-up">
									<li>

										<h6><?php echo Services::Language()->translate('Menu Item'); ?></h6>
										<dl class="content">
											<dd>
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
						    				</dd>
						    			</dl>

									</li>
									<li>

										<h6><?php echo Services::Language()->translate('List View'); ?></h6>
										<dl>
											<dd>
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
						    				</dd>
						    			</dl>

									</li>
									<li>

										<h6><?php echo Services::Language()->translate('Item View'); ?></h6>
										<dl class="content">
											<dd>
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
											</dd>
										</dl>

									</li>
									<li>

										<h6><?php echo Services::Language()->translate('Edit Views'); ?></h6>
										<dl class="content">
											<dd>
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
											</dd>
										</dl>
										
									</li>
									<li>

										<h6><?php echo Services::Language()->translate('Menu Item'); ?></h6>
										<dl class="content">
											<dd></dd>
										</dl>
										
									</li>
									<li>

										<h6><?php echo Services::Language()->translate('Menu Item'); ?></h6>
										<dl class="content">
											<dd></dd>
										</dl>
										
									</li>
									<li>

										<h6><?php echo Services::Language()->translate('Menu Item'); ?></h6>
										<dl class="content">
											<dd></dd>
										</dl>
										
									</li>
								</ul>


							</div>
							
						</li>
					</ul>

				</li>
			</ul>

		</form>
		<a class="close-reveal-modal">&#215;</a>
	</div>

	<div id="application-options" class="reveal-modal">
		hellp
		<a class="close-reveal-modal">&#215;</a>
	</div>



