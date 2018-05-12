using UnityEngine.EventSystems;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System;
using Lean.Touch;

public class PainSelector : WindowController {
	public static Action<bool,WindowPanels> OnPainSelector;
	public static Action OnDestroyLastPainPoint;
	public static Action<APIManager> OnSavePainPoints;
	public static Action OnResetCamera;

	public static Action OnUpdate;

	public Button buttonCancelPain;
	public GameObject buttonAcceptPain;
	public Button buttonStartSession;

	[SerializeField] Text sessionNameLabel;
	[SerializeField] GameObject skipObject;

	//	private List<int> startPainLevels = new List<int>();

	Vector2 lastTouchPosition;
	//	bool isDragged = false;
	int painCount = 0;

	// Use this for initialization
	public override void Start() {
		base.Start();

		if (!ProfileWindowController.OnProfile) {
			if (UserInfo.NextSession == null) {
				Loader.Instane.ShowLoading();
				// checking for next session
				apiManager.GetAllSession((data, gotSession) => {
					if (gotSession)
						DoRest();
					else {
						// user does not authenticated, logout
						Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
							DoLogout();
						});
					}
				}, APIManager.RESPONSE_SESSION);
			} else
				DoRest();
		} else
			DoRest();

		skipObject.SetActive(!UserInfo.PainSelectorSkip);
	}

	void DoRest() {
		Loader.Instane.RemoveLoading();
		buttonCancelPain.gameObject.SetActive(false);
		buttonAcceptPain.SetActive(false);
		buttonStartSession.gameObject.SetActive(false);

		MouseOrbitImproved.OnTouchModel += OnTouchModel;

		if (UserInfo.UserAvatar.painPoints != null)
			painCount = UserInfo.UserAvatar.painPoints.Count;
		else
			painCount = 0;

		if (painCount > 0) {
			buttonCancelPain.gameObject.SetActive(true);
			buttonAcceptPain.SetActive(true);
		}

		if (OnPainSelector != null)
			OnPainSelector(true, WindowPanels.PainSelector);

		sessionNameLabel.text = UserInfo.NextSession.title;
	}

	void OnDisable() {
		if (OnPainSelector != null)
			OnPainSelector(false, WindowPanels.PainSelector);

		MouseOrbitImproved.OnTouchModel -= OnTouchModel;
	}

	private Color GetColorByPainIntensity(int intensity) {
		return Color.black;
	}

	public void OnTutorialSkip() {
		skipObject.SetActive(false);
		UserInfo.PainSelectorSkip = true;
	}

	// user is clicking on model to add pain points
	void OnTouchModel(Vector3 point) {
		painCount++;
		buttonCancelPain.gameObject.SetActive(true);
		buttonAcceptPain.SetActive(true);
		buttonStartSession.gameObject.SetActive(false);
	}

	public void AddNewPainButtonHandler() {
		buttonStartSession.gameObject.SetActive(false);
	}

	public void CancelPain() {
		DestroyLastPainPoint();
		if (painCount == 0)
			buttonCancelPain.gameObject.SetActive(false);
	}

	// when user is clicking on the accept button
	public void AcceptPain() {
		// if user comes from profile screen
		if (!ProfileWindowController.OnProfile) {
			buttonCancelPain.gameObject.SetActive(false);
			buttonAcceptPain.SetActive(false);
			buttonStartSession.gameObject.SetActive(true);
		} else {
			// fresh logged in user
			if (OnSavePainPoints != null)
				OnSavePainPoints(apiManager);
			stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
		}
	}

	void DestroyLastPainPoint() {
		if (painCount == 0)
			return;

		painCount--;
		if (OnDestroyLastPainPoint != null)
			OnDestroyLastPainPoint();
	}

	public void StartNewSessionButtonClicked() {
		if (OnSavePainPoints != null)
			OnSavePainPoints(apiManager);

		// play next session
		UserInfo.CurrentSession = UserInfo.NextSession;
		if (UserInfo.CurrentSession != null) {
			if (int.Parse(UserInfo.CurrentSession.id) > Constants.PURCHASE_SESSION_ID && !UserInfo.IsSubscribe) {
				stateMachine.LoadPopupUI(WindowPanels.Subscription);
			} else {
				if (UserInfo.IsQuizSession)
					stateMachine.MoveToSelected(WindowPanels.Quiz);
				else
					stateMachine.MoveToSelected(WindowPanels.Session);
			}
		}
	}

	public void OnClickHelp() {
		skipObject.SetActive(true);
		//string help = "Now that you’ve customized your avatar, it’s time to add pain in areas that you most often feel pain. Simply tap the part of your virtual body that feels pain and you’ll see the pain get added. It helps to zoom into the affected part of the body.\r\n\nWhen navigating around your virtual body, if you get into an awkward angle at any point click the recenter button at the top.\r\n\nIf you want to remove pain, simply click the undo button as many times as you need. Once you’re ready to move on, click the tick.\r\n\nAn online avatar that represents you, and the pain that you feel, is a very powerful aid in our visualization practices!";
		//Popup.Instance.ShowPopup("Help!", help, null);
	}

	public void ResetCamera() {
		if (OnResetCamera != null)
			OnResetCamera();
	}
}
