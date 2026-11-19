<?php
/**
 * Copy that repeats across pages, kept out of the templates so it can be
 * edited without reading past markup.
 *
 * Reviews are quoted exactly as they appear on the live pages, each family
 * with its own set — the landing pages carry the shortened versions, the
 * homepage and /lpc/ the full ones.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_ROOT' ) || exit;

/** DHCS licence, shown in the microsite footer and on /about/. */
const DL_LICENSE = array(
	'number'  => '#191329BP',
	'expires' => '4/30/2027',
);

/** The residences. `map` opens the address in Google Maps. */
const DL_ADDRESSES = array(
	array( 'line1' => '553 & 553.5 N Mariposa Ave.', 'line2' => 'Los Angeles, CA 90004', 'map' => 'https://www.google.com/maps/search/?api=1&query=553+N+Mariposa+Ave+Los+Angeles+CA+90004' ),
	array( 'line1' => '555 & 555.5 N Mariposa Ave.', 'line2' => 'Los Angeles, CA 90004', 'map' => 'https://www.google.com/maps/search/?api=1&query=555+N+Mariposa+Ave+Los+Angeles+CA+90004' ),
);

/** The AI summary on the A/D landing pages. Bing drops the brand sentence. */
function dl_lp_summary( bool $bing ): string {
	$lead = 'Detox Locator’s partner programs consistently receive praise for their life-changing impact. ';
	$rest = 'Clients highlight the immediate support they receive during the first call, comprehensive medical care, and compassionate staff who go above and beyond. Many share stories of transformation—from breaking free of long-term addictions to discovering new purpose through therapy and peer support. Reviews frequently mention feeling safe, understood, and supported throughout the recovery journey. Alumni particularly value the personalized treatment approaches, quality of care, and strong foundations built for lasting sobriety.';
	return $bing ? $rest : $lead . $rest;
}

/** @return array<int,array{name:string,quote:string}> */
function dl_lp_reviews(): array {
	return array(
		array( 'name' => 'Dylon', 'quote' => 'They saved me from a difficult situation. I was down to literally nothing and finally decided to seek help one morning. After being turned down elsewhere, Darrin contacted me within 20 minutes. He informed me about the program and supported me in getting the help I needed. Staff immediately took me in kindly and helped me medically when I needed to see a doctor right away. My case manager Shane helped set everything up with ease. My time there was great - the fellow clients, staff, food, resources - everything was perfect.' ),
		array( 'name' => 'Patrick', 'quote' => 'My treatment experience was most definitely five-star. It was, in fact, life-changing. The recovery doctors, therapists, psychiatrists, group leaders, and technicians are soulfully skilled. Participating in their groups and one-on-one sessions always proved helpful and inward-seeking. I made lifelong friendships that I\'ll cherish forever. What was clear from day one was that they put YOU first and tailor your journey to your needs.' ),
		array( 'name' => 'Anonymous', 'quote' => 'This program has done wonders beyond I could ever imagine. After 8 years of Xanax addiction, I can now say I\'m free with the tools, knowledge, hope, and abilities to maintain my sobriety. I highly recommend this treatment for anyone trying to improve their lives, whether for addiction or general mental health. The staff are wonderful people.' ),
		array( 'name' => 'Joey', 'quote' => 'This place changed my life! The staff are fantastic - everyone there was very helpful. Their sober living program is fantastic as well. Highly recommended.' ),
		array( 'name' => 'Kailey', 'quote' => 'My experience was filled with understanding, love and compassion. The techs felt like family, always there to help navigate any issues. My case manager made sure I felt heard and handled everything professionally. I\'m so grateful for them saving my life.' ),
		array( 'name' => 'Justin', 'quote' => 'This is a beautiful place to start your path to a healthy, fulfilling new way to live. During my detox experience, I received instruction, counseling and guidance to finally surrender and begin healing. I remain an active member of the Alumni program. My sobriety date is 5/4/23.' ),
		array( 'name' => 'Jeff', 'quote' => 'I had a wonderful experience! Checked in for 30 days of treatment and ended up staying for 35 - I really didn\'t want to leave. The staff is amazing, they help take care of your needs and are very caring and compassionate. I felt safe and learned a lot about myself. The accommodations are excellent and the food was great. Thank you for taking good care of me. I\'m excited for my new journey in life.' ),
		array( 'name' => 'Antonio', 'quote' => 'Great place to reset and start your journey to recovery as long as you\'re willing to put in the work for yourself. You have to want to change, and most times in life you have to separate from your comfort zone in order to elevate. (SEPARATE TO ELEVATE)' ),
		array( 'name' => 'Jacob', 'quote' => 'This program has helped me greatly and made it easier to focus on my recovery. The staff here have genuine love and support towards the clients. If you\'re afraid to make that decision to come to treatment, don\'t be - they accommodate you and reassure you that your feelings are valid. It\'s a great place that has helped me start my life all over again. Give it a try and let your new improved life unfold.' ),
	);
}

