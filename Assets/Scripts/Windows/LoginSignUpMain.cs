using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class LoginSignUpMain : WindowController {
	

	public void OnClickSignupWithEmail() {
		stateMachine.MoveToSelected(WindowPanels.SignUpWithEmail);
	}

	public void OnClickAlreadyAMember(){
		stateMachine.MoveToSelected(WindowPanels.LoginWithEmail);
	}

	public void OnClickTermsAndConditions(){
		Application.OpenURL("");
	}

	public void OnTerms(){
		stateMachine.LoadPopupUI(WindowPanels.Terms);
	}

	public void OnPrivacy(){
		stateMachine.LoadPopupUI(WindowPanels.Conditions);
	}
}
