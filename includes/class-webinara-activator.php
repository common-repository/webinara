<?php 
/**
*@package: WebinaraPlugin
*/

class Webinara_Activator {
	
	public static function webi_activate_action() {	 
		$current_user = wp_get_current_user();
		$all_pages = array(__('Webinars','webinara') => '[webinars]', __('Events','webinara') => '[events]');
		foreach($all_pages as $page_title => $page_cont){
			$new_page = array(
				'post_type' => 'page',
				'post_title' => $page_title,
				'post_content' => $page_cont,
				'post_status' => 'publish',
				'post_author' => $current_user->ID,
			);
			$new_page_id = wp_insert_post($new_page);
			if(!empty($new_page_id)){
				update_option('_webi_'.strtolower($page_title).'_page_id', $new_page_id);
			}
		}

		
		$labels = array(
			'name'               => __( 'Webinars','webinara' ),
			'singular_name'      => __( 'Webinar','webinara' ),
			'add_new'            => __( 'Add New Webinar','webinara' ),
			'add_new_item'       => __( 'Add New Webinar','webinara' ),
			'edit_item'          => __( 'Edit Webinar','webinara' ),
			'new_item'           => __( 'New Webinar','webinara' ),
			'all_items'          => __( 'All Webinars','webinara' ),
			'view_item'          => __( 'View Webinar','webinara' ),
			'search_items'       => __( 'Search Webinars','webinara' ),
			'featured_image'     => __('Event Image Banner','webinara'),
			'set_featured_image' => __('Add Event Image Banner','webinara')
		  );
		 
		  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
		  $args = array(
				'labels'            => $labels,
				'description'       => __('Holds our Webinars and webinar specific data', 'webinara'),
				'public'            => true,
				'menu_position'     => 5,
				'supports'          => array( 'title', 'editor', 'thumbnail'),
				'has_archive'       => true,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'has_archive'       => false,
				'menu_icon'			=> plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png',
				'rewrite'           => array( 'slug' => 'webinars' ),
				'show_in_menu'  	=> false,
			  );

			register_post_type( 'webinar', $args );
		  
		  $labels = array(
			'name'               => __( 'Events','webinara' ),
			'singular_name'      => __( 'Event','webinara' ),
			'add_new'            => __( 'Add New Event','webinara' ),
			'add_new_item'       => __( 'Add New Event','webinara' ),
			'edit_item'          => __( 'Edit Event','webinara' ),
			'new_item'           => __( 'New Event','webinara' ),
			'all_items'          => __( 'All Events','webinara' ),
			'view_item'          => __( 'View Event','webinara' ),
			'search_items'       => __( 'Search Events','webinara' ),
			'featured_image'     => __('Event Image Banner','webinara'),
			'set_featured_image' => __('Add Event Image Banner','webinara')
		  );
		 
		  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
		  $args = array(
			'labels'            => $labels,
			'description'       => __('Holds our Events and event specific data','webinara'),
			'public'            => true,
			'menu_position'     => 5,
			'supports'          => array( 'title', 'editor', 'thumbnail'),
			'has_archive'       => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'has_archive'       => false,
			'menu_icon'			=> plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png',
			'rewrite'           => array( 'slug' => 'events' ),
			'show_in_menu'  	=>	false,
		  );
	 
		register_post_type( 'event', $args );
		
		$app_post_type = array("event","webinar");
		$labels = array(
			"name" => __( 'Event Categories','webinara' ),
			"singular_name" => __( 'Event Category','webinara' ),
			);

			$args = array(
				'label' => __( 'Event Categories','webinara' ),
				'labels' => $labels,
				'public' => true,
				'hierarchical' => true,
				'label' => __('Event Categories','webinara'),
				'show_ui' => true,
				'query_var' => true,					
				'show_admin_column' => false,
				'show_in_rest' => false,
				'rest_base' => '',
				'show_in_quick_edit' => false,
				'show_in_menu' => true,	
					);
			register_taxonomy( 'event_categories', $app_post_type, $args );											
			
			$labels = array(
				'name' => __( 'Event Tags','webinara' ),
				'singular_name' => __( 'Tag','webinara' ),
				'search_items' =>  __( 'Search Tags','webinara' ),
				'popular_items' => __( 'Popular Tags','webinara' ),
				'all_items' => __( 'All Tags','webinara' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Tag','webinara' ), 
				'update_item' => __( 'Update Tag','webinara' ),
				'add_new_item' => __( 'Add New Tag','webinara' ),
				'new_item_name' => __( 'New Tag Name','webinara' ),
				'separate_items_with_commas' => __( 'Separate tags with commas','webinara' ),
				'add_or_remove_items' => __( 'Add or remove tags','webinara' ),
				'choose_from_most_used' => __( 'Choose from the most used tags','webinara' ),
				'menu_name' => __( 'Event Tags','webinara' ),					
			  ); 						

		  register_taxonomy('event_tag',$app_post_type,array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'event_tag' ),
			'show_in_menu' => true,
		  ));
		  
