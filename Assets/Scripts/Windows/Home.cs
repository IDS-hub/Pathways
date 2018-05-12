using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System.Linq;
using System;

public class Home : WindowController {

	[SerializeField] GameObject playObject;
	[SerializeField] GameObject historyObject;
	[SerializeField] GameObject button;
	[SerializeField] GameObject dailyDose;

	[SerializeField] Text heading_text;
	[SerializeField] Text daily_dose_text;

	[SerializeField] Transform playSessionHolder;
	[SerializeField] Transform historySessionHolder;

	[SerializeField] GameObject play_next_item_prefab;
	[SerializeField] GameObject session_history_item_prefab;

	public void OnClickMenu() {
		stateMachine.EnableLeftPanel(true);
	}

	public void OnEnable() {
		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(false, WindowPanels.Home);
		
		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);

		Character.OnSetModel += OnSetModel;
	}

	void OnDisable() {
		Character.OnSetModel -= OnSetModel;
	}

	public override void Start() {
		base.Start();

		playObject.SetActive(false);
		historyObject.SetActive(false);
		button.SetActive(false);
		dailyDose.SetActive(false);

		Loader.Instane.ShowLoading();
		apiManager.GetAllSession((jsonData, success) => {
			Loader.Instane.RemoveLoading();
			if (success) {
				List<Session> sessionData = UserInfo.SessionList.OrderBy(o => int.Parse(o.id)).Reverse().ToList();
				int newSessionCount = 0;
				for (int i = 0; i < sessionData.Count; i++) {
					if (!sessionData[i].isWatched) {
						newSessionCount++;
						playObject.SetActive(true);
						AddContent(play_next_item_prefab, playSessionHolder, sessionData[i]);
					} else {
						historyObject.SetActive(true);
						AddContent(session_history_item_prefab, historySessionHolder, sessionData[i]);
					}
				}

				if (newSessionCount > 1)
					heading_text.text = "New Sessions";
				else
					heading_text.text = "Next Session";
				
				button.SetActive(true);
				dailyDose.SetActive(true);
				daily_dose_text.text = UserInfo.DailyDose;
			} else {
				// user does not authenticated, logout
				Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
					DoLogout();
				});
			}
		}, APIManager.RESPONSE_SESSION);

		LoadAvatar();
		ProfileWindowController.OnProfile = true;
	}

	void OnSetModel() {
		if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(false, WindowPanels.Home);

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);
	}

	private void AddContent(GameObject prefab, Transform parent, Session data) {
		GameObject temp = Instantiate(prefab) as GameObject;
		temp.transform.SetParent(parent);

		RectTransform panel = (RectTransform)temp.transform;

		panel.transform.localPosition = Vector3.zero;
		temp.transform.localScale = Vector3.one;

		if (!temp.activeSelf)
			temp.SetActive(true);	

		if (data != null) {
			SessionContentPanel content = temp.GetComponent<SessionContentPanel>();
			if (content != null)
				content.SetSessionData(data);
		}
	}

	void LoadAvatar() {
		GameObject loadedAvatar = GameObject.FindGameObjectWithTag("Player");
		if (loadedAvatar != null)
			Debug.Log(string.Format("name {0} and tag {1}", loadedAvatar.name, loadedAvatar.tag));

		if (loadedAvatar == null) {
			loadedAvatar = Resources.Load<GameObject>(UserInfo.UserAvatar.isFemale ? "AvatarFemale" : "AvatarMale");
			loadedAvatar = Instantiate<GameObject>(loadedAvatar);
		} else if (PainSelector.OnPainSelector != null)
			PainSelector.OnPainSelector(true, WindowPanels.ProfileWindow);
	}

	public void GoToSessionList() {
		stateMachine.MoveToSelected(WindowPanels.SessionList);
	}
}
