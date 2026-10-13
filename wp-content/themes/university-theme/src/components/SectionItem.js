import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function SectionItem({
	section,
	index,
	total,
	onUpdate,
	onRemove,
	onMoveUp,
	onMoveDown,
}) {
	const [collapsed, setCollapsed] = useState(false);

	const openMediaUploader = () => {
		if (!window.wp || !window.wp.media) {
			alert('WordPress media library is unavailable.');
			return;
		}

		const frame = window.wp.media({
			title: __('Select or Upload Section Image', 'university-theme'),
			button: { text: __('Use this image', 'university-theme') },
			multiple: false,
		});

		frame.on('select', () => {
			const attachment = frame.state().get('selection').first().toJSON();
			onUpdate(index, 'image', attachment.url);
		});

		frame.open();
	};

	const removeImage = () => {
		onUpdate(index, 'image', '');
	};

	return (
		<div className={`repeater-card ${collapsed ? 'is-collapsed' : ''}`}>
			<div className="repeater-card-header">
				<div className="card-header-left" onClick={() => setCollapsed(!collapsed)}>
					<button
						type="button"
						className="btn-collapse-toggle"
						title={collapsed ? __('Expand', 'university-theme') : __('Collapse', 'university-theme')}
					>
						{collapsed ? '▶' : '▼'}
					</button>
					<span className="card-index-badge">#{index + 1}</span>
					<strong className="card-title-preview">
						{section.title ? section.title : __('(Untitled Section)', 'university-theme')}
					</strong>
				</div>

				<div className="card-header-actions">
					<button
						type="button"
						className="btn-action-icon"
						disabled={index === 0}
						onClick={() => onMoveUp(index)}
						title={__('Move Up', 'university-theme')}
					>
						▲
					</button>
					<button
						type="button"
						className="btn-action-icon"
						disabled={index === total - 1}
						onClick={() => onMoveDown(index)}
						title={__('Move Down', 'university-theme')}
					>
						▼
					</button>
					<button
						type="button"
						className="btn-action-icon btn-remove-item"
						onClick={() => onRemove(index)}
						title={__('Remove Section', 'university-theme')}
					>
						✕
					</button>
				</div>
			</div>

			{!collapsed && (
				<div className="repeater-card-body">
					<div className="form-group-modern">
						<label className="field-label-sub">{__('Section Title', 'university-theme')}</label>
						<input
							type="text"
							className="meta-input-modern"
							placeholder={__('e.g. Curriculum Overview', 'university-theme')}
							value={section.title || ''}
							onChange={(e) => onUpdate(index, 'title', e.target.value)}
						/>
					</div>

					<div className="form-group-modern">
						<label className="field-label-sub">{__('Section Description', 'university-theme')}</label>
						<textarea
							rows="3"
							className="meta-textarea-modern"
							placeholder={__('Detailed information about this curriculum module or requirement...', 'university-theme')}
							value={section.description || ''}
							onChange={(e) => onUpdate(index, 'description', e.target.value)}
						/>
					</div>

					<div className="form-group-modern">
						<label className="field-label-sub">{__('Section Image (Optional)', 'university-theme')}</label>
						{section.image ? (
							<div className="media-preview-container">
								<img src={section.image} alt={section.title || 'Section Image'} className="media-preview-thumbnail" />
								<div className="media-preview-buttons">
									<button type="button" className="btn-modern btn-secondary-sm" onClick={openMediaUploader}>
										{__('Replace Image', 'university-theme')}
									</button>
									<button type="button" className="btn-modern btn-danger-sm" onClick={removeImage}>
										{__('Remove', 'university-theme')}
									</button>
								</div>
							</div>
						) : (
							<button type="button" className="btn-modern btn-upload-media" onClick={openMediaUploader}>
								<span className="dashicons dashicons-format-image"></span>
								{__('Select Image from Media Library', 'university-theme')}
							</button>
						)}
					</div>
				</div>
			)}
		</div>
	);
}
