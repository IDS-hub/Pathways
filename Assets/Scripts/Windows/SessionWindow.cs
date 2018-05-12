using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using DG.Tweening;


// manual session :- 2,12,19,20,32,39,44,48,51,52,60

public class SessionWindow : WindowController {
	public static Action<bool> OnRotateModel;
	public static bool IS_OLD_SESSION = false;

	[SerializeField] AudioPlayer audioPlayer;
	[SerializeField] GameObject introSessionObject;

	[SerializeField] Text messegeText;
	[SerializeField] Image wall_image;
	[SerializeField] Image logo;
	[SerializeField] Image ImageText;
	[SerializeField] Animator animator;

	float writeSpeed = .03f;
	int letterWrote = 0;


	void OnEnable() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);
		
		audioPlayer.PlayAudio();

		SessionWork32.ShowText += ShowText;
		SessionWork44.ShowImage += ShowImage;
		SessionWork52.ShowAnimation += ShowAnimation;
		SessionWork60.ShowAnimation += ShowAnimation;

		Subscription.OnCloseSubcription += OnCloseSubcription;

		Character.HideCharacter += HideCharacter;

		if (OnRotateModel != null)
			OnRotateModel(true);
	}

	void OnDisable() {
		SessionWork32.ShowText -= ShowText;
		SessionWork44.ShowImage -= ShowImage;
		SessionWork52.ShowAnimation -= ShowAnimation;
		SessionWork60.ShowAnimation -= ShowAnimation;

		Subscription.OnCloseSubcription -= OnCloseSubcription;

		Character.HideCharacter -= HideCharacter;

		if (OnRotateModel != null)
			OnRotateModel(false);
	}

	public override void Start() {
		base.Start();
		audioPlayer.OnPlayerFinishWork += OnPlayerFinished;

		introSessionObject.SetActive(!UserInfo.SessionSkip);
		// manual session :- 2,12,19,20,32,39,44,48,51,52,60

	//	gameObject.AddComponent<SessionWork2>(); // power pose

		// adding session related work script
		if (UserInfo.CurrentSession.id == "2") {
			gameObject.AddComponent<SessionWork2>(); // idle pose and pain color change
		} else if (UserInfo.CurrentSession.id == "13") {
			gameObject.AddComponent<SessionWork12>(); // idle pose and pain color change
		} else if (UserInfo.CurrentSession.id == "20") {
			gameObject.AddComponent<SessionWork19>(); // power pose
		} else if (UserInfo.CurrentSession.id == "21") {
			gameObject.AddComponent<SessionWork20>(); // idle pose and pain color change
		} else if (UserInfo.CurrentSession.id == "33") {
			gameObject.AddComponent<SessionWork32>();
		} else if (UserInfo.CurrentSession.id == "40") {
			gameObject.AddComponent<SessionWork39>(); // idle pose and pain color change
		} else if (UserInfo.CurrentSession.id == "45") {
			gameObject.AddComponent<SessionWork44>();
		} else if (UserInfo.CurrentSession.id == "49") {
			gameObject.AddComponent<SessionWork48>(); // idle pose and pain color change
		} else if (UserInfo.CurrentSession.id == "52") {
			gameObject.AddComponent<SessionWork51>(); // power pose
		} else if (UserInfo.CurrentSession.id == "53") {
			gameObject.AddComponent<SessionWork52>();
		} else if (UserInfo.CurrentSession.id == "62") {
			gameObject.AddComponent<SessionWork60>();
		}

		ShowText(false, "", null);
		ShowAnimation(false, null);
		ShowImage(false, null);

		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(false, WindowPanels.Session);
	}

	private void OnDestroy() {
		audioPlayer.OnPlayerFinishWork -= OnPlayerFinished;
	}

	public void OnCloseButtonClicked() {
		if (!UserInfo.CurrentSession.isWatched) {
			Popup.Instance.ShowDoublePopup("Attention!", "Are you sure you want to end this session early? It will be marked as complete but you can always come back to it later.", () => {
				OnPlayerFinished();
			}, null);
		} else
			OnPlayerFinished();
	}

	public void OnClickStartSession() {
		introSessionObject.SetActive(false);
		UserInfo.SessionSkip = true;
	}
		
	// audio play finish. Updating the session. Then checking for user's feedback
	private void OnPlayerFinished() {
		if (int.Parse(UserInfo.CurrentSession.id) == 15 && !UserInfo.CurrentSession.isWatched) {
			Popup.Instance.ShowPopup("Milestone Complete!", "You can always see the next 5 upcoming sessions so that you can pick what you want to work on first.", () => {
				FinishSession();
			});
		} else
			FinishSession();
	}

	void FinishSession(){
		Loader.Instane.ShowLoading();
		audioPlayer.ResetAudio();
		IS_OLD_SESSION = UserInfo.CurrentSession.isWatched;
		apiManager.PlaySession(UserInfo.CurrentSession.id, (temp, isSuccess) => {
			if (isSuccess) {
				UserInfo.CurrentSession.isWatched = true;
				if (UserInfo.NextSession == null) {
					apiManager.GetAllSession((data, gotSession) => {
						CheckIfSubscribed();
					}, APIManager.RESPONSE_SESSION);
				} else
					CheckIfSubscribed();
			} else {
				// user does not authenticated, logout
				Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
					DoLogout();
				});
			}

		}, -1);
	}

	// checking for subscription
	void CheckIfSubscribed() {
		if (ShouldShowSubscribtion) {
			Loader.Instane.RemoveLoading();
			stateMachine.LoadPopupUI(WindowPanels.Subscription);
		} else {
			OnCloseSubcription();
		}
	}

	// checking whether subscription window should show or not
	bool ShouldShowSubscribtion {
		get { 
			if (UserInfo.IsSubscribe)
				return false;

			if (IS_OLD_SESSION)
				return false;

			if (int.Parse(UserInfo.CurrentSession.id) <= 8)
				return false;

			for (int i = 0; i < UserInfo.SessionList.Count; i++) {
				if (int.Parse(UserInfo.SessionList[i].id) == 8 && UserInfo.SessionList[i].isWatched)
					return true;
			}

			return false;
		}
	}

	// checking if can show RatePain screen or direct go to profile screen
	void OnCloseSubcription() {
		Loader.Instane.RemoveLoading();
		if(UserInfo.CurrentSession.session_summary.Length > 0)
			stateMachine.MoveToSelected(WindowPanels.RatePainWindow);
		else
			stateMachine.MoveToSelected(WindowPanels.Home);
	}

	// showing text according to audio timeline
	void ShowText(bool show, string text, TimeManagement time) {
		//AudioController.Instance.StopWriterSound();
		messegeText.enabled = show;
		messegeText.text = show ? "" : text;
		logo.enabled = !show;
		StopCoroutine("AnimateText");
		if (show) {
			float alreadyPassed = time.currentTime - time.startTime;
			writeSpeed = (time.endTime - time.startTime - 5f) / (float)text.Length;
			letterWrote = 0;
			if (alreadyPassed > 0)
				letterWrote = Mathf.RoundToInt((alreadyPassed / writeSpeed));

			Debug.Log(string.Format("letter wrote {0}, total letter {1}, writeSpeed {2}, alreadyPassed {3}", letterWrote, text.Length, writeSpeed, alreadyPassed));

			//AudioController.Instance.PlayTypeWriterSound();
			StartCoroutine("AnimateText", text);
		}
	}

	// type writer animation
	IEnumerator AnimateText(string writeText) {
		for (int i = letterWrote; i < (writeText.Length + 1); i++) {
			messegeText.text = writeText.Substring(0, i);
			yield return new WaitForSeconds(writeSpeed);
		}

		AudioController.Instance.StopWriterSound();
	}

	void ShowImage(bool show, Sprite sp) {
		ImageText.enabled = show;
		ImageText.sprite = sp;
		logo.enabled = !show;
	}

	// showing session relation gif animation
	void ShowAnimation(bool show, RuntimeAnimatorController anim) {
		ImageText.enabled = show;
		animator.enabled = show;
		logo.enabled = !show;
		if (anim != null)
			animator.runtimeAnimatorController = anim;
	}

	// hide/unhide character depending on the session
	void HideCharacter(bool doHide, bool doAnim) {
		if (doAnim) {
			if (!doHide)
				logo.enabled = false;

			wall_image.enabled = true;

			Color color = wall_image.color;
			color.a = doHide ? 0 : 1;
			wall_image.color = color;

			wall_image.DOFade(doHide ? 1 : 0, 1f).OnComplete(() => {
				if (Character.HideCharacter != null)
					Character.HideCharacter(doHide, false);
			});

		} else {
			logo.enabled = doHide;
			Color color = wall_image.color;
			color.a = 1;
			wall_image.color = color;
			wall_image.enabled = doHide;
		}
	}
}

public class TimeManagement {
	public float startTime;
	public float endTime;
	public float currentTime;

	public TimeManagement(float startTime, float endTime, float currentTime) {
		this.startTime = startTime;
		this.endTime = endTime;
		this.currentTime = currentTime;
	}
}
