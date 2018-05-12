using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class IntroAudioController : WindowController {
	[SerializeField] AudioPlayer player;
	[SerializeField] GameObject continueButton;

	public override void Start() {
		base.Start();

		if (continueButton != null)
			continueButton.SetActive(false);

		player.OnPlayerFinishWork += OnPlayerFinish;
		player.PlayAudio();
	}

	public void OnContinueButtonClicked() {
		//SessionManager.Instance.IncreaseSessionIndex();
		Loader.Instane.ShowLoading();
		apiManager.PlaySession(UserInfo.CurrentSession.id, (temp, isSuccess) => {
			if (isSuccess) {
				UserInfo.CurrentSession.isWatched = true;
				if (UserInfo.NextSession == null) {
					apiManager.GetAllSession((data, gotSession) => {
						Loader.Instane.RemoveLoading();
						stateMachine.MoveToSelected(WindowPanels.Creation_NEW);
					}, APIManager.RESPONSE_SESSION);
				} else {
					Loader.Instane.RemoveLoading();
					stateMachine.MoveToSelected(WindowPanels.Creation_NEW);
				}
			} else {
				// user does not authenticated, logout
				Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
					DoLogout();
				});
			}

		}, -1);
	}

	void OnPlayerFinish() {
		if (continueButton != null)
			continueButton.SetActive(true);
	}

	private void OnDisable() {
		player.OnPlayerFinishWork -= OnPlayerFinish;
	}
}
