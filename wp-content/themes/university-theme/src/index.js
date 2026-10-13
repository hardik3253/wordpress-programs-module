import { render } from '@wordpress/element';
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import AcademicMetaPanel from './components/AcademicMetaPanel';
import SectionsRepeater from './components/SectionsRepeater';
import './style.scss';

function ProgramMetaApp() {
	const initialData = window.universityProgramData || {};
	const [academicYear, setAcademicYear] = useState(initialData.academicYear || '');
	const [duration, setDuration] = useState(initialData.duration || '');
	const [sections, setSections] = useState(
		Array.isArray(initialData.contentSections) ? initialData.contentSections : []
	);

	// Synchronize with hidden input fields for standard WP form POST save
	useEffect(() => {
		const yearField = document.getElementById('academic_year_field');
		if (yearField) yearField.value = academicYear;

		const durationField = document.getElementById('duration_field');
		if (durationField) durationField.value = duration;

		const sectionsField = document.getElementById('content_sections_json_field');
		if (sectionsField) sectionsField.value = JSON.stringify(sections);

		// Also sync to Gutenberg core/editor store if active
		if (window.wp && window.wp.data && window.wp.data.dispatch) {
			try {
				const editor = window.wp.data.dispatch('core/editor');
				if (editor && editor.editPost) {
					editor.editPost({
						meta: {
							academic_year: academicYear,
							duration: duration,
							content_sections: sections,
						},
					});
				}
			} catch (e) {
				// Ignore if not in Gutenberg editor context
			}
		}
	}, [academicYear, duration, sections]);

	const handleMetaChange = (field, value) => {
		if (field === 'academicYear') setAcademicYear(value);
		if (field === 'duration') setDuration(value);
	};

	const handleAddSection = () => {
		const newSection = {
			id: Date.now(),
			title: '',
			description: '',
			image: '',
		};
		setSections([...sections, newSection]);
	};

	const handleUpdateSection = (index, key, value) => {
		const updated = [...sections];
		updated[index] = { ...updated[index], [key]: value };
		setSections(updated);
	};

	const handleRemoveSection = (index) => {
		if (confirm(__('Are you sure you want to delete this section?', 'university-theme'))) {
			const updated = sections.filter((_, idx) => idx !== index);
			setSections(updated);
		}
	};

	const handleReorderSections = (newSections) => {
		setSections(newSections);
	};

	return (
		<div className="university-program-panel-wrapper">
			<div className="panel-brand-header">
				<div className="brand-header-title">
					<span className="dashicons dashicons-welcome-learn-more brand-icon"></span>
					<h4>{__('Program Meta & Specifications', 'university-theme')}</h4>
				</div>
				<span className="status-badge-verified">{__('React Admin UI Active', 'university-theme')}</span>
			</div>

			<AcademicMetaPanel
				academicYear={academicYear}
				duration={duration}
				onChange={handleMetaChange}
			/>

			<hr className="panel-divider" />

			<SectionsRepeater
				sections={sections}
				onAdd={handleAddSection}
				onUpdate={handleUpdateSection}
				onRemove={handleRemoveSection}
				onReorder={handleReorderSections}
			/>
		</div>
	);
}

// Mount the React app into container
document.addEventListener('DOMContentLoaded', () => {
	const root = document.getElementById('university-program-metabox-root');
	if (root) {
		render(<ProgramMetaApp />, root);
	}
});
