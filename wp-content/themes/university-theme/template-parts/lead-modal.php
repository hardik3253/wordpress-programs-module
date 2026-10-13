<?php
/**
 * Lead Form Popup & Thank You Alert Modal
 * Matches the reference design for "Register your interest" and "Thank you!" state
 */
?>
<div class="lead-modal-overlay" id="leadModalOverlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="leadModalTitle">
	<div class="lead-modal-dialog">
		<!-- Modal Header -->
		<div class="lead-modal-header">
			<h2 class="lead-modal-title" id="leadModalTitle">Register your interest</h2>
			<button type="button" class="lead-modal-close" id="leadModalClose" aria-label="Close modal" <?php echo program_track_attr( 'button', 'close-lead-modal', 'lead-form-popup', 'componentButton' ); ?>>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22282E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<!-- Modal Body: Form View -->
		<div class="lead-modal-body" id="leadModalFormContainer">
			<form id="leadCaptureForm" class="lead-form" novalidate>
				<!-- Row 1: Enquiring As -->
				<div class="form-group full-width">
					<label for="enquiring_as">I am enquiring as <span class="required">*</span></label>
					<div class="select-wrapper">
						<select id="enquiring_as" name="enquiring_as" required>
							<option value="" disabled selected>Select an option</option>
							<option value="student">Prospective Student</option>
							<option value="parent">Parent / Guardian</option>
							<option value="professional">Working Professional</option>
							<option value="other">Other</option>
						</select>
					</div>
				</div>

				<!-- Row 2: Level & Programme -->
				<div class="form-row">
					<div class="form-group half-width">
						<label for="level_of_interest">Level of interest <span class="required">*</span></label>
						<div class="select-wrapper">
							<select id="level_of_interest" name="level_of_interest" required>
								<option value="" disabled selected>Select an option</option>
								<option value="undergraduate">Undergraduate</option>
								<option value="postgraduate">Postgraduate</option>
							</select>
						</div>
					</div>
					<div class="form-group half-width">
						<label for="programme_of_interest">Programme of interest <span class="required">*</span></label>
						<div class="select-wrapper">
							<select id="programme_of_interest" name="programme_of_interest" required>
								<option value="" disabled selected>Select an option</option>
								<option value="bca">Bachelor of Computer Applications (BCA)</option>
								<option value="be">Bachelor of Engineering (B.E.)</option>
								<option value="mca">Master of Computer Applications (MCA)</option>
								<option value="me">Master of Engineering (M.E.)</option>
								<option value="other">Other Programme</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Row 3: First & Last Name -->
				<div class="form-row">
					<div class="form-group half-width">
						<label for="first_name">First name <span class="required">*</span></label>
						<input type="text" id="first_name" name="first_name" required>
					</div>
					<div class="form-group half-width">
						<label for="last_name">Last name <span class="required">*</span></label>
						<input type="text" id="last_name" name="last_name" required>
					</div>
				</div>

				<!-- Row 4: Country & City -->
				<div class="form-row">
					<div class="form-group half-width">
						<label for="country">Country <span class="required">*</span></label>
						<div class="select-wrapper">
							<select id="country" name="country" required>
								<option value="" disabled selected>Select an option</option>
								<option value="IN">India</option>
								<option value="UK">United Kingdom</option>
								<option value="US">United States</option>
								<option value="AE">UAE</option>
							</select>
						</div>
					</div>
					<div class="form-group half-width">
						<label for="city">City <span class="required">*</span></label>
						<div class="select-wrapper">
							<select id="city" name="city" required>
								<option value="" disabled selected>Select an option</option>
								<option value="Mumbai">Mumbai</option>
								<option value="Pune">Pune</option>
								<option value="Delhi">Delhi NCR</option>
								<option value="Bangalore">Bangalore</option>
								<option value="Other">Other City</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Row 5: Phone & Email -->
				<div class="form-row">
					<div class="form-group half-width">
						<label for="phone_number">Phone number <span class="required">*</span></label>
						<input type="tel" id="phone_number" name="phone_number" value="+91" required>
					</div>
					<div class="form-group half-width">
						<label for="email">Email <span class="required">*</span></label>
						<input type="email" id="email" name="email" required>
					</div>
				</div>

				<!-- Disclaimer & Submit Strip -->
				<div class="form-disclaimer-wrapper">
					<p class="form-disclaimer">
						By clicking the button below, you agree to receive communications via Email/Call/WhatsApp/SMS about this academic program and other academic programs from University of Aberdeen or its trusted third-party partners and service providers. 
						<a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="privacy-link" <?php echo program_track_attr( 'link', 'privacy-policy-link', 'lead-form-popup', 'componentLink' ); ?>>Privacy Policy</a>
					</p>

					<!-- Submit Button (Left-aligned golden yellow) -->
					<div class="form-actions">
						<button type="submit" class="btn btn-gold btn-submit-lead" id="btnSubmitLead" <?php echo program_track_attr( 'button', 'submit-lead-form', 'lead-form-popup', 'componentButton' ); ?>>
							Submit
						</button>
					</div>
				</div>
			</form>
		</div>

		<!-- Modal Body: Thank You State (Matching Reference Image 2) -->
		<div class="lead-modal-thankyou" id="leadModalThankYou" style="display: none;">
			<div class="thank-you-box">
				<div class="thank-you-icon" aria-hidden="true">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="2"/>
						<path d="m8.5 12 2.5 2.5 5-5" stroke="#FFFFFF" stroke-width="2.2"/>
					</svg>
				</div>
				<div class="thank-you-content">
					<h3 class="thank-you-title">Thank you for registering your interest</h3>
					<p class="thank-you-desc">
						We'll send you helpful information and updates about this programme, the application process, and more.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
