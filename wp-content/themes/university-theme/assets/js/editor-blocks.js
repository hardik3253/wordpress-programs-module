/**
 * Custom Gutenberg Blocks for Aberdeen Theme
 * Pure JavaScript using WordPress core libraries (wp.blocks, wp.element, wp.blockEditor, wp.components)
 */

(function (wp) {
	'use strict';

	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var InspectorControls = wp.blockEditor.InspectorControls || wp.editor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Button = wp.components.Button;
	var MediaUpload = wp.blockEditor.MediaUpload || wp.editor.MediaUpload;

	var defaultImages = (window.AberdeenThemeData && window.AberdeenThemeData.defaultImages) || {};
	var defaultUrls = (window.AberdeenThemeData && window.AberdeenThemeData.urls) || {};

	/* ==========================================================================
	   1. Hero Slider Block
	   ========================================================================== */
	registerBlockType('aberdeen/hero-slider', {
		title: 'Aberdeen Hero Slider',
		icon: 'slides',
		category: 'aberdeen-blocks',
		description: 'Full-width hero slider matching the University of Aberdeen Mumbai design.',
		attributes: {
			headline: {
				type: 'string',
				default: 'Join the University of Aberdeen in India'
			},
			subtext: {
				type: 'string',
				default: 'Global challenges are waiting for new ways of thinking. Discover your true north and start creating a more inclusive and sustainable world'
			},
			linkUrl: {
				type: 'string',
				default: defaultUrls.programmes || ''
			},
			imageUrl: {
				type: 'string',
				default: defaultImages.heroSkyline || ''
			}
		},

		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var currentImg = attributes.imageUrl || defaultImages.heroSkyline || '';

			return el(Fragment, {},
				el(InspectorControls, {},
					el(PanelBody, { title: 'Slider Settings', initialOpen: true },
						el(TextControl, {
							label: 'Headline',
							value: attributes.headline,
							onChange: function (val) { setAttributes({ headline: val }); }
						}),
						el(TextareaControl, {
							label: 'Subtitle / Description',
							value: attributes.subtext,
							onChange: function (val) { setAttributes({ subtext: val }); }
						}),
						el(TextControl, {
							label: 'Link URL (Arrow button)',
							value: attributes.linkUrl,
							onChange: function (val) { setAttributes({ linkUrl: val }); }
						}),
						el(MediaUpload, {
							onSelect: function (media) { setAttributes({ imageUrl: media.url }); },
							type: 'image',
							render: function (obj) {
								return el(Button, {
									isSecondary: true,
									onClick: obj.open,
									style: { marginTop: '10px' }
								}, 'Change Skyline Background Image');
							}
						})
					)
				),
				el('div', { className: 'aberdeen-editor-block aberdeen-hero-editor-preview', style: { backgroundImage: 'url(' + currentImg + ')' } },
					el('div', { className: 'hero-editor-bottom-bar' },
						el('div', { className: 'hero-editor-content' },
							el('h1', { className: 'hero-editor-headline' }, attributes.headline),
							el('p', { className: 'hero-editor-subtext' }, attributes.subtext + ' ➔'),
							el('div', { className: 'hero-editor-dots' },
								el('span', { className: 'dot active' }),
								el('span', { className: 'dot active' }),
								el('span', { className: 'dot hollow' })
							)
						)
					)
				)
			);
		},

		save: function () {
			return null; // Rendered dynamically on server side
		}
	});

	/* ==========================================================================
	   2. Careers and Employability Block
	   ========================================================================== */
	registerBlockType('aberdeen/careers-section', {
		title: 'Aberdeen Careers and Employability',
		icon: 'businessperson',
		category: 'aberdeen-blocks',
		description: 'Careers and Employability two-column showcase matching the Figma design.',
		attributes: {
			title: {
				type: 'string',
				default: 'Careers and Employability'
			},
			imageUrl: {
				type: 'string',
				default: defaultImages.careersStudents || ''
			},
			paragraph1: {
				type: 'string',
				default: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
			},
			paragraph2: {
				type: 'string',
				default: 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
			},
			buttonText: {
				type: 'string',
				default: 'See More'
			},
			buttonUrl: {
				type: 'string',
				default: defaultUrls.programmes || ''
			}
		},

		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var currentImg = attributes.imageUrl || defaultImages.careersStudents || '';

			return el(Fragment, {},
				el(InspectorControls, {},
					el(PanelBody, { title: 'Careers Settings', initialOpen: true },
						el(TextControl, {
							label: 'Section Heading',
							value: attributes.title,
							onChange: function (val) { setAttributes({ title: val }); }
						}),
						el(TextControl, {
							label: 'Button Text',
							value: attributes.buttonText,
							onChange: function (val) { setAttributes({ buttonText: val }); }
						}),
						el(TextControl, {
							label: 'Button URL',
							value: attributes.buttonUrl,
							onChange: function (val) { setAttributes({ buttonUrl: val }); }
						}),
						el(MediaUpload, {
							onSelect: function (media) { setAttributes({ imageUrl: media.url }); },
							type: 'image',
							render: function (obj) {
								return el(Button, {
									isSecondary: true,
									onClick: obj.open,
									style: { marginTop: '10px' }
								}, 'Change Section Image');
							}
						})
					)
				),
				el('div', { className: 'aberdeen-editor-block aberdeen-careers-editor-preview' },
					el('div', { className: 'careers-editor-header' },
						el('h2', { className: 'careers-editor-title' }, attributes.title),
						el('div', { className: 'gold-accent-line' })
					),
					el('div', { className: 'careers-editor-grid' },
						el('div', { className: 'careers-editor-media' },
							el('img', { src: currentImg, alt: 'Students' })
						),
						el('div', { className: 'careers-editor-content' },
							el(TextareaControl, {
								label: 'Paragraph 1',
								value: attributes.paragraph1,
								onChange: function (val) { setAttributes({ paragraph1: val }); }
							}),
							el(TextareaControl, {
								label: 'Paragraph 2',
								value: attributes.paragraph2,
								onChange: function (val) { setAttributes({ paragraph2: val }); }
							}),
							el('div', { className: 'editor-btn-gold' }, attributes.buttonText)
						)
					)
				)
			);
		},

		save: function () {
			return null;
		}
	});

	/* ==========================================================================
	   3. Our Programmes Block
	   ========================================================================== */
	registerBlockType('aberdeen/our-programmes', {
		title: 'Aberdeen Our Programmes',
		icon: 'welcome-learn-more',
		category: 'aberdeen-blocks',
		description: 'Two-column programmes category cards matching the Figma design.',
		attributes: {
			title: {
				type: 'string',
				default: 'Our Programmes'
			},
			card1Title: {
				type: 'string',
				default: 'Undergraduate Programmes'
			},
			card1Desc: {
				type: 'string',
				default: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.'
			},
			card1Image: {
				type: 'string',
				default: defaultImages.aiUndergrad || ''
			},
			card1Link: {
				type: 'string',
				default: defaultUrls.undergraduate || ''
			},
			card2Title: {
				type: 'string',
				default: 'Postgraduate Programmes'
			},
			card2Desc: {
				type: 'string',
				default: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.'
			},
			card2Image: {
				type: 'string',
				default: defaultImages.lecturePostgrad || ''
			},
			card2Link: {
				type: 'string',
				default: defaultUrls.postgraduate || ''
			}
		},

		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var c1Img = attributes.card1Image || defaultImages.aiUndergrad || '';
			var c2Img = attributes.card2Image || defaultImages.lecturePostgrad || '';

			return el(Fragment, {},
				el(InspectorControls, {},
					el(PanelBody, { title: 'Programmes Settings', initialOpen: true },
						el(TextControl, {
							label: 'Section Heading',
							value: attributes.title,
							onChange: function (val) { setAttributes({ title: val }); }
						}),
						el(PanelBody, { title: 'Card 1: Undergraduate', initialOpen: false },
							el(TextControl, {
								label: 'Card 1 Title',
								value: attributes.card1Title,
								onChange: function (val) { setAttributes({ card1Title: val }); }
							}),
							el(TextareaControl, {
								label: 'Card 1 Description',
								value: attributes.card1Desc,
								onChange: function (val) { setAttributes({ card1Desc: val }); }
							}),
							el(TextControl, {
								label: 'Card 1 Link URL',
								value: attributes.card1Link,
								onChange: function (val) { setAttributes({ card1Link: val }); }
							}),
							el(MediaUpload, {
								onSelect: function (media) { setAttributes({ card1Image: media.url }); },
								type: 'image',
								render: function (obj) {
									return el(Button, { isSecondary: true, onClick: obj.open }, 'Change Card 1 Image');
								}
							})
						),
						el(PanelBody, { title: 'Card 2: Postgraduate', initialOpen: false },
							el(TextControl, {
								label: 'Card 2 Title',
								value: attributes.card2Title,
								onChange: function (val) { setAttributes({ card2Title: val }); }
							}),
							el(TextareaControl, {
								label: 'Card 2 Description',
								value: attributes.card2Desc,
								onChange: function (val) { setAttributes({ card2Desc: val }); }
							}),
							el(TextControl, {
								label: 'Card 2 Link URL',
								value: attributes.card2Link,
								onChange: function (val) { setAttributes({ card2Link: val }); }
							}),
							el(MediaUpload, {
								onSelect: function (media) { setAttributes({ card2Image: media.url }); },
								type: 'image',
								render: function (obj) {
									return el(Button, { isSecondary: true, onClick: obj.open }, 'Change Card 2 Image');
								}
							})
						)
					)
				),
				el('div', { className: 'aberdeen-editor-block aberdeen-programmes-editor-preview' },
					el('div', { className: 'programmes-editor-header' },
						el('h2', { className: 'programmes-editor-title' }, attributes.title),
						el('div', { className: 'gold-accent-line-center' })
					),
					el('div', { className: 'programmes-editor-grid' },
						el('div', { className: 'programme-editor-card' },
							el('div', { className: 'card-img-wrap' },
								el('img', { src: c1Img, alt: attributes.card1Title })
							),
							el('div', { className: 'card-body-wrap' },
								el('h3', { className: 'card-title-gold' }, attributes.card1Title),
								el('p', { className: 'card-desc' }, attributes.card1Desc),
								el('div', { className: 'card-link-gold' }, 'See More ➔')
							)
						),
						el('div', { className: 'programme-editor-card' },
							el('div', { className: 'card-img-wrap' },
								el('img', { src: c2Img, alt: attributes.card2Title })
							),
							el('div', { className: 'card-body-wrap' },
								el('h3', { className: 'card-title-gold' }, attributes.card2Title),
								el('p', { className: 'card-desc' }, attributes.card2Desc),
								el('div', { className: 'card-link-gold' }, 'See More ➔')
							)
						)
					)
				)
			);
		},

		save: function () {
			return null;
		}
	});

})(window.wp);