		 $event_category = '{"Accounting and Finance":0,"Arts and Culture":0,"Business Intelligence":0,"Communications and PR":0,"Education":0,"Entrepreneurship":0,"Environment":0,"Food and Drink":0,"Government, Politics and Policy":0,"Health and Fitness":0,"Healthcare":0,"Human Resources":0,"IT and Technology":0,"Jobs, Employment and Careers":0,"Leadership and Management":0,"Legal":0,"Marketing":0,"Music":0,"Non-Profit Organizations":0,"Personal Development":0,"Procurement and Supply Chain":0,"Production and Operations":0,"Project Management":0,"Real Estate":0,"Retail":0,"Sales":0,"Sports":0,"Travel, Tourism and Hospitality":0,"Utilities and Energy":0,".NET":"IT and Technology","2D Drafting":"IT and Technology","3D Modelling":"IT and Technology","3D Printing":"IT and Technology","3rd Party Logistics (3PLs)":"Procurement and Supply Chain","401(K)":"Accounting and Finance","Abundance and Prosperity Affirmations":"Personal Development","Account Management":"Sales","Account-Based Marketing (ABM)":"Marketing","Account-based Sales":"Sales","Accounting":"Accounting and Finance","Accounting Education":"Accounting and Finance","Accounting IT Systems":"Accounting and Finance","Accounting Systems":"Accounting and Finance","Acting":"Arts and Culture","Active Trading":"Accounting and Finance","Addictions":"Personal Development","Administrative Law and Public Policy":"Legal","Administrative Processes (Government Politics and Policy)":"Government, Politics and Policy","Adobe Software":"IT and Technology","Adult and Continuing Education":"Education","Advanced Threat Protection":"IT and Technology","Adventure Travel":"Travel, Tourism and Hospitality","Advertising":"Marketing","AEC Design":"IT and Technology","Afterlife Science":"Personal Development","Agencies":"Communications and PR","Agents and Agencies (Real Estate)":"Real Estate","Agile Marketing":"Marketing","Agile Project Management":"IT and Technology","Agricultural Education\u200e":"Food and Drink","Agricultural Health and Safety\u200e":"Food and Drink","Agricultural Microbiology":"Food and Drink","Agricultural Production\u200e":"Food and Drink","AI Technology":"Entrepreneurship","Air Pollution":"Environment","Airbnb":"Travel, Tourism and Hospitality","Airline Travel":"Travel, Tourism and Hospitality","Alternative Investments":"Accounting and Finance","Alternative Realty Finance":"Accounting and Finance","Alumni Programs":"Human Resources","American Campaigns and Elections":"Government, Politics and Policy","Analysis and Opinions":"Accounting and Finance","Analytics (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Android":"IT and Technology","Angel Capital":"Entrepreneurship","Angel Capital (Finance)":"Accounting and Finance","Anger Management":"Personal Development","Annual Reporting":"Accounting and Finance","App Development":"IT and Technology","App Marketing":"Marketing","App Strategy":"Entrepreneurship","Application Containers":"IT and Technology","Application Security":"IT and Technology","Appraisal and Valuation (Finance)":"Accounting and Finance","Appraisal and Valuation (Real Estate)":"Real Estate","Architecture":"Real Estate","Arctic Ecology":"Environment","Art and Travel":"Arts and Culture","Art Business":"Arts and Culture","Art Collecting":"Arts and Culture","Artificial Intelligence (AI)":"Business Intelligence","Artists (Art)":"Arts and Culture","Arts":"Arts and Culture","Asset Management":"Accounting and Finance","Assurance":"Accounting and Finance","Attractions":"Travel, Tourism and Hospitality","Audit":"Accounting and Finance","Augmented and Virtual Reality":"Marketing","Augmented Reality":"IT and Technology","AutoCAD":"IT and Technology","Automation (IT)":"IT and Technology","Automobile Buying and Financing":"Accounting and Finance","Aviation":"Travel, Tourism and Hospitality","Awards & Incentives":"Marketing","B2B Marketing":"Marketing","B2C Marketing":"Marketing","Backhaul":"IT and Technology","Backlogs":"IT and Technology","Bank Regulation":"Accounting and Finance","Banking":"Accounting and Finance","Banking Law":"Legal","Banking Services":"Accounting and Finance","Basel":"Accounting and Finance","Bed and Breakfast":"Travel, Tourism and Hospitality","Benefits, Compensation and Perks":"Human Resources","Beverages":"Food and Drink","Big Data Analytics":"Business Intelligence","Big Data for ITSM":"IT and Technology","Binary Options":"Accounting and Finance","Bioclimatology":"Environment","Biodiversity":"Environment","Bioinformatics":"IT and Technology","Blockchain":"Accounting and Finance","Blood":"Healthcare","Board of Directors":"Leadership and Management","Body Implants":"IT and Technology","Bonds and Fixed Income":"Accounting and Finance","Booking Agencies":"Music","Bookkeeping":"Accounting and Finance","Bootstrapping":"Entrepreneurship","Borrowing Money":"Entrepreneurship","Brand Ambassador":"Marketing","Branding":"Marketing","Broadcasting":"Marketing","Brokers":"Accounting and Finance","Budget Travel":"Travel, Tourism and Hospitality","Budgeting (Government Politics and Policy)":"Government, Politics and Policy","Building Company Culture":"Human Resources","Bulletin Board Systems":"IT and Technology","Business Idea":"Entrepreneurship","Business intelligence (BI)":"IT and Technology","Business Intelligence for Beginners":"Business Intelligence","Business Intelligence Insights":"IT and Technology","Business Plans":"Entrepreneurship","Business Transformation":"Leadership and Management","Business Travel":"Travel, Tourism and Hospitality","Cable (IT and Telco)":"IT and Technology","CAD and CAM":"IT and Technology","Camping":"Travel, Tourism and Hospitality","Cancer":"Healthcare","Candlestick Patterns":"Accounting and Finance","Car Rentals":"Travel, Tourism and Hospitality","Cardio":"Health and Fitness","Cardiovascular":"Healthcare","Career Management":"Human Resources","Career Planning":"Jobs, Employment and Careers","Career Planning (HR)":"Human Resources","Cash Flow":"Accounting and Finance","Category Management":"Procurement and Supply Chain","Cellular Communication":"IT and Technology","CFD":"Accounting and Finance","Change Management\u200e":"Leadership and Management","Change, Configuration and Release Management":"IT and Technology","Charitable Organizations":"Non-Profit Organizations","Charter Jets":"Travel, Tourism and Hospitality","Charts and Patterns":"Accounting and Finance","Chatbots":"IT and Technology","Childrens Health":"Healthcare","Chiropractic":"Healthcare","Citizenship":"Government, Politics and Policy","City Guides and Tours":"Travel, Tourism and Hospitality","Civil Procedure and Dispute Resolution":"Legal","Climate":"Environment","Clinical Trials":"Healthcare","Cloud Applications":"IT and Technology","Cloud Computing (public, private or hybrid)":"IT and Technology","Cloud Security":"IT and Technology","Coaching":"Personal Development","Cognitive Technologies":"IT and Technology","Cold Calling Techniques":"Sales","Collaboration (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Collaborative Project Management":"Project Management","Collaborative Projects":"Project Management","College and University Planning":"Education","College Financing":"Accounting and Finance","Colleges and Universities":"Education","Commercial Lending":"Accounting and Finance","Commercial Property":"Real Estate","Commercial Real Estate Investments (CRE)":"Accounting and Finance","Commodities":"Accounting and Finance","Communication":"Communications and PR","Communication Skills":"Communications and PR","Community Development and Management":"Marketing","Community Organizations (Non-Profit)":"Non-Profit Organizations","Compensation":"Human Resources","Compliance (Finance)":"Accounting and Finance","Compliance (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Computer Assisted Translation":"IT and Technology","Computer Science":"IT and Technology","Conceptual selling":"Sales","Concerts and Events":"Music","Conflict Resolution":"Government, Politics and Policy","Congenital Disorders":"Healthcare","Consciousness":"Personal Development","Constitutional Law":"Legal","Construction (Production and Operations)":"Production and Operations","Construction (Real Estate)":"Real Estate","Consumer Engagement":"Marketing","Consumer Finance":"Accounting and Finance","Containers (IT)":"IT and Technology","Content Management System (CMS)":"IT and Technology","Content Marketing":"Marketing","Continual Service Improvement":"IT and Technology","Continuous Deployment (IT)":"IT and Technology","Continuous Development (IT)":"IT and Technology","Continuous Integration (IT)":"IT and Technology","Contract Management (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Converged Infrastructures":"IT and Technology","Cooking":"Food and Drink","Cooking Education":"Food and Drink","Copyright (Music)":"Music","Copyright Law":"Legal","Copywriters":"Communications and PR","Core HRIS":"Human Resources","Corporate and Securities Law and Transactions":"Legal","Corporate Farming":"Food and Drink","Corporate Finance":"Accounting and Finance","Corporate Governance\u200e":"Leadership and Management","Corporate Identity Design":"Communications and PR","Corporate Social Entrepreneurship":"Entrepreneurship","Corporate Tax":"Accounting and Finance","Cosmetic Medical":"Healthcare","Creative Art":"Arts and Culture","Creative Entrepreneurship":"Entrepreneurship","Creative Thinking":"Entrepreneurship","Credit and Collection":"Accounting and Finance","Criminal Law and Procedure":"Legal","Critical Chain Project Management":"Project Management","Cross-Selling":"Sales","Crowdfunding":"Entrepreneurship","Cruise Ship Reviews":"Travel, Tourism and Hospitality","Culture Themes":"Arts and Culture","Customer Engagement (Marketing)":"Marketing","Customer Engagement (Sales)":"Sales","Customer Experience Management (CEM)":"Marketing","Customer Marketing":"Marketing","Customer Relationship Management (CRM)":"Sales","Customer Relationship Management (CRM) (IT)":"IT and Technology","Customer Success":"Sales","Customer Success (Sales)":"Sales","Customized Project Methodology":"Project Management","Data and Analytics in Finance":"Accounting and Finance","Data Center Efficiency and Sustainability":"IT and Technology","Data Center Management":"IT and Technology","Data Communications":"IT and Technology","Data Formats":"IT and Technology","Data Management (BI)":"Business Intelligence","Data Management (Energy)":"Utilities and Energy","Data Management (IT)":"IT and Technology","Data Management (Marketing)":"Marketing","Data Management\u200e (Project)":"Project Management","Data Modeling":"Business Intelligence","Data Preparation":"Business Intelligence","Data Science":"Business Intelligence","Data Storage, Retrevial and DMBS":"IT and Technology","Data Visualization":"Business Intelligence","Data-driven Security":"IT and Technology","Database":"IT and Technology","Decision Theory\u200e":"Leadership and Management","Deforestation":"Environment","Demand and Lead Generation":"Marketing","Demand Management (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Demergers and Spin-Off":"Accounting and Finance","Democracy and Governance":"Government, Politics and Policy","Dental Health":"Healthcare","Desktop Publishing":"IT and Technology","Destination Tips":"Travel, Tourism and Hospitality","Development, Management and Policy":"Government, Politics and Policy","DevOps":"IT and Technology","Digestion":"Healthcare","Digestive Disorders":"Healthcare","Digital Advertising":"Marketing","Digital Marketing":"Marketing","Digital Sales (Music)":"Music","Direct Marketing":"Marketing","Direct Sales (B2B)":"Sales","Direct Sales (B2C)":"Sales","Distance Learning":"Education","Distressed Assets":"Accounting and Finance","Distribution (Marketing)":"Marketing","Distribution (Music)":"Music","Domestic Policymaking":"Government, Politics and Policy","Drones":"IT and Technology","Ear":"Healthcare","Eco-Tourism":"Travel, Tourism and Hospitality","Eco, Green and Environment":"Utilities and Energy","Ecological Energetics":"Environment","Ecological Forecasting":"Environment","Ecology":"Environment","Economic and Financial Policy":"Government, Politics and Policy","Economic Indicators":"Accounting and Finance","Economics":"Accounting and Finance","Ecotechnology":"Environment","EdTech (Education)":"Education","EdTech (IT)":"IT and Technology","Education and Training":"Education","Educational Services":"Education","Educational Testing":"Education","Effective Teams":"Leadership and Management","Elderly Care":"Healthcare","Elections":"Government, Politics and Policy","Electricity":"Utilities and Energy","Electronics":"IT and Technology","Elementary Schools":"Education","Email Marketing":"Marketing","Email Marketing to Category":"Marketing","Employee Compensation and Benefits (Finance)":"Accounting and Finance","Employee Engagement and Training":"Human Resources","Employee Retention Strategies":"Human Resources","Empowerment":"Personal Development","Emulators":"IT and Technology","Encryption and Data Protection":"IT and Technology","Energy (IT)":"IT and Technology","Energy Analytics":"Utilities and Energy","Energy and Environment":"Utilities and Energy","Energy Cybersecurity":"Utilities and Energy","Energy Development":"Utilities and Energy","Energy Retail":"Utilities and Energy","Energy Services":"Utilities and Energy","Energy Storage":"Utilities and Energy","Energy Technology":"Utilities and Energy","Energy Use":"Utilities and Energy","Energy Waste":"Utilities and Energy","Engagement":"Human Resources","Engineering":"Production and Operations","Enterprise (IT and Telco)":"IT and Technology","Enterprise Architecture Frameworks":"IT and Technology","Enterprise Resource Planning":"Leadership and Management","Enterprise Resource Planning (ERP)":"IT and Technology","Entrepreneur Ecosystem":"Entrepreneurship","Entrepreneurial Economics":"Entrepreneurship","Entrepreneurial Leadership":"Leadership and Management","Entrepreneurship and Innovation":"Entrepreneurship","Entrepreneurship Education":"Entrepreneurship","Entrepreneurship Organizations\u200e":"Entrepreneurship","Environmental Analysis":"Environment","Environmental Certification":"Environment","Environmental Disasters":"Environment","Environmental Economics":"Environment","Environmental Health":"Environment","Environmental Law":"Legal","Environmental Planning":"Environment","Environmental Policy Employers":"Government, Politics and Policy","Environmental Regulations":"Government, Politics and Policy","Environmental Science":"Environment","Environmental Studies":"Environment","Environmental Technology":"Environment","eProcurement":"Procurement and Supply Chain","Equities - General":"Accounting and Finance","Equities - Large Caps":"Accounting and Finance","Equities - Penny Stocks":"Accounting and Finance","Equities - Small Caps":"Accounting and Finance","Equity Research":"Accounting and Finance","Escrow Services (Real Estate)":"Real Estate","Ethical Living":"Environment","European Central Bank (ECB)":"Accounting and Finance","Events":"Marketing","Executive Compensation":"Accounting and Finance","Exercise":"Health and Fitness","Eye":"Healthcare","Facebook Marketing":"Marketing","Factoring":"Accounting and Finance","Family Health":"Healthcare","Family Law":"Legal","Fashion Entrepreneur":"Entrepreneurship","Federal Reserve (FED)":"Accounting and Finance","Female Entrepreneurs":"Entrepreneurship","Fertilizers":"Food and Drink","Festivals":"Travel, Tourism and Hospitality","Fibonacci":"Accounting and Finance","Field Service Companies":"Accounting and Finance","Finance and Mortgage (Real Estate)":"Real Estate","Finance Education":"Accounting and Finance","Finance IT Systems":"Accounting and Finance","Finance Systems":"Accounting and Finance","Financial Accounting":"Accounting and Finance","Financial Advisor Software":"Accounting and Finance","Financial Advisors - Career Education":"Accounting and Finance","Financial Advisors - Products and Investments":"Accounting and Finance","Financial Advisors - Saving and Spending":"Accounting and Finance","Financial Advisors - Your Clients":"Accounting and Finance","Financial Consultants":"Accounting and Finance","Financial Freedom":"Personal Development","Financial Information Services":"Accounting and Finance","Financial News":"Accounting and Finance","Financial Planning":"Accounting and Finance","Financial Regulation":"Legal","Financial Services Contractors":"Accounting and Finance","FinTech":"Accounting and Finance","FinTech Technologies":"Accounting and Finance","First Time Cruising":"Travel, Tourism and Hospitality","Fitness":"Health and Fitness","Fitness Inspiration":"Health and Fitness","Fonts":"IT and Technology","Food and Health":"Healthcare","Food Lovers":"Food and Drink","Food Packaging":"Food and Drink","Food Processing":"Food and Drink","Food Retail":"Food and Drink","Food Security":"Food and Drink","Food Service":"Food and Drink","Food Storage":"Food and Drink","Food Technology":"Food and Drink","Food Trends":"Food and Drink","Foreign Service - Culture and Politics":"Government, Politics and Policy","Foreign Service - International Political Economy":"Government, Politics and Policy","Foreign Service - International Politics":"Government, Politics and Policy","Forex":"Accounting and Finance","Fund Investments":"Accounting and Finance","Fund Rankings":"Accounting and Finance","Fundamental Analysis":"Accounting and Finance","Future of Digital Banking":"Accounting and Finance","Futures":"Accounting and Finance","FX Trading":"Accounting and Finance","Game-based Learning (Education)":"Education","Game-based Learning (HR)":"Human Resources","Gamification (IT)":"IT and Technology","Gap Year Travel":"Travel, Tourism and Hospitality","Gender and Sexuality Legal Studies":"Legal","Generation":"Utilities and Energy","Generic Health Relevance":"Healthcare","Geographic Information System (GIS)":"IT and Technology","Geospatial":"IT and Technology","Global Entrepreneurship":"Entrepreneurship","Global Environment":"Government, Politics and Policy","Goal Setting (Personal Development)":"Personal Development","Golf Travel and Resorts":"Travel, Tourism and Hospitality","Governance":"Leadership and Management","Government":"Government, Politics and Policy","Government Bonds":"Accounting and Finance","Government, Politics, Policy Studies":"Government, Politics and Policy","Graphic Design":"Arts and Culture","Graphics Hardware":"IT and Technology","Green Development":"Environment","Grief Support":"Personal Development","Growth Hacking":"Marketing","Hackaton":"IT and Technology","Hacking":"IT and Technology","Hadoop, Spark and Big Data Tools":"Business Intelligence","Happiness":"Personal Development","Health and Beauty":"Health and Fitness","Health and Fitness Education":"Health and Fitness","Health Care and the Law":"Legal","Healthy Diets":"Health and Fitness","Healthy Food":"Food and Drink","Hedging":"Accounting and Finance","High Yield Bonds":"Accounting and Finance","Higher Education":"Education","Higher Education Trends":"Education","Hiking Backpacking":"Travel, Tourism and Hospitality","History and Heritage Travel":"Travel, Tourism and Hospitality","History and Philosophy of Law":"Legal","Home Automation":"IT and Technology","Home Brewing":"Food and Drink","Home Schooling":"Education","Home-Based Business":"Entrepreneurship","Hospitality Certifications and Degrees":"Travel, Tourism and Hospitality","Hosting":"IT and Technology","Hotel Accommodation":"Travel, Tourism and Hospitality","Housing Issues":"Real Estate","How to Study":"Education","HR Analytics and Technologies":"Human Resources","HR and Talent Analytics":"Human Resources","HR Tech":"Human Resources","HR Trends":"Human Resources","Human Rights":"Legal","Human Rights and Social Justice":"Government, Politics and Policy","Human Translation":"Communications and PR","Human-Computer Interaction":"IT and Technology","Hydrology":"Environment","Hypnosis":"Personal Development","Identity":"Marketing","IFDA":"Accounting and Finance","IFRS":"Accounting and Finance","IMS":"IT and Technology","In-Game Advertising":"Marketing","Incident Management":"IT and Technology","Incident Response":"IT and Technology","Incubators":"Entrepreneurship","Indices":"Accounting and Finance","Industrial Design":"Production and Operations","Infection":"Healthcare","Inflammatory and Immune System":"Healthcare","Information Technology":"IT and Technology","Infrastructure Investments":"Accounting and Finance","Initial Public Offering (IPO)":"Accounting and Finance","Injuries and Accidents":"Healthcare","Innovation (Entrepreneurship)":"Entrepreneurship","Innovation (Personal Development)":"Personal Development","Innovation Leadership":"Leadership and Management","Innovation Management":"Leadership and Management","Innovations in Payments":"Accounting and Finance","Inside Sales":"Sales","Inspiration (Personal Development)":"Personal Development","Institutional Entrepreneur":"Entrepreneurship","Instruments":"Music","Insurance":"Accounting and Finance","Insurance (Real Estate)":"Real Estate","Integrated Communications":"IT and Technology","Integration":"IT and Technology","Intellectual Property":"Legal","Intellectual Property and Technology":"Legal","Internal Entrepreneur":"Entrepreneurship","International Marketing":"Marketing","International Non-Profit Organizations\u200e":"Non-Profit Organizations","International Policymaking":"Government, Politics and Policy","International Student Recruitment":"Education","International Trade":"Government, Politics and Policy","International, Foreign, and Comparative Law":"Legal","Internet of Things (IoT) (BI)":"Business Intelligence","Internet of Things (IoT) (IT)":"IT and Technology","Internet of Things (IoT) (Marketing)":"Marketing","Internet of Things (IoT) Security":"IT and Technology","Intranet":"IT and Technology","Introduction to FinTech":"Accounting and Finance","Introduction to ITSM":"IT and Technology","Introduction to Marketing":"Marketing","Inventors":"Entrepreneurship","Inventory":"Procurement and Supply Chain","Investigation Services":"Accounting and Finance","Investing Basics":"Accounting and Finance","Investing for Beginners":"Accounting and Finance","Investment Banking":"Accounting and Finance","Investment Grade":"Accounting and Finance","Investment Portfolio":"Accounting and Finance","Investment Services":"Accounting and Finance","Investor Relations (Finance)":"Accounting and Finance","IT - Infrastructure and Operations":"IT and Technology","IT Analytics":"IT and Technology","IT and Tech Trends":"IT and Technology","IT Monitoring and Metrics":"IT and Technology","IT Security":"IT and Technology","IT Service Management":"IT and Technology","IT Support and Help Desk":"IT and Technology","ITIL Framework":"IT and Technology","Jobs, Employment and Careers webinars":"Jobs, Employment and Careers","KPIs":"Leadership and Management","Labor and Employment Law":"Legal","Labor Law":"Legal","Landing-Pages":"Marketing","Language Schools":"Education","Law and Economics":"Legal","Law, Humanities, and the Social Sciences":"Legal","Lead Qualification Strategies":"Sales","Leadership and Mentoring":"Human Resources","Leadership Psychology":"Leadership and Management","Lean - Six Sigma - JIT":"Production and Operations","Lean Project Management":"Project Management","Learning and Development":"Human Resources","Leasing Services":"Accounting and Finance","Legal Profession and Professional Responsibility":"Legal","Licensing (Music)":"Music","Limousine Rental":"Travel, Tourism and Hospitality","LinkedIn (Marketing)":"Marketing","LinkedIn (Sales)":"Sales","Linux":"IT and Technology","Live Music":"Arts and Culture","Loans":"Accounting and Finance","Location Intelligence":"IT and Technology","Location-based Marketing":"Marketing","Logistics":"Procurement and Supply Chain","Logistics (Food)":"Food and Drink","Loyalty Marketing":"Marketing","LTE":"IT and Technology","Luxury Cruising":"Travel, Tourism and Hospitality","Machine Learning (BI)":"Business Intelligence","Machine Learning (IT)":"IT and Technology","Machine Translation":"IT and Technology","Macromanagement":"Leadership and Management","Malware":"IT and Technology","Management Education\u200e":"Leadership and Management","Management Information Systems (MIS)":"IT and Technology","Management Systems (Project)":"Project Management","Management Training":"Leadership and Management","Management Training and Development":"Human Resources","Manufacturing":"Production and Operations","Mapping the Customer Journey":"Marketing","Maps":"IT and Technology","Market Intelligence (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Market Research":"Marketing","Market Segmentation":"Marketing","Marketing Analytics":"Marketing","Marketing Automation":"Marketing","Marketing Campaigns":"Marketing","Marketing Consultancy":"Marketing","Marketing Education":"Marketing","Marketing Ethics":"Marketing","Marketing Management":"Marketing","Marketing Operations":"Marketing","Marketing Software":"Marketing","Marketing Strategy":"Marketing","Marketing Trends":"Marketing","Massage and Acupuncture":"Healthcare","Media (Spiritual)":"Personal Development","Media Monitoring":"Communications and PR","Medical Billing":"Accounting and Finance","Medical Tourism":"Travel, Tourism and Hospitality","Medicine":"Healthcare","Meditation":"Health and Fitness","Mediumship":"Personal Development","Memory Technologies and Data Storage":"IT and Technology","Memory Training":"Personal Development","Mens Health":"Healthcare","Mental Health":"Healthcare","Merchant Services":"Accounting and Finance","Mergers and Acquisitions (M&A)":"Accounting and Finance","Messaging":"IT and Technology","Metabolic and Endocrine":"Healthcare","MICE (Meetings, Incentives, Conferences, Exhibitions)":"Travel, Tourism and Hospitality","Microecosystem":"Environment","Microfinance":"Accounting and Finance","Micromanagement":"Leadership and Management","Microsoft Dynamics GP":"IT and Technology","Middle Schools and High Schools":"Education","Mind Development":"Personal Development","Mining":"Utilities and Energy","Mixed Media Art":"Arts and Culture","Mobile":"IT and Technology","Mobile Computing":"IT and Technology","Mobile Data":"IT and Technology","Mobile Device Security":"IT and Technology","Mobile Marketing":"Marketing","Monetization Strategy":"Entrepreneurship","Money Laundering":"Accounting and Finance","Mortgages":"Accounting and Finance","Motivation (Personal Development)":"Personal Development","Moving and Relocation":"Real Estate","MS Office":"IT and Technology","Multi-Generational Travel":"Travel, Tourism and Hospitality","Multimedia":"IT and Technology","Musculoskeletal":"Healthcare","Museums":"Arts and Culture","Music Hardware":"Music","Music Production":"Music","Music Retail":"Music","Music Software":"Music","Music Tech":"Music","Music Videos":"Music","Mutual Funds and ETFs":"Accounting and Finance","Nanotechnology":"IT and Technology","NAS":"IT and Technology","National Security Law":"Legal","Negotiation (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Network Management":"IT and Technology","Network Security":"IT and Technology","Networks (IT and Telco)":"IT and Technology","Neuro-Linguistic Programming (NLP)":"Personal Development","Neurological":"Healthcare","New Accounting Regulations":"Accounting and Finance","New Product Development":"Marketing","New Technology":"IT and Technology","News":"Communications and PR","NFV":"IT and Technology","Non-Commercial Partnerships (Non-Profit)":"Non-Profit Organizations","Non-Performing Loans":"Accounting and Finance","Non-Profit Fundraising":"Non-Profit Organizations","Non-Profit Institutions":"Non-Profit Organizations","Non-Profit Management":"Non-Profit Organizations","Nuclear Power":"Utilities and Energy","Nursing":"Healthcare","Nutrition":"Health and Fitness","Offboarding":"Human Resources","Office Administration\u200e":"Leadership and Management","Office Space":"Real Estate","Officials":"Government, Politics and Policy","Offshore Services":"Accounting and Finance","Oil and Gas":"Utilities and Energy","Oil and Gas Professional Services":"Utilities and Energy","Onboarding":"Human Resources","Online Marketing":"Marketing","Online Media (Music)":"Music","Online Non-Profit Organizations\u200e":"Non-Profit Organizations","Online Retailers":"Retail","Open Source":"IT and Technology","Open Source Analytics":"Business Intelligence","Operating Systems":"IT and Technology","Optical Networks":"IT and Technology","Options and Futures":"Accounting and Finance","Oral and Gastrointestinal":"Healthcare","Organizational Ethics":"Leadership and Management","Organizational Project Management":"Project Management","Organizing (Personal Development)":"Personal Development","Out-of-Home Advertising":"Marketing","Outdoor Leisure":"Travel, Tourism and Hospitality","Outsourcing (Finance)":"Accounting and Finance","Outsourcing (IT)":"IT and Technology","Outsourcing\u200e (Leadership)":"Leadership and Management","PaaS":"IT and Technology","Packet-Optical":"IT and Technology","Painting":"Arts and Culture","Pandemic and Epidemic Diseases":"Healthcare","Parallel Computing":"IT and Technology","Paranormal and Supernatural Investigation (PSI)":"Personal Development","Payroll":"Human Resources","Payroll Services":"Accounting and Finance","Penetration Testing":"IT and Technology","Performance and Capacity":"IT and Technology","Performance Management":"Human Resources","Performance Reviews":"Human Resources","Performing Arts":"Arts and Culture","Performing Rights Organizations (Music)":"Music","Personal Creativity":"Personal Development","Personal Finance":"Accounting and Finance","Personal Finance - Budgeting":"Accounting and Finance","Personal Finance - Credit and Loans":"Accounting and Finance","Personal Finance - Entrepreneurship":"Accounting and Finance","Personal Finance - Home and Auto":"Accounting and Finance","Personal Finance - Insurance":"Accounting and Finance","Personal Finance - Life Stages":"Accounting and Finance","Personal Finance - Net Worth":"Accounting and Finance","Personal Finance - Retirement":"Accounting and Finance","Personal Finance - Savings":"Accounting and Finance","Personal Finance - Taxes":"Accounting and Finance","Personal Growth":"Personal Development","Personal Health (Personal Development)":"Personal Development","Personal Leadership and Success":"Personal Development","Personal Wealth":"Accounting and Finance","Personas":"Marketing","Pet Friendly Accommodation":"Travel, Tourism and Hospitality","Philantropology (Finance)":"Accounting and Finance","Philantropology (Non-Profit)":"Non-Profit Organizations","Philosophy of Education":"Education","Phishing":"IT and Technology","Photography":"Arts and Culture","Physiotherapy":"Healthcare","Pilates":"Health and Fitness","Pitching to Investor":"Entrepreneurship","Plant Maintenance":"Production and Operations","Plastic Surgery":"Healthcare","Podcasts":"Marketing","Policy Goals":"Government, Politics and Policy","Policy Management (IT and Telco)":"IT and Technology","Policy Processes":"Government, Politics and Policy","Political Economy":"Government, Politics and Policy","Political Entrepreneurship":"Entrepreneurship","Political Parties":"Government, Politics and Policy","Positive Attitude":"Personal Development","PR (Music)":"Music","Predictive Analytics":"Business Intelligence","Predictive Marketing":"Marketing","Preschools":"Education","Press Release Services":"Communications and PR","Pricing":"Marketing","Prince2":"Project Management","Print":"Marketing","Privacy vs. Security Concerns":"IT and Technology","Private Banking":"Accounting and Finance","Private Equity (Finance)":"Accounting and Finance","Private Equity Capital (Entrepreneurship)":"Entrepreneurship","Private Tutors":"Education","Process-Based Project Management":"Project Management","Procurement Solutions":"Procurement and Supply Chain","Product Demonstration":"Marketing","Product Development":"Entrepreneurship","Product Development (IT)":"Entrepreneurship","Product Lifecycle Management (PLM)":"IT and Technology","Product Marketing":"Marketing","Product Placement":"Marketing","Productivity Hacks":"Personal Development","Professional Development":"Human Resources","Program Development (Government Politics and Policy)":"Government, Politics and Policy","Program management":"Project Management","Programming":"IT and Technology","Project Controlling and Project Control Systems":"Project Management","Project Governance":"Project Management","Project Management 2.0":"Project Management","Project Management Institute\u200e (PMI)":"Project Management","Project Management Office (PMO)":"Project Management","Project Management Software":"Project Management","Project Portfolio Management (PPM)":"Project Management","Property Development":"Real Estate","Property Information":"Real Estate","Property Listings":"Real Estate","Property Management":"Real Estate","Property Valuations":"Real Estate","Property, Real Estate, and Trusts and Estates (Legal)":"Legal","Public and Non-profit Management":"Government, Politics and Policy","Public Management":"Government, Politics and Policy","Public Opinion":"Government, Politics and Policy","Public Policy":"Government, Politics and Policy","Public Relations":"Communications and PR","Public Relations (Marketing)":"Marketing","Publication":"Marketing","Publishing Companies (Music)":"Music","Purchasing":"Procurement and Supply Chain","Quality Assurance (QA) and Quality Control (QC)":"Production and Operations","Racial, Economic, and Social Justice":"Legal","Radio":"Music","Radio Plugging":"Music","Ransomware and Malware":"IT and Technology","Rating Agencies":"Accounting and Finance","Real Estate Inspection":"Real Estate","Real Estate Investments":"Real Estate","Real Estate Investments Debt":"Accounting and Finance","Real Estate Investments Equity":"Accounting and Finance","Real Estate Legal":"Real Estate","Real Estate Planning":"Real Estate","Real Estate Services":"Real Estate","Reassurance":"Accounting and Finance","Record Labels":"Music","Recovery (Health and Fitness)":"Health and Fitness","Recovery (Healthcare)":"Healthcare","Recruitment and Sourcing Techniques":"Human Resources","Relaxation":"Health and Fitness","Remixes":"Music","Renal and Urogenital":"Healthcare","Renewable Energy":"Utilities and Energy","Rental Agreements":"Real Estate","Reporting (Projects)":"Project Management","Reproductive Health and Childbirth":"Healthcare","Research and Development (R&D)":"Production and Operations","Residential Property":"Real Estate","Resource Economic":"Government, Politics and Policy","Resource Management (Government Politics and Policy)":"Government, Politics and Policy","Respiratory":"Healthcare","Restaurant Management":"Food and Drink","Retail Business":"Retail","Retail Consultancy":"Retail","Retail Coupons":"Retail","Retail Design":"Retail","Retail Display":"Retail","Retail Distribution":"Retail","Retail Industry":"Retail","Retail Jobs and Careers":"Retail","Retail Logistics":"Retail","Retail Magazines":"Retail","Retail Management":"Retail","Retail Marketing":"Retail","Retail Merchandising":"Retail","Retail News":"Retail","Retail Planning":"Retail","Retail Price Tactics":"Retail","Retail Printable":"Retail","Retail Sales":"Retail","Retail Services":"Retail","Retail Signs":"Retail","Retail Software":"Retail","Retail Solutions":"Retail","Retail Stores":"Retail","Retail Strategy":"Retail","Retail Systems":"Retail","Retail Technology":"Retail","Retail Training":"Retail","Retirement and Pension":"Accounting and Finance","Reverse Mergers":"Accounting and Finance","Rewards and Recognition":"Human Resources","Risk Communication (Government Politics and Policy)":"Government, Politics and Policy","Risk Management":"Accounting and Finance","Risk Management (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Risk-based Security":"IT and Technology","River Cruising":"Travel, Tourism and Hospitality","Roadmap":"IT and Technology","Robotics":"IT and Technology","Robotics and Automation":"Production and Operations","Royalties (Music)":"Music","SaaS":"IT and Technology","Safari Tours":"Travel, Tourism and Hospitality","Sailing":"Travel, Tourism and Hospitality","Sales Acceleration":"Sales","Sales and Business Development":"Sales","Sales and Marketing Alignment":"Marketing","Sales Coaching":"Sales","Sales Coaching and Training":"Sales","Sales Enablement":"Sales","Sales Forecasting":"Sales","Sales Leadership":"Sales","Sales Leadership vs. Management":"Sales","Sales Management":"Sales","Sales Methods":"Sales","Sales Metrics and Analytics":"Sales","Sales Negotiation":"Sales","Sales Outsourcing":"Sales","Sales Strategy":"Sales","Sales Tools and Technology":"Sales","Sales Training (Marketing)":"Marketing","Sales Training and Development":"Sales","Salesforce":"IT and Technology","Science, Technology, and Infrastructure Policy":"Government, Politics and Policy","Scrum":"Project Management","SDN":"IT and Technology","Search Engine Marketing (SEM)":"Marketing","Search Engine Optimization (SEO)":"Marketing","Security":"IT and Technology","Security (Government Politics and Policy)":"Government, Politics and Policy","Security Analytics and Threat Analysis":"IT and Technology","Security, authentication & fraud challenges":"Accounting and Finance","Seed Capital (Entrepreneurship)":"Entrepreneurship","Seed Capital (Finance)":"Accounting and Finance","Self Esteem and Interpersonal Attraction":"Personal Development","Self-Development":"Personal Development","Self-Help":"Personal Development","Semiconductors":"IT and Technology","Senior Health":"Healthcare","Service Catalog and Request Management":"IT and Technology","Service Desk Optimization":"IT and Technology","Service Provider (IT and Telco)":"IT and Technology","Sharing Economy - Travel":"Travel, Tourism and Hospitality","Singel Sign-On (SSO)":"IT and Technology","Ski Resorts":"Travel, Tourism and Hospitality","Skin":"Healthcare","Sleep":"Health and Fitness","Small Cells":"IT and Technology","Small-Business Finance":"Accounting and Finance","Small-Business Finance (Entrepreneurship)":"Entrepreneurship","Small-Scale Project Management":"Project Management","Smart Grid":"Utilities and Energy","Smart Home":"Utilities and Energy","Social and Political Thought":"Government, Politics and Policy","Social Economy (Non-Profit)":"Non-Profit Organizations","Social Entrepreneurship":"Entrepreneurship","Social Entrepreneurship (Non-Profit)":"Non-Profit Organizations","Social Marketing":"Marketing","Social Media":"Marketing","Social Media Marketing":"Marketing","Social Policy":"Government, Politics and Policy","Social Process":"Government, Politics and Policy","Social Responsibility":"Accounting and Finance","Social Responsible Investments (SRI)":"Accounting and Finance","Social Selling":"Sales","Social Trading":"Accounting and Finance","Software Development":"IT and Technology","Software Project Management\u200e":"Project Management","Software-defined Data Centers":"IT and Technology","Software-defined Networks":"IT and Technology","Software-defined Storage":"IT and Technology","Solutions Selling":"Sales","Solvency I and II":"Accounting and Finance","Song Camps":"Music","Songwriting":"Music","Sourcing (IT)":"IT and Technology","Sourcing and Recruiting":"Human Resources","Sovereign Wealth Funds":"Accounting and Finance","Special Education":"Education","Specialty Schools":"Education","Speech Technology":"IT and Technology","Speed Reading":"Personal Development","Spend Analysis (Procurement \/ Supply Chain)":"Procurement and Supply Chain","Spiritual Growth":"Personal Development","Sporting Events":"Travel, Tourism and Hospitality","Sports webinars":"Sports","Sprint":"IT and Technology","Startup Build and Grow":"Entrepreneurship","Startup Business":"Entrepreneurship","Startup Business Models":"Entrepreneurship","Startup Company Culture":"Entrepreneurship","Startup Financing (Entrepreneurship)":"Entrepreneurship","Startup Financing (Finance)":"Accounting and Finance","Startup Funding":"Entrepreneurship","Startup Strategy":"Entrepreneurship","Startup Technology":"Entrepreneurship","Staycations":"Travel, Tourism and Hospitality","Stockbrokers":"Accounting and Finance","Stocktrading":"Accounting and Finance","Storage Innovations":"IT and Technology","Strategic Leadership":"Leadership and Management","Strategic Management\u200e":"Leadership and Management","Strategic Planning":"Leadership and Management","Strategic Sourcing":"Procurement and Supply Chain","Strategy":"Leadership and Management","Streaming (Music)":"Music","Stress Management":"Personal Development","Stroke":"Healthcare","Student Loans":"Accounting and Finance","Study Abroad":"Education","Succession Management":"Human Resources","Supercomputing":"IT and Technology","Supplier Management":"Procurement and Supply Chain","Supply Chain Finance":"Procurement and Supply Chain","Surety Bonds":"Accounting and Finance","Sustainability":"Accounting and Finance","Sustainable Development":"Government, Politics and Policy","Swing Trading":"Accounting and Finance","System Administration":"IT and Technology","Talent Acquisition":"Human Resources","Talent Management":"Human Resources","Task-Oriented and Relationship-Oriented Leadership":"Leadership and Management","Tax Preparation":"Accounting and Finance","Taxation":"Legal","Taxes":"Accounting and Finance","Team Agility":"Leadership and Management","Team Productivity":"Leadership and Management","Technical Analysis":"Accounting and Finance","Technical Indicators":"Accounting and Finance","Techniques (Personal Development)":"Personal Development","Technology (IT and Telco)":"IT and Technology","Telecommunications":"IT and Technology","Telemarketing \/ Telesales":"Sales","Test Preparation":"Education","Theater":"Arts and Culture","Theory and Methods (Education)":"Education","Time and Attendance":"Human Resources","Time Management":"Personal Development","Timeshares":"Travel, Tourism and Hospitality","Top Retailers":"Retail","Top Universities and Colleges":"Education","Torts":"Legal","Trade Shows":"Sales","Trading Education":"Accounting and Finance","Trading Software":"Accounting and Finance","Trading Strategies":"Accounting and Finance","Trading Systems":"Accounting and Finance","Train\/Rail Travel":"Travel, Tourism and Hospitality","Transactional Leadership":"Leadership and Management","Transactional Selling":"Sales","Transformational Leadership":"Leadership and Management","Translation Services":"Communications and PR","Transmission and Distribution":"Utilities and Energy","Transnational Education":"Education","Transportation (IT)":"IT and Technology","Travel Planning":"Travel, Tourism and Hospitality","Tutoring Centers":"Education","Twitter Marketing":"Marketing","United Nations":"Government, Politics and Policy","United State Congress":"Government, Politics and Policy","Upselling":"Sales","Urban Studies and Planning":"Environment","US GAAP":"Accounting and Finance","US Municipal Bonds":"Accounting and Finance","Usenet":"IT and Technology","User Experience (UX)":"IT and Technology","User Interface (UI)":"IT and Technology","Utilities and Energy Compliance":"Utilities and Energy","Utilities and Energy HSE":"Utilities and Energy","Utilities and Environment":"Utilities and Energy","Vacation Homes":"Travel, Tourism and Hospitality","Vacation Rentals":"Travel, Tourism and Hospitality","Valuation":"Accounting and Finance","Vegan Food":"Food and Drink","Vegetarian Food":"Food and Drink","Venture Capital (Entrepreneurship)":"Entrepreneurship","Venture Capital (Finance)":"Accounting and Finance","Video Games and Gaming (IT)":"IT and Technology","Video Marketing":"Marketing","Video On-Demand":"IT and Technology","Vintage Art":"Arts and Culture","Virtual Project Management":"Project Management","Virtual Reality":"IT and Technology","Virtualization (server, desktop, etc.)":"IT and Technology","Visual Merchandising":"Marketing","VoLTE":"IT and Technology","Volunteer Services Organizations":"Non-Profit Organizations","Volunteerism Travel":"Travel, Tourism and Hospitality","Warehousing":"Procurement and Supply Chain","Waste Management":"Environment","Water":"Utilities and Energy","Water Cycle Management":"Environment","Water Pollution":"Environment","Wealth Management - Estate Planning":"Accounting and Finance","Wealth Management - Insurance":"Accounting and Finance","Wealth Management - Philanthropy":"Accounting and Finance","Wealth Management - Real Estate":"Accounting and Finance","Wealth Management - Tax Strategy":"Accounting and Finance","Weaponry (IT)":"IT and Technology","Wearable Computers":"IT and Technology","Web Design":"IT and Technology","Web Design (Marketing)":"Marketing","Web Technology":"IT and Technology","Webcasts":"Marketing","Webinars":"Marketing","Weight Loss":"Health and Fitness","Weight Management":"Healthcare","Wholesale":"Food and Drink","Wholesale and Distribution":"Food and Drink","Wifi":"IT and Technology","Windows":"IT and Technology","Wine and Spirits":"Food and Drink","Wine Lovers":"Food and Drink","Wine Travelling":"Food and Drink","Wireless":"IT and Technology","Women Entrepreneurs":"Entrepreneurship","Women in Music":"Music","Womens Health":"Healthcare","WordPress":"IT and Technology","Work Life Balance":"Personal Development","Workforce Management":"Human Resources","Workouts":"Health and Fitness","Workplace":"Human Resources","World Bank":"Accounting and Finance","World War II Dormant Accounts":"Accounting and Finance","Yoga":"Health and Fitness","Young Entrepreneur":"Entrepreneurship"}';
		 $event_categories = json_decode($event_category);
		 foreach($event_categories as $event_cat => $event_cat_parent)
		 {
			 if($event_cat_parent == "0")
			 {
				 wp_insert_term($event_cat,'event_categories');
			 }
			 else
			 {
				 wp_insert_term($event_cat,'event_categories',array(parent => term_exists($event_cat_parent)));
			 }				 
		 }
		 
