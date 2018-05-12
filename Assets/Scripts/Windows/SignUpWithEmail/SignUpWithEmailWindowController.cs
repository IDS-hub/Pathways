using System;
using System.Collections;
using System.Collections.Generic;
using System.Net.Mail;
using UnityEngine;
using UnityEngine.UI;

public class SignUpWithEmailWindowController : WindowController {
	
	[SerializeField] InputField firstNameField;
	[SerializeField] InputField lastNameField;
	[SerializeField] InputField emailField;
	[SerializeField] InputField passwordField;


	public void OnGetSratedFreeButtonClicked() {
		if (CheckPassword() && CheckEmail()) {
			Loader.Instane.ShowLoading();
			apiManager.WebRequestProfileSingUp(firstNameField.text, lastNameField.text, emailField.text, passwordField.text, (value, success) => {
				if (success) {
					UserInfo.AccessToken = value.GetField("accessToken", ""); 
					apiManager.WebRequestProfileShow((jsonData, positive) => {
						if (positive) {
							apiManager.GetAllSession(null, APIManager.RESPONSE_SESSION);

							apiManager.AddUserDiagnonis(null);

							stateMachine.MoveToSelected(WindowPanels.IntroductionWelcome);

							Loader.Instane.RemoveLoading();
						}
					}, APIManager.RESPONSE_PROFILE_SHOW);
				} else {
					string msg = value.GetField("message", ""); 
					Popup.Instance.ShowPopup("Attention", msg.Length > 0 ? msg : "Some thing is wrong, please try again", null);
				}
			});
		} else {
			//TODO: Implement error
		}

	}

	public void OnEndEditName(string input) {
		Debug.Log("OnEndEditName " + input);
		lastNameField.ActivateInputField();
		lastNameField.Select();
	}

	public void OnEndEditLastName(string input) {
		Debug.Log("OnEndEditLastName " + input);
		emailField.ActivateInputField();
		emailField.Select();
	}

	public void OnEndEditEmail(string input) {
		Debug.Log("OnEndEditEmail " + input);
		passwordField.ActivateInputField();
		passwordField.Select();
	}

	/*public void OnEndEdit(string input){
		Debug.Log("input " + input);
		if (input == "a") {
			lastNameField.ActivateInputField();
			lastNameField.Select();
			//TouchScreenKeyboard.Open("", TouchScreenKeyboardType.Default, false, false, false);
		} else if (input == "b") {
			emailField.ActivateInputField();
			emailField.Select();
			//TouchScreenKeyboard.Open("", TouchScreenKeyboardType.EmailAddress, false, false, false);
		} else if (input == "c") {
			passwordField.ActivateInputField();
			passwordField.Select();
			//TouchScreenKeyboard.Open("");
		}
	}*/

	public void OnAlreadyMemberButtonClicked() {
		stateMachine.MoveToSelected(WindowPanels.LoginWithEmail);
	}

	bool CheckPassword() {
		if (passwordField.text.ToCharArray().Length < 6) {
			Popup.Instance.ShowPopup("Attention", "Password should more than 6 characters", null);
			return false;
		} else
			return true;
	}

	bool CheckEmail() {
		try {
			new MailAddress(emailField.text);
			return true;
		} catch (FormatException) {
			return false;
		}
	}

	public void OnClickBack() {
		stateMachine.MoveToSelected(WindowPanels.LoginSignUpMainWindow);
	}

	public void OnTerms(){
		stateMachine.LoadPopupUI(WindowPanels.Terms);
	}

	public void OnPrivacy(){
		stateMachine.LoadPopupUI(WindowPanels.Conditions);
	}

}