/** The homepage's AI summary, from the live homepage. */
function dl_home_summary(): string {
	return 'Clients consistently describe Invigorate Behavioral Health as a life-changing environment where compassionate staff offer both clinical and emotional support. From the first call to ongoing care, multiple reviewers highlight tailored treatment plans, skilled medical teams, and heartfelt guidance. Many mention forming lifelong friendships, feeling safe, and learning about themselves in a nurturing setting. Reviewers also note that the staff go above and beyond to address unique needs—whether it’s coordinating immediate medical attention, advocating for personal issues, or helping clients overcome fears about entering treatment. Across every story, Invigorate emerges as a place that fosters genuine connection and renewed hope, encouraging personal growth and transformation on the path to lasting recovery.';
}

/**
 * Full-length reviews, as on the live homepage and /lpc/. Paragraph breaks
 * are kept as an array; `avatar` is the reviewer's Google photo where /lpc/
 * shows one.
 *
 * @return array<int,array{name:string,name_home?:string,avatar:string,paras:array<int,string>}>
 */
function dl_full_reviews(): array {
	return array(
		array( 'name' => 'Patrick', 'avatar' => 'patrick', 'paras' => array(
			'My time at Invigorate Behavioral Health was most definitely a five-star experience. It was, in fact, life-changing.',
			'The recovery doctors, therapists, psychiatrists, group leaders, and technicians are soulfully skilled. Participating in their groups and one-on-one sessions always proved helpful and inward-seeking.',
			'In addition, I made lifelong friendships that I’ll cherish forever. We took “field trips” to see some of the area’s monuments on several occasions, which were very enjoyable.',
			'What was clear from the moment I made my first call to the end of my 30 days of treatment was that Invigorate puts YOU first and tailors your journey to your needs.',
			'Make that call to Invigorate Behavioral Health. It may just save your life, too!',
		) ),
		array( 'name' => 'Dylon', 'avatar' => 'dylon', 'paras' => array(
			'Invigorate definitely saved me from a difficult situation. I was down to literally nothing and finally decided to seek help one morning. I contacted a facility near home but did not get accepted. No more than 20 minutes later Darrin had contacted me. He informed me about the program and was on my side when it came to getting the help I was looking for. Staff immediately took me in very kindly, also helped me medically as I needed to see a doctor right away. Upon getting back to the facility feeling much better I was fully admitted to the program and later met with Shane, my case manager. He helped me get set up with everyone I needed to talk to to get through everything with ease. My time spent there was great. The fellow clients, the staff, food the resources. Everything was set up up perfect. Highly recommend this program to any one in need. My time was well spent.',
		) ),
		// The live homepage credits this one to "Joey"; /lpc/ to "Stuart Lyon".
		// Each page keeps the attribution it has today.
		array( 'name' => 'Stuart Lyon', 'name_home' => 'Joey', 'avatar' => 'stuart', 'paras' => array(
			'This place changed my life! Don’t know where I would be without it. The staff are fantastic, especially garret make sure you go and talk to him. Everyone there was very helpful. Highly recommended. There sober living is fantastic as well.',
		) ),
		array( 'name' => 'Kailey', 'avatar' => 'kailey', 'paras' => array(
			'My experience at Invigorate was filled with understanding, love and compassion. The techs Savanna, Melissa, Jen and Victor felt like family. Always there to help me navigate any and all issues. Savanna took charge in helping me handle a situation that i wasn’t sure how to. Shane was also a huge advocate for me. He made sure I felt heard and knew he would make sure it was handled. Im so grateful to the invigorate for saving my life.',
		) ),
		array( 'name' => 'Justin', 'avatar' => 'justin', 'paras' => array(
			'Invigorate is a beautiful place to start your path to a healthy fulfilling new way to live. I was client of Shane’s during my own detox experience ( not my first) where I was given instruction, counseling and guidance to finally surrender and begin the process of healing. I remain an active member of the community as a part of the Alumni program. My name is Justin I’m a addict and alcoholic my sobriety date 5/4/23.',
		) ),
		array( 'name' => 'Jeff', 'avatar' => 'jeff', 'paras' => array(
			'I had a wonderful experience at Invigorate! Checked in for 30 days of treatment I ended up staying for 35 I really didn’t want to leave. The staff is amazing, they help take care of your needs and r very carrying and compassionate. I felt safe and learned a lot about myself. The houses r sweet and the food was great. Thank u for taking good care of me. I’m excited for my new journey in life.',
		) ),
		array( 'name' => 'Antonio', 'avatar' => 'antonio', 'paras' => array(
			'Great place to reset and start your journey to recovery as long as you’re willing to put in the work for yourself you have to want to change and most time in life you have to separate from your comfort zone in order to elevate.',
		) ),
		array( 'name' => 'Jacob', 'avatar' => 'jacob', 'paras' => array(
			'It has helped me greatly this facility has made it easier to focus on my recovery. The staff here have genuine love and support towards the clients. If you are feeling afraid to make that decision to come to treatment, don’t be they accommodate you. Reassure you that the way you are feeling are indeed valid. That everything will be alright. It’s a great place to be it has helped me start my life all over again. And I truly do feel better with being here. So give it a try and let your new improved life unfold.',
		) ),
	);
}

