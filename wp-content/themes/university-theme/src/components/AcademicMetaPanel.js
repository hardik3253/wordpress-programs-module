import { __ } from '@wordpress/i18n';

export default function AcademicMetaPanel({ academicYear, duration, onChange }) {
	return (
		<div className="program-meta-grid">
			<div className="program-meta-field">
				<label htmlFor="academic-year-input" className="meta-field-label">
					{__('Academic Year', 'university-theme')}
					<span className="meta-field-hint">{__('(e.g. 2025-26)', 'university-theme')}</span>
				</label>
				<input
					id="academic-year-input"
					type="text"
					className="meta-input-modern"
					placeholder="2025-26"
					value={academicYear}
					onChange={(e) => onChange('academicYear', e.target.value)}
				/>
			</div>

			<div className="program-meta-field">
				<label htmlFor="duration-input" className="meta-field-label">
					{__('Duration', 'university-theme')}
					<span className="meta-field-hint">{__('(e.g. 3 Years)', 'university-theme')}</span>
				</label>
				<input
					id="duration-input"
					type="text"
					className="meta-input-modern"
					placeholder="3 Years"
					value={duration}
					onChange={(e) => onChange('duration', e.target.value)}
				/>
			</div>
		</div>
	);
}
