<?php
/**
 * Lead Form Popup & Thank You Alert Modal
 * Based directly on the Figma "Lead Form W/o Check" and "Thank you!" designs
 */
?>
<div class="lead-modal-overlay" id="leadModalOverlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="leadModalTitle">
	<div class="lead-modal-dialog">
		<!-- Modal Header -->
		<div class="lead-modal-header">
			<h2 class="lead-modal-title" id="leadModalTitle">Register your interest</h2>
			<button type="button" class="lead-modal-close" id="leadModalClose" aria-label="Close modal" <?php echo program_track_attr( 'button', 'close-lead-modal', 'lead-form-popup', 'componentButton' ); ?>>
				&times;
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
						<input type="text" id="first_name" name="first_name" placeholder="First name" required>
					</div>
					<div class="form-group half-width">
						<label for="last_name">Last name <span class="required">*</span></label>
						<input type="text" id="last_name" name="last_name" placeholder="Last name" required>
					</div>
				</div>

				<!-- Row 4: Country & City -->
				<div class="form-row">
					<div class="form-group half-width">
						<label for="country">Country <span class="required">*</span></label>
						<div class="select-wrapper">
							<select id="country" name="country" required>
								<option value="" disabled selected>Select an option</option>
								<option value="IN" selected>India</option>
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
								<option value="Mumbai" selected>Mumbai</option>
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
						<div class="phone-input-wrapper">
							<span class="country-prefix">+91</span>
							<input type="tel" id="phone_number" name="phone_number" placeholder="Phone number" required>
						</div>
					</div>
					<div class="form-group half-width">
						<label for="email">Email <span class="required">*</span></label>
						<input type="email" id="email" name="email" placeholder="Email" required>
					</div>
				</div>

				<!-- Disclaimer -->
				<p class="form-disclaimer">
					By clicking the button below, you agree to receive communications via Email/Call/WhatsApp/SMS about this academic program and other academic programs from University of Aberdeen or its trusted third party partners and service providers. 
					<a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="privacy-link" <?php echo program_track_attr( 'link', 'privacy-policy-link', 'lead-form-popup', 'componentLink' ); ?>>Privacy Policy</a>
				</p>

				<!-- Submit Button -->
				<div class="form-actions">
					<button type="submit" class="btn btn-gold btn-submit-lead" id="btnSubmitLead" <?php echo program_track_attr( 'button', 'submit-lead-form', 'lead-form-popup', 'componentButton' ); ?>>
						Submit
					</button>
				</div>
			</form>
		</div>

		<!-- Modal Body: Thank You State (from Figma design) -->
		<div class="lead-modal-thankyou" id="leadModalThankYou" style="display: none;">
			<div class="thank-you-box">
				<div class="thank-you-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="12" cy="12" r="11" fill="#1E7E34"/>
						<path d="M7 12.5L10.5 16L17 9" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
				<div class="thank-you-content">
					<h3 class="thank-you-title">Thank you for registering your interest</h3>
					<p class="thank-you-desc">
						We'll send you helpful information and updates about this programme, the application process, and more.
					</p>
				</div>
			</div>
			<div class="thank-you-actions">
				<button type="button" class="btn btn-dark js-close-thankyou" <?php echo program_track_attr( 'button', 'close-thank-you-screen', 'lead-form-thankyou', 'componentButton' ); ?>>
					Close
				</button>
			</div>
		</div>
	</div>
</div>
