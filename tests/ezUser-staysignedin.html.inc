<div class="ezUser" id="ezUser">		<form id="ezUser-signin-form" class="ezUser-form" onsubmit="return false">
			<fieldset class="ezUser-fieldset">
				<input id="ezUser-username" tabindex="3201" value="" class="ezUser-text" onkeyup="ezUser_keyUp(event)" size="40" type="text">
				<label class="ezUser-label" for="ezUser-username">Username:</label>
				<input id="ezUser-password" tabindex="3202" value="" onfocus="ezUser_passwordFocus(this)" onblur="ezUser_passwordBlur(this)" class="ezUser-text" onkeyup="ezUser_keyUp(event)" size="40" type="password">
				<label class="ezUser-label" for="ezUser-password">Password:</label>
			</fieldset>
			<fieldset class="ezUser-fieldset">
				<div id="ezUser-message" class="ezUser-message" onclick="ezUser_click(this)"><p class="ezUser-message-hidden"></p></div>
				<input id="ezUser-signin" value="Sign in" tabindex="3204" class="ezUser-button ezUser-action ezUser-buttonstate-0" onclick="ezUser_click(this)" onmouseover="ezUser_setButtonState(this, 1, true)" onmouseout="ezUser_setButtonState(this, 1, false)" onfocus="ezUser_setButtonState(this, 2, true)" onblur="ezUser_setButtonState(this, 2, false)" type="button">
				<input id="ezUser-goaccount" value="Register" tabindex="3203" class="ezUser-button ezUser-action ezUser-buttonstate-0" onclick="ezUser_click(this)" onmouseover="ezUser_setButtonState(this, 1, true)" onmouseout="ezUser_setButtonState(this, 1, false)" onfocus="ezUser_setButtonState(this, 2, true)" onblur="ezUser_setButtonState(this, 2, false)" type="button">
			</fieldset>
			<fieldset class="ezUser-fieldset">
				<input id="ezUser-staySignedIn" value="Stay signed in" tabindex="3207" class="ezUser-button ezUser-preference ezUser-buttonstate-4" onclick="ezUser_click(this)" onmouseover="ezUser_setButtonState(this, 1, true)" onmouseout="ezUser_setButtonState(this, 1, false)" onfocus="ezUser_setButtonState(this, 2, true)" onblur="ezUser_setButtonState(this, 2, false)" type="button">
				<input id="ezUser-rememberMe" value="Remember me" tabindex="3206" class="ezUser-button ezUser-preference ezUser-buttonstate-4" onclick="ezUser_click(this)" onmouseover="ezUser_setButtonState(this, 1, true)" onmouseout="ezUser_setButtonState(this, 1, false)" onfocus="ezUser_setButtonState(this, 2, true)" onblur="ezUser_setButtonState(this, 2, false)" type="button">
				<input id="ezUser-reminder" value="Reset password" tabindex="3205" class="ezUser-button ezUser-preference ezUser-buttonstate-0" onclick="ezUser_click(this)" onmouseover="ezUser_setButtonState(this, 1, true)" onmouseout="ezUser_setButtonState(this, 1, false)" onfocus="ezUser_setButtonState(this, 2, true)" onblur="ezUser_setButtonState(this, 2, false)" type="button">
			</fieldset>
		</form></div>