/**
 * Carrier tiles, as the live landing pages show them (logo on a tinted card).
 *
 * @return array<int,array{file:string,alt:string}>
 */
function dl_carrier_tiles(): array {
	return array(
		array( 'file' => 'cigna', 'alt' => 'Cigna' ),
		array( 'file' => 'harvard', 'alt' => 'Harvard Pilgrim Health Care' ),
		array( 'file' => 'geha', 'alt' => 'GEHA' ),
		array( 'file' => 'bluecross', 'alt' => 'Blue Cross Blue Shield' ),
		array( 'file' => 'anthem', 'alt' => 'Anthem Blue Cross Blue Shield' ),
		array( 'file' => 'aetna', 'alt' => 'Aetna' ),
		array( 'file' => 'horizon', 'alt' => 'Horizon Blue Cross Blue Shield' ),
		array( 'file' => 'highmark', 'alt' => 'Highmark' ),
		array( 'file' => 'umr', 'alt' => 'UMR' ),
		array( 'file' => 'medica', 'alt' => 'Medica' ),
		array( 'file' => 'providence', 'alt' => 'Providence' ),
	);
}

/**
 * Transparent carrier marks for the newer layouts. Sizes are the trimmed
 * artwork's, from tools/build_assets.py; `h` is the display height in px,
 * larger for stacked lockups so every mark reads at a similar size.
 *
 * Providence is left out here: its artwork says "Medicare Advantage Plans",
 * and these pages say no Medicare.
 *
 * @return array<int,array{file:string,alt:string,w:int,h:int,show:int}>
 */
function dl_carrier_marks(): array {
	return array(
		array( 'file' => 'anthem', 'alt' => 'Anthem Blue Cross Blue Shield', 'w' => 292, 'h' => 44, 'show' => 22 ),
		array( 'file' => 'bcbs', 'alt' => 'Blue Cross Blue Shield', 'w' => 321, 'h' => 63, 'show' => 28 ),
		array( 'file' => 'aetna', 'alt' => 'Aetna', 'w' => 209, 'h' => 42, 'show' => 22 ),
		array( 'file' => 'cigna', 'alt' => 'Cigna', 'w' => 112, 'h' => 64, 'show' => 32 ),
		array( 'file' => 'uhc', 'alt' => 'UnitedHealthcare', 'w' => 200, 'h' => 65, 'show' => 32 ),
		array( 'file' => 'highmark', 'alt' => 'Highmark', 'w' => 268, 'h' => 44, 'show' => 20 ),
		array( 'file' => 'horizon-bcbs', 'alt' => 'Horizon Blue Cross Blue Shield', 'w' => 192, 'h' => 43, 'show' => 22 ),
		array( 'file' => 'geha', 'alt' => 'GEHA', 'w' => 222, 'h' => 53, 'show' => 22 ),
		array( 'file' => 'umr', 'alt' => 'UMR', 'w' => 88, 'h' => 44, 'show' => 24 ),
		array( 'file' => 'harvard-pilgrim', 'alt' => 'Harvard Pilgrim Health Care', 'w' => 284, 'h' => 64, 'show' => 30 ),
	);
}