		 $webinara_form_field = array(
			'general' => array(
				'subtitle' => array(
					'label' => __( 'Sub-title','webinara' ),
					'type' => 'text',
					'enable' => 1,
				),
				'featuredevent' => array(
					'label' => __( 'Featured Event','webinara' ),
					'type' => 'checkbox',
					'enable' => 1,
				),
				'alldayevent' => array(
					'label' => __( 'All day','webinara' ),
					'type' => 'checkbox',
					'enable' => 1,
				),
				'timezone' => array(
					'label' => __( 'Timezone','webinara' ),
					'type' => 'select',
					'enable' => 1,
				),
				'whyattend' => array(
					'label' => __( 'Why Attend','webinara' ),
					'type' => 'wp-editor',
					'enable' => 1,
				),
				'whoattened' => array(
					'label' => __( 'Who should attend','webinara' ),
					'type' => 'wp-editor',
					'enable' => 1,
				)	
			),
			'speaker' => array(
				'speaker_first_name' => array(
					'label' => __( 'First Name','webinara' ),
					'type' => 'text',
					'description' => __( 'First Name','webinara' ),
					'enable' => 1,
					'required' => 1	
				),
				'speaker_last_name' => array(
					'label' => __( 'Last Name','webinara' ),
					'type' => 'text',
					'description' => __( 'Last Name','webinara' ),
					'enable' => 1,
					'required' => 1
				),
				'speaker_company' => array(
					'label' => __( 'Company','webinara' ),
					'type' => 'text',
					'description' => __( 'Company','webinara' ),
					'enable' => 1,
					'required' => 1
				),
				'speaker_title' => array(
					'label' => __( 'Title','webinara' ),
					'type' => 'text',
					'description' => __( 'Title','webinara' ),
					'enable' => 1,
					'required' => 1	
				),
				'speaker_website' => array(
					'label' => __( 'Website','webinara' ),
					'type' => 'text',
					'description' => __( 'https://www.example.com','webinara' ),
					'enable' => 1,					
				),
				'speaker_twitter' => array(
					'label' => __( 'Twitter','webinara' ),
					'type' => 'text',
					'description' => __( '@twitter','webinara' ),
					'enable' => 1,					
				),
				'speaker_facebook' => array(
					'label' => __( 'Facebook','webinara' ),
					'type' => 'text',
					'description' => __( 'https://www.facebook.com/profile','webinara' ),
					'enable' => 1,				
				),
				'speaker_linkedin' => array(
					'label' => __( 'LinkedIn','webinara' ),
					'type' => 'text',
					'description' => __( 'https://www.linkedin.com/profile','webinara' ),
					'enable' => 1,					
				),
				'speaker_bio' => array(
					'label' => __( 'Speaker Bio','webinara' ),
					'type' => 'wp-editor',
					'description' => __( 'Speaker Bio','webinara' ),
					'enable' => 1,					
				),
				'speaker_image' => array(
					'label' => __( 'Speaker Image','webinara' ),
					'type' => 'image',
					'description' => __( 'Speaker Image','webinara' ),
					'enable' => 1,					
				)
			),
			'additional' => array(
				'attachments' => array(
					'enable' => 1,
					'type' => 'repeater',			
				),				
				'video' => array(
					'enable' => 1,
					'type' => 'text',			
				),
				'sponsor' => array(
					'label' => __( 'Webinar sponsor logo (Recommended size 100px(W) * 100px(H))','webinara' ),
					'enable' => 1,
					'type' => 'sponsor',
				)
			)
		);
		
		update_option('_webi_webinarform_fields', $webinara_form_field);
		update_option('_webi_eventform_fields', $webinara_form_field);
		update_option('_webi_events_per_page', 12);
		update_option('_webi_webinars_per_page', 12);		
		update_option('_webi_enable_webinars', 1);
		update_option('_webi_enable_events', 1);
	}		
}