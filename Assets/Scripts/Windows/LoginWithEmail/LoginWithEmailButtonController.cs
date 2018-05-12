using System;
using System.Collections;
using System.Collections.Generic;
using System.Net.Mail;
using UnityEngine;
using UnityEngine.UI;

public class LoginWithEmailButtonController : WindowController {
	[SerializeField] InputField emailField;
	[SerializeField] InputField passwordField;

	public void OnLoginButtonClicked() {
		if (ValidateEmail() && ValidatePassword()) {
			Loader.Instane.ShowLoading();
			apiManager.WebRequestSignIn(emailField.text, passwordField.text, (value, success) => {
				if (success) {
					UserInfo.AccessToken = value.GetField("accessToken",""); 
					apiManager.WebRequestProfileShow((jsonData, positive) => {
						if (positive) {

							apiManager.GetAllSession((data, gotSession) => {

								// if avatar customisation data found, then the user is returning.
								if (jsonData.GetField("avatarJsonData", "").Length == 0){
									stateMachine.MoveToSelected(WindowPanels.IntroductionWelcome);
									apiManager.AddUserDiagnonis(null);
								}
								else
									stateMachine.MoveToSelected(WindowPanels.Home);

								Loader.Instane.RemoveLoading();

							}, APIManager.RESPONSE_SESSION);

						}
					}, APIManager.RESPONSE_PROFILE_SHOW);
				}
				else{
					string msg = value.GetField("message",""); 
					Popup.Instance.ShowPopup("Attention", msg.Length > 0 ? msg : "Some thing is wrong, please try again", null);
				}
			});
		} else {
			//TODO: 
		}
	}

	public void OnForgotPasswordButtonClicked() {
		stateMachine.MoveToSelected(WindowPanels.ForgotPassword);
	}

	public void ClickOnDontHaveAnAccount() {
		stateMachine.MoveToSelected(WindowPanels.SignUpWithEmail);
	}

	bool ValidateEmail() {
		try {
			new MailAddress(emailField.text);
			return true;
		} catch (FormatException) {
			return false;
		}
	}

	bool ValidatePassword() {
		if (passwordField.text.ToCharArray().Length < 6) {
			Popup.Instance.ShowPopup("Attention", "Password should more than 6 characters!", null);
			return false;
		}
		else
			return true;
	}
}