/** Suggestions for the insurance field. Free text is still accepted. */
function dl_insurance_options(): array {
	return array(
		'Aetna', 'Anthem', 'Blue Cross Blue Shield', 'Cigna', 'GEHA', 'Harvard Pilgrim',
		'Highmark', 'Horizon BCBS', 'Medica', 'Providence', 'UMR', 'UnitedHealthcare',
	);
}

/**
 * The team on /about/, from invigoratebh.com/our-team/ (names, credentials,
 * roles and headshots as published there; the one-liners are condensed from
 * their bios). No medical director is listed: the Invigorate and Pinnacle
 * pages name different people, so that waits until someone confirms which.
 *
 * `photo` is the file stem in assets/img/team/.
 *
 * @return array<int,array{name:string,creds:string,role:string,line:string,photo:string}>
 */
function dl_team(): array {
	return array(
		array( 'name' => 'Dr. Brandi Jordan', 'creds' => 'Ph.D., LMFT, LAADC', 'role' => 'Chief Operating Officer', 'photo' => 'brandi-jordan',
			'line' => 'Oversees every clinical and operational team, so care holds one standard from arrival to discharge. Sits on the board of CCAPP.' ),
		array( 'name' => 'Dr. Arlene Ghahremani', 'creds' => 'Ph.D., LMFT', 'role' => 'Clinical Director', 'photo' => 'arlene-ghahremani',
			'line' => '“Dr G.” Years in dual-diagnosis programs, and research into relationships and PTSD.' ),
		array( 'name' => 'Darrin Thibault', 'creds' => 'CADC-I', 'role' => 'Program Director', 'photo' => 'darrin-thibault',
			'line' => 'Usually the first person you meet at the residence. Found recovery himself after twenty years of addiction.' ),
		array( 'name' => 'Arin Larson', 'creds' => 'RADT', 'role' => 'Program Director', 'photo' => 'arin-larson',
			'line' => 'Grew up in Los Angeles, graduated from UCLA, and has worked in treatment since 2018.' ),
		array( 'name' => 'Jason Phillips', 'creds' => 'LMFT', 'role' => 'Therapist', 'photo' => 'jason-phillips',
			'line' => 'Individual and group therapy. Certified in trauma-focused CBT, with mindfulness-based relapse prevention.' ),
		array( 'name' => 'Luz Marquez', 'creds' => 'AMFT', 'role' => 'Therapist', 'photo' => 'luz-marquez',
			'line' => 'Anxiety, depression, trauma and addiction, using CBT, DBT and motivational interviewing.' ),
		array( 'name' => 'Marcus Davis', 'creds' => 'RADT', 'role' => 'Case Manager', 'photo' => 'marcus-davis',
			'line' => 'Fifteen years in addiction, now sober, and guiding others along the path he walked.' ),
		array( 'name' => 'Connie Johnson', 'creds' => '', 'role' => 'Director of Business Development & Alumni', 'photo' => 'connie-johnson',
			'line' => 'Sixteen years in behavioral health, with a focus on aftercare and keeping alumni connected.' ),
	);
}

/** The admissions counselors: the people who answer the phone. */
function dl_admissions_team(): array {
	return array(
		array( 'name' => 'Seth Holtz', 'photo' => 'seth-holtz' ),
		array( 'name' => 'Ben Yurchak', 'photo' => 'ben-yurchak' ),
		array( 'name' => 'Brian Bosley', 'photo' => 'brian-bosley' ),
	);
}
