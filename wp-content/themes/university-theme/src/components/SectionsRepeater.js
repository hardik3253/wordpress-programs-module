import { __ } from '@wordpress/i18n';
import SectionItem from './SectionItem';

export default function SectionsRepeater({ sections, onAdd, onUpdate, onRemove, onReorder }) {
	const handleMoveUp = (index) => {
		if (index === 0) return;
		const next = [...sections];
		const temp = next[index - 1];
		next[index - 1] = next[index];
		next[index] = temp;
		onReorder(next);
	};

	const handleMoveDown = (index) => {
		if (index === sections.length - 1) return;
		const next = [...sections];
		const temp = next[index + 1];
		next[index + 1] = next[index];
		next[index] = temp;
		onReorder(next);
	};

	return (
		<div className="repeater-container-modern">
			<div className="repeater-header-bar">
				<div>
					<h3 className="repeater-section-heading">
						{__('Content Sections (Repeater)', 'university-theme')}
					</h3>
					<p className="repeater-section-sub">
						{__('Add structured sections with optional images that render dynamically on the single program page.', 'university-theme')}
					</p>
				</div>
				<button type="button" className="btn-modern btn-primary-add" onClick={onAdd}>
					+ {__('Add Section', 'university-theme')}
				</button>
			</div>

			{sections.length === 0 ? (
				<div className="repeater-empty-placeholder">
					<span className="dashicons dashicons-text-page placeholder-icon"></span>
					<p className="placeholder-text">{__('No content sections added yet.', 'university-theme')}</p>
					<button type="button" className="btn-modern btn-outline" onClick={onAdd}>
						{__('Add First Section', 'university-theme')}
					</button>
				</div>
			) : (
				<div className="repeater-cards-list">
					{sections.map((section, idx) => (
						<SectionItem
							key={section.id || idx}
							section={section}
							index={idx}
							total={sections.length}
							onUpdate={onUpdate}
							onRemove={onRemove}
							onMoveUp={handleMoveUp}
							onMoveDown={handleMoveDown}
						/>
					))}
				</div>
			)}
		</div>
	);
}
