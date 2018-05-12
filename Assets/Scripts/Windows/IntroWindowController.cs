using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class IntroWindowController : WindowController {
	[SerializeField] UnityEngine.UI.Text nameLabel;
	[SerializeField] GameObject button;

	public override void Start() {
		base.Start();

		if (button != null)
			button.SetActive(false);

		if(nameLabel != null)
			nameLabel.text = "Hi " + UserInfo.FirstName + ", ";
	}

	void OnEnable(){
		TypeWriterEffect.OnEndWriting += OnEndWriting;
	}

	void OnDisable(){
		TypeWriterEffect.OnEndWriting -= OnEndWriting;
	}

	public void OnClickButtonOhNoThenWait(){
		stateMachine.MoveToSelected(WindowPanels.IntroductionPainGoAway);
	}

	public void OnClickNext(){
		stateMachine.MoveToSelected(WindowPanels.IntroductionPainGoAway2);
	}

	public void OnClickNextOnCrhonicPain(){
		stateMachine.MoveToSelected(WindowPanels.IntroductionChronicPain2);
	}

	public void OnClickButtonHowCouldYouFixThis(){
		stateMachine.MoveToSelected(WindowPanels.IntroductionChronicPain);
	}

	public void OnClickLetsGo(){
		if (UserInfo.SessionList != null && UserInfo.SessionList.Count > 0) {
			UserInfo.CurrentSession = UserInfo.SessionList[0];
			stateMachine.MoveToSelected(WindowPanels.IntroductionAudio);
		} else
			Debug.Log("No sessions to play");
	}

	void OnEndWriting(){
		if (button != null)
			button.SetActive(true);
	}
}
