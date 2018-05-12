using System.Collections;
using System.Collections.Generic;
using System.Net.Mail;
using UnityEngine;
using UnityEngine.UI;


public class ForgotPasswordWindow : WindowController {
	[SerializeField] InputField inputField;

	public void OnRequestButtonClicked() {
		if (ValidateEmail()) {
			Loader.Instane.ShowLoading();
			apiManager.WebRequestPasswordEdit(inputField.text, (json,sucess)=>{
				Loader.Instane.RemoveLoading();
				if(sucess){
					/*Popup.Instance.ShowPopup("Success", "Please check your email for verification link.", ()=>{
						stateMachine.MoveToSelected(WindowPanels.LoginWithEmail);
					});*/
					stateMachine.LoadPopupUI(WindowPanels.PasswordSend);
				}	
				else{
					Popup.Instance.ShowPopup("Error!", "Please enter valid email", null);
				}
			});
		}
	}

	bool ValidateEmail() {
		try {
			new MailAddress(inputField.text);
			return true;
		} catch (System.FormatException) {
			Popup.Instance.ShowPopup("Attention", "Please check your mail.", null);
			return false;
		}
	}

	public void OnClickBack(){
		stateMachine.MoveToSelected(WindowPanels.LoginWithEmail);
	}

}
