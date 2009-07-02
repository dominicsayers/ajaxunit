<ezUser container="ezUser-account"><![CDATA[		<!-- Mode is 'display', new flag is false (originally mode was 'result', new flag was true) -->
		<form id="ezUser-account-form" class="ezUser-form" onsubmit="return false">
			<fieldset class="ezUser-fieldset">
				<input id= "ezUser-account-email"
					tabindex	=	"3211"
					value		=	"dominic_sayers@hotmail.com"
					type		=	"text"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-email">* Email address:</label>
				<input id= "ezUser-account-firstName"
					tabindex	=	"3212"
					value		=	"Dominic"
					type		=	"text"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-firstName">First name:</label>
				<input id= "ezUser-account-lastName"
					tabindex	=	"3213"
					value		=	"Sayers"
					type		=	"text"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-lastName">Last name:</label>
				<input id= "ezUser-account-username"
					tabindex	=	"3214"
					value		=	"dominicsayers"
					type		=	"text"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-username">* Username:</label>
				<input id= "ezUser-account-password"
					tabindex	=	"3215"
					value		=	"************"
					type		=	"password"
					onfocus		=	"ezUser_passwordFocus(this)"
					onblur		=	"ezUser_passwordBlur(this)"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-password">* Password:</label>
				<input id= "ezUser-account-confirm"
					tabindex	=	"3216"
					value		=	"************"
					type		=	"password"
					onfocus		=	"ezUser_passwordFocus(this)"
					onblur		=	"ezUser_passwordBlur(this)"
					disabled	=	"disabled"
					class		=	"ezUser-text"
					onkeyup		=	"ezUser_keyUp(event)"
					size		=	"40"
				/>
				<label class="ezUser-label" for="ezUser-account-confirm">* Confirm password:</label>
			</fieldset>
			<fieldset class="ezUser-fieldset">
				<div id="ezUser-account-message" class="ezUser-message" onclick="ezUser_click(this)"><p class="ezUser-message-plain">* = mandatory field</p></div>
				<input id="ezUser-account-edit" value="Edit"
					tabindex	=	"3217"
					type		=	"button"
					class		=	"ezUser-button ezUser-action ezUser-buttonstate-0"
					onclick		=	"ezUser_click(this)"
					onmouseover	=	"ezUser_setButtonState(this, 1, true)"
					onmouseout	=	"ezUser_setButtonState(this, 1, false)"
					onfocus		=	"ezUser_setButtonState(this, 2, true)"
					onblur		=	"ezUser_setButtonState(this, 2, false)"
				/>
				<input id="ezUser-account-new" value="New"
					tabindex	=	"3218"
					type		=	"button"
					class		=	"ezUser-button ezUser-action ezUser-buttonstate-0"
					onclick		=	"ezUser_click(this)"
					onmouseover	=	"ezUser_setButtonState(this, 1, true)"
					onmouseout	=	"ezUser_setButtonState(this, 1, false)"
					onfocus		=	"ezUser_setButtonState(this, 2, true)"
					onblur		=	"ezUser_setButtonState(this, 2, false)"
				/>
			</fieldset>
			<fieldset class="ezUser-fieldset">
				<div id="ezUser-account-text" class="ezUser-text" onclick="ezUser_click(this)"><p class="ezUser-message-text">Your account has been created and a confirmation email has been sent. Please click on the link in the confirmation email to verify your account.</p></div>
				<input id="ezUser-account-resend" value="Resend"
					tabindex	=	"3219"
					type		=	"button"
					class		=	"ezUser-button ezUser-action ezUser-buttonstate-0"
					onclick		=	"ezUser_click(this)"
					onmouseover	=	"ezUser_setButtonState(this, 1, true)"
					onmouseout	=	"ezUser_setButtonState(this, 1, false)"
					onfocus		=	"ezUser_setButtonState(this, 2, true)"
					onblur		=	"ezUser_setButtonState(this, 2, false)"
				/>
				<input id="ezUser-account-new"			type="hidden" value="false" />
				<input id="ezUser-account-useSavedPassword"	type="hidden" value="false" />
			</fieldset>
		</form>]]></ezUser>