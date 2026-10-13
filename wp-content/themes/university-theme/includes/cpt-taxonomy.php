<?php
/**
 * Task 2: Content Structure - CPT 'program' and taxonomy 'program-type'
 * Resides 100% inside the theme per the "no custom plugins" requirement.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPT 'program' and Hierarchical Taxonomy 'program-type'
 */
function university_register_cpt_and_taxonomy() {
	// 1. CPT 'program' (label: Programs, rewrite slug: programmes)
	$labels = array(
		'name'                  => _x( 'Programs', 'Post type general name', 'university-theme' ),
		'singular_name'         => _x( 'Program', 'Post type singular name', 'university-theme' ),
		'menu_name'             => _x( 'Programs', 'Admin Menu text', 'university-theme' ),
		'name_admin_bar'        => _x( 'Program', 'Add New on Toolbar', 'university-theme' ),
		'add_new'               => __( 'Add New', 'university-theme' ),
		'add_new_item'          => __( 'Add New Program', 'university-theme' ),
		'new_item'              => __( 'New Program', 'university-theme' ),
		'edit_item'             => __( 'Edit Program', 'university-theme' ),
		'view_item'             => __( 'View Program', 'university-theme' ),
		'all_items'             => __( 'All Programs', 'university-theme' ),
		'search_items'          => __( 'Search Programs', 'university-theme' ),
		'parent_item_colon'     => __( 'Parent Programs:', 'university-theme' ),
		'not_found'             => __( 'No programs found.', 'university-theme' ),
		'not_found_in_trash'    => __( 'No programs found in Trash.', 'university-theme' ),
		'featured_image'        => _x( 'Program Featured Image', 'Overrides the "Featured Image" phrase', 'university-theme' ),
		'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'university-theme' ),
		'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'university-theme' ),
		'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'university-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'programmes', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'programmes',
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'program', $args );

	// 2. Hierarchical Taxonomy 'program-type'
	$tax_labels = array(
		'name'              => _x( 'Program Types', 'taxonomy general name', 'university-theme' ),
		'singular_name'     => _x( 'Program Type', 'taxonomy singular name', 'university-theme' ),
		'search_items'      => __( 'Search Program Types', 'university-theme' ),
		'all_items'         => __( 'All Program Types', 'university-theme' ),
		'parent_item'       => __( 'Parent Program Type', 'university-theme' ),
		'parent_item_colon' => __( 'Parent Program Type:', 'university-theme' ),
		'edit_item'         => __( 'Edit Program Type', 'university-theme' ),
		'update_item'       => __( 'Update Program Type', 'university-theme' ),
		'add_new_item'      => __( 'Add New Program Type', 'university-theme' ),
		'new_item_name'     => __( 'New Program Type Name', 'university-theme' ),
		'menu_name'         => __( 'Program Types', 'university-theme' ),
	);

	$tax_args = array(
		'hierarchical'      => true,
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'program-type', 'with_front' => false ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'program-type', array( 'program' ), $tax_args );
}
add_action( 'init', 'university_register_cpt_and_taxonomy', 0 );

/**
 * 3. Ensure Undergraduate & Postgraduate Terms and 10 Sample Programs
 */
