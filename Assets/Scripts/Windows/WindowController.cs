using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class WindowController : MonoBehaviour {
	protected tStateMachine stateMachine;
	protected APIManager apiManager;

	public virtual void Start() {

		GameObject temp = GameObject.FindGameObjectWithTag("StateMachine");
		if(temp != null)
			stateMachine = temp.GetComponent<tStateMachine>();

		temp = GameObject.FindGameObjectWithTag("APIManager");
		if(temp != null)
			apiManager = temp.GetComponent<APIManager>();
		//apiManager = GameObject.FindGameObjectWithTag("APIManager").GetComponent<APIManager>();
		

	}

	public void OnClickFB() {
		FacebookHandler._instance.DoLogIn((returnStirng, success) => {
			if (success) {
				Loader.Instane.ShowLoading();

				MiniJsonObject userData = new MiniJsonObject(returnStirng);
				// getting first name and last name
				string name = userData.GetField("name", "N/A");
				string[] array = name.Split(' ');
				string fname = "";
				string lname = "";
				if (array.Length > 0)
					fname = array[0];
				if (array.Length > 1)
					lname = array[1];

				string fbID = userData.GetField("id", "");

				apiManager.WebRequestFacebookLogin(fname, lname, fbID, (jsonData, isSuccess) => {
					if (isSuccess) {
						UserInfo.AccessToken = jsonData.GetField("accessToken", ""); 
						apiManager.WebRequestProfileShow((profileJsonData, positive) => {
							if (positive) {

								apiManager.GetAllSession((data, gotSession) => {

									//if (UserInfo.UserOriginalDiagnosis != null && UserInfo.UserOriginalDiagnosis.Count > 0)
									//apiManager.AddUserDiagnonis(null);

									if (profileJsonData.GetField("avatarJsonData", "").Length == 0) {
										stateMachine.MoveToSelected(WindowPanels.IntroductionWelcome);
										apiManager.AddUserDiagnonis(null);
									} else
										stateMachine.MoveToSelected(WindowPanels.Home);

									Loader.Instane.RemoveLoading();

								}, APIManager.RESPONSE_SESSION);
							}
						}, APIManager.RESPONSE_PROFILE_SHOW);
					}
				});
			}
		});
	}

	// logging out current user
	protected void DoLogout() {
		Loader.Instane.ShowLoading();
		apiManager.WebRequestSignOut((value, success) => {
			FacebookHandler._instance.DoLogOut();
			UserInfo.Reset();
			stateMachine.MoveToSelected(WindowPanels.LoginSignUpMainWindow);
			ProfileWindowController.OnProfile = false;
			GameObject loadedAvatar = GameObject.FindGameObjectWithTag("Player");
			if (loadedAvatar != null)
				Destroy(loadedAvatar);

			Loader.Instane.RemoveLoading();
		});
	}
	
}