function university_seed_sample_programs() {
	if ( get_option( 'university_programs_seeded' ) ) {
		return;
	}

	// Create terms: Undergraduate and Postgraduate
	$terms = array(
		'Undergraduate' => 'undergraduate',
		'Postgraduate'  => 'postgraduate',
	);

	$term_map = array();
	foreach ( $terms as $name => $slug ) {
		$term = get_term_by( 'slug', $slug, 'program-type' );
		if ( ! $term ) {
			$inserted = wp_insert_term( $name, 'program-type', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $inserted ) ) {
				$term_map[ $slug ] = (int) $inserted['term_id'];
			}
		} else {
			$term_map[ $slug ] = (int) $term->term_id;
		}
	}

	// 10 Sample programs (5 Undergraduate, 5 Postgraduate)
	$sample_programs = array(
		// 5 Undergraduate
		array(
			'title'       => 'Bachelor of Computer Applications (BCA)',
			'slug'        => 'bachelor-of-computer-applications-bca',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'excerpt'     => 'Learn programming, software development, database architecture, and modern cloud technologies through practical, industry-focused education.',
			'content'     => 'The Bachelor of Computer Applications (BCA) is a cutting-edge undergraduate curriculum tailored to produce top-tier software engineers, systems developers, and technology innovators. Students gain direct exposure to real-world software architecture, cloud platforms, and full-stack engineering paradigms.',
			'sections'    => array(
				array( 'title' => 'Curriculum Overview', 'description' => 'Comprehensive core modules covering Full-Stack Web Development, Data Structures & Algorithms, Object-Oriented Software Design, and Distributed Cloud Computing.' ),
				array( 'title' => 'Industry Placement & Internships', 'description' => 'Guaranteed internship semester with leading global tech companies in Mumbai and worldwide, providing direct production codebase experience.' ),
				array( 'title' => 'State-of-the-Art Labs', 'description' => 'Dedicated advanced computer laboratories equipped with high-performance computing clusters and collaborative agile project spaces.' ),
			),
		),
		array(
			'title'       => 'Bachelor of Engineering (B.E.) in Computer Engineering',
			'slug'        => 'bachelor-of-engineering-be',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '4 Years',
			'excerpt'     => 'Gain technical knowledge and practical engineering skills to solve real-world problems with advanced robotics, embedded systems, and computing systems.',
			'content'     => 'The Bachelor of Engineering (B.E.) in Computer Engineering bridges hardware design and advanced software systems. This intensive 4-year degree prepares engineers for high-reliability computing, IoT architecture, and automated intelligence.',
			'sections'    => array(
				array( 'title' => 'Hardware & Systems Engineering', 'description' => 'Deep exploration of computer architecture, microprocessor design, real-time operating systems, and VLSI circuit fundamentals.' ),
				array( 'title' => 'Accreditation & Standards', 'description' => 'Globally recognized engineering credentials aligned with British engineering benchmarks and Indian technical standards.' ),
			),
		),
		array(
			'title'       => 'B.Sc. (Hons) in Artificial Intelligence & Data Science',
			'slug'        => 'bsc-artificial-intelligence-data-science',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'excerpt'     => 'Master modern machine learning algorithms, deep neural architectures, and big data pipelines guided by world-class academic researchers.',
			'content'     => 'Designed at the frontier of computational intelligence, this program trains students in foundational statistics, neural networks, natural language processing, and ethical AI deployment.',
			'sections'    => array(
				array( 'title' => 'Core Intelligence Modules', 'description' => 'Supervised and Unsupervised Learning, Computer Vision, Transformers, Generative AI models, and High-Performance ML Pipelines.' ),
				array( 'title' => 'Capstone Research Project', 'description' => 'Hands-on applied research project tackling real-world healthcare, fintech, or climate modeling challenges under faculty supervision.' ),
			),
		),
		array(
			'title'       => 'Bachelor of Business Administration (BBA) - Global Business',
			'slug'        => 'bachelor-of-business-administration-bba',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'excerpt'     => 'Develop strategic leadership, international finance, and entrepreneurship capabilities designed for tomorrow\'s corporate executives.',
			'content'     => 'A forward-thinking management degree cultivating strategic mindset, cross-border corporate strategy, business analytics, and venture innovation.',
			'sections'    => array(
				array( 'title' => 'Global Exchange Programme', 'description' => 'Opportunity to spend one study semester at the University of Aberdeen main campus in Scotland.' ),
				array( 'title' => 'Leadership Incubator', 'description' => 'Executive business mentorship and startup incubation with prominent alumni and venture investors in Mumbai.' ),
			),
		),
		array(
			'title'       => 'B.Sc. (Hons) in Cyber Security & Digital Forensics',
			'slug'        => 'bsc-cyber-security-digital-forensics',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'excerpt'     => 'Protect mission-critical networks, defend infrastructure against cyber vulnerabilities, and master modern incident response analysis.',
			'content'     => 'An elite cybersecurity program focused on defensive operations, offensive testing, threat hunting, cryptography, and digital evidence analysis.',
			'sections'    => array(
				array( 'title' => 'Live Cyber Range Scenarios', 'description' => 'Hands-on Red-Team vs Blue-Team simulation exercises in air-gapped forensic laboratories.' ),
				array( 'title' => 'Security Certification Track', 'description' => 'Aligned with industry-standard CISSP and CEH foundational knowledge certifications.' ),
			),
		),

		// 5 Postgraduate
		array(
			'title'       => 'Master of Computer Applications (MCA)',
			'slug'        => 'master-of-computer-applications-mca',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'excerpt'     => 'Build advanced skills in software architecture, distributed systems, container orchestration, and emerging enterprise engineering paradigms.',
			'content'     => 'The MCA provides specialized graduate-level engineering training in enterprise architecture, microservices, cloud native frameworks, and high-throughput databases.',
			'sections'    => array(
				array( 'title' => 'Enterprise Architecture', 'description' => 'Microservice architectures, event-driven streaming with Kafka, Kubernetes orchestration, and continuous deployment.' ),
				array( 'title' => 'Industry Dissertation', 'description' => 'Semester-long capstone engineering project solving an active enterprise software challenge.' ),
			),
		),
		array(
			'title'       => 'Master of Engineering (M.E.) in Software Systems',
			'slug'        => 'master-of-engineering-me',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'excerpt'     => 'Advance your technical expertise through specialized research, high-reliability engineering, and industrial collaboration.',
			'content'     => 'A rigorous research-oriented master\'s program centered on formal methods, reliable distributed computing, and safety-critical software design.',
			'sections'    => array(
				array( 'title' => 'Research Specialization', 'description' => 'Advanced algorithmic analysis, fault-tolerant consensus systems, and formal verification.' ),
			),
		),
		array(
			'title'       => 'M.Sc. in Data Science & Business Analytics',
			'slug'        => 'msc-data-science-business-analytics',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'excerpt'     => 'Harness big data and quantitative modeling to extract high-value insights, drive algorithmic business decisions, and innovate.',
			'content'     => 'Merging quantitative analytics with strategic managerial decision-making, this program empowers graduates to lead data-driven corporate transformations.',
			'sections'    => array(
				array( 'title' => 'Predictive Econometrics & Big Data', 'description' => 'Distributed data processing using PySpark, enterprise cloud lakes, and probabilistic forecasting models.' ),
			),
		),
		array(
			'title'       => 'MBA - International Business & Digital Transformation',
			'slug'        => 'mba-international-business',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'excerpt'     => 'Executive management education providing cross-cultural leadership skills, financial acumen, and tech-driven corporate strategy.',
			'content'     => 'Tailored for visionary business leaders, this MBA integrates financial acumen, digital transformation strategy, and ethical corporate governance.',
			'sections'    => array(
				array( 'title' => 'Executive Mentorship Network', 'description' => 'Direct strategic coaching from Scottish and multinational C-suite executive leaders.' ),
			),
		),
		array(
			'title'       => 'M.Sc. in Cloud Computing & DevOps Architecture',
			'slug'        => 'msc-cloud-computing-devops',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'excerpt'     => 'Design scalable serverless architectures, multicloud infrastructures, and automated Continuous Integration / Continuous Deployment pipelines.',
			'content'     => 'Focused on enterprise-scale cloud operations, Site Reliability Engineering (SRE), Infrastructure as Code (Terraform), and secure DevSecOps workflows.',
			'sections'    => array(
				array( 'title' => 'Cloud Certification Alignment', 'description' => 'Curriculum mapped to AWS Certified Solutions Architect Professional and Azure DevOps Engineer Expert credentials.' ),
			),
		),
	);

	foreach ( $sample_programs as $p ) {
		$existing = get_page_by_path( $p['slug'], OBJECT, 'program' );
		if ( ! $existing ) {
			$post_id = wp_insert_post( array(
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_excerpt' => $p['excerpt'],
				'post_content' => $p['content'],
				'post_status'  => 'publish',
				'post_type'    => 'program',
			) );

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, 'academic_year', $p['year'] );
				update_post_meta( $post_id, 'duration', $p['duration'] );
				update_post_meta( $post_id, 'content_sections', $p['sections'] );

				if ( isset( $term_map[ $p['type'] ] ) ) {
					wp_set_object_terms( $post_id, array( (int) $term_map[ $p['type'] ] ), 'program-type' );
				}
			}
		} else {
			// Update existing posts to ensure meta is populated
			update_post_meta( $existing->ID, 'academic_year', $p['year'] );
			update_post_meta( $existing->ID, 'duration', $p['duration'] );
			update_post_meta( $existing->ID, 'content_sections', $p['sections'] );
			if ( isset( $term_map[ $p['type'] ] ) ) {
				wp_set_object_terms( $existing->ID, array( (int) $term_map[ $p['type'] ] ), 'program-type' );
			}
		}
	}

	update_option( 'university_programs_seeded', 1 );
}
add_action( 'after_switch_theme', 'university_seed_sample_programs' );
add_action( 'admin_init', 'university_seed_sample_programs' );
